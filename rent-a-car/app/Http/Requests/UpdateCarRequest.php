<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $currentYear = date('Y');
        return [
            'type' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'year' => 'required|integer|min:2000|max:'.$currentYear,
            'price_per_day' => 'required|integer|min:1',
            'photo' => 'required|string|max:255',
            'document' => 'required|string|max:255',
        ];
    }
}
