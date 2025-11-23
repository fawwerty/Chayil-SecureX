<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Verify2FARequest extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'email' => 'required|email',
            'token' => 'required|string'
        ];
    }
}
