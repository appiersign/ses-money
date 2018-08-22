@extends('layouts.base')
@section('content')
    <div class="row gap-20 masonry pos-r">
        <div class="masonry-sizer col-md-6"></div>
        <div class="masonry-item col-12">
            <div class="bgc-white bd bdrs-3 p-20 mB-20">
                <h4 class="c-grey-900 mB-20">Transfers</h4>
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
                    @if (isset($transfers))
                        @foreach($transfers as $transfer)
                            <tr>
                                <th scope="row">{{ $transfer->merchant_id }}</th>
                                <td>{{ $transfer->transaction_id }}</td>
                                <td>{{ $transfer->account_number }}</td>
                                <td>{{ $transfer->provider }}</td>
                                <td>{{ $transfer->amount }}</td>
                                <td>{{ $transfer->response_status }}</td>
                                <td>{{ $transfer->created_at }}</td>
                                <td>
                                    <a href="{{ route('merchants.edit', ['ses_money_id' => $transfer->response_status]) }}" class="btn btn-outline-primary">
                                        <span class="icon-holder"><i class="c-white-500 ti-pencil"></i></span>
                                    </a>
                                    <a href="{{ route('merchants.show', ['ses_money_id' => $transfer->response_status]) }}" class="btn btn-outline-warning">
                                        <span class="icon-holder"><i class="c-white-500 ti-zoom-in"></i></span>
                                    </a>
                                    <a href="{{ route('merchants.destroy', ['ses_money_id' => $transfer->response_status]) }}" class="btn btn-outline-danger">
                                        <span class="icon-holder"><i class="c-white-500 ti-trash"></i></span>
                                    </a>
                                </td>
                            </tr>
@endforeach
                        {{ $transfers->links() }}
                        @else
                        <tr>
                            <td scope="row" colspan="7"> no transfers created</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop