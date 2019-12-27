<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('frontend.home.welcome');
    }

    public function impressum()
    {
        return view('frontend.home.impressum');
    }

    public function agb()
    {
        return view('frontend.home.agb');
    }
}
