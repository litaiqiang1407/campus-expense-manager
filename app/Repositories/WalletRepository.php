<?php

namespace App\Repositories;

use App\Models\Wallet;
use App\Models\Icon;
use App\Models\Category;
use App\Models\Transaction;
use App\Repositories\IconRepository;

class WalletRepository
{
    protected $iconRepo;
    protected $walletTypeRepo;

    public function __construct(IconRepository $iconRepo)
    {
        $this->iconRepo = $iconRepo;
    }

    public function userHasWallet($userId)
    {
        return Wallet::where('user_id', $userId)->exists();
    }
    public function getAllWallets($userId,  $limit = null)
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
        ->limit($limit)
        ->get();
    }

    public function getThreeWallets($userId)
    {
        return Wallet::select(
            'wallets.id',
            'wallets.name',
            'wallets.balance',
            'icons.path as icon_path',
            'icons.name as icon_name'
        )
        ->where('user_id', $userId)
        ->where('wallets.name', '!=', Wallet::TOTAL_WALLET_NAME) 
        ->join('icons', 'wallets.icon_id', '=', 'icons.id')
        ->limit(3)
        ->get();
    }
        

    public function getWalletTypes()
    {
        return ['Basic', 'Linked', 'Credit', 'Goal'];
    }

    public function getDefaultWalletType()
    {
        return $this->getWalletTypes()[0];
    }

    public function walletExistsWithName($userId, $walletName)
    {
        return Wallet::where('user_id', $userId)
            ->where('name', $walletName)
            ->exists();
    }

    public function createWallet($data, $userId)
    {
        $wallet = Wallet::create(array_merge($data, ['user_id' => $userId]));

        $category = Category::where('name', 'Other Income')->first();

        if ($category) {
            Transaction::create([
                'category_id' => $category->id,
                'amount' => $wallet->balance,
                'wallet_id' => $wallet->id,
                'user_id' => $userId,
                'date' => now(), 
                'note' => 'Initial balance', 
            ]);
        }

        $this->recalculateTotalWalletBalance($userId);

        return $wallet;
    }

    public function createFirstWallet($userId)
    {
        $walletType = $this->getDefaultWalletType();
        $icon = $this->iconRepo->getIconByPath(Wallet::DEFAULT_WALLET_ICON);

        return Wallet::create([
            'name' => Wallet::DEFAULT_WALLET_NAME,
            'balance' => 0,
            'user_id' => $userId,
            'icon_id' => $icon->id,
            'wallet_type_id' => $walletType,
        ]);
    }

    public function getWalletById($walletId)
    {
        return Wallet::select(
            'wallets.id',
            'wallets.name',
            'wallets.balance',
            'wallets.wallet_type_name',
            'wallets.icon_id',
            'icons.path as icon_path',
            'icons.name as icon_name'
        )
        ->join('icons', 'wallets.icon_id', '=', 'icons.id')
        ->findOrFail($walletId);
    }

    public function getWalletNameById($userId, $walletId = null)
    {
        if ($walletId) {
            return Wallet::with('icon')->where('id', $walletId)->where('user_id', $userId)->first();
        }

        return Wallet::where('user_id', $userId)
            ->where('name', Wallet::TOTAL_WALLET_NAME)
            ->first();
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
                'icon_id' => $this->iconRepo->getIconByPath(Wallet::TOTAL_WALLET_ICON)->id,
                'wallet_type_name' => $this->getDefaultWalletType(),
            ]);
        } else {
            $totalWallet->update(['balance' => $totalBalance]);
        }

        return $totalWallet;
    }

    public function searchWallets($userId, $search, $limit = null)
    {
        if (empty($search)) {
            return $this->getAllWallets($userId, $limit);
        }
        return Wallet::select(
                'wallets.id',
                'wallets.name',
                'wallets.balance',
                'icons.path as icon_path',
                'icons.name as icon_name'
            )
            ->where('user_id', $userId)
            ->where('wallets.name', 'LIKE', '%' . $search . '%') 
            ->orWhere('wallets.balance', 'LIKE', '%' . $search . '%')
            ->join('icons', 'wallets.icon_id', '=', 'icons.id')
            ->limit($limit)
            ->get();
    }
}
