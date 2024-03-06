<?php

namespace App\Http\Resources\Advisor;

use Illuminate\Http\Request;
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
        foreach ($this['days'] as $day) {
                array_push($days, [
                    'id' => $day->id,
                    'day' => $day->day,
                    'from' => $day->from,
                    'to' => $day->to,
                    'break_from' => $day['break_from'],
                    'break_to' => $day['break_to'],
                ]);

        }
        return $days;
    }
}
