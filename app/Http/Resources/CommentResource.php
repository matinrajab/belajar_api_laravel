<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            // langsung arahkan pada username writer
            'writer' => $this->content->writer->username,
            // langsung arahkan pada title
            'title' => $this->content->title,
            // langsung arahkan pada news_content
            'news_content' => $this->content->news_content,
            'comment_content' => $this->comment_content,
            // langsung arahkan pada username commentator
            'commentator' => $this->commentator->username,
            'created_at' => date_format($this->created_at, "Y/m/d"),
            'updated_at' => date_format($this->updated_at, "Y/m/d"),
        ];
    }
}
