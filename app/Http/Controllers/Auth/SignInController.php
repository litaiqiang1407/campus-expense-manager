<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SignInService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SignInController extends Controller
{
    protected $signInService;

    public function __construct(SignInService $signInService)
    {
        $this->signInService = $signInService;
    }

    public function index(Request $request)
    {
        return Inertia::render('Signin/Index');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $result = $this->signInService->login($request->only('email', 'password'));

        if (isset($result['type'])) {
            return response()->json($result, 401); 
        }

        return redirect()->route('Home'); 
    }
}

