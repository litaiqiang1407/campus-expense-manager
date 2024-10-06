<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        Category::create([
            'name' => 'Food',
            'parent_id' => null,
            'type' => 'expense',
            'user_id' => 1,
            'icon_id' => 1,
        ]);

        Category::create([
            'name' => 'Salary',
            'parent_id' => null,
            'type' => 'income',
            'user_id' => 1,
            'icon_id' => 2,
        ]);
    }
}
