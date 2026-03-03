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