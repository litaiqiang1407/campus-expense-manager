<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Wallet;

class TransactionRepository
{
    // Tạo giao dịch mới
    public function createTransaction($data, $userId)
    {
        return Transaction::create(array_merge($data, ['user_id' => $userId]));
    }

    // Cập nhật giao dịch
    public function updateTransaction($transactionId, $data)
    {
        $transaction = Transaction::findOrFail($transactionId);
        $transaction->update($data);
        return $transaction;
    }

    public function getTransactionById($transactionId)
    {
        return Transaction::findOrFail($transactionId);
    }
    public function getWalletsByUser($userId)
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

    public function getCategories()
    {
        return Category::all();
    }
}
