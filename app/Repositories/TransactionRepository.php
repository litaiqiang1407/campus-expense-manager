<?php
namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository
{
    public function getTransactionsByWallet($userId, $walletId)
    {
        return Transaction::where('user_id', $userId)
            ->where('wallet_id', $walletId)
            ->get(['id', 'category_id', 'amount', 'date']);
    }
}
