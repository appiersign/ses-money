@extends('layouts.base')
@section('content')
    <div class="row gap-20 masonry pos-r">
        <div class="masonry-sizer col-md-6"></div>
        <div class="masonry-item col-md-6">
            <div class="bgc-white p-20 bd">
                <h6 class="c-grey-900">Create New Terminal</h6>
                <div class="mT-30">
                    <form action="{{ route('terminals.store') }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group row">
                            <label for="merchant-name" class="col-sm-2 col-form-label">Name:</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="merchant-name" placeholder="Terminal Name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="terminal-merchant" class="col-sm-2 col-form-label">Merchant:</label>
                            <div class="col-sm-10">
                                <select name="merchant" id="terminal-merchant" class="form-control">
                                    <option value="">choose merchant</option>
                                    @if (isset($merchants))
                                        @foreach($merchants as $merchant)
                                            <option value="{{ $merchant->id }}" {{ (old('merchant') == $merchant->id)? 'selected' : '' }}>{{ $merchant->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="terminal-type" class="col-sm-2 col-form-label">Type:</label>
                            <div class="col-sm-10">
                                <select name="type" id="terminal-type" class="form-control">
                                    <option value="">choose type</option>
                                    <option value="web" {{ (old('type') == 'web')? 'selected' : '' }}>Web</option>
                                    <option value="offline" {{ (old('type') == 'offline')? 'selected' : '' }}>Offline</option>
                                </select>
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