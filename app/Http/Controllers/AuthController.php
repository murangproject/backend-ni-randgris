<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function changePassword(Request $request) {
        $fields = $request->validate([
            'password' => 'required|confirmed',
        ]);

        $user = Auth::user();

        $user->password = Hash::make($fields['password']);
        $user->save();

        return response()->json([
            'message' => 'Password changed successfully',
            'user' => $user,
        ], 200);
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::with('schedules')->where('email', $fields['email'])->first();

        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Bad credentials'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $initialize = $fields['password'] == env('INITIAL_USER_PASSWORD');

        $response = [];

        if($initialize) {
            $response = [
                'user' => $user,
                'token' => $token,
                'initialize' => $initialize
            ];
        } else {
            $response = [
                'user' => $user,
                'token' => $token
            ];
        }

        return response($response, 201);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return [
            'message' => 'Logged out'
        ];
    }

    public function role(Request $request) {
        $user = Auth::user();
        $role = $user->role_type;
        return response()->json(['role_type' => $role], 200);
    }
}
