@extends('front.layout.front')
@section('content')
<section class="product-list-sec my-3">
    <div class="custom-container px-5">
        <div class="row">
            <div class="col-md-3">
                <div class="left-side-bar">
                    <nav class='nav-sidebar'>
                        <ul class="nav flex-column">
                            <li class='sub-menu'><a class="nav-link title" href='#settings'>Category                <div class='fa fa-caret-down right float-right'></div>                </a>
                                <ul class="flex-column"> @if(sizeof($category_filter)>0) @endif </ul>
                            </li>
                            <li class='sub-menu'><a class="nav-link title" href='#message'>Price                <div class='fa fa-caret-down right float-right'></div>                </a>
                                <div class="price-range-block">
                                    <div id="slider-range" class="price-filter-range" name="rangeInput"></div>
                                    <div class="d-flex align-items-center justify-content-between my-4">
                                        <input type="number" min=0 max="9900" oninput="validity.valid||(value='0');" id="min_price" class="price-range-field" /> <span class="d-flex align-items-center" style="color:#999;">to</span>
                                        <input type="number" min=0 max="10000" oninput="validity.valid||(value='10000');" id="max_price" class="price-range-field" /> </div>
                                    <button class="price-range-search" id="price-range-submit">Search</button>
                                    <div id="searchResults" class="search-results-block"></div>
                                </div>
                            </li>
                            <li class='sub-menu'><a class="nav-link title" href='#message'>Size                <div class='fa fa-caret-down right float-right'></div>                </a>
                                <ul class="flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link" href='#settings'>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="xs-check" name="xs-check">
                                                <label class="custom-control-label" for="xs-check">XS</label>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href='#settings'>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="s-check" name="s-check">
                                                <label class="custom-control-label" for="s-check">S</label>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href='#settings'>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="m-check" name="m-check">
                                                <label class="custom-control-label" for="m-check">M</label>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href='#settings'>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="l-check" name="l-check">
                                                <label class="custom-control-label" for="l-check">L</label>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href='#settings'>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="xl-check" name="xl-check">
                                                <label class="custom-control-label" for="xl-check">XL</label>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href='#settings'>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="xx-check" name="xx-check">
                                                <label class="custom-control-label" for="xx-check">XXL</label>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="col-md-9">
                <div class="rigt-sidebar product-list-div">
                    <div class="container-fluid mb-3 p-0">
                        <ul class="breadcrumb justify-content-start">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item">
                                <a href="#">
                                    <?=Request::segment(2)?>
                                </a>
                            </li>
                            <?=(Request::segment(2))?'<li class="breadcrumb-item"><a href="#">'.Request::segment(3).'</a></li>':''?>
                                <?=(Request::segment(3))?'<li class="breadcrumb-item"><a href="#">'.Request::segment(4).'</a></li>':''?>
                        </ul>
                    </div>
                    <div class="container-fluid p-0">
                        <div class="d-flex justify-content-between align-items-center w-100 mb-3">
                            <div class="title-categories">
                                <h5 class="m-0"><?=@$_GET['q']?></h5> </div>
                            <div class="short-product d-flex justify-content-end align-items-center">
                                <div class="sh-text px-3">
                                    <h5 class="m-0">Short</h5> </div>
                                <div class="drop-short">
                                    <form action="/action_page.php">
                                        <select name="price" class="custom-select">
                                            <option selected="">New</option>
                                            <option value="Low">Price: Low To High</option>
                                            <option value="High">Price: High To Low</option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row"> @foreach($product as $vs)
                        <div class="col-md-4 col-sm-4 col-6">
                            <div class="products-wrap">
                                <div class="products-img-wrap">
                                    <div class="slide-img">
                                        <a href="{{URL::to('product/'.$vs->slug)}}">
                                            <?PHP                         foreach($vs->product_image as $vs1)                        {                           if($vs1->is_default):                              $image= $vs1->image;                           endif;                        }                       ?>
                                                <figure class="swap-on-hover"> <img class="swap-on-hover__front-image" src="{{ URL::asset('public/admin/uploads/product/'.$image) }}"> <img class="swap-on-hover__back-image" src="{{ URL::asset('public/admin/uploads/product/'.$vs->product_image[1]->image) }}" /> </figure>
                                        </a>
                                    </div>
                                    <div class="favrite"><i class="wishlist fa fa-heart-o" aria-hidden="true"></i></div>
                                </div>
                                <div class="title-product">{{$vs->name}}</div>
                                <div class="price-product"><span class="price-new"><i class="fa fa-inr" aria-hidden="true"></i>{{$vs->sell_price}}</span><span class="price-old"><del><i class="fa fa-inr" aria-hidden="true"></i>{{$vs->starting_price}}</del></span> </div>
                            </div>
                        </div> @endforeach </div>
                    <!--<div class="d-flex justify-content-center align-items-center my-5">           <ul class="pagination">          <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>          <li class="page-item active"><a class="page-link" href="#">1</a></li>          <li class="page-item"><a class="page-link" href="#">2</a></li>          <li class="page-item"><a class="page-link" href="#">3</a></li>          <li class="page-item"><a class="page-link" href="#">Next</a></li>        </ul>         </div>--></div>
            </div>
        </div>
    </div>
</section>@section('scripts')
<script>
    $('.sub-menu ul').show();
    $(".sub-menu a").click(function() {
        $(this).parent(".sub-menu").children("ul").slideToggle("100");
        $(this).find(".right").toggleClass("fa-caret-up fa-caret-down");
    });
</script>
<script>
    $('.wishlist').click(function() {
        $(this).toggleClass('fa-heart-o fa-heart');
    }); //window.history.pushState("Details", "Title", "yourNewPage");
</script>@stop
@endsection