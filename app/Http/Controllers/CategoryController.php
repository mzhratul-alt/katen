<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Nette\Utils\Random;

class CategoryController extends Controller
{
    //Index Page(Listing Page)
    public function index()
    {

        $categories = Category::paginate(5);
        return view('backend.category.list', compact('categories'));
    }

    //Store(Store new data)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);
        $category_slug = str($request->name)->slug();
        $slug_count = Category::where('slug', 'LIKE', '%' . $category_slug . '%')->count();

        if ($slug_count > 0) {
            $category_slug .= '-' . $slug_count + 1;
        }


        $category = new Category();
        $category->name = $request->name;
        $category->slug = $category_slug;
        $category->save();
        return back();
    }

    //Edit Page(Edit Form)
    public function edit($id)
    {
        $categories = Category::paginate(5);
        $editData = Category::findOrFail($id, ['id', 'name']);
        return view('backend.category.list', compact('categories', 'editData'));
    }

    //Update(Update Single Data)
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);
        $category_slug = str($request->name)->slug();
        $slug_count = Category::where('slug', 'LIKE', '%' . $category_slug . '%')->count();

        if ($slug_count > 0) {
            $category_slug .= '-' . $slug_count + 1;
        }


        $category = Category::find($id);
        $category->name = $request->name;
        $category->slug = $category_slug;
        $category->save();
        return back();
    }

    //Delete(Delete Single Data)
    public function delete($id)
    {
        $category_count = Category::count();
        if ($category_count > 1) {
            $category = Category::find($id);
            $category->delete();
            return back();
        }
        return back();
    }
}
