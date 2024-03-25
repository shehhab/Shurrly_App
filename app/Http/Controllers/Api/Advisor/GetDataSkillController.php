<?php

namespace App\Http\Controllers\Api\Advisor;

use App\Models\Skill;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetDataSkillController extends Controller
{
    public function __invoke()
    {

        $skills = Skill::all(['id', 'name', 'categories_id']);

        $categories = Category::all(['id', 'name' ]);

        $data = [
            'Category' => $categories,
            'skills' => $skills,
        ];
        return $this->handleResponse(data: $data, status: true, code: 200);

    }
}
