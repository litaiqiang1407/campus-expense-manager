<?php

namespace App\Services;

use App\Repositories\BudgetRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\WalletRepository;

class BudgetService
{
    protected $budgetRepo;
    protected $transactionRepo;
    protected $walletRepo;

    public function __construct(
        BudgetRepository $budgetRepo, 
        TransactionRepository $transactionRepo, 
        WalletRepository $walletRepo
    ) {
        $this->budgetRepo = $budgetRepo;
        $this->transactionRepo = $transactionRepo;
        $this->walletRepo = $walletRepo;
    }

    public function getBudgetOverview($userId, $walletId = null)
    {
        $wallet = $this->walletRepo->getWalletNameById($userId, $walletId);
        
        if (!$wallet) {
            return ['budgets' => [], 'transactions' => [], 'wallet' => null];
        }

        $budgets = $this->budgetRepo->getBudgetsByWallet($userId, $wallet->id);
        $transactions = $this->transactionRepo->getTransactionsByWallet($userId, $wallet->id);

        return [
            'wallet' => [
                'id' => $wallet->id,
                'icon_path' => $wallet->icon->path ?? null,
            ],
            'budgets' => $budgets->map(function ($budget) {
                return [
                    'id' => $budget->id,
                    'amount' => $budget->amount,
                    'time_range' => $budget->time_range,
                    'created_at' => $budget->created_at,
                    'category' => [
                        'id' => $budget->category->id,
                        'name' => $budget->category->name,
                        'icon_path' => $budget->category->icon->path ?? null,
                    ],
                ];
            }),
            'transactions' => $transactions->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'category_id' => $transaction->category_id,
                    'amount' => $transaction->amount,
                    'date' => $transaction->date,
                ];
            })
        ];
    }
}
