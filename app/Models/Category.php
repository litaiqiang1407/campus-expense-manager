<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'parent_id', 'type', 'user_id', 'icon_id'
    ];

    // Quan hệ đến Icon (để lấy thông tin icon của category)
    public function icon()
    {
        return $this->belongsTo(Icon::class);
    }

    // Quan hệ với Transaction (liên kết với các giao dịch)
    public function transactions()
    {
        return $this->hasMany(Transaction::class); 
    }

    // Quan hệ để lấy các danh mục con dựa trên parent_id
    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Quan hệ để lấy danh mục cha
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
}