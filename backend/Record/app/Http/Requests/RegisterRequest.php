<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
            'password'=>['required','string'],
            'userName'=>['required','string',Rule::unique('user','name')],
            'email'=>['nullable','email', Rule::unique('user','email')],
            'phoneNumber'=>['nullable','string','max:15', Rule::unique('user','phone')]
        ];
    }

    public function toModel() {
        $data = $this->validated();

        return[
            'password'=>$data['password'],
            'name'=>$data['userName'],
            'email'=>$data['email'] ?? null,
            'phone'=>$data['phoneNumber'] ?? null
        ];
    }
}
