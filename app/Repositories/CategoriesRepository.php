<?php

namespace App\Repositories;

use App\Models\Category;

class CategoriesRepository
{
    public function createCategory($data, $userId)
    {
        return Category::create(array_merge($data, ['user_id' => $userId]));
    }
}
