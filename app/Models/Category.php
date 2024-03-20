<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'

    ];

    public function cats()
    {
        return $this->hasMany(Cat::class);
    }
    public function skills()
    {
        return $this->hasMany(Cat::class);
    }
    public function advisors()
    {
        return $this->hasMany(Advisor::class);
    }


}
