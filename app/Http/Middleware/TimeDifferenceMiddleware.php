<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TimeDifferenceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->headers->get('Content-Type') === 'application/json') {
            $data = json_decode($response->getContent(), true);
            $data = $this->transformTimeAgo($data);
            $response->setContent(json_encode($data));
        }

        return $response;
    }
    protected function transformTimeAgo($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                // Apply time ago format for specific attributes
                if (in_array($key, ['created_at', 'updated_at']) && $value) {
                    $data[$key] = Carbon::parse($value)->diffForHumans();
                }

                // Recursively check nested arrays or objects
                if (is_array($value) || is_object($value)) {
                    $data[$key] = $this->transformTimeAgo($value);
                }
            }
        }

        return $data;
    }
}
