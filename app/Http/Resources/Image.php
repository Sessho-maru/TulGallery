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
                'image_id' => $this->id,
                'uploader' => $this->user->name,
                'url' => $this->url,
                'description' => $this->description,
                'external_link' => $this->external_link,
                'last_updated' => $this->updated_at->diffForHumans()
            ],
            'links' => [
                'self' => $this->path()
            ]
        ];
    }
}
