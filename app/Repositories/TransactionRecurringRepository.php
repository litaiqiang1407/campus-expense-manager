<?php

namespace App\Repositories;

use App\Models\RecurringTransaction;
use App\Models\Category;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionRecurringRepository
{
    public function createRecurringTransaction($data, $userId)
    {
        return RecurringTransaction::create(array_merge($data, ['user_id' => $userId]));
    }
}
