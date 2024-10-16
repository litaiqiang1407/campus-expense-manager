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

        $categoryIds = Category::pluck('id')->toArray();  
        $walletIds = Wallet::pluck('id')->toArray();      
        $userIds = User::pluck('id')->toArray();          

        for ($i = 0; $i < 50; $i++) {
            Budget::create([
                'category_id' => $faker->randomElement($categoryIds), 
                'amount' => $faker->randomFloat(2, 100, 10000),       
                'time_range' => $faker->randomElement(['week', 'month', 'quarter', 'year', 'custom']),
                'wallet_id' => $faker->randomElement($walletIds), 
                'user_id' => $faker->randomElement($userIds),         
            ]);
        }
    }
}
