<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'attachment' => ['required', 'file', 'max:'.(config('app.max_upload_size') * 1024 * 1024)],
            'sender' => ['required', 'string', 'email', 'max:255'],
            'comments' => ['nullable', 'string', 'max:512'],
            'recipient' => ['nullable', 'string', 'email', 'max:255'],
            'password' => ['nullable', 'string', 'min:6'],
            'expiry' => ['required', 'int', 'in:1,7,30'],
        ];
    }
}
