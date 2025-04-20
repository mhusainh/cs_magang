<?php

namespace App\Http\Middleware;

use App\Repositories\LogRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ApiLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function __construct(private LogRepository $logRepository) {}

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Check if the response is an instance of JsonResponse
        if ($response instanceof \Illuminate\Http\JsonResponse) {
            $responseData = $response->getData(true);

            // Determine the error message based on the response structure
            $errorMessage = null;
            if (isset($responseData['meta']['code']) && ($responseData['meta']['code'] < 200 || $responseData['meta']['code'] >= 300)) {
                $errorMessage = $responseData['meta']['message'] ?? null;
            }
            // Log the request and response
            $this->logRepository->create([
                'feature' => $request->path(),
                'created_time' => now()->format('H:i:s'),
                'request_data' => json_encode($request->all()),
                'response_data' => json_encode($responseData),
                'error_message' => $errorMessage,
            ]);
        }

        return $response;
    }
}
