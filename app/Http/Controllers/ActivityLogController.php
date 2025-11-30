<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Eager load 'user' to avoid N+1 query performance issues
        $logs = ActivityLog::with('user')->latest()->paginate(20);
        
        return view('activity_logs.index', compact('logs'));
    }
}