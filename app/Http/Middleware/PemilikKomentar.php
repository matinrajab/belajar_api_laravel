<?php

namespace App\Http\Middleware;

use App\Models\Comment;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PemilikKomentar
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentUser = Auth::user();
        $comment = Comment::findOrFail($request->id);

        // jika user_id dari comment tidak sama dengan id dari user yang sedang login
        if ($comment->user_id != $currentUser->id) {
            return response()->json(['message' => 'data not found'], 404);
        }

        // diarahkan ke CommentController
        return $next($request);
    }
}
