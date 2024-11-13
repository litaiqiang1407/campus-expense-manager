<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Icon;

class TotalIconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Icon::updateOrCreate(
            ['path' => '/assets/icon/total.png'],
            ['name' => 'Total Icon'] 
        );
    }
}