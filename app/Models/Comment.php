<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes; //untuk menggunakan softdelete
    protected $table = "comment";

    protected $fillable = ['post_id', 'user_id', 'comment_content'];

    public function commentator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function content(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }
}
