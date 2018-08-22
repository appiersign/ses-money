@extends('layouts.base')
@section('content')
    <div class="row gap-20 masonry pos-r">
        <div class="masonry-sizer col-md-6"></div>
        <div class="masonry-item col-md-6">
            <div class="bgc-white p-20 bd">
                <h6 class="c-grey-900">Transfer Funds</h6>
                <div class="mT-30">
                    <form action="{{ route('transfers.store') }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group row">
                            <label for="payment-provider" class="col-sm-3 col-form-label">Provider:</label>
                            <div class="col-sm-9">
                                <select name="provider" id="payment-provider" class="form-control">
                                    <option value="">select provider</option>
                                    <option value="MTN">MTN</option>
                                    <option value="TGO">Tigo</option>
                                    <option value="ATL">Airtel</option>
                                    <option value="VDF">Vodafone</option>
                                    <option value="MAS">Mastercard</option>
                                    <option value="VIS">VIsa</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="merchant-name" class="col-sm-3 col-form-label">Account Number:</label>
                            <div class="col-sm-9">
                                <input type="text" name="account_number" value="{{ old('account_number') }}" class="form-control" id="merchant-name" placeholder="TekPulse Consult" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="merchant-name" class="col-sm-3 col-form-label">Amount:</label>
                            <div class="col-sm-9">
                                <input type="text" name="amount" value="{{ old('amount') }}" class="form-control" id="merchant-name" placeholder="TekPulse Consult" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="merchant-name" class="col-sm-3 col-form-label">Transaction ID:</label>
                            <div class="col-sm-9">
                                <input type="text" name="transaction_id" value="{{ old('transaction_id') }}" class="form-control" id="merchant-name" placeholder="TekPulse Consult" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="merchant-name" class="col-sm-3 col-form-label">Merchant ID:</label>
                            <div class="col-sm-9">
                                <input type="text" name="merchant_id" value="{{ old('merchant_id') }}" class="form-control" id="merchant-name" placeholder="TekPulse Consult" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="merchant-name" class="col-sm-3 col-form-label">Terminal ID:</label>
                            <div class="col-sm-9">
                                <input type="text" name="terminal_id" value="{{ old('terminal_id') }}" class="form-control" id="merchant-name" placeholder="TekPulse Consult" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="merchant-name" class="col-sm-3 col-form-label">Description:</label>
                            <div class="col-sm-9">
                                <input type="text" name="description" value="{{ old('description') }}" class="form-control" id="merchant-name" placeholder="TekPulse Consult" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="merchant-name" class="col-sm-3 col-form-label">Response URL:</label>
                            <div class="col-sm-9">
                                <input type="text" name="response_url" value="{{ old('response_url') }}" class="form-control" id="merchant-name" placeholder="TekPulse Consult" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10"></div>
                            <div class="col-sm-12 col-md-2">
                                <button type="submit" class="btn btn-primary">submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop