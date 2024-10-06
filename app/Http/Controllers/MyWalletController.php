<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Wallet;
use App\Models\WalletType;

class MyWalletController extends Controller
{
    public function index(Request $request)
    {
        $wallets = Wallet::select(
            'wallets.id', 
            'wallets.name', 
            'wallets.balance', 
            'icons.path as icon_path', 
            'icons.name as icon_name'
        )
        ->join('icons', 'wallets.icon_id', '=', 'icons.id') // Join with icons table
        ->get();

        $walletTypes = WalletType::select('id', 'name')->get();

        if ($request->wantsJson()) {
            return response()->json([
                'walletTypes' => $walletTypes,
                'wallets' => $wallets]);
        }

        return Inertia::render('MyWallet/Index', [
            'walletTypes' => $walletTypes,
            'wallets' => $wallets,
        ]);
    }
}
