<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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
            $access_token = JWT::encode($payload, $key, 'HS256');

            return response()->json(['access_token' => $access_token], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'nama' => 'required',
            'nama_undangan' => 'required',
            'type' => 'required'
        ]);
        if ($validation->fails()) return response()->json($validation->errors(), 400);
        $payload = [
            'nama' => $request->nama,
            'nama_undangan' => $request->nama_undangan,
            'type' => $request->type
        ];
        $key = config('app.jwt_key');
        $to = JWT::encode($payload, $key, 'HS256');

        return response()->json(['to' => $to], 200);
    }

    public function read(Request $request)
    {
        try {

            $token = $request->to;
            $key = config('app.jwt_key');

            if (!$token) return response()->json(['message' => 'maaf link anda salah'], 401);
            $checkToken = JWT::decode($token, new Key($key, "HS256"));
            return response()->json($checkToken, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => "maaf link anda salah"], 401);
        }
    }
}
