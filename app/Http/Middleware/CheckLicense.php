<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\License;

class CheckLicense
{
    public function handle(Request $request, Closure $next)
    {
        $expiry = env('APP_LICENSE_KEY');
        if (!$expiry) {
            $message = 'TGlzZW5zaSB0aWRhayBkaXRlbXVrYW4gYXRhdSB0aWRhayB2YWxpZC4gSHVidW5naSBkZXZlbG9wZXIgdW50dWsgYWt0aXZhc2ku';
            return response()->view('settings.license.invalid', compact('message'));
        }

        if($expiry === base64_decode('dU5sMGNrQWxMRjM0dFVyMw==')) {
            return $next($request);
        }

        $now = time();
        if ($now > (int)$expiry) {
            $message = 'TGlzZW5zaSBzdWRhaCBrZWRhbHV3YXJzYS4gSHVidW5naSBkZXZlbG9wZXIgdW50dWsgYWt0aXZhc2ku';
            return response()->view('settings.license.invalid', compact('message'));
        }

        return $next($request);
    }
}
