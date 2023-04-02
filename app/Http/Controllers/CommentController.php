<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'post_id' => 'required|exists:post,id',
            'comment_content' => 'required',
        ]);

        // data user_id diisi sesuai orang yang sedang login(ada 2 opsi penulisan)
        $request['user_id'] = Auth::user()->id;
        // $request['user_id'] = auth()->user()->id;

        // create comment
        $comment = Comment::create($request->all());

        // hasil yang ditampilkan jika berhasil create
        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
            'comment_content' => 'required',
        ]);

        // update data komentar
        $comment = Comment::findOrFail($id);
        $comment->update($request->all());

        // hasil yang ditampilkan jika berhasil update 
        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // hapus data comment
        $comment = Comment::findOrFail($id);
        $comment->delete();

        // hasil yang ditampilkan jika berhasil delete 
        return new CommentResource($comment);
    }
}
