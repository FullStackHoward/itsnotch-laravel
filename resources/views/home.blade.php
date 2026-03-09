@extends('layouts.app')

@section('content')
    <!-- Latest Music Section -->
    <section class="latest-music">
        <h2 class="section-title">Latest Music</h2>
        <div class="section-divider"></div>

        <!-- Filter Pills -->
        <div class="filter-group-wrap">
            <div class="filter-group">
                <span class="filter-label">Genre</span>
                <div class="pills">
                    <a href="{{ request()->fullUrlWithQuery(['genre' => null, 'page' => null]) }}" class="pill pill--genre {{ request('genre') ? '' : 'active' }}">All</a>
                    @foreach($genres as $g)
                        <a href="{{ request()->fullUrlWithQuery(['genre' => $g, 'page' => null]) }}" class="pill pill--genre {{ request('genre') === $g ? 'active' : '' }}">{{ $g }}</a>
                    @endforeach
                </div>
            </div>
            <div class="filter-group">
                <span class="filter-label">Sub-Genre</span>
                <div class="pills">
                    <a href="{{ request()->fullUrlWithQuery(['subgenre' => null, 'page' => null]) }}" class="pill pill--subgenre {{ request('subgenre') ? '' : 'active' }}">All</a>
                    @foreach($subgenres as $sg)
                        <a href="{{ request()->fullUrlWithQuery(['subgenre' => $sg, 'page' => null]) }}" class="pill pill--subgenre {{ request('subgenre') === $sg ? 'active' : '' }}">{{ $sg }}</a>
                    @endforeach
                </div>
            </div>
            <div class="filter-group">
                <span class="filter-label">Mood</span>
                <div class="pills">
                    <a href="{{ request()->fullUrlWithQuery(['mood' => null, 'page' => null]) }}" class="pill pill--mood {{ request('mood') ? '' : 'active' }}">All</a>
                    @foreach($moods as $m)
                        <a href="{{ request()->fullUrlWithQuery(['mood' => $m, 'page' => null]) }}" class="pill pill--mood {{ request('mood') === $m ? 'active' : '' }}">{{ $m }}</a>
                    @endforeach
                </div>
            </div>
            <div class="filter-group">
                <span class="filter-label">Type</span>
                <div class="pills">
                    <a href="{{ request()->fullUrlWithQuery(['type' => null, 'page' => null]) }}" class="pill pill--type {{ request('type') ? '' : 'active' }}">All</a>
                    @foreach($types as $t)
                        <a href="{{ request()->fullUrlWithQuery(['type' => $t, 'page' => null]) }}" class="pill pill--type {{ request('type') === $t ? 'active' : '' }}">{{ $t }}</a>
                    @endforeach
                </div>
            </div>
        </div>

        @foreach($tracks as $track)
            @include('partials.track-card', ['track' => $track])

            @if(!$loop->last)
                <hr class="track-divider">
            @endif
        @endforeach

        <!-- Pagination -->
        {{ $tracks->withQueryString()->links('partials.pagination') }}
    </section>

    <!-- Music Packs & Latest Video -->
    <section class="packs-video">

        <!-- Left: Music Packs -->
        <div class="packs-col">
            <h2 class="packs-title">Music Packs</h2>

            @foreach($packs as $pack)
                <div class="pack-item">
                    <div class="pack-artwork">
                        @if($pack->cover_art_path)
                            <img src="{{ asset('storage/' . $pack->cover_art_path) }}" alt="{{ $pack->name }}" class="artwork-placeholder artwork-placeholder--sm" style="object-fit:cover;">
                        @else
                            <div class="artwork-placeholder artwork-placeholder--sm"></div>
                        @endif
                    </div>
                    <div class="pack-info">
                        <h3 class="pack-name">{{ $pack->name }}</h3>
                        @if($pack->status === 'download' && $pack->download_path)
                            <a href="{{ asset('storage/' . $pack->download_path) }}" class="pack-link">Download &#8595;</a>
                        @elseif($pack->status === 'patreon' && $patreonUrl)
                            <a href="{{ $patreonUrl }}" target="_blank" rel="noopener noreferrer" class="pack-link pack-link--patron">Patreon Only &#8599;</a>
                        @else
                            <span class="pack-link pack-link--coming-soon">Coming Soon!</span>
                        @endif
                    </div>
                </div>
            @endforeach

            {{ $packs->withQueryString()->links('partials.pagination') }}
        </div>

        <!-- Right: Latest Video -->
        <div class="video-col">
            <h2 class="packs-title">Latest Video</h2>
            <div class="video-embed">
                <iframe
                    src="{{ $youtubeEmbedUrl ?? 'https://www.youtube.com/embed/dQw4w9WgXcQ' }}"
                    title="Latest video by Notch64"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                ></iframe>
            </div>
        </div>

    </section>
@endsection
