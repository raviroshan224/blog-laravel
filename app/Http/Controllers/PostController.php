<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::get();
        return response()->json($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('post-create')) {
            return response()->json([
                'message' => 'You do not have permission to view posts.'
            ], 403);
        }
        $post = Post::create($request->all());
        return response()->json($post, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::find($id);
        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = auth()->user();
        
        
        if (!auth()->user()->can('post-edit') ) {
            return response()->json([
                'message' => 'You do not have permission to update this.'
            ], 403);
        }
        $post = Post::find($id);

        $isAdmin = $user->hasRole('admin');
        $isAuthor = $user->id == $post->author_id;
        if (!$isAdmin && !$isAuthor) {
            return response()->json([
                'message' => 'You can only edit your own posts or you must be an admin.',
                'post_author' => $post->user->name ?? 'Unknown',
                'your_role' => $user->getRoleNames()->first() ?? 'No role'
            ], 403);
        }

        $post->update($request->all());
        return response()->json($post, 200);
    }
    
    /**
     
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!auth()->user()->can('post-delete')) {
            return response()->json([
                'message' => 'You do not have permission to view posts.'
            ], 403);
        }
        $post = Post::find($id);

        $user = auth()->user();
        $isAdmin = $user->hasRole('admin');
        $isAuthor = $user->id == $post->author_id;
        if (!$isAdmin && !$isAuthor) {
            return response()->json([
                'message' => 'You can only delete your own posts or you must be an admin.',
                'post_author' => $post->user->name ?? 'Unknown',
                'your_role' => $user->getRoleNames()->first() ?? 'No role'
            ], 403);
        }

        $post->delete();
        return response()->json($post,200);
    }
}
