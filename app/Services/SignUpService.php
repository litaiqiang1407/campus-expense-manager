<?php

namespace App\Services;

use App\Repositories\SignUpRepository;
use Illuminate\Support\Facades\Auth;

class SignUpService
{
    protected $signUpRepository;

    public function __construct(SignUpRepository $signUpRepository)
    {
        $this->signUpRepository = $signUpRepository;
    }

    public function register(array $data)
    {
        if ($this->signUpRepository->userExists($data['email'])) {
            return [
                'type' => 'emailExist',
                'error' => 'This email is already registered. Please sign in instead.',
            ];
        }

        $user = $this->signUpRepository->createUser($data);
        Auth::login($user);

        return $user; 
    }
}
