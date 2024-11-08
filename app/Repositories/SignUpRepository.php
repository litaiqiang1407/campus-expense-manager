<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SignUpRepository 
{
    public function createUser(array $data)
    {
        return User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function userExists(string $email)
    {
        return User::where('email', $email)->exists();
    }
}
