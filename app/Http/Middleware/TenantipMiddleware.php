<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantipMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       $allowedlist = config('access.allowed_tenant_ip_list');

       $ipAddresses = explode(';', $allowedlist);

        if (! in_array($request->ip(), $ipAddresses)) {

          // \Log::error('IP address is not whitelisted', ['ip address', $request->ip()]);
           return response()->json([
              'status' => 'error',
              'data' => null,
              'message' => 'IP Address '.$request->ip().' is not in allowed list',
          ], Response::HTTP_FORBIDDEN);

      }

        return $next($request);
    }
}
