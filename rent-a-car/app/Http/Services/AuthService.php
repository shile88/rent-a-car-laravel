<?php

namespace App\Http\Services;


use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login($email, $password)
    {
        $user = $this->getUserByCredentials($email, $password);

        if(!$user)
            return false;
        return $user->createToken('access_token')->plainTextToken;
    }

    private function getUserByCredentials($email, $password)
    {
        $user = User::query()->where('email', $email)->first();

        if($user && Hash::check($password, $user->password))
            return $user;
        else
            return null;
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
    }
}
