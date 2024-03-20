<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class SavedProductResource extends JsonResource
{
    public function toArray($request): array
    {


        $data =  [
            'product_id' => $this->product_id,
            'title' => $this->product->title,
            'cover_product' => $this->product->getFirstMediaUrl('cover_product'),
            'skills' => $this->product->advisor->category->pluck('name')->toArray(),

        ];

        if ($this->product->video_duration !== null) {
            $data['video_duration'] = $this->product->video_duration;
        }

        if ($this->product->pdf_page_count !== null) {
            $data['pdf_page_count'] = $this->product->pdf_page_count;
        }

        return $data;

    }
}
