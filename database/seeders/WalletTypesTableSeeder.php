<?php

namespace Database\Seeders;

use App\Models\WalletType;
use Illuminate\Database\Seeder;

class WalletTypesTableSeeder extends Seeder
{
    public function run()
    {
        WalletType::create([
            'name' => 'Cash',
        ]);

        WalletType::create([
            'name' => 'Bank Account',
        ]);
    }
}
