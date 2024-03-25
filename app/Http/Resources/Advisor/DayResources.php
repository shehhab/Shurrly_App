<?php

namespace App\Http\Resources\Advisor;

use Illuminate\Http\Resources\Json\JsonResource;

class DayResources extends JsonResource
{
    public function toArray($request)
    {
        $advisor = auth()->user();

        $days = [];

        if ($advisor && $advisor->days()->exists()) {
            $advisorDays = $advisor->days()->get();

            foreach ($advisorDays as $day) {
                $days[] = [
                    'id' => $day->id,
                    'day' => $day->day,
                    'from' => $day->from,
                    'to' => $day->to,
                    'break_from' => $day->break_from,
                    'break_to' => $day->break_to,
                ];
            }
        }

        return $days;
    }
}
