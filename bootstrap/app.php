<?php

use App\Http\Middleware\DetectDomain;
use App\Http\Middleware\EnsureTenantLabActive;
use App\Http\Middleware\ExpireImpersonation;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Models\Domain;
use App\Models\Lab;
use App\Models\WebsiteSetting;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'webhooks/*',
        ]);

        $middleware->web(append: [
            DetectDomain::class,
            EnsureTenantLabActive::class,
            ExpireImpersonation::class,
        ]);

        $middleware->alias([
            'superadmin' => SuperAdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (PostTooLargeException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'The file or total upload size is too large. Please use files under 50MB and ensure your server allows large uploads.'], 413);
            }

            return redirect()->back()
                ->with('error', 'The file or total upload size is too large. Please use files under 50MB per file. If needed, increase upload_max_filesize and post_max_size in your server PHP settings.')
                ->withInput($request->except('banner_images', 'logo', 'favicon', 'hero_image'));
        });

        // Branded 404 for tenant sites (lab from middleware, currentLabId(), or Domain::activeLabIdForHost).
        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return null;
            }
            if ($request->is('super-admin*')) {
                return null;
            }
            if ($request->user()?->isSuperAdmin()) {
                return null;
            }

            $labId = null;
            if (app()->bound('lab_id') && app('lab_id')) {
                $labId = (int) app('lab_id');
            } elseif (function_exists('currentLabId')) {
                $labId = currentLabId();
            }

            // Same host → lab as DetectDomain: ensures branded 404 (incl. mobile nav) for every tenant domain.
            if (! $labId) {
                $labId = Domain::activeLabIdForHost($request->getHost());
            }

            if (! $labId) {
                return null;
            }

            $lab = Lab::find($labId);
            if (! $lab) {
                return null;
            }

            $settings = WebsiteSetting::where('lab_id', $labId)->first();

            return response()->view('errors.404-vendor', [
                'lab' => $lab,
                'settings' => $settings,
            ], 404);
        });
    })->create();
