<?php

namespace App\Http\Controllers\Api\Seeker\Explore;

use App\Models\SavedProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Auth\GetProfileResource;
use App\Http\Resources\Auth\SavedProductResource;

class ViewProductSavedController extends Controller
{
    public function __invoke(Request $request)
    {

        $seeker_id = $request->user()->id;

        $savedProducts = SavedProduct::with('product')->where('seeker_id', $seeker_id)->get();

        $savedProductsResource = SavedProductResource::collection($savedProducts);

        return response()->json(['saved_products' => $savedProductsResource]);
    }
    }
