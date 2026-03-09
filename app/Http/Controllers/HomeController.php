<?php

namespace App\Http\Controllers;

use App\Models\Pack;
use App\Models\SiteSetting;
use App\Models\Track;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Extract unique filter values from comma-separated columns
        $extractUnique = fn(string $col) => Track::whereNotNull($col)
            ->getQuery()
            ->pluck($col)
            ->flatMap(fn($v) => explode(',', $v))
            ->map(fn($v) => trim($v))
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $genres = $extractUnique('genre');
        $subgenres = $extractUnique('subgenre');
        $moods = $extractUnique('mood');
        $types = $extractUnique('type');

        $query = Track::active()->latest();

        if ($request->filled('genre')) {
            $query->where('genre', 'LIKE', '%' . $request->input('genre') . '%');
        }

        if ($request->filled('subgenre')) {
            $query->where('subgenre', 'LIKE', '%' . $request->input('subgenre') . '%');
        }

        if ($request->filled('mood')) {
            $query->where('mood', 'LIKE', '%' . $request->input('mood') . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', 'LIKE', '%' . $request->input('type') . '%');
        }

        $tracks = $query->paginate(6);
        $packs = Pack::paginate(3, ['*'], 'packs_page');
        $youtubeEmbedUrl = SiteSetting::getYoutubeEmbedUrl(SiteSetting::getValue('youtube_embed_url'));
        $patreonUrl = SiteSetting::getValue('patreon_url');

        return view('home', compact('tracks', 'packs', 'youtubeEmbedUrl', 'patreonUrl', 'genres', 'subgenres', 'moods', 'types'));
    }
}
