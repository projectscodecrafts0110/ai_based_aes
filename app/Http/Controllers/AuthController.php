<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Confirm password
        if ($validated['password'] !== $request->input('password_confirmation')) {
            return back()->withErrors(['password_confirmation' => 'Passwords do not match.'])->withInput();
        };

        // Create user
        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Redirect after success
        return redirect()->route('login')
            ->with('success', 'Account created successfully! Please log in.');
    }

    public function login(Request $request)
    {
        //  Validate input
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Attempt login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect based on role
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Logged in successfully as Admin!');
            } else {
                return redirect()->route('dashboard')
                    ->with('success', 'Logged in successfully!');
            }
        }

        // Failed login
        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
