<?php

namespace App\Repositories;

use App\Models\Wallet;
use App\Models\WalletType;
use App\Models\Icon;

class WalletRepository
{
    public function getAllWallets()
    {
        return Wallet::select(
            'wallets.id',
            'wallets.name',
            'wallets.balance',
            'icons.path as icon_path',
            'icons.name as icon_name'
        )
        ->join('icons', 'wallets.icon_id', '=', 'icons.id')
        ->get();
    }

    public function getWalletTypes()
    {
        return WalletType::select('id', 'name')->get();
    }

    public function createWallet($data, $userId)
    {
        return Wallet::create(array_merge($data, ['user_id' => $userId]));
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
        return $wallet;
    }

    public function getIcons()
    {
        return Icon::select('id', 'name', 'path')->get();
    }
}
