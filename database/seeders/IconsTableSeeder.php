<?php

namespace Database\Seeders;

use App\Models\Icon;
use Illuminate\Database\Seeder;

class IconsTableSeeder extends Seeder
{
    /**
     * Seed the icons table.
     */
    public function run(): void
    {
        Icon::create([
            'name' => 'Other Income',
            'path' => '/assets/icon/box.png',
        ]);
    }
}
