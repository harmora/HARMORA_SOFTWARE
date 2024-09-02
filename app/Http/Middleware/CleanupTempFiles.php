<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

class CleanupTempFiles
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // If the user exits, check and delete temp files older than 1 hour
        $files = Storage::files('temp');
        $expiration = now()->subHours(1);

        foreach ($files as $file) {
            if (Storage::lastModified($file) < $expiration->timestamp) {
                Storage::delete($file);
            }
        }

        return $response;
    }

}
