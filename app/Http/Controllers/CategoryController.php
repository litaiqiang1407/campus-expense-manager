<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve categories with their parent names
        $categories = Category::select(
                'categories.id', 
                'categories.name', 
                'categories.type', 
                'icons.path as icon_path', 
                'icons.name as icon_name', 
                'parent_categories.name as parent_name' // Add the parent category's name
            )
            ->join('icons', 'categories.icon_id', '=', 'icons.id') // Assuming your icon table is named 'icons'
            ->leftJoin('categories as parent_categories', 'categories.parent_id', '=', 'parent_categories.id') // Self-join to get parent category's name
            ->get(); // Retrieve the data

        if ($request->wantsJson()) {
            return response()->json($categories);
        }

        return Inertia::render('Categories/Index', [
            'categories' => $categories,
        ]);
    }
}
