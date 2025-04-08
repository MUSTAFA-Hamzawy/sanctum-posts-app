<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected PostService $postService;
    public function __construct(PostService $postService) {
        $this->postService = $postService;
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $posts = $this->postService->all($perPage);

        return response()->json($posts);
    }

    public function store(PostRequest $request)
    {
        $post = $this->postService->create($request->validated());
        return response()->json(['message' => 'Post created', 'post' => $post], 201);
    }

    public function show($id)
    {
        try {
            return response()->json($this->postService->find($id));
        }catch (ModelNotFoundException $e){
            return response()->json(['message' => 'Post not found'], 404);
        }

    }

    public function update(PostRequest $request, Post $post)
    {
        $validData = $request->validated();
        if (key_exists('title', $validData) && empty(trim($validData['title']))) {
            return response()->json(['error' => 'Title cannot be empty.'], 422);
        }

        if (key_exists('content', $validData) && empty(trim($validData['content']))) {
            return response()->json(['error' => 'Content cannot be empty.'], 422);
        }

        if (!$this->postService->isOwner($post)) {
            return response()->json(['error' => 'only owner post allowed to edit.'], 403);
        }

        $post->fill($request->only(['title', 'content']));
        if ($post->isDirty(['title', 'content'])) {
            $post = $this->postService->update($post, $validData);
            return response()->json(['message' => 'Post updated', 'post' => $post]);
        }

        return response()->json(['message' => 'No changes detected.'], 304);
    }

    public function destroy(Post $post)
    {
        if (!$this->postService->isOwner($post)) {
            return response()->json(['error' => 'only owner post allowed to delete'], 403);
        }

        $this->postService->delete($post);
        return response()->json(['message' => 'Post deleted']);
    }
}
