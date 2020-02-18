<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Source extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        /*
         * We have to access all the translatable attributes
         * explicitly so they get translated 😔
         */
        return array_merge(parent::toArray($request), [
            'title' => $this->title,
            'text' => $this->text,
            'thumbnail' => $this->thumbnail,
            'transcript' => $this->transcript,
            'category' => [
                'name' => $this->category->name,
                'color' => $this->category->color,
                'color_light' => $this->category->color_light,
            ],
        ]);
    }
}
