<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        // Start total execution timer
        $startTotal = microtime(true);

        // Start DB timer
        $startDB = microtime(true);

        $users = DB::table('users')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            })
            ->paginate(5);

        $dbDuration = round((microtime(true) - $startDB) * 1000, 2);
        $totalDuration = round((microtime(true) - $startTotal) * 1000, 2);

        return view('home', compact('users', 'dbDuration', 'totalDuration', 'search'));
    }

    // DELETE FUNCTION
    public function destroy($id)
    {
        DB::table('users')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'User deleted successfully!');
    }
}