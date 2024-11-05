<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Services\SignUpService;
use Inertia\Inertia;

class SignUpController extends Controller
{
    protected $signUpService;

    public function __construct(SignUpService $signUpService)
    {
        $this->signUpService = $signUpService;
    }
    
    public function index(Request $request)
    {
        return Inertia::render('Signup/Index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $result = $this->signUpService->register($request->only('email', 'password'));

        if (isset($result['type'])) {
            return response()->json($result, 409); 
        }

        return redirect()->route('Home'); 
    }
}