<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrongPassword implements Rule
{
    public function passes($attribute, $value)
    {
        // Check if the password meets the criteria
        if (empty($value)) {
            return false;
        } elseif (strlen($value) < 8) {
            return false;

        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/', $value)) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'The :attribute must be contain at least one uppercase letter, one lowercase letter, one digit, and one special character.';
    }

}
