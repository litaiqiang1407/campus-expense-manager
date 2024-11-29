<?php

namespace App\Repositories;

use App\Models\RecurringTransaction;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionRecurringRepository
{
    protected $transactionRepo;

    public function __construct(TransactionRepository $transactionRepo)
    {
        $this->transactionRepo = $transactionRepo;
    }
    public function createRecurringTransaction($data, $userId)
    {
        return RecurringTransaction::create(array_merge($data, ['user_id' => $userId]));
    }
    public function getIndexRecurringTransaction($userId)
    {
        return RecurringTransaction::with(['category.icon'])
            ->select('id', 'amount', 'category_id', 'start_date', 'wallet_id')
            ->where('user_id', $userId)
            ->get();
    }
    public function getDetailsRecurringTransaction($transactionRecurringId)
    {
        $transaction = RecurringTransaction::with(['category.icon', 'wallet.icon'])
            ->select('id', 'amount', 'category_id', 'wallet_id')
            ->where('id', $transactionRecurringId)
            ->first(); // Use first() to get a single record

        if (!$transaction) {
            // Handle the case where the transaction does not exist
            throw new \Exception('Transaction not found');
        }

        return $transaction;
    }
    public function deleteTransactionByIdAndUserId(int $transactionId, int $userId): bool
    {
        try {
            $transaction = RecurringTransaction::join('categories', 'recurring_transactions.category_id', '=', 'categories.id')
                ->where('recurring_transactions.id', $transactionId)
                ->where('recurring_transactions.user_id', $userId)
                ->select('recurring_transactions.category_id', 'categories.type', 'recurring_transactions.wallet_id', 'recurring_transactions.amount')
                ->first();

            if (!$transaction) {
                return false; // Giao dịch không tồn tại hoặc không thuộc về người dùng
            }

            $isIncome = ($transaction->type != 'income');
            $this->transactionRepo->updateWalletBalance($transaction->wallet_id, $transaction->amount, $isIncome);

            // Xoá giao dịch
            return RecurringTransaction::where('id', $transactionId)
                ->where('user_id', $userId)
                ->delete();
        } catch (\Exception $e) {
            return false;
        }
    }
}
