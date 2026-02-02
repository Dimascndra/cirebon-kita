<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Redirect user to their dashboard based on role
        if ($user->hasRole('SuperAdmin') || $user->hasRole('Admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('Company')) {
            return redirect()->route('company.dashboard');
        } elseif ($user->hasRole('Applicant')) {
            return redirect()->route('applications.index');
        }

        // Default fallback
        return view('dashboard');
    }
}
