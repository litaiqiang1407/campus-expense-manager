<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Http\Request;
use App\Models\Wallet;
use Inertia\Inertia;

class HomeController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }
    public function index(Request $request)
    {
        $user_id = $request->user()->id; 

        $totalBalance = Wallet::where('user_id', $user_id)->sum('balance');

        $walletList = $this->walletService->getWallets($user_id, 3);
        
        if ($request->wantsJson()) {
            return response()->json([
                'totalBalance' => $totalBalance,
                'walletList' => $walletList
            ]);
        }

        return Inertia::render('Home/Index', [
            'totalBalance' => $totalBalance,
            'walletList' => $walletList
        ]);
    }
}