<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validate($request->rules());
        User::create($data);

        return response()->json([
            'status' => true,
            'code' => 201,
            'message' => 'user berhasil didaftarkan'
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validate($request->rules());
        $user = User::where('email', '=', $data['email'], false)->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response([
                'status' => false,
                'code' => 401,
                'message' => 'email atau password salah'
            ], 401);
        }

        $token = $user->createToken('user_login')->plainTextToken;

        return response()->json([
            'status' => true,
            'code' => 200,
            'token' => $token,
            'message' => 'berhasil login',
            'data' => [
                'username' => $user->name,
                'email' => $user->email,
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(
            [
                'status' => true,
                'code' => 200,
                'message' => 'berhasil logout'
            ],
            200
        );
    }
}
