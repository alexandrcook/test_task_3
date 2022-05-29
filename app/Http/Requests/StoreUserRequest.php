<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'email' => 'required',
            'password' => 'required',
            "name" => 'required',
//            "surname" => 'required',
//            "nickname" => 'required',
//            "phone" => 'required',
//            "updated_at" => 'required',
//            "created_at" => 'required',
//            "address" => 'required',
//            "city" => 'required',
//            "state" => 'required',
//            "zipcode" => 'required'
        ];
    }
}
