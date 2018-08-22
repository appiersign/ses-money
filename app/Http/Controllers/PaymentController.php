<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaymentRequest;
use App\Jobs\CreatePaymentJob;
use App\Jobs\MakePaymentJob;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function __construct()
    {
//        $this->middleware('merchant')->except('response', 'create', 'process', 'index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::orderBy('created_at', 'desc')->paginate(10);
        return view('pages.payment.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.payment.create');
    }

    public function process(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_number' => 'bail|required|min:10|max:16',
            'transaction_id' => 'bail|required|digits:12',
            'merchant_id' => 'bail|required|exists:merchants',
            'terminal_id' => 'bail|required|exists:terminals,ses_money_id|size:12',
            'amount' => 'bail|required|digits:12',
            'description' => 'bail|required|min:6|max:100',
            'response_url' => 'bail|required|url',
            'provider' => 'bail|required|size:3|in:MTN,TGO,ATL,VDF,VIS,MAS',
            'cvv' => 'bail|required_if:provider,MAS,VIS',
            'expiry_month' => 'bail|required_if:provider,MAS,VIS',
            'expiry_year' => 'bail|required_if:provider,MAS,VIS'
        ]);

        $validator->validate();

        $createPaymentJob = new CreatePaymentJob($request->all());
        $this->dispatch($createPaymentJob);
        $payment = new Payment();
        $payment->handle($request);
        $stan = Payment::where('transaction_id', $request->transaction_id)->where('merchant_id', $request->merchant_id)->first()->stan;
        return redirect()->route('payments.show', ['stan' => $stan]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreatePaymentRequest $request
     * @return JsonResponse
     */
    public function store(CreatePaymentRequest $request)
    {
        try {
            $createPaymentJob = new CreatePaymentJob($request->all());
            $this->dispatch($createPaymentJob);
            $payment = new Payment();
            return response()->json(array_merge($request->all(), $payment->process($request)));
        } catch (\Exception $exception) {
            logger($exception->getMessage());
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
     * @param string $stan
     * @return Payment
     */
    public function show(string $stan)
    {
        $payment = Payment::where('stan', $stan)->first();
        if ($payment) {
            return view('pages.payment.show', compact('payment'));
        }
        session()->flash('error', 'Payment not found!');
        return redirect()->route('payments');
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

    public function history()
    {
        return view('pages.payment.history');
    }

    public function handleHistory(Request $request)
    {
        $this->validate($request, [
            'from'  =>  'required|string',
            'to'    =>  'required|string'
        ]);

        return redirect()->route('payments.search', ['to' => $request->to, 'from' => $request->from]);
    }

    public function search(string $from, string $to)
    {
        $_from = Carbon::parse($from);
        $_to = Carbon::parse($to);

        if ($_to->diffInDays($_from) < 0 ){
            session()->flash('error', 'The end date cannot be older than the start date');
            return redirect()->back();
        }

        $payments = Payment::whereBetween('created_at', [$_from->startOfDay()->toDateTimeString(), $_to->endOfDay()->toDateTimeString()])->paginate(20);
        return view('pages.payment.search', compact('payments'));
    }
}
