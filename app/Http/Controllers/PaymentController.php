<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaymentRequest;
use App\Jobs\CreatePaymentJob;
use App\Jobs\MakePaymentJob;
use App\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreatePaymentRequest $request
     * @return JsonResponse
     */
    public function store(CreatePaymentRequest $request): JsonResponse
    {
        try {
            $createPaymentJob = new CreatePaymentJob($request->all());
            $this->dispatch($createPaymentJob);
            $payment = new Payment();
            return response()->json(array_merge($request->all(), $payment->process($request)));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(array_merge($request->all(), [
                'status' => 'failed',
                'code' => 5000,
                'reason' => 'payment could not be processed'
            ]));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param Payment $payment
     * @return Payment
     */
    public function show(Payment $payment): Payment
    {
        return $payment;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function response($provider, Request $request)
    {
        $_request = json_decode($request->getContent(), true);
        $response = [];
        $response['provider'] = $provider;
        if ($provider === 'mtn') {
            $response['external_id']    = $_request['invoiceNo'];
            $response['transaction_id'] = $_request['transactionId'];
            $response['response_code']  = $_request['responseCode'];
        } elseif ($provider === 'tigo') {
            $response['external_id']    = $_request['correlation_id'];
            $response['transaction_id'] = $_request['transaction_id'];
            $response['response_code']  = $_request['code'];
        } elseif ($provider === 'airtel') {
            $response['external_id']    = $_request['trans_id'];
            $response['transaction_id'] = $_request['trans_ref'];
            $response['response_code']  = $_request['trans_status'];
            $response['narration']      = $_request['message'];
        }
        $payment = new Payment();
        $payment->response($response);
    }
}
