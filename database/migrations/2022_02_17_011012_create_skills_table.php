<?php

use App\Models\Skill;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('public')->default(false);
            $table->foreignId('categories_id')->nullable()->references('id')->on('categories');
            $table->timestamps();
        });

        $skillData = [
            ['name' => 'Accounting', 'public' => true, 'categories_id' => 1, 'image_path' => 'Default\Category\Accounting.PNG'],
            ['name' => 'Business', 'public' => true, 'categories_id' => 1, 'image_path' => 'Default\Category\Business.PNG'],
            ['name' => 'Economics', 'public' => true, 'categories_id' => 1, 'image_path' => 'Default\Category\Economics.PNG'],
            ['name' => 'Finance', 'public' => true, 'categories_id' => 1, 'image_path' => 'Default\Category\Finance.PNG'],
            ['name' => 'Management', 'public' => true, 'categories_id' => 1, 'image_path' => 'Default\Category\Management.PNG'],
            ['name' => 'Marketing', 'public' => true, 'categories_id' => 1, 'image_path' => 'Default\Category\Marketing.PNG'],
        ];

        foreach ($skillData as $data) {
            $skill = Skill::create([
                'name' => $data['name'],
                'public' => $data['public'],
                'categories_id' => $data['categories_id'],
            ]);

            $imagePath = asset($data['image_path']);
            $skill->addMediaFromUrl($imagePath)->toMediaCollection('image_catogory');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
