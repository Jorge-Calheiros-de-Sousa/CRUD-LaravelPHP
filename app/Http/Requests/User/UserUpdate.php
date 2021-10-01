<?php

namespace App\Http\Requests\User;

use Hash;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdate extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            "name" => "required",
            "email" => "required|email",
            "password" => "required|min:8"
        ];
    }

    /**
     * Return only user data and Hash the password if exists
     */
    public function getUserData($user)
    {
        $data = $this->except(['_token', '_method']);

        if (!Hash::check($data["password"], $user->password)) {
            return back()->withErrors([
                "modal-message" => __("auth.password")
            ]);
        }

        if (array_key_exists("password", $data) && $data["password"]) {
            $data["password"] =  Hash::make($data["password"]);
        } elseif (array_key_exists("password", $data) && empty($data["password"])) {
            unset($data["password"]);
        }


        return $data;
    }
}
