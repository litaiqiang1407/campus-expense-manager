<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create parent categories without user_id for global access
        $parentCategories = [
            ['name' => 'Bills & Utilities', 'parent_id' => null, 'type' => 'expense', 'icon_id' => 5],
            ['name' => 'Food & Drink', 'parent_id' => null, 'type' => 'expense', 'icon_id' => 8],
            ['name' => 'Home', 'parent_id' => null, 'type' => 'expense', 'icon_id' => 15],
            ['name' => 'Income', 'parent_id' => null, 'type' => 'income', 'icon_id' => 37],
            ['name' => 'Expenses', 'parent_id' => null, 'type' => 'expense', 'icon_id' => 4],
        ];

        DB::table('categories')->insert($parentCategories);

        // Retrieve parent category IDs for setting up subcategories
        $billsId = DB::table('categories')->where('name', 'Bills & Utilities')->value('id');
        $foodAndDrinkId = DB::table('categories')->where('name', 'Food & Drink')->value('id');
        $homeId = DB::table('categories')->where('name', 'Home')->value('id');
        $incomeId = DB::table('categories')->where('name', 'Income')->value('id');
        $expensesId = DB::table('categories')->where('name', 'Expenses')->value('id');

        // Create subcategories without user_id
        $subcategories = [
            // Bills & Utilities Subcategories
            ['name' => 'Electricity Bill', 'parent_id' => $billsId, 'type' => 'expense', 'icon_id' => 5],
            ['name' => 'Gas Bill', 'parent_id' => $billsId, 'type' => 'expense', 'icon_id' => 11],
            ['name' => 'Water Bill', 'parent_id' => $billsId, 'type' => 'expense', 'icon_id' => 34],
            ['name' => 'Phone Bill', 'parent_id' => $billsId, 'type' => 'expense', 'icon_id' => 27],
            ['name' => 'Television Bill', 'parent_id' => $billsId, 'type' => 'expense', 'icon_id' => 31],
            ['name' => 'Other Utility Bills', 'parent_id' => $billsId, 'type' => 'expense', 'icon_id' => 22],

            // Food & Drink Subcategories
            ['name' => 'Food', 'parent_id' => $foodAndDrinkId, 'type' => 'expense', 'icon_id' => 7],
            ['name' => 'Drinks', 'parent_id' => $foodAndDrinkId, 'type' => 'expense', 'icon_id' => 8],

            // Home Subcategories
            ['name' => 'House', 'parent_id' => $homeId, 'type' => 'expense', 'icon_id' => 15],
            ['name' => 'Home Maintenance', 'parent_id' => $homeId, 'type' => 'expense', 'icon_id' => 13],
            ['name' => 'Home Services', 'parent_id' => $homeId, 'type' => 'expense', 'icon_id' => 14],
            ['name' => 'Houseware', 'parent_id' => $homeId, 'type' => 'expense', 'icon_id' => 16],

            // Income Subcategories
            ['name' => 'Salary', 'parent_id' => $incomeId, 'type' => 'income', 'icon_id' => 36],
            ['name' => 'Collect Interest', 'parent_id' => $incomeId, 'type' => 'income', 'icon_id' => 35],
            ['name' => 'Other Income', 'parent_id' => $incomeId, 'type' => 'income', 'icon_id' => 37],
            ['name' => 'Incoming Transfer', 'parent_id' => $incomeId, 'type' => 'income', 'icon_id' => 38],

            // Expenses Subcategories
            ['name' => 'Fitness', 'parent_id' => $expensesId, 'type' => 'expense', 'icon_id' => 6],
            ['name' => 'Gifts & Donations', 'parent_id' => $expensesId, 'type' => 'expense', 'icon_id' => 12],
            ['name' => 'Medical Check-up', 'parent_id' => $expensesId, 'type' => 'expense', 'icon_id' => 20],
            ['name' => 'Makeup', 'parent_id' => $expensesId, 'type' => 'expense', 'icon_id' => 19],
            ['name' => 'Pets', 'parent_id' => $expensesId, 'type' => 'expense', 'icon_id' => 26],
            ['name' => 'Shopping', 'parent_id' => $expensesId, 'type' => 'expense', 'icon_id' => 29],
            ['name' => 'Streaming Service', 'parent_id' => $expensesId, 'type' => 'expense', 'icon_id' => 30],
            ['name' => 'Rentals', 'parent_id' => $expensesId, 'type' => 'expense', 'icon_id' => 28],
            ['name' => 'Vehicle Maintenance', 'parent_id' => $expensesId, 'type' => 'expense', 'icon_id' => 33],
            ['name' => 'Outgoing Transfer', 'parent_id' => $expensesId, 'type' => 'expense', 'icon_id' => 23],
            ['name' => 'Pay Interest', 'parent_id' => $expensesId, 'type' => 'expense', 'icon_id' => 24],
        ];

        DB::table('categories')->insert($subcategories);
    }
}
