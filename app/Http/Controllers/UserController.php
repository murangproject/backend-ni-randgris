<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return User::with('schedules')->get()->values();
    }

    public function resetPassword(string $id) {
        $user = User::where('id', $id)->get()->first();

        if($user) {
            $user->password = Hash::make(env('INITIAL_USER_PASSWORD'));
            $user->save();

            return response()->json(
                [
                    'message' => 'Password reset successfully',
                    'user' => $user,
                ],
                200
            );
        }
    }

    public function store(Request $request)
    {
        $fields = $request->validate(
            [
                'first_name' => 'required|string',
                'middle_name'=> 'string',
                'last_name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'role_type' => 'required|in:admin,faculty,attendance_checker',
            ]
        );

        $user = User::create(
            [
                'first_name' => $fields['first_name'],
                'middle_name' => $fields['middle_name'],
                'last_name' => $fields['last_name'],
                'email' => $fields['email'],
                'role_type' => $fields['role_type'],
                'password' => env('INITIAL_USER_PASSWORD'),
            ]
        );

        if($user) {
            return response()->json(
                [
                    'message' => 'User created successfully',
                    'user' => $user,
                ],
                201
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::where('id', $id)->first();
        if($user) {
            $updated = $user->update($request->all());
            if($updated) {
                return response()->json(
                    [
                        'message' => 'User updated successfully',
                        'user' => $user,
                    ],
                    200
                );
            }
        }
    }

    public function destroy(string $id)
    {
        $user = User::where('id', $id)->first();
        if($user) {
            $user->is_deleted = true;
            $user->save();
            return response()->json(
                [
                    'message' => 'User archived successfully',
                    'user' => $user,
                ],
                200
            );
        }
    }

    public function restore(string $id)
    {
        $user = User::where('id', $id)->first();
        if($user) {
            $user->is_deleted = false;
            $user->save();
            return response()->json(
                [
                    'message' => 'User restored successfully',
                    'user' => $user,
                ],
                200
            );
        }
    }
}
