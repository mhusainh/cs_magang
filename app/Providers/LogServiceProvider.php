<?php

namespace App\Providers;

use App\Services\LogService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

class LogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LogService::class, function ($app) {
            return new LogService($app->make('App\\Repositories\\LogRepository'));
        });
    }

    public function boot(): void
    {
        $this->app->resolving('log', function ($logger) {
            $logger->macro('activity', function (string $feature, array $requestData = [], array $responseData = [], ?string $errorMessage = null) {
                $logService = app(LogService::class);
                return $logService->create([
                    'feature' => $feature,
                    'created_time' => now(),
                    'request_data' => json_encode($requestData),
                    'response_data' => json_encode($responseData),
                    'error_message' => $errorMessage
                ]);
            });
        });
    }
}
