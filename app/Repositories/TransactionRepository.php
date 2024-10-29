<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Wallet;

class TransactionRepository
{
    public function getTransactionsByUser($userId)
    {
        return Transaction::with(['category.icon'])
            ->select('id', 'amount', 'note', 'category_id', 'user_id', 'date')
            ->where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->get();
    }   

    public function createTransaction($data, $userId)
    {
        return Transaction::create(array_merge($data, ['user_id' => $userId]));
    }

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
    public function getCategoryById($categoryId)
    {
        return Category::findOrFail($categoryId);
    }

    public function getCategories()
    {
        return Category::all();
    }

    public function updateWalletBalance($walletId, $amount, $isIncome)
    {
        $wallet = Wallet::findOrFail($walletId);

        if ($isIncome) {
            $wallet->balance += $amount;
        } else {
            $wallet->balance -= $amount;
        }

        $wallet->save();
    }

}
