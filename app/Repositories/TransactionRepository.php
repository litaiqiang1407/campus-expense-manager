<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Models\Icon;

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
    // Lấy các biểu tượng (có thể không liên quan đến giao dịch)
    public function getIcons()
    {
        return Icon::select('id', 'name', 'path')->get();
    }
}
