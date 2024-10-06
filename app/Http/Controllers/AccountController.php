<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AccountController extends Controller
{
    public function index(Request $request )
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('SignIn')->with('error', 'You need to log in.');
        }

        $userInfo = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ];

        if ($request->wantsJson()) {
            return response()->json($userInfo);
        }
    
        return Inertia::render('Account/Index', [
            'user' => $userInfo,
        ]);
    }
}
