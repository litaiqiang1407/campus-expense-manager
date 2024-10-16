<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;

class CheckWallet
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($request->route()->getName() === 'CreateWallet' || $request->route()->getName() === 'StoreWallet') {
                return $next($request);
            }

            if (!Wallet::where('user_id', $user->id)->exists()) {
                return redirect()->route('CreateWallet', ['walletTypeId' => 1]);
            }
        }

        return $next($request);
    }
}
