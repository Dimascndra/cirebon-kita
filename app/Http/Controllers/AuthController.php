<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // View: Login Page
    public function showLogin()
    {
        return view('auth.login');
    }

    // View: Register Page
    public function showRegister()
    {
        return view('auth.register');
    }

    // View: Profile Page
    public function showProfile()
    {
        return view('auth.profile');
    }

    // API: Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $data = $this->authService->login($request->only('email', 'password'));
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => $data
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function webLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = \Illuminate\Support\Facades\Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken; // Also give token for SPA parts

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'token' => $token,
                    'user' => $user
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'The provided credentials do not match our records.',
        ], 401);
    }

    // API: Register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $data = $this->authService->register($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'data' => $data
        ]);
    }

    // API: Logout
    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }

    // API: Get Current User
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    }
}
