@extends('layouts.base')
@section('content')
    <div class="row gap-20 masonry pos-r">
        <div class="masonry-sizer col-md-6"></div>
        <div class="masonry-item col-md-6">
            <div class="bgc-white p-20 bd">
                <h6 class="c-grey-900">Payment Details</h6>
                <div class="mT-30">
                    <form action="{{ url('merchants/payments/process') }}" method="post">
                        <div class="form-group row">
                            <label for="payment-provider" class="col-sm-3 col-form-label">Provider:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $payment->provider }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="merchant-name" class="col-sm-3 col-form-label">Account Number:</label>
                            <div class="col-sm-9">
                                <input type="text" name="account_number" value="{{ $payment->account_number }}" class="form-control" id="merchant-name" placeholder="TekPulse Consult" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="merchant-name" class="col-sm-3 col-form-label">Amount:</label>
                            <div class="col-sm-9">
                                <input type="text" name="amount" value="{{ $payment->amount }}" class="form-control" id="merchant-name" placeholder="TekPulse Consult" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="merchant-name" class="col-sm-3 col-form-label">Transaction ID:</label>
                            <div class="col-sm-9">
                                <input type="text" name="transaction_id" value="{{ $payment->transaction_id }}" class="form-control" id="merchant-name" placeholder="TekPulse Consult" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="merchant-name" class="col-sm-3 col-form-label">Merchant ID:</label>
                            <div class="col-sm-9">
                                <input type="text" name="merchant_id" value="{{ $payment->merchant_id }}" class="form-control" id="merchant-name" placeholder="TekPulse Consult" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="merchant-name" class="col-sm-3 col-form-label">Terminal ID:</label>
                            <div class="col-sm-9">
                                <input type="text" name="terminal_id" value="{{ $payment->terminal_id }}" class="form-control" id="merchant-name" placeholder="TekPulse Consult" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="merchant-name" class="col-sm-3 col-form-label">Description:</label>
                            <div class="col-sm-9">
                                <input type="text" name="description" value="{{ $payment->description }}" class="form-control" id="merchant-name" placeholder="TekPulse Consult" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="merchant-name" class="col-sm-3 col-form-label">Response URL:</label>
                            <div class="col-sm-9">
                                <input type="text" name="response_url" value="{{ $payment->response_url }}" class="form-control" id="merchant-name" placeholder="TekPulse Consult" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label class="alert alert-info">{{ $payment->response_message }}</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop