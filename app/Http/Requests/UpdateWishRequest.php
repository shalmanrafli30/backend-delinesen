<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWishRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'wishes_sender'  => ['sometimes','string','max:20'],
            'wishes_account' => ['sometimes','nullable','string','max:30'],
            'wishes'         => ['sometimes','string'],
            'sts_id'         => ['sometimes','string','max:16','exists:sts_info,sts_id'],
        ];
    }
}

