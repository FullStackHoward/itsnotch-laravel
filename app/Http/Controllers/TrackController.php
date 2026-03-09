<?php

namespace App\Http\Controllers;

use App\Models\Track;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TrackController extends Controller
{
    public function download(Track $track): BinaryFileResponse
    {
        if (!$track->is_free || !$track->audio_path) {
            abort(403, 'This track is not available for free download.');
        }

        $path = Storage::disk('public')->path($track->audio_path);

        return response()->download($path, $track->title . '.' . pathinfo($track->audio_path, PATHINFO_EXTENSION));
    }
}
