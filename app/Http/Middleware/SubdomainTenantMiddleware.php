<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Company;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubdomainTenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $parts = explode('.', $host);

        // Basic subdomain extraction logic
        // Adjust this logic based on your actual domain setup (e.g., if using .co.uk, etc.)
        // For 'localhost', sub.localhost gives 2 parts.
        // For 'domain.com', sub.domain.com gives 3 parts.
        
        $isSubdomain = false;
        $subdomain = null;

        if ($host === 'localhost') {
            // Main domain
        } elseif (filter_var($host, FILTER_VALIDATE_IP)) {
            // IP address access, usually no subdomains
        } else {
            // Check for subdomain
            // If parts > 2 (sub.domain.com) OR (parts == 2 and ends with localhost)
            if (count($parts) > 2 || (count($parts) === 2 && $parts[1] === 'localhost')) {
                $subdomain = $parts[0];
                if ($subdomain !== 'www') {
                    $isSubdomain = true;
                }
            }
        }

        if ($isSubdomain && $subdomain) {
            $company = Company::where('subdomain', $subdomain)->first();

            if (!$company) {
                abort(404, 'Company/Tenant not found');
            }

            // Verify logged-in user belongs to this tenant
            if (auth()->check()) {
                // If user belongs to a different company, log them out or deny access
                if (auth()->user()->company_id !== $company->id) {
                    // Option 1: Logout (strict)
                    // auth()->logout();
                    // return redirect()->route('login')->with('error', 'You do not have access to this company.');
                    
                    // Option 2: 403 Forbidden
                    abort(403, 'You are logged in to a different company. Please logout and login to the correct company domain.');
                }
                
                // Also ensure the global scope permissions are set correctly
                setPermissionsTeamId($company->id);
            }
            
            // Store company in request for controllers to use if needed
            $request->attributes->set('current_company', $company);
        }

        return $next($request);
    }
}
