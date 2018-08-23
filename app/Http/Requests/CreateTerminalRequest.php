<?php

namespace App\Http\Requests;

use App\Merchant;
use App\Terminal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CreateTerminalRequest extends FormRequest
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
            'merchant' => 'required|exists:merchants,id',
            'name'  =>  'required|string|',
            'type'  =>  'required|string|in:web,offline'
        ];
    }

    public function withValidator(Validator $validator)
    {
        if (count(explode('/', $this->getPathInfo())) < 2){
            $validator->after(function ($validator){
                if (Terminal::where('merchant_id', $this->input('merchant'))->where('name', $this->input('name'))->count()){
                    $validator->errors()->add('error', 'Terminal name already used!');
                }
            });
        }
    }
}
