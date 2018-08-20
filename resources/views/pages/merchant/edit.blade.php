@extends('layouts.base')
@section('content')
    <div class="row gap-20 masonry pos-r">
        <div class="masonry-sizer col-md-6"></div>
        <div class="masonry-item col-md-6">
            <div class="bgc-white p-20 bd">
                <h6 class="c-grey-900">Create New Merchant</h6>
                <div class="mT-30">
                    <form action="{{ route('merchants.update', ['ses_money_id' => $merchant->ses_money_id]) }}" method="post">
                        <input type="hidden" name="_method" value="put">
                        {{ csrf_field() }}
                        <div class="form-group row">
                            <label for="merchant-name" class="col-sm-2 col-form-label">Name:</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" value="{{ old('name') ?? $merchant->name }}" class="form-control" id="merchant-name" placeholder="TekPulse Consult" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="merchant-email" class="col-sm-2 col-form-label">Email:</label>
                            <div class="col-sm-10">
                                <input type="email" name="email" value="{{ old('email') ?? $merchant->email }}" class="form-control" id="merchant-email" placeholder="merchant@email.com" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="merchant-telephone" class="col-sm-2 col-form-label">Telephone:</label>
                            <div class="col-sm-10">
                                <input type="text" name="telephone" value="{{ old('telephone') ?? $merchant->phone_number }}" class="form-control" id="merchant-telephone" placeholder="02XXXXXXXX">
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