<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;

class ComplaintController extends Controller
{
    public function index()
    {
        return view('guest.complaints.index');
    }
}
