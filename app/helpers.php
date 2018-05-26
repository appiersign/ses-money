<?php

function failedValidationResponse($errors){
    $response = [];
    foreach ($errors as $key => $array) {
        $response[$key] = $array[0];
    }
    return $response;
}