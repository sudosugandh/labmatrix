<?php

namespace App\Http\Middleware;

use App\Models\Domain;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DetectDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Super-admin UI does not need tenant context; allow any host without binding lab_id.
        if ($request->is('super-admin*')) {
            return $next($request);
        }

        // Automated tests: no real domain on Host header — bind a fixed lab from phpunit.xml.
        if (app()->environment('testing')) {
            $labId = (int) env('TESTING_TENANT_LAB_ID', 1);
            if ($labId > 0) {
                app()->instance('lab_id', $labId);
                config(['app.lab_id' => $labId]);

                return $next($request);
            }
        }

        // Public and lab routes always resolve the lab from the request host (even when a
        // super admin is logged in), so currentLabId() and WebsiteSetting work on /, CMS, etc.

        // Get host and normalize it (remove port if present)
        $host = $request->getHost();
        
        // Also get host without port for matching
        $hostWithoutPort = explode(':', $host)[0];
        
        try {
            // Find domain in database - try both with and without port
            // This handles cases like "appolo.local.com" and "appolo.local.com:8000"
            $domain = Domain::where(function($query) use ($host, $hostWithoutPort) {
                    $query->where('domain', $host)
                          ->orWhere('domain', $hostWithoutPort);
                })
                ->where('is_active', true)
                ->first();
            
            if (!$domain) {
                // Log for debugging
                \Log::warning('Domain not found in database', [
                    'host' => $host,
                    'host_without_port' => $hostWithoutPort,
                    'request_uri' => $request->getRequestUri(),
                    'full_url' => $request->fullUrl(),
                ]);
                
                // Domain not found - show specific error
                return response()->view('errors.domain-not-found', [], 404);
            }
            
            // ALWAYS set lab_id, even if already bound (to ensure it's correct for current request)
            // This is critical for SaaS - each request must have the correct lab_id
            app()->instance('lab_id', $domain->lab_id);
            
            // Also make it available via config for easier access
            config(['app.lab_id' => $domain->lab_id]);
            
            // Log successful domain detection (only in debug mode)
            if (config('app.debug')) {
                \Log::debug('Domain detected successfully', [
                    'host' => $host,
                    'lab_id' => $domain->lab_id,
                    'domain_id' => $domain->id,
                ]);
            }
            
            // Continue to next middleware/controller
            $response = $next($request);
            
            return $response;
        } catch (\Exception $e) {
            // Log the exception for debugging
            \Log::error('DetectDomain Middleware Error: ' . $e->getMessage(), [
                'host' => $host,
                'trace' => $e->getTraceAsString()
            ]);
            
            // If database is not ready, show domain not found error
            return response()->view('errors.domain-not-found', [], 404);
        }
    }
}
