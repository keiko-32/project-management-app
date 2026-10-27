<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\ProjectStatus;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     *  バリデーションルールを取得する
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // customerテーブルのidカラムに存在するかチェック
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'name' => ['required', 'string', 'max:20'],
            'status' => ['required', new Enum(ProjectStatus::class)],
            'amount' => ['required', 'integer', 'min:0'],
            'description' => ['string', 'nullable'],
            'order_date' => ['date', 'nullable'],
        ];
    }
}