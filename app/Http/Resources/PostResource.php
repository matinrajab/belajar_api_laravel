<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // jika tidak kita apa"kan, maka yang dikembalikan otomatis dari modelnya 
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            // menambahkah author dari setiap postingan
            'author' => $this->author,
            // langsung arahkan pada username
            'writer' => $this->writer->username,
            'title' => $this->title,
            'image' => $this->image,
            'news_content' => $this->news_content,
            'created_at' => date_format($this->created_at, "Y/m/d"),
            // menampilkan total comment
            'comment_total' => $this->whenLoaded('comments', function () {
                return $this->comments->count();
            }),
            // menampilkan comments
            'comments' => $this->whenLoaded('comments', function () {
                return collect($this->comments)->each(function ($comment) {
                    $comment->commentator;
                    return $comment;
                });
            })
        ];
    }
}
