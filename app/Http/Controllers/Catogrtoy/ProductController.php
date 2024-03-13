<?php

namespace App\Http\Controllers\Catogrtoy;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

use App\Models\Cat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function store()
    {
        $cats = DB::table('categories')->select('id', 'name')->get()->toArray();

        return view('admin.products', compact('cats'));
                // Fetch categories from the database

    }

    public function index(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'categories_id' => 'required|exists:categories,id' // Assuming 'categories' is the correct table name
        ]);

        $existingProduct = Cat::where('name', $request->name)->first();
        if ($existingProduct) {
            return redirect()->back()->with('existing_product', 'This catogroy  already exists!');
        }

        $cat = Cat::create([
            'name' => $data['name'],
            'categories_id' => $data['categories_id']
        ]);
        Session::flash('success', 'Successfully Add Category');
        // Redirect to the desired page
        return redirect('/dashboard/all_cats');

    }





}
