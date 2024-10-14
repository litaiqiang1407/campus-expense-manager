<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Wallet;
use App\Models\User;

class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Fetch valid foreign key IDs from the database
        $categoryIds = Category::pluck('id')->toArray();  // Get all category IDs
        $walletIds = Wallet::pluck('id')->toArray();      // Get all wallet IDs
        $userIds = User::pluck('id')->toArray();          // Get all user IDs

        for ($i = 0; $i < 50; $i++) {
            Budget::create([
                'category_id' => $faker->randomElement($categoryIds), // Select a random valid category ID
                'amount' => $faker->randomFloat(2, 100, 10000),       // Random amount
                'time_range' => $faker->randomElement(['week', 'month', 'quarter', 'year', 'custom']),
                'wallet_id' => $faker->randomElement($walletIds),     // Select a random valid wallet ID
                'user_id' => $faker->randomElement($userIds),         // Select a random valid user ID
            ]);
        }
    }
}
