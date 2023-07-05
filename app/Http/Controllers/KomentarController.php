<?php

namespace App\Http\Controllers;

use App\Models\Komentar;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class KomentarController extends Controller
{
    //

    public function komentar(Request $request)
    {

        try {

            $token = $request->header('access_token');
            $key = config('app.jwt_key');
            if (!$token) return response()->json(['message' => 'maaf anda tidak bisa komentar'], 401);
            $checkToken = JWT::decode($token, new Key($key, "HS256"));
            $komentar = Komentar::create([
                'nama' => $request->nama,
                'type' => $request->type,
                'komentar' => $request->komentar,
            ]);

            return response()->json(['message' => 'komentar anda berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function allKomentar(Request $request)
    {
        try {
            $komentar = Komentar::orderBy('id', "DESC")->get();
            return response()->json($komentar, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
