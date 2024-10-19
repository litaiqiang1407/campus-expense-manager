<?php
namespace App\Repositories;

use App\Models\Icon;

class IconRepository
{
    public function getIconByPath($path)
    {
        return Icon::where('path', $path)->first();
    }

    public function getIcons()
    {
        return Icon::select('id', 'name', 'path')->get();
    }
}

