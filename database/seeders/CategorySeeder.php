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
        // Fetch all valid icon_ids from the icons table
        $validIconIds = DB::table('icons')->pluck('id')->toArray();

        // Create parent categories based on existing icon_ids
        $parentCategories = [
            ['name' => 'Bills & Utilities', 'parent_id' => null, 'type' => 'expense', 'icon_id' => $this->getRandomIconId($validIconIds)],
            ['name' => 'Food & Drink', 'parent_id' => null, 'type' => 'expense', 'icon_id' => $this->getRandomIconId($validIconIds)],
            ['name' => 'Home', 'parent_id' => null, 'type' => 'expense', 'icon_id' => $this->getRandomIconId($validIconIds)],
            ['name' => 'Income', 'parent_id' => null, 'type' => 'income', 'icon_id' => $this->getRandomIconId($validIconIds)],
            ['name' => 'Expenses', 'parent_id' => null, 'type' => 'expense', 'icon_id' => $this->getRandomIconId($validIconIds)],
        ];

        // Insert parent categories
        DB::table('categories')->insert($parentCategories);

        // Retrieve parent category IDs for subcategories
        $billsId = DB::table('categories')->where('name', 'Bills & Utilities')->value('id');
        $foodAndDrinkId = DB::table('categories')->where('name', 'Food & Drink')->value('id');
        $homeId = DB::table('categories')->where('name', 'Home')->value('id');
        $incomeId = DB::table('categories')->where('name', 'Income')->value('id');
        $expensesId = DB::table('categories')->where('name', 'Expenses')->value('id');

        // Create subcategories based on existing icon_ids
        $subcategories = [
            ['name' => 'Electricity Bill', 'parent_id' => $billsId, 'type' => 'expense', 'icon_id' => $this->getRandomIconId($validIconIds)],
            ['name' => 'Gas Bill', 'parent_id' => $billsId, 'type' => 'expense', 'icon_id' => $this->getRandomIconId($validIconIds)],
            ['name' => 'Water Bill', 'parent_id' => $billsId, 'type' => 'expense', 'icon_id' => $this->getRandomIconId($validIconIds)],
            ['name' => 'Phone Bill', 'parent_id' => $billsId, 'type' => 'expense', 'icon_id' => $this->getRandomIconId($validIconIds)],
            ['name' => 'Television Bill', 'parent_id' => $billsId, 'type' => 'expense', 'icon_id' => $this->getRandomIconId($validIconIds)],
            ['name' => 'Other Utility Bills', 'parent_id' => $billsId, 'type' => 'expense', 'icon_id' => $this->getRandomIconId($validIconIds)],
            ['name' => 'Food', 'parent_id' => $foodAndDrinkId, 'type' => 'expense', 'icon_id' => $this->getRandomIconId($validIconIds)],
            ['name' => 'Drinks', 'parent_id' => $foodAndDrinkId, 'type' => 'expense', 'icon_id' => $this->getRandomIconId($validIconIds)],
            ['name' => 'House', 'parent_id' => $homeId, 'type' => 'expense', 'icon_id' => $this->getRandomIconId($validIconIds)],
            ['name' => 'Home Maintenance', 'parent_id' => $homeId, 'type' => 'expense', 'icon_id' => $this->getRandomIconId($validIconIds)],
            ['name' => 'Salary', 'parent_id' => $incomeId, 'type' => 'income', 'icon_id' => $this->getRandomIconId($validIconIds)],
            ['name' => 'Fitness', 'parent_id' => $expensesId, 'type' => 'expense', 'icon_id' => $this->getRandomIconId($validIconIds)],
        ];

        // Insert subcategories
        DB::table('categories')->insert($subcategories);
    }

    /**
     * Get a random icon ID from the existing valid icon IDs.
     *
     * @param array $validIconIds
     * @return int
     */
    private function getRandomIconId(array $validIconIds): int
    {
        return $validIconIds[array_rand($validIconIds)];
    }
}
