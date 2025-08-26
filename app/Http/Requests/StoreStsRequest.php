<?php

// app/Http/Requests/StoreStsRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'sts_id'   => ['required', 'string', 'max:16'],  // mis. STS2025
            'sts_name' => ['required', 'string'],
        ];
    }
}
