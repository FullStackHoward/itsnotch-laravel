<?php

namespace App\Support;

class Asset
{
    /**
     * An asset URL with the file's modification time appended as a version
     * query string.
     *
     * Static assets under public/ are served with a 30-day max-age, so a
     * deploy that changes a file without changing its URL leaves returning
     * visitors on the cached copy until it expires. Keying the URL on mtime
     * makes a changed file a new URL, so the long max-age stays safe.
     */
    public static function versioned(string $path): string
    {
        $url = asset($path);
        $mtime = @filemtime(public_path($path));

        return $mtime === false ? $url : $url . '?v=' . $mtime;
    }
}
