# PHP_Laravel12_ServerTiming

## Introduction

The PHP_Laravel12_ServerTiming project is a Laravel 12 application designed to demonstrate the use of the Server-Timing HTTP header. Server-Timing allows developers to measure and report server-side performance metrics directly to the browser, making it easier to identify bottlenecks and optimize application performance. This project leverages the beyondcode/laravel-server-timing package to track execution times, database queries, and custom metrics in a clean and user-friendly interface.

The application displays a simple Users List from the database and shows the server timing metrics on the same page, giving a practical demonstration of how server-side performance data can be captured and analyzed in real-time.

---

## Project Overview

- Demonstrates server-side performance monitoring in a Laravel 12 application.

- Uses BeyondCode/laravel-server-timing package to track metrics.

- Measures total execution time and database query duration.

- Displays a Users List fetched from the database.

- Shows server timing metrics directly on the page in real-time.

- Built with TailwindCSS for a clean and responsive interface.

- Implements middleware for performance measurement.

- Provides a practical example for optimizing and analyzing Laravel app performance.

---

## Prerequisites

- PHP >= 8.1

- Composer

- MySQL / MariaDB

- Node.js & NPM (optional)

---

## Step 1: Create Laravel 12 Project

```bash
composer create-project laravel/laravel  PHP_Laravel12_ServerTiming "12.*"
cd PHP_Laravel12_ServerTiming
```

---

## Step 2: Database Setup

Update .env:

```.env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=server_timing_db
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations:

```bash
php artisan migrate
```

Optional: Seed some data:

```bash
php artisan db:seed
```
---

## Step 3: Install Server-Timing Package

```bash
composer require beyondcode/laravel-server-timing
```

Optional: publish config:

```bash
php artisan vendor:publish --provider="BeyondCode\ServerTiming\ServerTimingServiceProvider"
```

---

## Step 4: Register Middleware in bootstrap/app.php

Open bootstrap/app.php and add the middleware registration:

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
            // Prepend server timing middleware
        $middleware->prepend(
            \BeyondCode\ServerTiming\Middleware\ServerTimingMiddleware::class
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
```

---

## Step 5: Server Timing Middleware

Generate middleware:

```bash
php artisan make:middleware ServerTimingMiddleware
```

Open this file:

app/Http/Middleware/ServerTimingMiddleware.php

```php
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
```

---

## Step 6: Create Controller

Generate controller:

```bash
php artisan make:controller HomeController
```
app/Http/Controllers/HomeController.php:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Start total execution timer
        $startTotal = microtime(true);

        // Start DB timer
        $startDB = microtime(true);
        $users = DB::table('users')->get();
        $dbDuration = round((microtime(true) - $startDB) * 1000, 2); // in ms

        // Stop total execution timer
        $totalDuration = round((microtime(true) - $startTotal) * 1000, 2);

        return view('home', compact('users', 'dbDuration', 'totalDuration'));
    }
}
```

---

## Step 7: Create Blade File

resources/views/home.blade.php

```html
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Laravel Server Timing Demo</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-100 font-sans">

        <!-- Header -->
        <header class="bg-indigo-600 text-white shadow-md">
            <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold">Laravel Server Timing Demo</h1>
                <span class="text-sm">v1.0</span>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-6 py-10 space-y-8">

            <!-- Users List -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Users List</h2>

                @if($users->isEmpty())
                    <p class="text-gray-500">No users found.</p>
                @else
                    <ul class="divide-y divide-gray-200">
                        @foreach($users as $user)
                            <li class="py-3 flex justify-between items-center">
                                <span class="text-gray-700 font-medium">{{ $user->name }}</span>
                                <span class="text-gray-500 text-sm">{{ $user->email }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- Server Timing Metrics -->
            <div class="bg-indigo-50 border-l-4 border-indigo-600 rounded p-6">
                <h3 class="text-indigo-700 font-semibold mb-2">Server Timing Metrics</h3>
                <ul class="text-gray-700 text-sm space-y-1">
                    <li><strong>Total Execution:</strong> {{ $totalDuration }} ms</li>
                    <li><strong>DB Queries:</strong> {{ $dbDuration }} ms</li>
                </ul>
            </div>

        </main>

    </body>
    </html>
```

## Step 8: routes/web.php:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);
```

---

## Step 9: Run Development Server

Start Laravel server:

```bash
php artisan serve
```
Visit:

```bash
http://127.0.0.1:8000/
```

---

## Output

<img width="1919" height="1027" alt="Screenshot 2026-03-03 161806" src="https://github.com/user-attachments/assets/58145c1e-4246-4684-9a92-16756c12719d" />

---

## Project Structure

```
PHP_Laravel12_ServerTiming/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── HomeController.php
│   │   └── Middleware/
│   │       └── ServerTimingMiddleware.php
├── bootstrap/
│   └── app.php
├── config/
│   └── timing.php       <-- Optional server timing config
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   └── views/
│       └── home.blade.php
├── routes/
│   └── web.php
├── storage/
├── vendor/
├── .env
└── composer.json
```

--- 

Your PHP_Laravel12_ServerTiming Project is now ready!



