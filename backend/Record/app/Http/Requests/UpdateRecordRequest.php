<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRecordRequest extends FormRequest
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
            'recordName' =>['required','string','max:64'],
            'typeId' => ['required','integer',Rule::exists('record_type','id')],
            'releaseYear' => ['sometimes','nullable','integer','digits:4'],
            'length' => ['sometimes','nullable','integer','min:1'],
            'recordFile' => ['sometimes','nullable','image','mimes:jpg','dimensions:ratio=1/1,max_width=1280']
        ];
    }

    public function toModel() {
        $data=$this->validated();

        return[
            'name'=>$data['recordName'],
            'type_id'=>$data['typeId'],
            'release_year'=>$data['releaseYear'] ?? null,
            'length'=>$data['length'] ?? null,
        ];
    }
}
