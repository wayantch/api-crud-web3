<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index(){
        $posts = Post::latest()->paginate(5);

        return new PostResource(true, 'List Data Post', $posts);
    }

    public function store(Request $request)
    {
        // devine validator
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'nim' => 'required',
            'prodi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // upload
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        $post = Post::create([
            'image' => $image->hashName(),
            'name' => $request->name,
            'nim' => $request->nim,
            'prodi' => $request->prodi
        ]);

        return new PostResource(true, 'Data Post Berhasil Ditambahkan!', $post);
    }

    public function show($id)
    {
        $post = Post::find($id);

        return new PostResource(true, 'Data Post Ditemukan!', $post);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'image'  => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name'  => 'required',
            'nim'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $post = Post::find($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            Storage::delete('public/posts/'. basename($post->image));

            $post->update([
                'image' => $image->hashName(),
                'name' => $request->name,
                'nim' => $request->nim,
                'prodi' => $request->prodi,
            ]);
        } else {
            $post->update([
                'name' => $request->name,
                'nim' => $request->nim,
                'prodi' => $request->prodi,
            ]);
        }

        return new PostResource(true, 'Data Post Berhasil Diupdate!', $post);
    }

    public function destroy($id)
    {
        $post = Post::find($id);

        Storage::delete('public/posts/'. basename($post->image));

        $post->delete();

        return new PostResource(true, 'Data Post Berhasil Dihapus!', null);
    }

}
