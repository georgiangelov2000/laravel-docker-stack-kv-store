<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KvSetRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'key' => ['required','string','max:191'],
            'value' => ['required'],           // accept any JSON
            'ttl' => ['nullable','integer','min:1','max:31536000'], // up to 1y
        ];
    }

    public function messages(): array
    {
        return [
            'key.required' => 'The key field is required.',
            'key.string' => 'The key must be a valid string.',
            'key.max' => 'The key may not be longer than 191 characters.',

            'value.required' => 'The value field cannot be empty.',

            'ttl.integer' => 'The TTL must be an integer value (in seconds).',
            'ttl.min' => 'The TTL must be at least 1 second.',
            'ttl.max' => 'The TTL cannot exceed 31,536,000 seconds (1 year).',
        ];
    }
}
