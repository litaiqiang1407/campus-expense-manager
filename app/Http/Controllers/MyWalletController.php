<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Wallet;

class MyWalletController extends Controller
{
    public function index(Request $request)
    {
        // Join with the Icon model to fetch icon fields
        $wallets = Wallet::select('id', 'name', 'balance', 'icon_id')
            ->join('icons', 'wallets.icon_id', '=', 'icons.id') // Assuming your icon table is named 'icons'
            ->select('wallets.id', 'wallets.name', 'wallets.balance', 'icons.path as icon_path', 'icons.name as icon_name')
            ->get(); // Retrieve the data

        if ($request->wantsJson()) {
            return response()->json($wallets);
        }

        return Inertia::render('MyWallet/Index', [
            'wallets' => $wallets,
        ]);
    }
}
