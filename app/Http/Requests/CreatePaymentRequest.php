<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CreatePaymentRequest extends FormRequest
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
            'transaction_id'    => 'bail|required|digits:12',
            'merchant_id'       => 'bail|required|exists:merchants',
            'terminal_id'       => 'bail|required|exists:terminals,ses_money_id|size:12',
            'amount'            => 'bail|required|digits:12',
            'description'       => 'bail|required|min:6|max:100',
            'response_url'      => 'bail|required|url',
            'provider'          => 'bail|required|size:3|in:MTN,TGO,ATL,VDF,VIS,MAS'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json($errors, JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
