<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transfers = Transfer::paginate(20);
        return view('pages.transfer.index', compact('transfers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.transfer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_number' => 'bail|required|min:10|max:16',
            'transaction_id' => 'bail|required|digits:12',
            'merchant_id'   => 'bail|required|exists:merchants',
            'terminal_id'   => 'bail|required|exists:terminals,ses_money_id|size:12',
            'amount'        => 'bail|required|digits:12',
            'description'   => 'bail|required|min:6|max:100',
            'provider'      => 'bail|required|size:3|in:MTN,TGO,ATL,VDF'
        ]);

        $validator->validate();

        try {
            $data = $request->all();
            $data['stan'] = stan();
            $transfer = Transfer::create($data);
            ( new Transaction() )->credit($transfer);
            return redirect()->route('transfers.show', ['stan' => $transfer->stan]);
        } catch (\Exception $exception) {
            logger($exception);
            session()->flash('error', 'Transfer initiation failed!');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param string $stan
     * @return \Illuminate\Http\Response
     */
    public function show(string $stan)
    {
        $transfer = Transfer::where('stan', $stan)->first();
        return view('pages.transfer.show', compact('transfer'));
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

    public function history()
    {
        return view('pages.transfer.history');
    }

    public function handleHistory(Request $request)
    {
        $this->validate($request, [
            'from'  =>  'required|string',
            'to'    =>  'required|string'
        ]);

        return redirect()->route('transfers.search', ['to' => $request->to, 'from' => $request->from]);
    }

    public function search(string $from, string $to)
    {
        $_from = Carbon::parse($from);
        $_to = Carbon::parse($to);

        if ($_to->diffInDays($_from) < 0 ){
            session()->flash('error', 'The end date cannot be older than the start date');
            return redirect()->back();
        }

        $transfers = Transfer::whereBetween('created_at', [$_from->startOfDay()->toDateTimeString(), $_to->endOfDay()->toDateTimeString()])->paginate(20);
        return view('pages.transfer.search', compact('transfers'));
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
}
