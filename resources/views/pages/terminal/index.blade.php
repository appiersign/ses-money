@extends('layouts.base')
@section('content')
    <div class="row gap-20 masonry pos-r">
        <div class="masonry-sizer col-md-6"></div>
        <div class="masonry-item col-12">
            <div class="bgc-white bd bdrs-3 p-20 mB-20">
                <h4 class="c-grey-900 mB-20">Terminals</h4>
                <a href="{{ route('terminals.create') }}" class="btn btn-info">create</a>
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Merchant</th>
                        <th scope="col">SES-Money ID</th>
                        <th scope="col">Type</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($terminals)
                        @foreach($terminals as $terminal)
                            <tr>
                                <th scope="row">{{ $terminal->name }}</th>
                                <td>{{ $terminal->merchant->name }}</td>
                                <td>{{ $terminal->ses_money_id }}</td>
                                <td>{{ $terminal->type }}</td>
                                <td>
                                    @if ($terminal->is_active == 1)
                                        <span
                                                class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-green-50 c-green-500">active</span>
                                    @else
                                        <span
                                                class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-red-50 c-red-500">deactivated</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('terminals.edit', ['ses_money_id' => $terminal->ses_money_id]) }}"
                                       class="btn btn-outline-primary">
                                        <span class="icon-holder"><i class="c-white-500 ti-pencil"></i></span>
                                    </a>
                                    <a href="{{ route('terminals.show', ['ses_money_id' => $terminal->ses_money_id]) }}"
                                       class="btn btn-outline-warning">
                                        <span class="icon-holder"><i class="c-white-500 ti-zoom-in"></i></span>
                                    </a>
                                    <a href="{{ route('terminals.destroy', ['ses_money_id' => $terminal->ses_money_id]) }}"
                                       class="btn btn-outline-danger">
                                        <span class="icon-holder"><i class="c-white-500 ti-trash"></i></span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        {{ $terminals->links() }}
                    @else
                        <tr>
                            <td scope="row" colspan="7"> no merchants created</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop