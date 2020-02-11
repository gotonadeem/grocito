@extends('seller.layouts.seller')
@section('content')
    <div class="right_side_bar">
        <div class="middle_content">
            <ul class="button">
                <li><a href="{{ URL::to('inventory') }}">Inventory</a></li>
                <li><a href="{{ URL::to('order') }}">Orders</a></li>
                <li><a href="{{ URL::to('payment') }}">Payments</a></li>
                <li><a href="{{ URL::to('catalog') }}">Catalogs Upload</a></li>
                <li><a href="{{ URL::to('notice') }}">Notice Board</a></li>
            </ul>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection