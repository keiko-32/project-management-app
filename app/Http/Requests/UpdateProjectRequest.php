<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\ProjectStatus;


class UpdateProjectRequest extends FormRequest
{
    /**
     * リクエストに適用するバリデーションルールを取得
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules():array
    {


        return [
            'name' => ['required', 'string', 'max:20'],
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'amount' => ['integer', 'required','min:0'],
            'status' => ['required', new Enum(ProjectStatus::class)],
            'description' => ['string', 'nullable'],
            'order_date' => ['date', 'nullable'],
        ];
    }
}
