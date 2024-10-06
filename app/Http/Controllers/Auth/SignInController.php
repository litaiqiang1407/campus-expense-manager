<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SignInController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Signin/Index');
    }
}