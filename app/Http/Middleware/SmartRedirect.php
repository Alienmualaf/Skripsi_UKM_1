<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\RedirectResponse;

class SmartRedirect
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. If GET request, detect if we are visiting a create or edit form
        if ($request->isMethod('GET')) {
            $path = $request->getPathInfo();
            if (
                str_ends_with($path, '/edit') || 
                str_ends_with($path, '/create') || 
                str_contains($path, '/edit/') || 
                str_contains($path, '/create/')
            ) {
                $previous = url()->previous();
                // Ensure previous URL exists, is different from current,
                // and belongs to our application domain, and is not another edit/create form
                if (
                    $previous && 
                    $previous !== url()->current() && 
                    str_starts_with($previous, $request->getSchemeAndHttpHost()) &&
                    !str_contains($previous, '/edit') && 
                    !str_contains($previous, '/create')
                ) {
                    session(['smart_redirect_to' => $previous]);
                }
            }
        }

        // Process request
        $response = $next($request);

        // 2. If RedirectResponse on successful mutation (POST/PUT/PATCH/DELETE),
        // override target URL to the saved previous page.
        if ($response instanceof RedirectResponse && !$request->isMethod('GET')) {
            $referer = $request->headers->get('referer');
            $isFormSubmission = $referer && (str_contains($referer, '/edit') || str_contains($referer, '/create'));

            if ($isFormSubmission) {
                $hasErrors = false;
                if (method_exists($response, 'getSession') && $response->getSession()) {
                    $hasErrors = $response->getSession()->has('errors');
                }

                $targetUrl = $response->getTargetUrl();
                if (!$hasErrors && session()->has('smart_redirect_to') && !str_contains($targetUrl, 'login')) {
                    $target = session()->pull('smart_redirect_to');
                    $response->setTargetUrl($target);
                }
            }
        }

        return $response;
    }
}
