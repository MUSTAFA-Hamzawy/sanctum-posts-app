<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostService
{
    public function create(array $data)
    {
        return Auth::user()->posts()->create($data);
    }

    public function all($perPage = 10)
    {
        return Post::with('user:id,name')->latest()->paginate($perPage);
    }

    public function find($id)
    {
        return Post::with('user:id,name')->findOrFail($id);
    }

    public function update(Post $post, array $data)
    {
        $post->update($data);
        return $post;
    }

    public function delete(Post $post): void
    {
        $post->delete();
    }

    public function isOwner(Post $post): bool
    {
        return $post->user_id === Auth::id();
    }
}
