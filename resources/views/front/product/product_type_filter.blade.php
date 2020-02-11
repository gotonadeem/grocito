
<div class="filter-sidebar">
    <input type="hidden" name="product_type" id="product_type" value="{{$type}}">
    <div class="price-sec mb-3">
        <h4>Price</h4>
        <div class="price-range-block">
            <div id="slider-range" class="price-filter-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content" name="rangeInput">
                <div class="ui-slider-range ui-corner-all ui-widget-header" style="left: 0%; width: 100%;"></div><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 0%;"></span><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 100%;"></span></div>
            <div class="d-flex align-items-center justify-content-between my-4">
                <input type="number" min="0" max="9900" oninput="validity.valid||(value='0');" readonly="" id="min_price" class="price-range-field">
                <span class="d-flex align-items-center" style="color:#999;">to</span>
                <input type="number" min="0" max="10000" oninput="validity.valid||(value='10000');" readonly="" id="max_price" class="price-range-field">
            </div>

            <div id="searchResults" class="search-results-block"></div>
        </div>
    </div>
    <div class="brand-sec mb-3">
        <h4>Brand</h4>
        <ul class="nav flex-column">

            @foreach($brand_filter as $ks=>$vs)
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <div class="custom-control custom-checkbox">
                            <input onclick="filter_now()" type="checkbox" class="custom-control-input brand_value" value="{{$vs->id}}" id="FruitsDrinks{{$ks}}" name="brand_value">
                            <label class="custom-control-label" for="FruitsDrinks{{$ks}}">{{$vs->name}}</label>
                        </div>
                    </a>
                </li>
            @endforeach


        </ul>
    </div>
    <div class="discount-sec mb-3">
        <h4>Discount</h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" onclick="filter_now()" class="custom-control-input offer_value" value="0-100" id="discont1" name="discont">
                        <label class="custom-control-label" for="discont1">upto 100₹</label>
                    </div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" onclick="filter_now()" class="custom-control-input offer_value" id="discont2" name="discont" value="101-250">
                        <label class="custom-control-label" for="discont2">101₹ - 250₹</label>
                    </div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" onclick="filter_now()" class="custom-control-input offer_value" id="discont3" name="discont" value="251-500">
                        <label class="custom-control-label" for="discont3">251₹ - 500₹</label>
                    </div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" onclick="filter_now()" class="custom-control-input offer_value" id="discont4" value="501-1000" name="discont">
                        <label class="custom-control-label" for="discont4">501₹ - 1000₹</label>
                    </div>
                </a>
            </li>
        </ul>
    </div>
    <!--
<div class="brand-sec mb-3">
<h4>Pack Size</h4>
<input type="text" class="form-control" name="search" placeholder="Search by Pack Size">
<ul class="nav flex-column">
    <li class="nav-item"> <a class="nav-link" href="#">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="Packsize1" name="Packsize">
                <label class="custom-control-label" for="Packsize1">2X250ML Multipack</label>
            </div>
        </a> </li>
    <li class="nav-item"> <a class="nav-link" href="#">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="Packsize2" name="Packsize">
                <label class="custom-control-label" for="Packsize2">2X250ML Multipack</label>
            </div>
        </a> </li>
    <li class="nav-item"> <a class="nav-link" href="#">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="Packsize3" name="Packsize">
                <label class="custom-control-label" for="Packsize3">12X250ML Multipack</label>
            </div>
        </a> </li>
    <li class="nav-item"> <a class="nav-link" href="#">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="Packsize4" name="Packsize">
                <label class="custom-control-label" for="Packsize4">2X250ML Multipack</label>
            </div>
        </a> </li>
    <li class="nav-item"> <a class="nav-link" href="#">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="Packsize5" name="Packsize">
                <label class="custom-control-label" for="Packsize5">450ML Multipack</label>
            </div>
        </a> </li>
</ul>
</div>-->

</div>