<?php

namespace Database\Seeders;

use App\Models\Wallet;
use Illuminate\Database\Seeder;

class WalletsTableSeeder extends Seeder
{
    public function run()
    {
        Wallet::create([
            'name' => 'Cash',
            'wallet_type_id' => 3,
            'balance' => 500.00,
            'user_id' => 1,
            'icon_id' => 1,
        ]);

        Wallet::create([
            'name' => 'Bank Account',
            'wallet_type_id' => 5,
            'balance' => 2000.00,
            'user_id' => 1,
            'icon_id' => 2,
        ]);
    }
}
