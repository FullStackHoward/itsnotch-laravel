(function () {
    'use strict';

    var audio = null;
    var activeBtn = null;

    var ICON_PLAY = '\u25B6';   // ▶
    var ICON_PAUSE = '\u23F8\uFE0E';  // ⏸︎ (text style, not emoji)

    function resetButton(btn) {
        if (!btn) return;
        btn.textContent = ICON_PLAY;
        btn.setAttribute('aria-label', 'Play');
        var fill = btn.closest('.track-player').querySelector('.progress-fill');
        if (fill) fill.style.width = '0%';
    }

    function stopCurrent() {
        if (audio) {
            audio.pause();
            audio.removeAttribute('src');
            audio.load();
        }
        resetButton(activeBtn);
        audio = null;
        activeBtn = null;
    }

    // Single delegated click handler — check progress bar first, then play button
    document.addEventListener('click', function (e) {
        // --- Seek on progress bar ---
        var bar = e.target.closest('.progress-bar');
        if (bar) {
            e.stopImmediatePropagation();

            if (!activeBtn || !audio || !audio.duration) return;

            var player = bar.closest('.track-player');
            if (!player || player !== activeBtn.closest('.track-player')) return;

            var rect = bar.getBoundingClientRect();
            var x = e.clientX - rect.left;
            var pct = Math.max(0, Math.min(1, x / rect.width));
            audio.currentTime = pct * audio.duration;
            return;
        }

        // --- Play / pause button ---
        var btn = e.target.closest('.play-btn');
        if (!btn) return;

        var src = btn.getAttribute('data-src');
        if (!src) return;

        // Same button clicked again — toggle play/pause
        if (btn === activeBtn && audio) {
            if (audio.paused) {
                audio.play();
                btn.textContent = ICON_PAUSE;
                btn.setAttribute('aria-label', 'Pause');
            } else {
                audio.pause();
                btn.textContent = ICON_PLAY;
                btn.setAttribute('aria-label', 'Play');
            }
            return;
        }

        // Different track — stop the previous one
        stopCurrent();

        // Create new audio and start playback
        audio = new Audio(src);
        activeBtn = btn;

        var player = btn.closest('.track-player');
        var fill = player ? player.querySelector('.progress-fill') : null;

        audio.addEventListener('timeupdate', function () {
            if (!fill || !audio || !audio.duration) return;
            var pct = (audio.currentTime / audio.duration) * 100;
            fill.style.width = pct + '%';
        });

        audio.addEventListener('ended', function () {
            resetButton(activeBtn);
            audio = null;
            activeBtn = null;
        });

        audio.play();
        btn.textContent = ICON_PAUSE;
        btn.setAttribute('aria-label', 'Pause');
    });
})();
