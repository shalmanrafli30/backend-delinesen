<?php

// app/Http/Controllers/Api/UserAccountController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserAccountController extends Controller
{
    public function index()
    {
        return UserResource::collection(UserAccount::with('role')->get());
    }

    public function show(UserAccount $user_account)
    {
        return new UserResource($user_account->load('role'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'       => 'required|string|max:16|unique:user_account,user_id',
            'user_name'     => 'required|string|max:20|unique:user_account,user_name',
            'user_full_name'=> 'required|string|max:50',
            'user_password' => 'required|string|min:8',
            'role_id'       => 'required|string|max:16',
        ]);
        $data['user_password'] = Hash::make($data['user_password']);

        $user = UserAccount::create($data);
        return new UserResource($user);
    }

    public function update(Request $request, UserAccount $user_account)
    {
        $data = $request->validate([
            'user_name'     => 'sometimes|string|max:20|unique:user_account,user_name,'.$user_account->user_id.',user_id',
            'user_full_name'=> 'sometimes|string|max:50',
            'user_password' => 'sometimes|string|min:8',
            'role_id'       => 'sometimes|string|max:16',
        ]);
        if (isset($data['user_password'])) {
            $data['user_password'] = Hash::make($data['user_password']);
        }

        $user_account->update($data);
        return new UserResource($user_account);
    }

    public function destroy(UserAccount $user_account)
    {
        $user_account->delete();
        return response()->json(['message'=>'Deleted'],200);
    }
}
