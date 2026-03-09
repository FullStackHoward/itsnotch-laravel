# ItsNotch.com

**Live music portfolio and storefront for Notch64** — a DMV-based music producer specializing in instrumentals, music kits, audio logos, and indents for gaming, TV, and film. Built as a full Laravel application replacing a legacy WordPress site.

---

## Tech Stack

- **Backend:** PHP 8.4 / Laravel 12
- **Admin Panel:** Filament v3
- **Database:** MySQL
- **Frontend:** Blade templating, vanilla JavaScript, custom CSS
- **Storage:** Laravel filesystem with public disk
- **Color Extraction:** `league/color-extractor`
- **Deployment Target:** AlmaLinux / Apache / cPanel (Linode VPS)

---

## Features

### Public Frontend
- Fully responsive music storefront built from a custom Figma design
- N64 retro gaming visual theme with animated cloud strip, gradient layout, and pixel art assets
- Audio player with play/pause toggle, real-time progress bar, and seek functionality built in vanilla JavaScript with no dependencies
- Track cards with dynamically extracted dominant color applied as a translucent CSS overlay bar, matching the cover art palette automatically on upload
- Filter system for Genre, Sub-Genre, Mood, and Type — all populated dynamically from database values, no hardcoded options
- Clickable tag links on each track that apply filters and reflect the active pill state at the top of the page
- Free tracks serve a direct download via a protected Laravel route
- Paid tracks display a preview clip and route users to Patreon via a single globally configured URL
- Music Packs section with three states: Download, Patreon Only, and Coming Soon
- Paginated track list (6 per page) and paginated music packs (3 per page) with shared pagination partial
- Latest YouTube video embed with smart URL normalization — accepts full watch URLs, short URLs, embed URLs, or bare video IDs
- Full SEO meta suite including Open Graph, Twitter/X Card, geographic meta, and canonical URL

### Admin Panel (Filament v3)
- Track management with file uploads for cover art, full audio, and preview clips
- TagsInput fields for Genre, Sub-Genre, Mood, and Type with live database-driven suggestions
- Automatic dominant color extraction on track save via model observer
- Pack management with conditional form fields based on selected status
- Site Settings for global Patreon URL and YouTube video URL managed in one place

---

## Architecture Decisions

**Single Patreon URL as a site setting** — Rather than storing a Patreon URL per track, a single global value is managed in Site Settings and applied to all paid tracks automatically.

**Preview files instead of DRM** — Paid tracks store a separate preview audio file rather than encrypting or token-gating the full file, eliminating server-side complexity while still protecting full track access.

**Comma-separated tag storage with array casting** — Genre, Sub-Genre, Mood, and Type are stored as comma-separated strings and cast to arrays via the Track model, keeping the schema simple while supporting multi-value filtering.

**CSS color overlay via pseudo-element** — The translucent colored bar on each track's cover art uses a `::before` pseudo-element with `opacity` rather than setting opacity on the parent, keeping child text and images fully opaque while the background remains translucent.

---

## Project Context

Part of a broader personal brand infrastructure under the **Notch64** identity, spanning music production, gaming community (Vice Gamers), and creative community (Vice Creators). ItsNotch.com replaces BigNotch.com as the primary music home and Patreon funnel.

Related projects:
- `vicegamers.com` — Django/Python community site
- `vicecreators.com` — Django/Python community site
- `notch64.com` — PHP(Laravel) App that links all properties

---

## Author

Built by **Howard** (FullStackHoward) — full-stack developer and DevOps engineer with a background in front-end development and digital marketing. Currently pursuing a Master's in DevOps and AWS certification.

- Portfolio: [howard.codes](https://howard.codes)
