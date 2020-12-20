<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Base64Image implements Rule
{
    private $MAX_IMAGE_SIZE = 8192;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(int $maxImageSize)
    {
        $this->MAX_IMAGE_SIZE = $maxImageSize;
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
        $size = strlen(base64_decode($value));
        $size_kb = $size / 1024;
        return $size_kb <= $this->MAX_IMAGE_SIZE;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute may not be greater than 8 MB.';
    }
}
