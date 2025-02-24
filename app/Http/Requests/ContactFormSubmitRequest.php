<?php

namespace App\Http\Requests;

use App\DataTransferObjects\StoreContactFormData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

final class ContactFormSubmitRequest extends FormRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['bail', 'required', 'string', 'min:2', 'max:10'],
            'email' => ['bail', 'required', 'string', 'email', 'max:100',
                static function (string $attr, string $val, \Closure $fail) {
                    [, $domain] = array_pad(explode('@', $val, 2), 2, null);
                    if (mb_strtolower($domain) === 'gmail.com') {
                        $fail(__('Gmail addresses are not allowed.'));
                    }
                },
            ],
            'phone' => ['bail', 'required', 'string', 'max:100', 'starts_with:+,0'],
            'message' => ['bail', 'required', 'string', 'min:10'],
            'street' => ['bail', 'present', 'nullable', 'string', 'max:200'],
            'state' => ['bail', 'present', 'nullable', 'string', 'max:200'],
            'zip' => ['bail', 'present', 'nullable', 'string', 'max:200'],
            'country' => ['bail', 'present', 'nullable', 'string', 'max:200'],
            'images' => ['bail', 'nullable', 'array'],
            'images.*' => ['bail', 'required', 'image'],
            'files' => ['bail', 'nullable', 'array'],
            'files.*' => ['bail', 'required', File::types(['pdf'])->max(20 * 1024)],
        ];
    }

    public function asData(): StoreContactFormData
    {
        $data = $this->safe()->all();
        $data['files'] ??= [];
        $data['images'] ??= [];

        return new StoreContactFormData(
            ...$data,
        );
    }
}
