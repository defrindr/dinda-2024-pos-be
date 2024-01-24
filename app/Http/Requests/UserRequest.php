<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;

class UserRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'code'     => 'required|min:8',
            'name'     => 'required|min:4',
            'email'    => 'required|email',
            'phone'    => 'required|min:10,max:13',
            'photo'    => 'required|file',
            'role'     => "required|in:" . implode(",", array_keys(User::listRoles()))
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $user = $this->route()->parameter('user');
            $rules['username'] = [
                'required',
                'string',
                'min:5',
                Rule::unique(User::getTableName())->ignore($user),
            ];
            $rules['photo'] = [
                'file',
                'nullable',
                'mimes:jpg,png,gif'
            ];
            $rules['password'] = [
                'nullable',
                'string',
                'min:8'
            ];
        }

        return $rules;
    }
}
