<?php

function failedValidationResponse($errors){
    $response = [];
    foreach ($errors as $key => $array) {
        $response[$key] = $array[0];
    }
    return $response;
}

function merchantIdValidated(\App\Merchant $merchant, $merchant_id){
    return $merchant->ses_money_id === $merchant_id;
}