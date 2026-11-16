<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalUsers = User::count();
        $openIncidents = Incident::where('status', 'open')->count();
        $pendingIncidents = Incident::where('status', 'pending')->count();
        $inProgressIncidents = Incident::where('status', 'in_progress')->count();

        return view('home', compact(
            'totalUsers',
            'openIncidents',
            'pendingIncidents',
            'inProgressIncidents'
        ));
    }
}
