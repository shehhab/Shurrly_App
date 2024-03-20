<?php

namespace App\Http\Resources\Advisor;

use App\Models\Advisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class DayResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


        $days = [];

        // التحقق من وجود القيمة قبل الوصول إليها
        if (isset($this['days']) && is_array($this['days'])) {
            foreach ($this['days'] as $day) {
                array_push($days, [
                    'id' => $day->id ?? null, // استخدام ?? للتحقق من وجود القيمة قبل الوصول إليها
                    'day' => $day->day ?? null,
                    'from' => $day->from ?? null,
                    'to' => $day->to ?? null,
                    'break_from' => $day['break_from'] ?? null, // استخدام ?? للتحقق من وجود القيمة قبل الوصول إليها
                    'break_to' => $day['break_to'] ?? null, // استخدام ?? للتحقق من وجود القيمة قبل الوصول إليها
                ]);
            }
        }

        return $days;
    }
}
