<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWishRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'wishes_id'      => ['nullable', 'string', 'max:32'],   // PK varchar
            'wishes_sender'  => ['required', 'string', 'max:20'],
            'wishes_account' => ['nullable', 'string', 'max:30'],
            'wishes'         => ['required', 'string'],
            'sts_id'         => ['required', 'string', 'max:16', 'exists:sts_info,sts_id'],
        ];
    }
}
