@extends('layouts.base')
@section('content')
    <div class="row gap-20 masonry pos-r">
        <div class="masonry-sizer col-md-6"></div>
        <div class="masonry-item col-12">
            <div class="bgc-white bd bdrs-3 p-20 mB-20">
                <h4 class="c-grey-900 mB-20">Payments</h4>
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th scope="col">Merchant ID</th>
                        <th scope="col">Transaction ID</th>
                        <th scope="col">Account Number</th>
                        <th scope="col">Provider</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Status</th>
                        <th scope="col">Date</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (isset($payments))
                        @foreach($payments as $payment)
                            <tr>
                                <th scope="row">{{ $payment->merchant_id }}</th>
                                <td>{{ $payment->transaction_id }}</td>
                                <td>{{ $payment->account_number }}</td>
                                <td>{{ $payment->provider }}</td>
                                <td>{{ $payment->amount }}</td>
                                <td>{{ $payment->response_status }}</td>
                                <td>{{ $payment->created_at }}</td>
                                <td>
                                    <a href="{{ route('merchants.edit', ['ses_money_id' => $payment->response_status]) }}" class="btn btn-outline-primary">
                                        <span class="icon-holder"><i class="c-white-500 ti-pencil"></i></span>
                                    </a>
                                    <a href="{{ route('merchants.show', ['ses_money_id' => $payment->response_status]) }}" class="btn btn-outline-warning">
                                        <span class="icon-holder"><i class="c-white-500 ti-zoom-in"></i></span>
                                    </a>
                                    <a href="{{ route('merchants.destroy', ['ses_money_id' => $payment->response_status]) }}" class="btn btn-outline-danger">
                                        <span class="icon-holder"><i class="c-white-500 ti-trash"></i></span>
                                    </a>
                                </td>
                            </tr>
@endforeach
                        {{ $payments->links() }}
                        @else
                        <tr>
                            <td scope="row" colspan="8" class="text-center text-capitalize"> no transactions found</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop