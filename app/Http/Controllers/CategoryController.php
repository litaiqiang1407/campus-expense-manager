<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // Lấy tham số type từ request
        $type = $request->input('type');
    
        // Bắt đầu truy vấn cho danh mục
        $categoriesQuery = Category::query()
            ->select(
                'categories.id',
                'categories.name',
                'categories.type',
                'icons.path as icon_path' // Chọn các trường cần thiết
            )
            ->join('icons', 'categories.icon_id', '=', 'icons.id'); // Kết hợp với icons để lấy đường dẫn biểu tượng
    
        // Lọc danh mục theo type nếu có
        if ($type) {
            $categoriesQuery->where('categories.type', $type);
        }
    
        // Lấy danh mục
        $categories = $categoriesQuery->get();
    
        // Trả về dưới dạng JSON nếu yêu cầu
        if ($request->wantsJson()) {
            return response()->json($categories);
        }
    
        // Render view với Inertia
        return Inertia::render('Categories/Index', [
            'categories' => $categories,
        ]);
    }
}
