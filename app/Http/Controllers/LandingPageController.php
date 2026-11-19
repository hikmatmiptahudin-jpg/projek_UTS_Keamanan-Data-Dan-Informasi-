<?php

namespace App\Http\Controllers;

class LandingPageController extends Controller
{
    public function index()
    {
        // Menampilkan halaman utama SecureAdmin
        return view('landing.index');
    }
}
