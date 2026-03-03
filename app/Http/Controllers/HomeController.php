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