<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Wallet;
use App\Models\WalletType;
use App\Models\Icon;

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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'wallet_type_id' => 'required',
            'balance' => 'required',
            'icon_id' => 'required',
        ]);

        Wallet::create([
            'name' => $request->name,
            'wallet_type_id' => $request->wallet_type_id,
            'balance' => $request->balance,
            'icon_id' => $request->icon_id,
            // 'user_id' => auth()->id(),
        ]);

        return redirect()->route('my-wallet.index');
    }

    public function create(Request $request)
    {
        $walletTypeID = $request->walletTypeId;
        $walletType = WalletType::select('id', 'name')->find($walletTypeID);

        $icons = Icon::select('id', 'name', 'path')->get();

        $walletTypeList = WalletType::select('id', 'name')->get();
    
        if ($request->wantsJson()) {
            return response()->json([
                'walletType' => $walletType,
                'icons' => $icons,
                'walletTypeList' => $walletTypeList,
            ]);
        }
    
        return Inertia::render('MyWallet/Create', [
            'walletType' => $walletType,
            'icons' => $icons,
            'walletTypeList' => $walletTypeList,
        ]);
    }
    
    public function edit(Request $request, $walletId)
    {
        $wallet = Wallet::select(
            'wallets.id', 
            'wallets.name', 
            'wallets.balance', 
            'wallets.wallet_type_id',
            'wallet_types.name as walletTypeName',  
            'wallets.icon_id',
            'icons.path as icon_path', 
            'icons.name as icon_name'
        )
        ->join('wallet_types', 'wallets.wallet_type_id', '=', 'wallet_types.id') 
        ->join('icons', 'wallets.icon_id', '=', 'icons.id') 
        ->findOrFail($walletId); 
    
        $walletTypes = WalletType::select('id', 'name')->get();
    
        // Check if the request is for JSON response
        if ($request->wantsJson()) {
            return response()->json([
                'wallet' => $wallet,
                'walletTypes' => $walletTypes,
            ]);
        }
    
        // Render the Inertia page for edit
        return Inertia::render('MyWallet/Edit', [
            'wallet' => $wallet,
            'walletTypes' => $walletTypes,
        ]);
    }
}
