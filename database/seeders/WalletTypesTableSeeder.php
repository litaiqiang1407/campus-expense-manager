<?php

namespace Database\Seeders;

use App\Models\WalletType;
use Illuminate\Database\Seeder;

class WalletTypesTableSeeder extends Seeder
{
    public function run()
    {
        WalletType::create([
            'name' => 'Basic Wallet',
        ]);

        WalletType::create([
            'name' => 'Linked Wallet',
        ]);

        WalletType::create([
            'name' => 'Credit Wallet',
        ]);

        WalletType::create([
            'name' => 'Goal Wallet',
        ]);
    }
}
