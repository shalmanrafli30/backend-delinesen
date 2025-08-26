<?php

// app/Http/Requests/UpdateStsRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'sts_name' => ['sometimes', 'string'],
        ];
    }
}
