<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $startTotal = microtime(true);
        $search = $request->search;

        $startDB = microtime(true);

        $users = DB::table('users')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
            })
            ->paginate(10);

        $dbDuration = round((microtime(true) - $startDB) * 1000, 2);
        $totalDuration = round((microtime(true) - $startTotal) * 1000, 2);

        if ($dbDuration > 100) {
            Log::warning("Slow Query Detected: {$dbDuration}ms");
        }

        return view('home', compact('users', 'dbDuration', 'totalDuration', 'search'));
    }

    public function destroy($id)
    {
        DB::table('users')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'User deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids');
        if ($ids) {
            DB::table('users')->whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', 'Selected users deleted successfully!');
        }
        return redirect()->back()->with('error', 'No users selected.');
    }
}