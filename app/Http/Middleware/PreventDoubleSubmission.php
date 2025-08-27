<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class PreventDoubleSubmission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only apply to POST, PUT, PATCH, DELETE requests
        if (!in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return $next($request);
        }

        // Generate unique key based on user, IP, route, and request data
        $key = $this->generateSubmissionKey($request);
        
        // Check if this exact request was submitted recently (within 3 seconds)
        if (Cache::has($key)) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan sedang diproses. Mohon tunggu sebentar dan jangan klik berulang kali.'
            ], 429); // 429 Too Many Requests
        }

        // Store the submission key for 3 seconds
        Cache::put($key, true, 3);

        $response = $next($request);

        // Remove the key after successful processing (optional optimization)
        Cache::forget($key);

        return $response;
    }

    /**
     * Generate a unique key for the submission
     */
    private function generateSubmissionKey(Request $request): string
    {
        $userId = Auth::check() ? Auth::user()->id : 'anonymous';
        $ip = $request->ip();
        $route = $request->route() ? $request->route()->getName() : $request->path();
        $method = $request->method();
        
        // For forms with file uploads, we exclude files from hash to prevent issues
        $data = $request->except(['_token', '_method']);
        if ($request->hasFile('*')) {
            // Remove file data but keep file names for uniqueness
            foreach ($request->allFiles() as $key => $file) {
                if (is_array($file)) {
                    $data[$key] = count($file) . '_files';
                } else {
                    $data[$key] = $file->getClientOriginalName();
                }
            }
        }
        
        $dataHash = md5(serialize($data));
        
        return "double_submission:{$userId}:{$ip}:{$method}:{$route}:{$dataHash}";
    }
}
