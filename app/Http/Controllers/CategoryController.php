<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {

        $categories = Category::select('id', 'name', 'type', 'icon_id')
            ->join('icons', 'categories.icon_id', '=', 'icons.id') // Assuming your icon table is named 'icons'
            ->select('categories.id', 'categories.name', 'categories.type', 'icons.path as icon_path', 'icons.name as icon_name')
            ->get(); // Retrieve the data

        if ($request->wantsJson()) {
            return response()->json($categories);
        }

        return Inertia::render('Categories/Index', [
            'categories' => $categories,
        ]);
    }
}
