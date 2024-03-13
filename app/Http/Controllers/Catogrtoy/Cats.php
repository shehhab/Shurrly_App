<?php

namespace App\Http\Controllers\Catogrtoy;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Controller;
use App\Models\Cat;
use Illuminate\Http\Request;

class Cats extends Controller
{
    public function index()
    {
        $categories = Cat::all();

        return view('admin.categories.index', ['categories' => $categories]);
    }
    public function destroy($id)
    {
        $category = Cat::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Category deleted successfully');
    }
    public function edit($id)
{
    $category = Cat::findOrFail($id);
    return view('admin.categories.edit', compact('category'));
}
public function update(Request $request, $id)
{
    $category = Cat::findOrFail($id);
    $category->update($request->all());
    Session::flash('success', 'Successfully update Ccategory');


    // Redirect to the desired page
    return redirect('/dashboard/all_cats');
}
}
