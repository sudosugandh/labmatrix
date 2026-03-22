<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\DetectDomain::class,
        ]);
        
        $middleware->alias([
            'superadmin' => \App\Http\Middleware\SuperAdminMiddleware::class,
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
    })->create();
