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

    public function updatePassword(Request $request) {
        $fields = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        $user = Auth::user();

        if(!Hash::check($fields['old_password'], $user->password)) {
            return response([
                'message' => 'Bad credentials'
            ], 401);
        }

        $user->password = Hash::make($fields['new_password']);
        $user->save();

        return response()->json([
            'message' => 'Password updated successfully',
            'user' => $user,
        ], 200);
    }

    public function updateProfile(Request $request) {
        $fields = $request->validate([
            'first_name' => 'required|string',
            'middle_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string',
        ]);

        $user = Auth::user();
        $updated = $user->update($fields);

        if($updated) {
            return response()->json([
                'message' => 'Profile updated successfully',
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Profile update failed',
            ], 500);
        }
    }

    public function requestPasswordReset(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
        ]);
        $user = User::where('email', $fields['email'])->first();
        if(!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $user->request_password_reset = true;
        $user->save();

        return response()->json([
            'message' => 'Password reset requested',
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
