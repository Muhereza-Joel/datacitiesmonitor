<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class AgeRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Check if the date is not null and is a valid date
        if (is_null($value) || !strtotime($value)) {
            return true; // Consider null values valid (nullable)
        }

        // Check if the date is at least 20 years ago
        return Carbon::parse($value)->diffInYears(Carbon::now()) >= 20;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You must be at least 20 years old.';
    }
}
