<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class StoreQueueRequest extends FormRequest
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
        $isArtist = in_array($this->input('type'), ['new_artist', 'edit_artist']);
        $isRecord = in_array($this->input('type'), ['new_record', 'edit_record']);

        return [
            'type'    => ['required', Rule::in('new_artist', 'new_record', 'edit_artist', 'edit_record')],
            'payload' => ['required', 'string'],

            // only apply artist files if type artist
            'artistIcon'  => [$isArtist ? 'nullable' : 'prohibited', 'image', 'mimes:jpg'],
            'artistCover' => [$isArtist ? 'nullable' : 'prohibited', 'image', 'mimes:jpg'],

            // only apply record file if type  record
            'recordFile'  => [$isRecord ? 'nullable' : 'prohibited', 'image', 'mimes:jpg', 'dimensions:ratio=1/1,max_width=1280'],
        ];
    }

    public function toModel() {
        $data = $this->validated();

        return[
            'type'=>$data['type'],
            'payload'=> $data['payload']
        ];
    }

    //check payload contents after main validation
    public function withValidator($validator) {

        $validator->after(function ($validator) {
            $payload=$this->input('payload');
            $data = is_string($payload) ? json_decode($payload,true) : $payload;

            if(!is_array($data)) {
                $validator->errors()->add('payload','Payload must be a valid JSON object!');
                return;
            }

            $rules = match($this->input('type')) {
                'new_artist','edit_artist'=>$this->artistPayloadRules(),
                'new_record', 'edit_record' => $this->recordPayloadRules(),
                default => null
            };

            if($rules) {
                $payloadValidator = Validator::make($data, $rules);

                if($payloadValidator->fails()) {
                    foreach ($payloadValidator->errors()->messages() as $field=>$messages) {
                        foreach($messages as $message) {
                            $validator->errors()->add("payload.$field",$message);
                        }
                    }
                }
            }
        });
    }

    //if the type is artist the validation rules apply to the payload
    private function artistPayloadRules(): array
    {

        $isEdit = $this->input('type') === 'edit_artist';

        return [
            'artistToModifyId'  => [$isEdit ? 'required' : 'prohibited', 'integer', Rule::exists('artist', 'id')], // only required on editing
            'artistName'        => ['required', 'string', 'max:64'],
            'isGroup'           => ['required', 'integer', Rule::in([0, 1])],
            'activeSince'       => ['sometimes', 'nullable', 'integer', 'digits:4'],
            'artistNationality' => ['sometimes', 'nullable', 'string', 'max:3'],
            'artistWebsite'     => ['sometimes', 'nullable', 'string', 'max:255']
        ];
    }

    //if the type is record the validation rules apply to the payload
    private function recordPayloadRules(): array
    {
        $isEdit = $this->input('type') === 'edit_record';

        return [
            'recordToModifyId'  => [$isEdit ? 'required' : 'prohibited', 'integer', Rule::exists('record', 'id')], 
            'recordName'        => ['required', 'string', 'max:64'],
            'typeId'            => ['required', 'integer', Rule::exists('record_type', 'id')],
            'releaseYear'       => ['sometimes', 'nullable', 'integer', 'digits:4'],
            'length'            => ['sometimes', 'nullable', 'integer', 'min:1']
        ];
    }

    public function messages() {
        return ['type.in'=>'The selected type is invalid. Valid types: new_record, new_artist, edit_record, edit_artist'];
    }
}
