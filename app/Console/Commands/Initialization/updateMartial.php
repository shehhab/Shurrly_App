<?php

namespace App\Console\Commands\Initialization;


use App\Models\Skill;

use App\Models\Product;
use App\Models\Rate;
use Illuminate\Console\Command;


class updateMartial extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'matrial';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'matrial Initialize';

    /**
     * Execute the console command.
     */
    public function handle()
    {

    // ---------------product 1 ---------------------//

    $product1 =Product::create([
        'title' => 'this is title 1',
        'description'=>'this is description 1',
        'price' => 50,
        'advisor_id' => 1,
        'pdf_page_count'=> 1 ,
        'skills[]' =>'Entrepreneurship and Startups'
    ]);

    $image1 = asset('Default/Category/3.jpg');
    $product1->addMediaFromUrl($image1)->toMediaCollection('cover_product');

    $video1 = asset('Default/Category/cv.pdf');
    $product1->addMediaFromUrl($video1)->toMediaCollection('product_pdf');


    $skill = Skill::where('name', 'Entrepreneurship and Startups')->first();
    $product1->skills()->attach($skill->id);
    $product1->save();



    // ---------------product 2 ---------------------//

    $product2 =Product::create([
        'title' => 'this is title 2',
        'description'=>'this is description 2',
        'price' => 100,
        'advisor_id' => 2,
        'video_duration' =>'00:00:01' ,
        'skills[]' =>'Entrepreneurship and Startups'
    ]);

    $image2 = asset('Default/Category/3.jpg');
    $product2->addMediaFromUrl($image2)->toMediaCollection('cover_product');

    $video2 = asset('Default/Category/h.mp4');
    $product2->addMediaFromUrl($video2)->toMediaCollection('Product_Video');


    $skill = Skill::where('name', 'Entrepreneurship and Startups')->first();
    $product2->skills()->attach($skill->id);
    $product2->save();


    // ---------------product 3 ---------------------//

    $product3 =Product::create([
        'title' => 'this is title 3',
        'description'=>'this is description 3',
        'price' => 300,
        'advisor_id' => 2,
        'video_duration' =>'00:00:01' ,
        'skills[]' =>'Entrepreneurship and Startups'
    ]);

    $image3 = asset('Default/Category/3.jpg');
    $product3->addMediaFromUrl($image3)->toMediaCollection('cover_product');

    $video3 = asset('Default/Category/h.mp4');
    $product3->addMediaFromUrl($video3)->toMediaCollection('Product_Video');


    $skill = Skill::where('name', 'Entrepreneurship and Startups')->first();
    $product3->skills()->attach($skill->id);
    $product3->save();




    // ---------------product 4 ---------------------//


    $product4 =Product::create([
        'title' => 'this is title 4',
        'description'=>'this is description 4',
        'price' => 400,
        'advisor_id' => 1,
        'pdf_page_count'=> 1 ,
        'skills[]' =>'Entrepreneurship and Startups'
    ]);

    $image4 = asset('Default/Category/3.jpg');
    $product4->addMediaFromUrl($image4)->toMediaCollection('cover_product');

    $video4 = asset('Default/Category/cv.pdf');
    $product4->addMediaFromUrl($video4)->toMediaCollection('product_pdf');


    $skill = Skill::where('name', 'Entrepreneurship and Startups')->first();
    $product4->skills()->attach($skill->id);
    $product4->save();



        // ---------------Rate 1---------------------//

    Rate::create([
        'seeker_id' => 1,
        'product_id' => 1,
        'rate'=> '4',
        'message'=> 'this message ' ,
    ]);







    $this->info('product initialized successfully.');


        return;

    }

}
