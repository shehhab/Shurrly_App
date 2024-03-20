<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;



class Product extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia ;
    protected $fillable = [
        "name",
        "title",
        "description",
        "price",
        "advisor_id",
        "video_duration",
        "pdf_page_count",
    ];





    public function advisor()
    {
        return $this->belongsTo(Advisor::class);
    }

    public function savedByUsers()
    {
        return $this->belongsToMany(SavedProduct::class, 'saved_products', 'product_id', 'seeker_id');
    }



    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover_product');
        $this->addMediaCollection('Product_Video');
        $this->addMediaCollection('product_pdf');


    }

}
