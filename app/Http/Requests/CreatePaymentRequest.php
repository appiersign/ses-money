<?php

namespace App\Http\Requests;

use App\Merchant;
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
            'account_number'    => 'bail|required|min:10|max:16',
            'transaction_id'    => 'bail|required|digits:12',
            'merchant_id'       => 'bail|required|exists:merchants',
            'terminal_id'       => 'bail|required|exists:terminals,ses_money_id|size:12',
            'amount'            => 'bail|required|digits:12',
            'description'       => 'bail|required|min:6|max:100',
            'response_url'      => 'bail|required|url',
            'provider'          => 'bail|required|size:3|in:MTN,TGO,ATL,VDF,VIS,MAS'
        ];
    }

    public function withValidator(Validator $validator)
    {
        if ($this->has('merchant_id')) {
            $merchant = Merchant::where('api_user', $this->getUser())->where('api_key', $this->getPassword())->where('merchant_id', $this->only('merchant_id'))->first();
            $validator->after(function ($validator) use ($merchant){
                if (is_null($merchant)){
                    $validator->errors()->add('merchant_id', 'merchant id is wrong');
                } elseif (!$merchant->hasTerminal($this->input('terminal_id'))) {
                    $validator->errors()->add('terminal_id', 'unknown terminal');
                }
            });
        }
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(failedValidationResponse($errors), JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
