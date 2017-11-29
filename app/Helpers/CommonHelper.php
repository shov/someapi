<?php declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

trait CommonHelper
{
    /**
     * Short routine validation data wrapping
     *
     * @param array $data
     * @param array $rules
     * @throws ValidationException
     */
    public function validateArray(array $data, array $rules)
    {
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}