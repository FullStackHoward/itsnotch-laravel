<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Primary Meta -->
    <title>It's Notch! | Music Production by Notch64</title>
    <meta name="description" content="Music production by Notch64. Instrumentals, music kits, audio logos, and indents for gaming, TV, and film. Creator of Pixelwave — a fusion of Chiptune, UK Drill, and Boom Bap. Based in the DMV.">
    <meta name="keywords" content="Notch64, ItsNotch, music producer, instrumentals, music kits, audio logo, music indent, sync licensing, gaming music, TV music, film scoring, Pixelwave, chiptune beats, UK drill, boom bap, DMV producer, Washington DC, Maryland, Virginia, free beats, beat store, music for content creators">
    <meta name="author" content="Notch64">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://itsnotch.com">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://itsnotch.com">
    <meta property="og:title" content="It's Notch! | Music Production by Notch64">
    <meta property="og:description" content="Instrumentals, music kits, audio logos, and indents for gaming, TV, and film. Creator of Pixelwave. Based in the DMV.">
    <meta property="og:image" content="https://itsnotch.com/img/og-image.png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="ItsNotch.com">
    <meta property="og:locale" content="en_US">

    <!-- Twitter / X Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@itsnotch64">
    <meta name="twitter:creator" content="@itsnotch64">
    <meta name="twitter:url" content="https://x.com/itsnotch64">
    <meta name="twitter:title" content="It's Notch! | Music Production by Notch64">
    <meta name="twitter:description" content="Instrumentals, music kits, audio logos, and indents for gaming, TV, and film. Creator of Pixelwave. Based in the DMV.">
    <meta name="twitter:image" content="https://itsnotch.com/img/og-image.png">

    <!-- Geographic -->
    <meta name="geo.region" content="US-MD">
    <meta name="geo.placename" content="DMV (Washington DC, Maryland, Virginia)">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-DHC13902FV"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-DHC13902FV');
    </script>

    <!-- Theme -->
    <meta name="theme-color" content="#FFAB63">

    <!-- Styles & Fonts -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@900&family=Fredoka+One&family=Nunito:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="hero-content">
            <h1 class="hero-title">It's Notch!</h1>
            <p class="hero-subtitle">Music by Notch64</p>
            <img src="{{ asset('img/n64-pixelface.png') }}" alt="Notch64 pixel art avatar" class="hero-avatar">
        </div>
        <div class="clouds-container">
            <div class="clouds-track">
                <img src="{{ asset('img/cloudstrip.svg') }}" alt="" class="cloud-strip" aria-hidden="true">
                <img src="{{ asset('img/cloudstrip.svg') }}" alt="" class="cloud-strip" aria-hidden="true">
                <img src="{{ asset('img/cloudstrip.svg') }}" alt="" class="cloud-strip" aria-hidden="true">
                <img src="{{ asset('img/cloudstrip.svg') }}" alt="" class="cloud-strip" aria-hidden="true">
            </div>
        </div>
    </section>

    <!-- Gradient wrapper: everything below hero -->
    <div class="gradient-wrap">

    <!-- Social Section -->
    <section class="social">
        <nav class="social-icons" aria-label="Social media links">
            <a href="https://www.facebook.com/ItsNotch64/" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                <img src="{{ asset('img/fb.svg') }}" alt="Facebook">
            </a>
            <a href="https://x.com/itsnotch64" target="_blank" rel="noopener noreferrer" aria-label="X (Twitter)">
                <img src="{{ asset('img/x.svg') }}" alt="X">
            </a>
            <a href="https://www.instagram.com/itsnotch64/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                <img src="{{ asset('img/ig.svg') }}" alt="Instagram">
            </a>
            <a href="https://www.youtube.com/@notch64" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
                <img src="{{ asset('img/yt.svg') }}" alt="YouTube">
            </a>
            <a href="https://soundcloud.com/Notch64" target="_blank" rel="noopener noreferrer" aria-label="SoundCloud">
                <img src="{{ asset('img/sc.svg') }}" alt="SoundCloud">
            </a>
        </nav>
        <p class="social-tagline">Instrumentals &ndash; SFX &ndash; Indents &ndash; Music Kits &ndash; Pixelwave</p>
    </section>

    @yield('content')

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-text">
            <p class="footer-copy">ItsNotch.com &copy; {{ date('Y') }}</p>
            <p class="footer-credit">website by fullstackhoward</p>
            <a href="mailto:him@notch64.com" class="footer-email" aria-label="Email Notch64">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="footer-email-icon" aria-hidden="true">
                    <rect x="2" y="4" width="20" height="16" rx="2"/>
                    <polyline points="2,4 12,13 22,4"/>
                </svg>
            </a>
        </div>
        <div class="footer-landscape">
            <img src="{{ asset('img/n64-landscape.png') }}" alt="Notch64 pixel art landscape">
        </div>
    </footer>

    </div><!-- /.gradient-wrap -->

    <script src="{{ asset('js/player.js') }}"></script>
</body>
</html>
