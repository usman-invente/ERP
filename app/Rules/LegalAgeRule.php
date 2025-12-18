<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class LegalAgeRule implements Rule
{
    public $legalAge = 16;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($age = NULL)
    {
        if($age != NULL)
            $this->legalAge = $age;
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
        $formattedValue = new Carbon($value);
        $legalAge = Carbon::now()->subYears($this->legalAge);
        return $formattedValue < $legalAge;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Sie sind unten ' . $this->legalAge . ' Jahre. Daher können Sie nicht registrieren!';
    }

}
