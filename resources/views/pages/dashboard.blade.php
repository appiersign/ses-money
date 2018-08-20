@extends('layouts.base')
@section('content')
    <div class="row gap-20 masonry pos-r">
        <div class="masonry-sizer col-md-6"></div>
        <div class="masonry-item w-100">
            <div class="row gap-20">
                <div class="col-md-3">
                    <div class="layers bd bgc-white p-20">
                        <div class="layer w-100 mB-10"><h6 class="lh-1">Total Transactions</h6></div>
                        <div class="layer w-100">
                            <div class="peers ai-sb fxw-nw">
                                <div class="peer peer-greed"><h5 id="">{{ $payments->count() + $transfers->count() }}</h5></div>
                                <div class="peer"><span
                                            class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-green-50 c-green-500">GHS {{ $total }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="layers bd bgc-white p-20">
                        <div class="layer w-100 mB-10"><h6 class="lh-1">Debits</h6></div>
                        <div class="layer w-100">
                            <div class="peers ai-sb fxw-nw">
                                <div class="peer peer-greed"><h4>{{ $transfers->count() }}</h4></div>
                                <div class="peer"><span
                                            class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-red-50 c-red-500">GHS {{ $transfer_sum }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="layers bd bgc-white p-20">
                        <div class="layer w-100 mB-10"><h6 class="lh-1">Credits</h6></div>
                        <div class="layer w-100">
                            <div class="peers ai-sb fxw-nw">
                                <div class="peer peer-greed"><h4>{{ $payments->count() }}</h4></div>
                                <div class="peer"><span
                                            class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-purple-50 c-purple-500">GHS {{ $payment_sum }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="layers bd bgc-white p-20">
                        <div class="layer w-100 mB-10"><h6 class="lh-1">Balance</h6></div>
                        <div class="layer w-100">
                            <div class="peers ai-sb fxw-nw">
                                <div class="peer peer-greed"><h4>+</h4></div>
                                <div class="peer"><span
                                            class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-blue-50 c-blue-500">GHS 00.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="masonry-item col-12">
            <div class="bgc-white bd bdrs-3 p-20 mB-20">
                <h4 class="c-grey-900 mB-20">Transactions</h4>
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th scope="col">Merchant ID</th>
                        <th scope="col">Transaction ID</th>
                        <th scope="col">Account Number</th>
                        <th scope="col">Provider</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($payments)
                        @foreach ($payments as $payment)
                            <tr>
                                <th scope="row">{{ $payment->merchant_id }}</th>
                                <td>{{ $payment->transaction_id }}</td>
                                <td>{{ $payment->account_number }}</td>
                                <td>{{ $payment->provider }}</td>
                                <td>{{ $payment->amount }}</td>
                                <td>
                                    @if ($payment->response_status == 'approved')
                                        <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-green-50 c-green-500">approved</span>
                                        @elseif($payment->response_status == 'pending')
                                        <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-red-50 c-blue-500">pending</span>
                                        @else
                                        <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-red-50 c-red-500">failed</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif

                    @if ($transfers)
                        @foreach ($transfers as $transfer)
                            <tr>
                                <th scope="row">{{ $transfer->merchant_id }}</th>
                                <td>{{ $transfer->transaction_id }}</td>
                                <td>{{ $transfer->account_number }}</td>
                                <td>{{ $transfer->provider }}</td>
                                <td>{{ $transfer->amount }}</td>
                                <td>
                                    @if ($transfer->response_status == 'approved')
                                        <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-green-50 c-green-500">approved</span>
                                    @elseif($transfer->response_status == 'pending')
                                        <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-red-50 c-blue-500">pending</span>
                                    @else
                                        <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-red-50 c-red-500">failed</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop