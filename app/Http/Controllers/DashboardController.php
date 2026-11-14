<?php

namespace App\Http\Controllers;

use App\Models\AdminData;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung seluruh data yang tersimpan
        $totalData = AdminData::count();

        // Mengirim jumlah data ke halaman dashboard
        return view('dashboard', compact('totalData'));
    }
}
