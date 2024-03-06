<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class ValidDateOfBirth implements Rule
{
    public function passes($attribute, $value)
    {
        $dateOfBirth = Carbon::createFromFormat('d/m/Y', $value);

        // Check if the user has an age between 16 and 70
        return $dateOfBirth->age >= 16 && $dateOfBirth->age <= 70;
    }

    public function message()
    {
        return 'The :attribute must be a valid date of birth between 16 and 70 years old.';
    }
}
