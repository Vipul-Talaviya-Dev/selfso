<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.category.index', [
        	'categories' => Category::latest()->get()
        ]);
    }

    public function create(Request $request)
	{
		$this->validate($request, [
            'name' => 'required|unique:categories,name,{id},id,deleted_at,NULL',
		]);

        Category::create([
            'name' => trim($request->get('name'))
        ]);

		return redirect(route('admin.categories'))->with(['success' => 'Category has been added successfully..']);
	}

    public function edit($id)
    {
        if(!$category = Category::find($id)) {
            return redirect()->back()->with(['error' => 'Invalid Selected Id']);
        }

        return view('admin.category.edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, $id)
    {
        if(!$category = Category::find($id)) {
            return redirect()->back()->with(['error' => 'Invalid Selected Id']);
        }

        $this->validate($request, [
            'name'=> 'required',
        ]);

        $category->title = trim($request->get('name'));
        $category->status = $request->get('status') ?: 0;
        $category->save();

        return redirect(route('admin.categories'))->with(['success' => 'Updated Category successfully..']);
    }

    public function status(Request $request)
    {
        if(!$category = Category::find($request->get('id'))) {
            return response()->json([
                'status' => false,
            ]);
        }

        $category->status = ($category->status == 1) ? 0 : 1;
        $category->save();

        return response()->json([
            'status' => true
        ]);
    }

    public function delete(Request $request)
    {
        if(!$category = Category::find($request->get('id'))) {
            return response()->json([
                'status' => false,
            ]);
        }

        $category->delete();

        return response()->json([
            'status' => true
        ]);
    }    
}
