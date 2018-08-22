@extends('layouts.base')
@section('content')
    <div class="row gap-20 masonry pos-r">
        <div class="masonry-sizer col-md-6"></div>
        <div class="masonry-item col-12">
            <div class="bgc-white bd bdrs-3 p-20 mB-20">
                <h4 class="c-grey-900 mB-20">Search Transfers</h4>
                <form action="" class="form-r" method="post">
                    {{ csrf_field() }}
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="inputEmail4">from:</label>
                            <input type="date" class="form-control" name="from" id="inputEmail4" placeholder="Email">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputPassword4">to:</label>
                            <input type="date" class="form-control" name="to" id="inputPassword4" placeholder="Password">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputPassword4"><br></label>
                            <input type="submit" class="btn btn-primary form-control" id="inputPassword4" placeholder="Password">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop