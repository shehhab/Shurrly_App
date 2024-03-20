<?php

namespace App\Http\Resources\Advisor\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data =  [

            'name' => $this->name,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'advisor_id' => $this->advisor_id,
            'image' => $this->getFirstMediaUrl('cover_product'),




        ];


        if ($videoUrl = $this->getFirstMediaUrl('Product_Video')) {
            $data['Product_Video'] = $videoUrl;
        }

        if ($pdfUrl = $this->getFirstMediaUrl('product_pdf')) {
            $data['product_pdf'] = $pdfUrl;
        }

        if ($this->video_duration !== null) {
            $data['video_duration'] = $this->video_duration;
        }

        if ($this->pdf_page_count !== null) {
            $data['pdf_page_count'] = $this->pdf_page_count;
        }

        return $data;


    }






}

