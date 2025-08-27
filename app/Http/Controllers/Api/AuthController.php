<?php

// app/Http/Controllers/Api/AuthController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'user_name'      => 'required|string|max:20|unique:user_account,user_name',
            'user_full_name' => 'required|string|max:50',
            'user_password'  => 'required|string|min:8',
            'role_id'        => 'required|string|max:16', // bisa tambahkan: exists:user_role,role_id
        ]);

        return DB::transaction(function () use ($data) {
            // Ambil next number dari USER### secara atomik
            $nextNumber = DB::table('user_account')
                ->selectRaw('COALESCE(MAX(CAST(SUBSTRING(user_id, 5) AS UNSIGNED)), 0) + 1 AS next')
                ->lockForUpdate() // kunci baris selama transaksi, cegah duplikasi saat paralel
                ->value('next');

            $payload = $data;
            $payload['user_id']        = 'USER' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            $payload['user_password']  = Hash::make($payload['user_password']);

            $user = UserAccount::create($payload);

            $token = $user->createToken('api')->plainTextToken;

            return response()->json([
                'user'  => new UserResource($user),
                'token' => $token,
            ], 201);
        });
    }


    public function login(Request $request)
    {
        $data = $request->validate([
            'user_name'     => 'required|string',
            'user_password' => 'required|string',
        ]);

        $user = UserAccount::where('user_name', $data['user_name'])->first();

        if (!$user || !Hash::check($data['user_password'], $user->user_password)) {
            throw ValidationException::withMessages([
                'user_name' => ['Invalid credentials'],
            ]);
        }

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'user'  => new UserResource($user),
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function me(Request $request)
    {
        return new UserResource($request->user());
    }
}
