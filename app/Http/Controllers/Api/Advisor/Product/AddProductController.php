<?php

namespace App\Http\Controllers\Api\Advisor\Product;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\Advisor\Product\AddProductRequest;
use App\Http\Resources\Advisor\Product\ProductResources;
use setasign\Fpdi\Fpdi;
use Smalot\PdfParser\Parser;



class AddProductController extends Controller
{

    public function __invoke(AddProductRequest $request)
    {
        $validatedData = $request->validated();
        $advisor = request()->user();

        $advisor = Product::create([
            'name' => $validatedData['name'],
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'advisor_id' =>  $advisor->id,
        ]);
        // Upload cover image
        if ($request->hasFile('image')) {
            $advisor->addMediaFromRequest('image')->toMediaCollection('cover_product');
        }

        // Upload PDF file
        if ($request->hasFile('product_pdf')) {
            $advisor->addMediaFromRequest('product_pdf')->toMediaCollection('product_pdf');
            // Calculate number of pages in the PDF
            $pageCount = $this->getPdfPageCount($advisor->getFirstMedia('product_pdf')->getPath());
            // Save page count to the database
            $advisor->update([
                'pdf_page_count' => $pageCount,
            ]);
        }

        if ($request->hasFile('Product_Video')) {
            $mediaItem = $advisor->addMediaFromRequest('Product_Video')->toMediaCollection('Product_Video');
            // Calculate video duration
            $duration = $this->getVideoDuration($mediaItem->getPath());
            // Save video duration to the database
            $advisor->update([
                'video_duration' => $duration,
            ]);
        }

        return $this->handleResponse(status:true, code:200 ,message:'Upload Product Successfully', data: new ProductResources($advisor));

    }
    private function getVideoDuration($filePath)
    {
        $getID3 = new \getID3;
        // Analyze the video file
        $fileInfo = $getID3->analyze($filePath);
        // Get the duration of the video in seconds
        $durationInSeconds = $fileInfo['playtime_seconds'];
        // Calculate hours
        $hours = floor($durationInSeconds / 3600);
        // Calculate remaining minutes
        $minutes = floor(($durationInSeconds / 60) % 60);
        // Calculate remaining seconds
        $seconds = $durationInSeconds % 60;

        // Format duration as "hours:minutes:seconds"
        $durationFormatted = sprintf("%d:%02d:%02d", $hours, $minutes, $seconds);

        return $durationFormatted ;
    }

    private function getPdfPageCount($filePath)
    {
        // Initialize the PDF parser
        $parser = new Parser();
        // Parse the PDF file
        $pdf = $parser->parseFile($filePath);
        // Get the number of pages
        return count($pdf->getPages());
    }
}


