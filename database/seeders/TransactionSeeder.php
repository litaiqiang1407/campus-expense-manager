<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction; // Model Transaction
use App\Models\Category; // Model Category
use App\Models\Wallet; // Model Wallet
use App\Models\User; // Model User
use Faker\Factory as Faker;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $categoryIds = Category::pluck('id')->toArray();
        $walletIds = Wallet::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();

        for ($i = 0; $i < 100; $i++) {
            Transaction::create([
                'category_id' => $faker->randomElement($categoryIds),
                'amount' => $faker->randomFloat(2, 10, 1000),
                'wallet_id' => $faker->randomElement($walletIds),
                'user_id' => $faker->randomElement($userIds),
                'note' => $faker->sentence(),
                'date' => $faker->dateTimeBetween('2024-11-01', '2024-11-30')->format('Y-m-d'),
            ]);
        }
    }
}
