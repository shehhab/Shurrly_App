<?php

namespace App\Http\Controllers\Api\Seeker\Explore;

use App\Models\Advisor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Skill;

class ExploreController extends Controller
{
    public function __invoke()
    {
        $products = Product::all();

        $formattedProducts = $products->map(function ($product) {
            $data = [
                'product_id' => $product->id,
                'product_title' => $product->title,
                'product_price' => $product->price,
                'product_cover_photo' => $product->getFirstMediaUrl('cover_product'),
                'skills' => $product->advisor->skills->pluck('name')->toArray(),
                'categories' => $product->advisor->category->pluck('name')->toArray(),
            ];

            // Check if video_duration is not null and add it to the data
            if ($product->video_duration !== null) {
                $data['video_duration'] = $product->video_duration;
            }

            // Check if pdf_page_count is not null and add it to the data
            if ($product->pdf_page_count !== null) {
                $data['pdf_page_count'] = $product->pdf_page_count;
            }

            return $data;
        });

        $skills = Skill::pluck('name', 'id');

        return $this->handleResponse(true, [
            'Skills' => $skills,
            'products' => $formattedProducts,
        ]);
    }
}
