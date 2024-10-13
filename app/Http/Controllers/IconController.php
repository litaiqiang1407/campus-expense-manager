<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Icon;

class IconController extends Controller
{
    public function index(Request $request)
    {
        $icons = Icon::select('id', 'name', 'path')->get();

        $icons->transform(function ($icon) {
            $icon->path = asset($icon->path);
            return $icon;
        });

        if ($request->wantsJson()) {
            return response()->json([
                'icons' => $icons
            ]);
        }

        return Inertia::render('Icon/Index', [
            'icons' => $icons,
        ]);
    }
}
