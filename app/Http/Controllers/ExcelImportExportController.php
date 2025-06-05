<?php

namespace App\Http\Controllers;

use App\Exports\CategoriesExport;
use App\Exports\PostsExport;
use App\Imports\CategoriesImport;
use Illuminate\Http\Request;
// use Illuminate\Routing\Controllers\HasMiddleWare;
// use Illuminate\Routing\Controllers\Middleware;
// use App\Models\Category;
use Maatwebsite\Excel\Facades\Excel;

class ExcelImportExportController extends Controller
{

    public function export_categories()
    {
        if (!auth()->user()->hasRole("admin")) {
            return response()->json([
                'message' => 'You do not have export permission.'
            ], 403);
        }
        return Excel::download(new CategoriesExport, 'categories.xlsx');
    }

    public function export_posts()
    {
        if (!auth()->user()->hasRole("admin")) {
            return response()->json([
                'message' => 'You do not have export permission.'
            ], 403);
        }
        return Excel::download(new PostsExport, 'posts.xlsx');
    }

    public function import_categories(Request $request)
    {
        if (!auth()->user()->hasRole("admin")) {
            return response()->json([
                'message' => 'You do not have import permission.'
            ], 403);
        }
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new CategoriesImport, $request->file('file'));

        return back()->with('success', 'Categories imported successfully!');
    }
}
