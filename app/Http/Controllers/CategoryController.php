<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
{
    // Lấy tất cả danh mục cha và kèm theo danh mục con (subcategories)
    $categories = Category::whereNull('parent_id') // Chỉ lấy danh mục cha (parent_id là null)
        ->with(['subcategories' => function ($query) {
            // Lấy các trường cần thiết từ bảng categories và icons cho danh mục con
            $query->select(
                'categories.id',
                'categories.name',
                'categories.type',
                'categories.parent_id',
                'icons.path as icon_path'
            )
            ->join('icons', 'categories.icon_id', '=', 'icons.id'); // Kết hợp với bảng icons để lấy icon cho danh mục con
        }])
        ->select(
            'categories.id', 
            'categories.name', 
            'categories.type', 
            'icons.path as icon_path' // Chọn trường cần thiết từ bảng categories và icons cho danh mục cha
        )
        ->join('icons', 'categories.icon_id', '=', 'icons.id') // Kết hợp với bảng icons để lấy icon cho danh mục cha
        ->get(); // Lấy tất cả danh mục cha và subcategories

    // Trả về dữ liệu JSON nếu request yêu cầu
    if ($request->wantsJson()) {
        return response()->json($categories);
    }

    // Trả về view và đổ dữ liệu ra giao diện với Inertia
    return Inertia::render('Categories/Index', [
        'categories' => $categories,
    ]);
}
}
