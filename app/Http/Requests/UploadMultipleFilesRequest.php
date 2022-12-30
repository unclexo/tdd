<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class UploadMultipleFilesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'files' => ['required', 'array'],
            'files.*' => Rule::forEach(function($value, $attribute) {
                if ($value instanceof UploadedFile) {
                    if (in_array($value->getMimeType(), ['image/jpeg', 'image/png', 'application/pdf'])) {
                        return ['mimes:jpg,png,pdf', 'size:200'];
                    } elseif ($value->getMimeType() === 'video/mp4') {
                        return ['mimes:mp4', 'size:1024'];
                    }
                }

                return ['prohibited'];
            }),
        ];
    }
}
