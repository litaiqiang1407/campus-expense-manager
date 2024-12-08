<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;
use App\Services\CategoriesService;

class CategoryController extends Controller
{
    protected $categoriesService;

    public function __construct(CategoriesService $categoriesService)
    {
        $this->categoriesService = $categoriesService;
    }
    public function index(Request $request)
    {
        $type = $request->input('type');
        $userId = $request->user()->id;

        $categoriesQuery = Category::query()
            ->select(
                'categories.id',
                'categories.parent_id',
                'categories.name',
                'categories.type',
                'icons.path as icon_path'
            )
            ->join('icons', 'categories.icon_id', '=', 'icons.id')
            ->where(function ($query) use ($userId) {
                $query->whereNull('categories.user_id')
                    ->orWhere('categories.user_id', $userId);
            });

        if ($type) {
            $categoriesQuery->where('categories.type', $type);
        }

        $categories = $categoriesQuery->get();

        if ($request->wantsJson()) {
            return response()->json($categories);
        }

        return Inertia::render('Categories/Index', [
            'categories' => $categories,
        ]);
    }
    public function create()
    {
        return Inertia::render('Categories/Create');
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|integer|exists:categories,id',
            'type' => 'nullable|string|in:income,expense',
            'icon_id' => 'nullable|integer|exists:icons,id',
        ]);

        $this->categoriesService->createCategory($validatedData, $request->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully!',
        ]);
    }
}
