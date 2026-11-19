<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditLogMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log mutating requests
        if (in_array(strtoupper($request->method()), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            try {
                AuditLog::create([
                    'user_id' => optional($request->user())->id,
                    'method' => $request->method(),
                    'route' => optional($request->route())->uri(),
                    'ip_address' => $request->ip(),
                    'user_agent' => substr((string) $request->header('User-Agent'), 0, 1024),
                    'payload' => collect($request->except(['password', 'password_confirmation', '_token']))
                        ->map(fn($v) => is_string($v) && strlen($v) > 500 ? substr($v, 0, 500) . '…' : $v)
                        ->all(),
                ]);
            } catch (\Throwable $e) {
                // swallow logging errors
            }
        }

        return $response;
    }
}


