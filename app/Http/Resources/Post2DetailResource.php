<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Post2DetailResource extends JsonResource
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
            'author' => $this->author,
            'writer' => $this->writer,
            'title' => $this->title,
            'news_content' => $this->news_content,
            'created_at' => date_format($this->created_at, "Y/m/d"),
        ];
    }
}
