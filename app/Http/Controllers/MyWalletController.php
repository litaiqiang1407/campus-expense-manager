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
        $userId = $request->user()->id;
        $wallets = $this->walletService->getWallets($userId);
        $walletTypes = $this->walletService->getWalletTypes();
        $totalWalletBalance = $this->walletService->recalculateTotalWalletBalance($userId);

        if ($request->wantsJson()) {
            return response()->json([
                'walletTypes' => $walletTypes,
                'wallets' => $wallets,
                'totalWalletBalance' => $totalWalletBalance,
            ]);
        }

        return Inertia::render('MyWallet/Index', [
            'walletTypes' => $walletTypes,
            'wallets' => $wallets,
            'totalWalletBalance' => $totalWalletBalance,
        ]);
    }

    public function store(Request $request)
    {
        $user_id = $request->user()->id;

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'wallet_type_id' => 'required|exists:wallet_types,id',
            'balance' => 'numeric|min:0',
            'icon_id' => 'required|exists:icons,id',
        ]);

        $userHasWallet = $this->walletService->userHasWallet($user_id);
        $result = $this->walletService->createWallet($validatedData, $user_id);

        if (!$result['success']) {
            return response()->json(['message' => $result['message']], 400);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'userHasWallet' => !$userHasWallet,
        ]);
    }

    public function create(Request $request)
    {
        $walletTypeID = $request->walletTypeId;
        $walletType = $this->walletService->getWalletTypes()->find($walletTypeID);
        $walletTypeList = $this->walletService->getWalletTypes();
        $icons = $this->walletService->getIcons();

        $user_id = $request->user()->id;

        $isFirstTime = !$this->walletService->userHasWallet($user_id);

        if ($request->wantsJson()) {
            return response()->json([
                'isFirstTime' => $isFirstTime,
                'walletType' => $walletType,
                'walletTypeList' => $walletTypeList,
                'icons' => $icons,
            ]);
        }

        return Inertia::render('MyWallet/Create', [
            'isFirstTime' => $isFirstTime,
            'walletType' => $walletType,
            'walletTypeList' => $walletTypeList,
            'icons' => $icons,
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
        $user_id = $request->user()->id;
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'wallet_type_id' => 'required|exists:wallet_types,id',
            'balance' => 'required|numeric|min:0',
            'icon_id' => 'required|exists:icons,id',
        ]);

        $this->walletService->updateWallet($walletId, $validatedData, $user_id);

        return response()->json([
            'success' => true,
            'message' => 'Wallet updated successfully!',
        ]);
    }

    public function delete(Request $request, $walletId)
    {
        $user_id = $request->user()->id;
        $this->walletService->deleteWallet($walletId, $user_id);

        return response()->json([
            'success' => true,
            'message' => 'Wallet deleted successfully!',
        ]);
    }
}
