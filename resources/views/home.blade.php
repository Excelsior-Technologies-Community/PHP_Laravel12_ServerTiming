<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen">

    <header class="bg-indigo-700 shadow">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-semibold text-white">User Management</h1>
            <span class="text-sm opacity-80">Laravel 12</span>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-8">

        @if(session('success'))
            <div class="bg-green-900 border-l-4 border-green-500 text-green-200 px-4 py-3 rounded mb-6 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-gray-800 border border-gray-700 rounded-lg p-4 mb-6 shadow-sm">
            <form method="GET" action="{{ route('home') }}" class="flex w-full gap-3">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by name or email"
                    class="w-full bg-gray-900 border border-gray-600 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 text-white outline-none">
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded text-sm hover:bg-indigo-500 transition">
                    Search
                </button>
            </form>
        </div>

        <form action="{{ route('users.bulkDelete') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete selected users?')">
            @csrf @method('DELETE')

            <div class="flex justify-between items-center mb-4">
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-700 shadow-sm transition">
                    Delete Selected
                </button>
            </div>

            <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden shadow-sm">
                <table class="w-full text-sm">
                    <thead class="bg-gray-700 text-gray-300">
                        <tr>
                            <th class="px-5 py-3 text-left"><input type="checkbox" id="selectAll" onclick="toggleAll(this)"></th>
                            <th class="px-5 py-3 text-left">Name</th>
                            <th class="px-5 py-3 text-left">Email</th>
                            <th class="px-5 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-750 transition">
                                <td class="px-5 py-3"><input type="checkbox" name="ids[]" value="{{ $user->id }}"></td>
                                <td class="px-5 py-3 font-medium text-white">{{ $user->name }}</td>
                                <td class="px-5 py-3 text-gray-400">{{ $user->email }}</td>
                                <td class="px-5 py-3 text-center">
                                    <button type="button" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $user->id }}').submit();" 
                                            class="text-red-400 hover:text-red-300 text-xs font-bold">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-5 py-6 text-center text-gray-500">No users found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        @foreach($users as $user)
            <form id="delete-form-{{ $user->id }}" action="{{ route('users.delete', $user->id) }}" method="POST" class="hidden">
                @csrf @method('DELETE')
            </form>
        @endforeach

        <div class="mt-4">
            {{ $users->appends(['search' => $search ?? ''])->links() }}
        </div>

        <div class="grid grid-cols-2 gap-4 mt-8">
            <div class="bg-gray-800 border border-gray-700 rounded p-4 shadow-sm border-l-4 border-indigo-500">
                <p class="text-xs text-gray-400">Total Execution Time</p>
                <p class="text-lg font-semibold text-indigo-400 mt-1">{{ $totalDuration }} ms</p>
            </div>
            <div class="bg-gray-800 border border-gray-700 rounded p-4 shadow-sm border-l-4 border-green-500">
                <p class="text-xs text-gray-400">DB Query Time</p>
                <p class="text-lg font-semibold {{ $dbDuration > 50 ? 'text-red-400' : 'text-green-400' }} mt-1">{{ $dbDuration }} ms</p>
            </div>
        </div>
    </main>

    <script>
        function toggleAll(source) {
            checkboxes = document.getElementsByName('ids[]');
            for(var i=0; i<checkboxes.length; i++) checkboxes[i].checked = source.checked;
        }
    </script>
</body>
</html>