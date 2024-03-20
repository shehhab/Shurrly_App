<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedProduct extends Model
{
    use HasFactory;

    protected $fillable = ['seeker_id', 'product_id'];

    public function seekre()
    {
        return $this->belongsTo(Seeker::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
