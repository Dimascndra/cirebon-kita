<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     */
    public function show()
    {
        return view('spa');
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Password confirmed.']);
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
