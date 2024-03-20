<?php

namespace App\Http\Controllers\Api\Seeker\Explore;

use App\Models\Product;
use App\Models\SavedProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UnSave_SaveProductController extends Controller
{
    public function __invoke(Request $request)
    {
        $seeker = $request->user();
        $productId = $request->input('product_id');

        if (!$request->has('product_id')) {
            return $this->handleResponse(message: 'Product ID is required',code :  400 , status:false);
        }
    $productExists = Product::where('id', $productId)->exists();

    if (!$productExists) {
        return $this->handleResponse(message: 'Not Found Product', code: 404 , status:false);
    }
        $savedProduct = SavedProduct::where('seeker_id', $seeker->id)
                                    ->where('product_id', $productId)
                                    ->first();

        if ($savedProduct) {
            $savedProduct->delete();
            $message = 'Product removed successfully';
        } else {
            SavedProduct::create([
                'seeker_id' => $seeker->id,
                'product_id' => $productId,
            ]);
            $message = 'Product saved successfully';
        }

        return $this->handleResponse(['message' => $message]);
    }
}
