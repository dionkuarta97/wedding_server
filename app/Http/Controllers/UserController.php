<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Firebase\JWT\JWT;

class UserController extends Controller
{
    //
    public function login(Request $request)
    {

        try {
            $validation = Validator::make($request->all(), [
                'username' => "required",
                'password' => "required"
            ], [
                'username.required' => 'username tidak boleh kosong',
                'password' => 'password tidak boleh kosong'
            ]);

            if ($validation->fails()) return response()->json($validation->errors(), 400);
            $checkUser = User::where('username', $request->username)->first();
            if (!$checkUser) return response()->json(['message' => 'username/password salah']);
            if ($checkUser['password'] !== $request->password) return response()->json(['message' => 'username/password salah'], 404);
            $payload = [
                'id' => $checkUser['id'],
                'username' => $checkUser['username']
            ];
            $key = config('app.jwt_key');
            $access_tokeen = JWT::encode($payload, $key, 'HS256');

            return response()->json(['access_token' => $access_tokeen], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
}
