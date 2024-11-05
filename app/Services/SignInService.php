<?php

namespace App\Services;

use App\Repositories\SignInRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SignInService
{
    protected $signInRepository;

    public function __construct(SignInRepository $signInRepository)
    {
        $this->signInRepository = $signInRepository;
    }

    public function login(array $data)
    {
        $user = $this->signInRepository->findUser($data['email']);

        if (!$user) {
            return [
                'type' => 'email',
                'error' => 'This email is not registered. Please sign up.',
            ];
        }

        if (!Hash::check($data['password'], $user->password)) {
            return [
                'type' => 'password',
                'error' => 'Incorrect password. Please try again.',
            ];
        }

        // Log in the user
        Auth::login($user);

        return $user; // Successful login response
    }
}
