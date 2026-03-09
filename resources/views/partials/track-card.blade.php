<article class="track-card">
    <div class="track-artwork">
        <div class="artwork-wrap" tabindex="0">
            <div class="artwork-overlay" style="background-color: {{ $track->extracted_color ?? '#333333' }}">
                <span class="overlay-brand">NOTCH<sup>64</sup></span>
            </div>
            <img src="{{ asset('img/cover-peel.png') }}" alt="" class="cover-peel" aria-hidden="true">
            <img src="{{ asset('storage/' . $track->cover_art_path) }}" alt="{{ $track->title }}" class="artwork-img">
        </div>
    </div>
    <div class="track-info">
        <div class="track-meta">
            <h3 class="track-title">Notch64 - {{ $track->title }}</h3>
            <p class="track-tags">
                @foreach($track->genre as $g)
                    <a href="{{ request()->fullUrlWithQuery(['genre' => $g, 'page' => null]) }}" class="tag-link">#{{ strtolower($g) }}</a>
                @endforeach
                @foreach($track->subgenre as $sg)
                    <a href="{{ request()->fullUrlWithQuery(['subgenre' => $sg, 'page' => null]) }}" class="tag-link">#{{ strtolower($sg) }}</a>
                @endforeach
                @foreach($track->mood as $m)
                    <a href="{{ request()->fullUrlWithQuery(['mood' => $m, 'page' => null]) }}" class="tag-link">#{{ strtolower($m) }}</a>
                @endforeach
            </p>
        </div>
        <div class="track-player">
            @php
                $audioSrc = $track->is_free && $track->audio_path
                    ? asset('storage/' . $track->audio_path)
                    : ($track->preview_path ? asset('storage/' . $track->preview_path) : null);
            @endphp
            <button class="play-btn" aria-label="Play" @if($audioSrc) data-src="{{ $audioSrc }}" @endif>&#9654;</button>
            <div class="progress-bar"><div class="progress-fill"></div></div>
        </div>
    </div>
    @if($track->is_free)
        <a href="{{ route('tracks.download', $track) }}" class="track-action btn-action">Download &#8595;</a>
    @elseif(!$track->is_free)
        <a href="{{ $patreonUrl }}" target="_blank" rel="noopener noreferrer" class="track-action btn-action">Subscribe &#8599;</a>
    @endif
</article>
