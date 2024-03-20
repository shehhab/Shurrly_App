<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cat extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "categories_id"
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
