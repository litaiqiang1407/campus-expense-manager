<?php

namespace App\Repositories;

use App\Models\Wallet;
use App\Models\WalletType;
use App\Models\Icon;

class WalletRepository
{
    public function userHasWallet($userId)
    {
        return Wallet::where('user_id', $userId)->exists();
    }
    public function getAllWallets($userId)
    {
        return Wallet::select(
            'wallets.id',
            'wallets.name',
            'wallets.balance',
            'icons.path as icon_path',
            'icons.name as icon_name'
        )
        ->where('user_id', $userId)
        ->join('icons', 'wallets.icon_id', '=', 'icons.id')
        ->get();
    }

    public function getWalletTypes()
    {
        return WalletType::select('id', 'name')->get();
    }

    public function createWallet($data, $userId)
    {
        $wallet = Wallet::create(array_merge($data, ['user_id' => $userId]));

        $this->recalculateTotalWalletBalance($userId);

        return $wallet;
    }

    public function getWalletById($walletId)
    {
        return Wallet::select(
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
    }

    public function updateWallet($walletId, $data)
    {
        $wallet = Wallet::findOrFail($walletId);
        $wallet->update($data);

        $this->recalculateTotalWalletBalance($wallet->user_id);

        return $wallet;
    }

    public function getIcons()
    {
        return Icon::select('id', 'name', 'path')->get();
    }

    public function deleteWallet($walletId)
    {
        $wallet = Wallet::findOrFail($walletId);

        $userId = $wallet->user_id;

        Wallet::destroy($walletId);

        $this->recalculateTotalWalletBalance($userId);

        return true;
    }

    public function recalculateTotalWalletBalance($userId)
    {
        $totalBalance = Wallet::where('user_id', $userId)
            ->where('name', '!=', Wallet::TOTAL_WALLET_NAME)
            ->sum('balance');

        $totalWallet = Wallet::where('user_id', $userId)
            ->where('name', Wallet::TOTAL_WALLET_NAME)
            ->first();

        if (!$totalWallet) {
            $totalWallet = Wallet::create([
                'name' => Wallet::TOTAL_WALLET_NAME,
                'balance' => $totalBalance,
                'user_id' => $userId,
                'icon_id' => Icon::where('path', Wallet::TOTAL_WALLET_ICON)->first()->id,
                'wallet_type_id' => $this->getDefaultWalletTypeId(),
            ]);
        } else {
            $totalWallet->update(['balance' => $totalBalance]);
        }

        return $totalWallet;
    }
    private function getDefaultWalletTypeId()
    {
        $defaultWalletType = WalletType::first();
        return $defaultWalletType ? $defaultWalletType->id : null;
    }
}
