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

    public function icon()
    {
        return $this->belongsTo(Icon::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class); 
    }

    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
}