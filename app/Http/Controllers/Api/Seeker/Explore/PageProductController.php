<?php

namespace App\Http\Controllers\Api\Seeker\Explore;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageProductController extends Controller
{
    public function __invoke(Request $request)
    {
        $productId = $request->input('product_id');

        $product = Product::find($productId);

    if (!$product) {
        return $this->handleResponse(false, 'Product not found', 404);
    }

        $formattedProduct = [
            'product_id' => $product->id,
            'product_title' => $product->title,
            'product_price' => $product->price,
            'product_description' => $product->description,
            'product_cover_photo' => $product->getFirstMediaUrl('cover_product'),
            'skills' => $product->advisor->skills->pluck('name')->toArray(),
        ];
        // Check if video_duration is not null and add it to the formatted product
         if ($product->video_duration !== null) {
            $formattedProduct['video_duration'] = $product->video_duration;
        }
        // Check if pdf_page_count is not null and add it to the formatted product
        if ($product->pdf_page_count !== null) {
            $formattedProduct['pdf_page_count'] = $product->pdf_page_count;
        }
        return $this->handleResponse(true, $formattedProduct);
    }
}
