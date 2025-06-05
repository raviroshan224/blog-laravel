<?php

nameSpace App\Exports;

use App\Models\Post;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PostsExport implements FromCollection, WithHeadings
{
    public function collection(){
        return Post::all()->map(function ($post){
            return [
                "Id"=> $post->id,
                "Title"=> $post->title,
                "Content"=> $post->content,
                "Author" => $post->author->name,
                "Category" => $post->category->name,
            ];
        });
    }

    public function headings(): array{
        return[
            "Post Id",
            "Title",
            "Content",
            "Author Name",
            "Category Name"
        ];
    }
}