<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use function Psy\debug;

class AuthController extends Controller
{
    /**
     * Register new user and issue token
     */
    public function register(Request $request)
    {
        // $request->validate([
            //     'name'     => 'required|string|max:255',
            //     'email'    => 'required|string|email|max:255|unique:users',
            //     'password' => 'required|string|min:6|confirmed',
            //     // 'role' => 'required|string'
            // ]);
            
        // \Log::info($request->email);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        // You can assign default role here if needed
        // $user->assignRole('editor');

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully.',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    /**
     * Login user and return token
     */
    public function login(Request $request)
    {
        // $request->validate([
        //     'email'    => 'required|email',
        //     'password' => 'required',
        // ]);

        \Log::info('User login attempt', ['email' => $request->email]);
        if (!Auth::attempt($request->only('email', 'password'))) {
            // throw ValidationException::withMessages([
            //     'email' => ['The provided credentials are incorrect.'],
            // ]);
            return response("Invalid Credentials", 400);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    /**
     * Logout user (revoke current token)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    public function test(Request $request){
        return response("sfasfasfsdf"); 
    }
}
