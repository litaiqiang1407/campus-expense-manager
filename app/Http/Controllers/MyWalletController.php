<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\WalletService;

class MyWalletController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function index(Request $request)
    {
        $wallets = $this->walletService->getWallets();
        $walletTypes = $this->walletService->getWalletTypes();

        if ($request->wantsJson()) {
            return response()->json([
                'walletTypes' => $walletTypes,
                'wallets' => $wallets
            ]);
        }

        return Inertia::render('MyWallet/Index', [
            'walletTypes' => $walletTypes,
            'wallets' => $wallets,
        ]);
    }

    public function store(Request $request)
    {
        $user_id = $request->user()->id;

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'wallet_type_id' => 'required|exists:wallet_types,id',
            'balance' => 'required|numeric|min:0',
            'icon_id' => 'required|exists:icons,id',
        ]);

        $this->walletService->createWallet($validatedData, $user_id);

        return response()->json([
            'success' => true,
            'message' => 'Wallet created successfully!',
        ]);
    }

    public function create(Request $request)
    {
        $walletTypeID = $request->walletTypeId;
        $walletType = $this->walletService->getWalletTypes()->find($walletTypeID);
        $icons = $this->walletService->getIcons();
        $walletTypeList = $this->walletService->getWalletTypes();

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
        $wallet = $this->walletService->getWalletById($walletId);
        $walletTypes = $this->walletService->getWalletTypes();

        if ($request->wantsJson()) {
            return response()->json([
                'wallet' => $wallet,
                'walletTypes' => $walletTypes,
            ]);
        }

        return Inertia::render('MyWallet/Edit', [
            'wallet' => $wallet,
            'walletTypes' => $walletTypes,
        ]);
    }

    public function update(Request $request, $walletId)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'wallet_type_id' => 'required|exists:wallet_types,id',
            'balance' => 'required|numeric|min:0',
            'icon_id' => 'required|exists:icons,id',
        ]);

        $this->walletService->updateWallet($walletId, $validatedData);
    
        return response()->json([
            'success' => true,
            'message' => 'Wallet updated successfully!',
        ]);
    }           
}
