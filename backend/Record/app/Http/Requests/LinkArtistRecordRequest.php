<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LinkArtistRecordRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'artistId'=>['required','integer',Rule::exists('Artist','id')],
            'recordId'=>['required','integer',Rule::exists('Record','id')]
        ];
    }

    public function toModel() {
        $data=$this->validated();

        return [
            'artist_id'=>$data['artistId'],
            'record_id'=>$data['recordId']
        ];
    }
}
