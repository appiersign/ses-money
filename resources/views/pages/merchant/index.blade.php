@extends('layouts.base')
@section('content')
    <div class="row gap-20 masonry pos-r">
        <div class="masonry-sizer col-md-6"></div>
        <div class="masonry-item col-12">
            <div class="bgc-white bd bdrs-3 p-20 mB-20">
                <h4 class="c-grey-900 mB-20">Merchants</h4>
                <a href="{{ route('merchants.create') }}" class="btn btn-info">create</a>
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Merchant ID</th>
                        <th scope="col">SES-Money ID</th>
                        <th scope="col">API User</th>
                        <th scope="col">Email</th>
                        <th scope="col">Telephone</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($merchants)
                        @foreach($merchants as $merchant)
                            <tr>
                                <th scope="row">{{ $merchant->name }}</th>
                                <td>{{ $merchant->merchant_id }}</td>
                                <td>{{ $merchant->ses_money_id }}</td>
                                <td>{{ $merchant->api_user }}</td>
                                <td>{{ $merchant->email }}</td>
                                <td>{{ $merchant->phone_number }}</td>
                                <td>
                                    @if ($merchant->is_active == 1)
                                        <span
                                                class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-green-50 c-green-500">active</span>
                                        @else
                                        <span
                                                class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-red-50 c-red-500">deactivated</span>
                                @endif
                                </td>
                                <td>
                                    <a href="{{ route('merchants.edit', ['ses_money_id' => $merchant->ses_money_id]) }}" class="btn btn-outline-primary">
                                        <span class="icon-holder"><i class="c-white-500 ti-pencil"></i></span>
                                    </a>
                                    <a href="{{ route('merchants.show', ['ses_money_id' => $merchant->ses_money_id]) }}" class="btn btn-outline-warning">
                                        <span class="icon-holder"><i class="c-white-500 ti-zoom-in"></i></span>
                                    </a>
                                    <a href="{{ route('merchants.destroy', ['ses_money_id' => $merchant->ses_money_id]) }}" class="btn btn-outline-danger">
                                        <span class="icon-holder"><i class="c-white-500 ti-trash"></i></span>
                                    </a>
                                </td>
                            </tr>
@endforeach
                        @else
                        <tr>
                            <td scope="row" colspan="7"> no merchants created</td>
                        </tr>
                    @endif
                    </tbody>
                    {{ $merchants->links() }}
                </table>
            </div>
        </div>
    </div>
@stop