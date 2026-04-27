<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateArtistRequest extends FormRequest
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
            'artistName'=>['required','string'],
            'activeSince'=>['sometimes','nullable','integer','digits:4'],
            'artistNationality'=>['sometimes','nullable','max:3'],
            'artistWebsite'=>['sometimes','nullable','string','max:255'],
            'isGroup'=>['sometimes','required','integer',Rule::in('0','1')],
            'artistIcon'=>['sometimes','nullable','image','mimes:jpg,jpeg'],
            'artistCover'=>['sometimes','nullable','image','mimes:jpg,jpeg']
            ];
    }

    public function toModel() {
        $data=$this->validated();

        return[
            'name'=>$data['artistName'],
            'active_since'=>$data['activeSince'] ?? null,
            'nationality'=>$data['artistNationality'] ?? null,
            'url'=> $data['artistWebsite'] ?? null,
            'is_group'=>$data['isGroup'] ?? 0,
            'icon_path' => $data['artistIcon'] ?? null,
            'cover_path' => $data['artistCover'] ?? null,  
        ];
    }
}
