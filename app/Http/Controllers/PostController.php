<?php

namespace App\Http\Controllers;

use App\Http\Resources\Post2DetailResource;
use App\Http\Resources\PostDetailResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $post = Post::all();

        // data yang akan dioper ke frontend akan dimasukkan ke sebuah key 'data'
        // return response()->json(['data' => $post]);

        // menggunakan API resource -> kita dapat seenak kita menambah key-key lainnya
        // (kalau ingin menampilkan banyak data atau berupa array)
        return PostResource::collection($post->loadMissing('comments:id,post_id,user_id,comment_content'));
        // post_id wajib dipanggil untuk menentukan postingan mana yg ingin kita tampilkan commentnya
        // user_id wajib dipanggil untuk mendapatkan siapa commentatornya
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required',
            // gambar tidak harus diupload
            'file' => 'file|mimes:jpg,jpeg,png',
        ]);

        $image = null;
        // jika mengirim file gambar
        if ($request->file) {
            // beri nama pada image
            $fileName = $this->generateRandomString();
            $extension = $request->file->extension();
            $image = $fileName . '.' . $extension;

            // file akan disimpan pada folder storage/app/image
            // $request->file adalah file yang disimpan
            Storage::putFileAs('image', $request->file, $image);
        }

        // isi data image
        $request['image'] = $image;
        // data author diisi sesuai orang yang sedang login
        $request['author'] = Auth::user()->id;
        // create data
        $post = Post::create($request->all());
        return new PostDetailResource($post);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // tanpa with
        $post = Post::findOrFail($id);

        // menggunakan json
        // return response()->json(['data' => $post]);

        // menggunakan API resource (kalau hanya ingin menampilkan 1 data) menggunakan 'new'
        return new PostDetailResource($post->loadMissing('comments:id,post_id,user_id,comment_content'));
        // post_id wajib dipanggil untuk menentukan postingan mana yg ingin kita tampilkan commentnya
        // user_id wajib dipanggil untuk mendapatkan siapa commentatornya
    }

    public function show2(string $id)
    {
        // id harus diikutsertakan pada 'with' jika ingin menampilkan beberapa field saja
        $post = Post::with('writer:id,username')->findOrFail($id);

        // menggunakan json
        // return response()->json(['data' => $post]);

        // menggunakan API resource (kalau hanya ingin menampilkan 1 data) menggunakan 'new'
        return new Post2DetailResource($post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required',
        ]);

        $post = Post::findOrFail($id);

        $image = $post->image;
        // cek apakah file diganti
        if ($request->file) {
            $request->validate([
                'file' => 'file|mimes:jpg,jpeg,png',
            ]);
            // hapus gambar lama dari storage
            Storage::delete('image/' . $post->image);
            // beri nama pada image
            $fileName = $this->generateRandomString();
            $extension = $request->file->extension();
            $image = $fileName . '.' . $extension;

            // file akan disimpan pada folder storage/app/image
            // $request->file adalah file yang disimpan
            Storage::putFileAs('image', $request->file, $image);
        }

        $request['image'] = $image;
        $post->update($request->all());

        // hasil yang ditampilkan jika berhasil update
        return new PostDetailResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // cari data
        $post = Post::findOrFail($id);
        // jika terdapat image
        if ($post->image) {
            // hapus gambar dari storage
            Storage::delete('image/' . $post->image);
        }
        // hapus data dari database
        $post->delete();

        // hasil yang ditampilkan jika berhasil delete
        return new PostDetailResource($post);
    }

    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
