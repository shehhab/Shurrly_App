<?php

namespace App\Http\Controllers\Admin\Catogrtoy;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillsController extends Controller
{
    public function index()
    {
        $categories = Skill::all();


        return view('admin.categories.index', ['categories' => $categories]);

    }

    // to destroy skill

    public function destroy($id)
    {
        $category = Skill::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Skill deleted successfully');
    }

// to edit skill
public function edit($id)
{
    $category = Skill::findOrFail($id);
    return view('admin.categories.edit', compact('category'));
}
public function update(Request $request, $id)
{
    $category = Skill::findOrFail($id);
    $category->update($request->all());
    Session::flash('success', 'Successfully update Skill');

    // Redirect to the desired page
    return redirect('/dashboard/all_Skills');
}
}
