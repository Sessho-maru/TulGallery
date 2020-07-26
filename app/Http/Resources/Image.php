<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Image extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'user_id' => $this->user->id,
                'user_name' => $this->user->user_name,
                'image_id' => $this->id,
                'url' => $this->url,
                'description' => $this->description,
                'tags' => $this->tags->pluck('tag_name')->toArray(),
                'last_updated' => $this->updated_at->diffForHumans(),
                'reported_count' => $this->reported_count
            ],
            'links' => [
                'self' => $this->path()
            ]
        ];
    }
}