<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePost;
use App\Http\Resources\PostResource;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::user()->id;
        $dataPosts = Posts::with('user')
            ->where('user_id', $userId)
            ->paginate(5);
        return PostResource::collection($dataPosts)
            ->additional([
                'status' => true,
                'code' => 200,
                'message' => 'berhasil mengambil semua data',
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePost $request)
    {
        $request->validate($request->rules());

        $title = $request['title'];
        $userId = Auth::user()->id;

        $request['slug'] = Str::slug($title);
        $request['user_id'] = $userId;

        $data = Posts::create($request->all());

        return response()->json([
            'status' => true,
            'code' => 201,
            'message' => 'berhasil menambahkan data',
            'data' => new PostResource($data)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Posts::with('user')->where('id', $id)->first();
        if (!$data) {
            return response()->json(
                [
                    'status' => false,
                    'code' => 404,
                    'message' => 'data tidak ditemukan'
                ],
                404
            );
        }

        $post = new PostResource($data);
        return response()->json(
            [
                'status' => true,
                'code' => 200,
                'message' => 'berhasil mengambil data',
                'data' => $post
            ],
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreatePost $request, string $id)
    {
        $post = Posts::find($id, '*');

        if (!$post) {
            return response()->json([
                'status' => false,
                'code' => 404,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $userId = Auth::user();

        if ($post->user_id !== $userId->id) {
            return response()->json([
                'status' => false,
                'code' => 403,
                'message' => 'Anda tidak memiliki hak untuk mengedit post ini'
            ], 403);
        }

        $dataUpdate = $request->validated();
        $dataUpdate['slug'] = Str::slug($request->title);

        $post->update($dataUpdate);

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => 'Berhasil memperbarui data',
            'data' => new PostResource($post)
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Posts::find($id, '*');

        if (!$post) {
            return response()->json([
                'status' => false,
                'code' => 404,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $userId = Auth::user();

        if ($post->user_id !== $userId->id) {
            return response()->json([
                'status' => false,
                'code' => 403,
                'message' => 'Anda tidak memiliki hak untuk mengedit post ini'
            ], 403);
        }

        $post->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => 'Berhasil menghapus data',
            'data' => new PostResource($post)
        ], 200);
    }
}
