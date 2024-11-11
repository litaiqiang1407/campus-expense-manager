<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\WalletRepository;
use App\Models\Wallet;

class CheckWallet
{
    protected $walletRepo;

    public function __construct(WalletRepository $walletRepo)
    {
        $this->walletRepo = $walletRepo;
    }

    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            if (!Wallet::where('user_id', $user->id)->exists()) {
                $this->walletRepo->createFirstWallet($user->id);
                return redirect()->route('Home');
            }
        }

        return $next($request);
    }
}
