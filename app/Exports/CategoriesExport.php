<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoriesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Category::all()->map(function ($category) {
            return [
                'Name' => $category->name,
                'Slug' => $category->slug,
                'Created At' => $category->created_at->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Name',
            'Slug',
            'Created Date',
        ];
    }
}
