(function () {
    'use strict';

    var BAR_WIDTH = 3;
    var BAR_GAP = 2;
    var MIN_BAR_HEIGHT = 2;

    function formatTime(seconds) {
        if (!isFinite(seconds) || seconds < 0) {
            seconds = 0;
        }
        var m = Math.floor(seconds / 60);
        var s = Math.floor(seconds % 60);
        return m + ':' + (s < 10 ? '0' : '') + s;
    }

    function hexToRgb(hex) {
        var fallback = { r: 26, g: 26, b: 26 };
        if (!hex) return fallback;

        hex = hex.replace('#', '');
        if (hex.length === 3) {
            hex = hex.charAt(0) + hex.charAt(0) + hex.charAt(1) + hex.charAt(1) + hex.charAt(2) + hex.charAt(2);
        }

        var num = parseInt(hex, 16);
        if (isNaN(num)) return fallback;

        return {
            r: (num >> 16) & 255,
            g: (num >> 8) & 255,
            b: num & 255
        };
    }

    // Map the fixed-resolution stored peaks array (from the server) onto
    // however many bar slots actually fit the current canvas width.
    function resamplePeaks(basePeaks, targetCount) {
        if (targetCount <= 0) return [];

        var result = new Array(targetCount);
        var ratio = basePeaks.length / targetCount;
        var i, start, end, j, max;

        for (i = 0; i < targetCount; i++) {
            start = Math.floor(i * ratio);
            end = Math.floor((i + 1) * ratio);
            if (end <= start) end = start + 1;

            max = 0;
            for (j = start; j < end && j < basePeaks.length; j++) {
                if (basePeaks[j] > max) max = basePeaks[j];
            }
            result[i] = max;
        }

        return result;
    }

    function initWaveformPlayer(root) {
        var src = root.getAttribute('data-src');
        var color = root.getAttribute('data-color') || '#1a1a1a';
        var rgb = hexToRgb(color);

        var playBtn = root.querySelector('.waveform-play-btn');
        var canvasWrap = root.querySelector('.waveform-canvas-wrap');
        var canvas = root.querySelector('.waveform-canvas');
        var fallbackFill = root.querySelector('.waveform-fallback-fill');
        var dataScript = root.querySelector('.waveform-data');
        var currentTimeEl = root.querySelector('.waveform-time--current');
        var durationEl = root.querySelector('.waveform-time--duration');

        if (!src || !playBtn) {
            return; // no audio source at all for this track
        }

        var audio = new Audio(src);
        audio.preload = 'metadata';

        var basePeaks = null;
        var knownDuration = null;
        var isDragging = false;

        if (dataScript && canvas) {
            try {
                var payload = JSON.parse(dataScript.textContent);
                basePeaks = payload.peaks;
                knownDuration = payload.duration;
            } catch (err) {
                basePeaks = null;
            }
        }

        var usingFallback = !basePeaks || !canvas;

        if (knownDuration) {
            durationEl.textContent = formatTime(knownDuration);
        }

        var ctx = usingFallback ? null : canvas.getContext('2d');

        function setupCanvasSize() {
            var dpr = window.devicePixelRatio || 1;
            var rect = canvasWrap.getBoundingClientRect();
            canvas.width = Math.max(1, Math.round(rect.width * dpr));
            canvas.height = Math.max(1, Math.round(rect.height * dpr));
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
            return { width: rect.width, height: rect.height };
        }

        function draw() {
            if (usingFallback || !basePeaks) return;

            var size = setupCanvasSize();
            var width = size.width;
            var height = size.height;
            var slot = BAR_WIDTH + BAR_GAP;
            var barCount = Math.max(1, Math.floor(width / slot));
            var peaks = resamplePeaks(basePeaks, barCount);
            var duration = knownDuration || audio.duration || 0;
            var progress = duration ? (audio.currentTime / duration) : 0;
            var progressIndex = Math.floor(progress * barCount);
            var centerY = height / 2;
            var i, x, barHeight, isPlayed;

            ctx.clearRect(0, 0, width, height);

            for (i = 0; i < barCount; i++) {
                x = i * slot;
                barHeight = Math.max(MIN_BAR_HEIGHT, peaks[i] * (height - 10));
                isPlayed = i < progressIndex;

                ctx.fillStyle = isPlayed
                    ? 'rgb(' + rgb.r + ',' + rgb.g + ',' + rgb.b + ')'
                    : 'rgba(' + rgb.r + ',' + rgb.g + ',' + rgb.b + ',0.28)';

                if (ctx.roundRect) {
                    ctx.beginPath();
                    ctx.roundRect(x, centerY - barHeight / 2, BAR_WIDTH, barHeight, BAR_WIDTH / 2);
                    ctx.fill();
                } else {
                    ctx.fillRect(x, centerY - barHeight / 2, BAR_WIDTH, barHeight);
                }
            }
        }

        function setPlayingUI(playing) {
            playBtn.textContent = playing ? '⏸︎' : '▶';
            playBtn.setAttribute('aria-label', playing ? 'Pause' : 'Play');
        }

        function updateFallback(ratio) {
            if (fallbackFill) {
                fallbackFill.style.width = (ratio * 100) + '%';
            }
        }

        function seekToClientX(clientX) {
            var duration = knownDuration || audio.duration;
            if (!duration) return;

            var rect = canvasWrap.getBoundingClientRect();
            var x = clientX - rect.left;
            var ratio = Math.max(0, Math.min(1, x / rect.width));
            audio.currentTime = ratio * duration;

            if (usingFallback) {
                updateFallback(ratio);
            } else {
                draw();
            }
        }

        playBtn.addEventListener('click', function () {
            if (audio.paused) {
                audio.play();
            } else {
                audio.pause();
            }
        });

        audio.addEventListener('play', function () { setPlayingUI(true); });
        audio.addEventListener('pause', function () { setPlayingUI(false); });

        audio.addEventListener('ended', function () {
            setPlayingUI(false);
            audio.currentTime = 0;
            if (usingFallback) {
                updateFallback(0);
            } else {
                draw();
            }
        });

        audio.addEventListener('timeupdate', function () {
            currentTimeEl.textContent = formatTime(audio.currentTime);
            var duration = knownDuration || audio.duration;
            if (usingFallback) {
                if (duration) updateFallback(audio.currentTime / duration);
            } else if (!isDragging) {
                draw();
            }
        });

        audio.addEventListener('loadedmetadata', function () {
            if (!knownDuration) {
                durationEl.textContent = formatTime(audio.duration);
            }
        });

        canvasWrap.addEventListener('pointerdown', function (e) {
            isDragging = true;
            try { canvasWrap.setPointerCapture(e.pointerId); } catch (err) { /* no-op */ }
            seekToClientX(e.clientX);
        });

        canvasWrap.addEventListener('pointermove', function (e) {
            if (!isDragging) return;
            seekToClientX(e.clientX);
        });

        function endDrag(e) {
            if (!isDragging) return;
            isDragging = false;
            try { canvasWrap.releasePointerCapture(e.pointerId); } catch (err) { /* no-op */ }
        }

        canvasWrap.addEventListener('pointerup', endDrag);
        canvasWrap.addEventListener('pointercancel', endDrag);

        if (!usingFallback) {
            var resizeTimer = null;
            window.addEventListener('resize', function () {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(draw, 120);
            });

            draw();
        }
    }

    function copyToClipboard(text) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            return navigator.clipboard.writeText(text);
        }

        return new Promise(function (resolve) {
            var textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();
            try {
                document.execCommand('copy');
            } catch (err) {
                /* no-op, nothing more we can do without Clipboard API support */
            }
            document.body.removeChild(textarea);
            resolve();
        });
    }

    function initShareButtons(root) {
        var shareUrl = root.getAttribute('data-share-url');
        var shareTitle = root.getAttribute('data-share-title') || document.title;
        var shareText = root.getAttribute('data-share-text') || '';
        var nativeBtn = root.querySelector('.share-btn--native');
        var copyBtn = root.querySelector('.share-btn--copy');

        if (nativeBtn && navigator.share) {
            nativeBtn.hidden = false;
            nativeBtn.addEventListener('click', function () {
                navigator.share({
                    title: shareTitle,
                    text: shareText,
                    url: shareUrl
                }).catch(function () {
                    // Share sheet dismissed or rejected; nothing to do.
                });
            });
        }

        if (copyBtn) {
            copyBtn.addEventListener('click', function () {
                copyToClipboard(shareUrl).then(function () {
                    copyBtn.classList.add('is-copied');
                    setTimeout(function () {
                        copyBtn.classList.remove('is-copied');
                    }, 1500);
                });
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        var waveformPlayers = document.querySelectorAll('.waveform-player');
        for (var i = 0; i < waveformPlayers.length; i++) {
            initWaveformPlayer(waveformPlayers[i]);
        }

        var shareGroups = document.querySelectorAll('.share-buttons');
        for (var j = 0; j < shareGroups.length; j++) {
            initShareButtons(shareGroups[j]);
        }
    });
})();
