<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return view('dashboard.admin');
        }

        if ($user->isReceptionist()) {
            return view('dashboard.receptionist');
        }

        return view('dashboard.guest');
    }
}
