<?php

namespace App\Http\Controllers;

use App\Exports\CategoriesExport;
use Illuminate\Http\Request;
// use Illuminate\Routing\Controllers\HasMiddleWare;
// use Illuminate\Routing\Controllers\Middleware;
use App\Models\Category;
use Maatwebsite\Excel\Facades\Excel;

class CategoryController extends Controller
{

    public function export()
    {
        return Excel::download(new CategoriesExport, 'categories.xlsx');
    }
    private function checkPermission()
    {
        if (!auth()->user()->can('category-manage')) {
            abort(403, 'You do not have permission to manage categories.');
        }
    }

    public function index()
    {
        // $this->checkPermission();
        $categories = Category::get();
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkPermission();
        $category = Category::create($request->all());
        return response()->json($category, 201);
        // return response("sdfsdfsdfsdf");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $this->checkPermission();
        $category = Category::find($id);
        return response()->json($category, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->checkPermission();
        $category = Category::find($id);
        $category->update($request->all());
        return response()->json($category, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->checkPermission();
        $category = Category::find($id);
        $category->delete();
        return response()->json($category, 200);
    }
}
