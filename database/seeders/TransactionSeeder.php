<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        Transaction::create([
            'category_id' => 1,
            'amount' => 100.50,
            'type' => 'income',
            'wallet_id' => 1,
            'user_id' => 1,
            'date' => now(),
            'note' => 'Salary',
        ]);
    }
}

