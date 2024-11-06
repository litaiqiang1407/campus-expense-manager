<?php

namespace App\Repositories;

use App\Models\User;

class SignInRepository 
{
    public function findUser(string $email)
    {
        return User::where('email', $email)->first();
    }
}
