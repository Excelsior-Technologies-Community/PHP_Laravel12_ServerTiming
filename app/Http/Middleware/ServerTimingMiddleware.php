<?php

namespace App\Http\Middleware;

use Closure;
use BeyondCode\ServerTiming\Facades\ServerTiming;
use Illuminate\Support\Facades\DB;

class ServerTimingMiddleware
{
    public function handle($request, Closure $next)
    {
        // Start total execution timer
        ServerTiming::start('total_execution'); 

        // Enable query logging
        DB::enableQueryLog();

        $response = $next($request);

        // Stop total execution timer
        ServerTiming::stop('total_execution');

        // Measure DB queries time (optional)
        $queries = DB::getQueryLog();
        $queryCount = count($queries);

        // Start a custom DB queries timer
        ServerTiming::start('db_queries');
        ServerTiming::stop('db_queries');

        // Attach Server-Timing header
        ServerTiming::send($response);

        return $response;
    }
}