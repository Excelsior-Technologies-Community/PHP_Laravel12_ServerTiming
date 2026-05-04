<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800">

    <!-- NAVBAR -->
    <header class="bg-indigo-600 text-white shadow">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-semibold">User Management</h1>
            <span class="text-sm opacity-80">Laravel 12</span>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-8">

        <!-- SUCCESS ALERT -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-6 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- SEARCH BAR -->
        <div class="bg-white border rounded-lg p-4 mb-6 shadow-sm">
            <form method="GET" action="{{ route('home') }}" class="flex w-full gap-3">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or email"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-400 outline-none">

                <button class="bg-indigo-600 text-white px-5 py-2 rounded text-sm hover:bg-indigo-700 transition">
                    Search
                </button>
            </form>
        </div>

        <!-- TABLE -->
        <div class="bg-white border rounded-lg overflow-hidden shadow-sm">

            <!-- TABLE HEADER -->
            <div class="px-5 py-3 border-b bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700">Users List</h2>
            </div>

            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="px-5 py-3 text-left">ID</th>
                        <th class="px-5 py-3 text-left">Name</th>
                        <th class="px-5 py-3 text-left">Email</th>
                        <th class="px-5 py-3 text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr class="border-t hover:bg-indigo-50 transition">
                            <td class="px-5 py-3">{{ $user->id }}</td>
                            <td class="px-5 py-3 font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $user->email }}</td>
                            <td class="px-5 py-3 text-center">

                                <form action="{{ route('users.delete', $user->id) }}" method="POST"
                                    onsubmit="return confirm('Delete this user?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="bg-red-100 text-red-600 px-3 py-1 rounded text-xs hover:bg-red-200">
                                        Delete
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-6 text-center text-gray-400">
                                No users found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- PAGINATION -->
            <div class="px-5 py-3 border-t bg-gray-50">
                {{ $users->links() }}
            </div>
        </div>

        <!-- METRICS -->
        <div class="grid grid-cols-2 gap-4 mt-6">

            <div class="bg-white border rounded p-4 shadow-sm border-l-4 border-indigo-500">
                <p class="text-xs text-gray-500">Total Execution Time</p>
                <p class="text-lg font-semibold text-indigo-600 mt-1">
                    {{ $totalDuration }} ms
                </p>
            </div>

            <div class="bg-white border rounded p-4 shadow-sm border-l-4 border-green-500">
                <p class="text-xs text-gray-500">DB Query Time</p>
                <p class="text-lg font-semibold text-green-600 mt-1">
                    {{ $dbDuration }} ms
                </p>
            </div>

        </div>

    </main>

</body>

</html>