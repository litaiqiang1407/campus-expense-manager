<?php

namespace App\Repositories;

use App\Models\Budget;

class BudgetRepository
{
    public function getBudgetsByWallet($userId, $walletId)
    {
        return Budget::with([
                'category' => function ($query) {
                    $query->select('id', 'name', 'icon_id');
                },
                'category.icon' => function ($query) {
                    $query->select('id', 'path');
                }
            ])
            ->where('user_id', $userId)
            ->where('wallet_id', $walletId)
            ->get(['id', 'category_id', 'amount', 'time_range', 'created_at']);
    }
}
