<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('user')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('history.index', compact('logs'));
    }
}