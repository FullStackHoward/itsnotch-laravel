@extends('layouts.app')

{{-- Blade's inline @section($name, $value) form already runs $value through
     e(), so these must NOT be escaped again here or titles containing & or '
     render as double-encoded entities. --}}
@section('title', $track->title . ' by Notch64 | ItsNotch.com')
@section('meta_description', $shareDescription)
@section('canonical', $shareUrl)
@section('og_type', 'music.song')
@section('og_url', $shareUrl)
@section('og_title', $track->title . ' by Notch64')
@section('og_description', $shareDescription)
@section('og_image', $coverUrl)
@if($coverDimensions)
@section('og_image_width', $coverDimensions['width'])
@section('og_image_height', $coverDimensions['height'])
@endif
@section('theme_color', $track->extracted_color ?? '#FFAB63')

@push('head')
<script type="application/ld+json">{!! $jsonLd !!}</script>
@endpush

@section('content')
    <section class="track-page">
        <div class="track-page-inner">
            <a href="{{ route('home') }}" class="back-link">&larr; All Tracks</a>

            <div class="track-detail">
                <div class="track-detail-artwork">
                    <div class="artwork-wrap artwork-wrap--detail">
                        <div class="artwork-overlay" style="background-color: {{ $track->extracted_color ?? '#333333' }}">
                            <span class="overlay-brand">NOTCH<sup>64</sup></span>
                        </div>
                        <img src="{{ asset('img/cover-peel.png') }}" alt="" class="cover-peel" aria-hidden="true">
                        <img src="{{ $coverUrl }}" alt="{{ $track->title }}" class="artwork-img">
                    </div>
                </div>

                <div class="track-detail-info">
                    <h1 class="track-detail-title">
                        Notch64 - {{ $track->title }}
                        @if($isPreview)
                            <span class="badge-preview">Preview</span>
                        @endif
                    </h1>

                    <p class="track-detail-tags">
                        @foreach($track->genre as $g)
                            <a href="{{ route('home', ['genre' => $g]) }}" class="tag-link">#{{ strtolower($g) }}</a>
                        @endforeach
                        @foreach($track->subgenre as $sg)
                            <a href="{{ route('home', ['subgenre' => $sg]) }}" class="tag-link">#{{ strtolower($sg) }}</a>
                        @endforeach
                        @foreach($track->mood as $m)
                            <a href="{{ route('home', ['mood' => $m]) }}" class="tag-link">#{{ strtolower($m) }}</a>
                        @endforeach
                    </p>

                    <div class="waveform-player" data-color="{{ $track->extracted_color ?? '#1a1a1a' }}" @if($audioSrc) data-src="{{ $audioSrc }}" @endif>
                        @if($audioSrc && $hasPeaks)
                            <script type="application/json" class="waveform-data">{!! $waveformPayload !!}</script>
                        @endif

                        <div class="waveform-controls">
                            <button type="button" class="waveform-play-btn" aria-label="Play" @unless($audioSrc) disabled @endunless>&#9654;</button>

                            <div class="waveform-canvas-wrap">
                                <canvas class="waveform-canvas"></canvas>
                                @if($audioSrc && !$hasPeaks)
                                    <div class="waveform-fallback-bar">
                                        <div class="waveform-fallback-fill"></div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="waveform-times">
                            <span class="waveform-time waveform-time--current">0:00</span>
                            <span class="waveform-time waveform-time--duration">{{ $track->duration_seconds ? '' : '--:--' }}</span>
                        </div>

                        @unless($audioSrc)
                            <p class="waveform-unavailable">Preview coming soon.</p>
                        @endunless
                    </div>

                    <div class="track-detail-actions">
                        @if($track->is_free)
                            <a href="{{ route('tracks.download', $track) }}" class="track-action btn-action">Download &#8595;</a>
                        @else
                            <a href="{{ $patreonUrl }}" target="_blank" rel="noopener noreferrer" class="track-action btn-action">Subscribe &#8599;</a>
                        @endif

                        <div class="share-buttons" data-share-url="{{ $shareUrl }}" data-share-title="{{ $track->title }} by Notch64" data-share-text="{{ $shareDescription }}">
                            <button type="button" class="share-btn share-btn--native" hidden aria-label="Share">
                                <svg class="share-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="18" cy="5" r="3"/>
                                    <circle cx="6" cy="12" r="3"/>
                                    <circle cx="18" cy="19" r="3"/>
                                    <line x1="8.6" y1="10.5" x2="15.4" y2="6.5"/>
                                    <line x1="8.6" y1="13.5" x2="15.4" y2="17.5"/>
                                </svg>
                            </button>
                            <a class="share-btn" href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode($track->title . ' by Notch64') }}" target="_blank" rel="noopener noreferrer" aria-label="Share on X">
                                <img src="{{ asset('img/x.svg') }}" alt="">
                            </a>
                            <a class="share-btn" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer" aria-label="Share on Facebook">
                                <img src="{{ asset('img/fb.svg') }}" alt="">
                            </a>
                            <button type="button" class="share-btn share-btn--copy" aria-label="Copy link">
                                <svg class="share-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M10 13a5 5 0 0 0 7.07 0l2.83-2.83a5 5 0 0 0-7.07-7.07l-1.5 1.5"/>
                                    <path d="M14 11a5 5 0 0 0-7.07 0L4.1 13.83a5 5 0 0 0 7.07 7.07l1.5-1.5"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script src="@versioned('js/track-page.js')"></script>
@endpush
