<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MakePaymentRequest extends FormRequest
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
            'transaction_id' => 'bail|required|digits:12',
            'merchant_id' => 'bail|required|exists:merchants',
            'amount' => 'bail|required|digits:12',
            'description' => 'bail|required|min:6|max:100',
            'provider' => 'bail|required|size:3|in:MTN,TGO,ATL,VDF,VIS,MAS'
        ];
    }
}
