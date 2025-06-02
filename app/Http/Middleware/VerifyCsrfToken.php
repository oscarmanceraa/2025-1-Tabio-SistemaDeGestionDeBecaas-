<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Add URIs to exclude from CSRF protection here
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if ($request->is('evaluador/toggle-convocatoria')) {
            Log::info('CSRF Token Debug', [
                'token_in_request' => $request->input('_token'),
                'token_in_session' => $request->session()->token(),
                'headers' => $request->headers->all(),
                'request_method' => $request->method(),
                'request_path' => $request->path(),
                'request_url' => $request->url(),
                'request_full_url' => $request->fullUrl(),
            ]);
        }

        return parent::handle($request, $next);
    }
}
