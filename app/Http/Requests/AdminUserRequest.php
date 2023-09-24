<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class AdminUserRequest extends FormRequest
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
    public function rules(Request $request)
    {
        if ($this->method() == 'PATCH') {
            $id = $request->route('admin_user')->id;
            return [
                'name' => 'required',
                'email' => 'required|email|unique:admin_users,email,'. $id,
                'phone' => 'required|numeric|unique:admin_users,phone,'. $id
            ];
        }else{
            return [
                'name' => 'required',
                'email' => 'required|email|unique:admin_users,email',
                'phone' => 'required|numeric|unique:admin_users,phone',
                'password' => 'required'
            ];
        }
    }
}
