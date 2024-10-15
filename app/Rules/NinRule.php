<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class NinRule implements Rule
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
       

        // Check if the NIN is in the correct format
        if (!preg_match('/^(CM|CF)[A-Z0-9]*$/', $value)) {
            return false; // NIN must start with CM or CF, contain any combination of uppercase letters and digits
        }

        // Check if all characters are uppercase
        if ($value !== strtoupper($value)) {
            return false; // NIN must be in uppercase
        }

        // NIN passed all checks
        return true;
    }

    public function message()
    {
        return 'The NIN must be unique, start with CM (for male) or CF (for female), and contain only uppercase letters and digits.';
    }
}
