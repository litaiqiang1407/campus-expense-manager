<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use InvalidArgumentException;
use App\Repositories\CategoriesRepository;

class CategoriesService
{
    protected $categoriesRepository;

    public function __construct(CategoriesRepository $categoriesRepository)
    {
        $this->categoriesRepository = $categoriesRepository;
    }
    public function createCategory($data, $userId)
    {
        $this->categoriesRepository->createCategory($data, $userId);
    }
}
