<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Validation\ValidationException;
// use Illuminate\Support\Facades\Auth;
// use function Psy\debug;

class PublicPostController extends Controller
{
    /**
     * Register new user and issue token
     */
    public function posts(Request $request)
    {
        $posts = Post::orderBy("created_at","desc")->paginate(10);
        return response()->json($posts);

    }
}
