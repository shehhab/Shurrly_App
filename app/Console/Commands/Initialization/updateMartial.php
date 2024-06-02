<?php

namespace App\Console\Commands\Initialization;


use App\Models\Skill;

use App\Models\Product;
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
    //    name:she
    //     title:title name 2
    //    description:this is description 2
    //     price:50.30
    //    advisor_id:1
    //     skills[]:Accounting
    //     skills[]:laravel


    $product1 =Product::create([
        'title' => 'this is title 1',
        'description'=>'this is description 1',
        'price' => 50,
        'advisor_id' => 1,
        'skills[]' =>'Economics'
    ]);

    $image1 = asset('Default/Category/3.jpg');
    $product1->addMediaFromUrl($image1)->toMediaCollection('cover_product');

    $video1 = asset('Default/Category/cv.pdf');
    $product1->addMediaFromUrl($video1)->toMediaCollection('product_pdf');


    $skill = Skill::where('name', 'Economics')->first();
    $product1->skills()->attach($skill->id);
    $product1->save();




    $product2 =Product::create([
        'title' => 'this is title 2',
        'description'=>'this is description 2',
        'price' => 100,
        'advisor_id' => 2,
        'skills[]' =>'Accounting'
    ]);

    $image2 = asset('Default/Category/3.jpg');
    $product2->addMediaFromUrl($image2)->toMediaCollection('cover_product');

    $video2 = asset('Default/Category/h.mp4');
    $product2->addMediaFromUrl($video2)->toMediaCollection('Product_Video');


    $skill = Skill::where('name', 'Accounting')->first();
    $product2->skills()->attach($skill->id);
    $product2->save();




    $this->info('product initialized successfully.');


        return;

    }

}
