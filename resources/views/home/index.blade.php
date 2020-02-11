@extends('layouts.app')
@section('content')
<section class="min_section">
    <div class="page-section page-sec-78910">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="column-content "><b style="font-size: 36px; text-shadow: 0 1px 3px rgba(0,0,0,.75); line-height: 48px; display: block; margin-bottom: 8px; color: #ffffff;">Find your dream house!</b><b style="font-size: 20px; text-shadow: 0 2px 3px rgba(0,0,0,.28); font-weight: 500; display: block; line-height: 24px; color: #ffffff;">We are offering the best Real Estate Deals</b> </div>
                    <div class="icons-lists">
                        <ul>
                            <li><i class="icon-location2" style="color: #ff6500!important; background-color: #ffffff !important;"></i> We sell a property every 45 minutes</li>
                            <li><i class="icon-checkmark" style="color: #ff6500!important; background-color: #ffffff !important;"></i> We abide by the strictest codes of practice</li>
                            <li><i class="icon-file-text" style="color: #ff6500!important; background-color: #ffffff !important;"></i> 11,300 buyers registered each month</li>
                        </ul>
                    </div>
                    <div class="property-content main-search fancy">
                        <ul id="nav-tabs" class="nav nav-tabs" role="tablist">
                            <li class="active">Search Best Home</li>
                        </ul>
                        <div id="Property-content" class="tab-content">
                            <form id="top-search-form">
                                <div class="field-holder search-popup-holder"> <a href="#" class="search-popup-btn" data-toggle="modal" data-target="#mysearchModal">What's this</a> </div>
                                <div role="tabpanel" class="tab-pane" id="home">
                                    <div class="search-default-fields">
                                        <div class="field-holder search-input">
                                            <input placeholder="What are you looking for?" class="input-field" name="search_title" type="text">
                                        </div>
                                        <div class="field-holder select-dropdown property-type checkbox">
                                            <input checked="checked" id="search_form_property_type1" name="property_type" value="for-sale" type="radio" hidden="">
                                            <label for="search_form_property_type1">For Sale</label>
                                        </div>
                                        <div class="field-holder search-input">
                                            <input placeholder="All Locations" class="location-field location-field-text " id="locations-field" name="location" type="text">
                                        </div>
                                        <div id="property_type_cate_fields" class="property-category-fields field-holder select-dropdown has-icon">
                                            <select class="chosen-select-no-single" id="wp_rem_property_category" name="property_category">
                                                <option selected="selected" value="">Categories</option>
                                                <option value="bungalow">Bungalow</option>
                                                <option value="commercial">Commercial</option>
                                                <option value="flats">Flats</option>
                                                <option value="houses">Houses</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        <div class="field-holder search-btn">
                                            <div class="search-btn-loader-9058 input-button-loader processing">
                                                <input class="bgcolor" value="Search" type="submit">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-section page-sec-78911 parallex-bg">
        <div class="container ">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                    <div class="wp-rem-property-content" id="wp-rem-property-content-49242810">
                        <div class="real-estate-property show-more-property v1">
                            <div class="element-title align-left">
                                <h2>Featured Properties</h2>
                                <p><span>Featured For Sale </span></p>
                                <a href="#" class="show-more-property">Show More Property</a> </div>
                            <div class="row">
                                <div class="portfolio grid-fading animated col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <div class="property-grid v1 ">
                                        <div class="img-holder"><a href=""> <img class="img-grid" src="{{ URL::asset('public/front/images/property-image01.jpg') }}" alt=""> </a></div>
                                        <div class="text-holder"> <span class="property-price"> Price On Request </span>
                                            <div class="post-title">
                                                <h4><a href="#">Apna Bungalow</a></h4>
                                            </div>
                                            <ul class="post-category-list">
                                                <li> <i class="icon-bed2"></i> 3 Bedrooms </li>
                                                <li> <i class="icon-man-woman"></i> 3 Bathrooms </li>
                                                <li> <i class="icon-directions_car"></i> 1 Garage </li>
                                                <li> <i class="icon-transform"></i> 1360 SqFt </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="portfolio grid-fading animated col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <div class="property-grid v1 ">
                                        <div class="img-holder"><a href=""> <img class="img-grid" src="{{ URL::asset('public/front/images/property-image02.jpg') }}" alt=""> </a></div>
                                        <div class="text-holder"> <span class="property-price"> Price On Request </span>
                                            <div class="post-title">
                                                <h4><a href="#">Apna Bungalow</a></h4>
                                            </div>
                                            <ul class="post-category-list">
                                                <li> <i class="icon-bed2"></i> 3 Bedrooms </li>
                                                <li> <i class="icon-man-woman"></i> 3 Bathrooms </li>
                                                <li> <i class="icon-directions_car"></i> 1 Garage </li>
                                                <li> <i class="icon-transform"></i> 1360 SqFt </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="portfolio grid-fading animated col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <div class="property-grid v1 ">
                                        <div class="img-holder"><a href=""> <img class="img-grid" src="{{ URL::asset('public/front/images/property-image03.jpg') }}" alt=""> </a></div>
                                        <div class="text-holder"> <span class="property-price"> Price On Request </span>
                                            <div class="post-title">
                                                <h4><a href="#">Apna Bungalow</a></h4>
                                            </div>
                                            <ul class="post-category-list">
                                                <li> <i class="icon-bed2"></i> 3 Bedrooms </li>
                                                <li> <i class="icon-man-woman"></i> 3 Bathrooms </li>
                                                <li> <i class="icon-directions_car"></i> 1 Garage </li>
                                                <li> <i class="icon-transform"></i> 1360 SqFt </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="portfolio grid-fading animated col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <div class="property-grid v1 ">
                                        <div class="img-holder"><a href=""> <img class="img-grid" src="{{ URL::asset('public/front/images/property-image04.jpg') }}" alt=""> </a></div>
                                        <div class="text-holder"> <span class="property-price"> Price On Request </span>
                                            <div class="post-title">
                                                <h4><a href="#">Apna Bungalow</a></h4>
                                            </div>
                                            <ul class="post-category-list">
                                                <li> <i class="icon-bed2"></i> 3 Bedrooms </li>
                                                <li> <i class="icon-man-woman"></i> 3 Bathrooms </li>
                                                <li> <i class="icon-directions_car"></i> 1 Garage </li>
                                                <li> <i class="icon-transform"></i> 1360 SqFt </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="portfolio grid-fading animated col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <div class="property-grid v1 ">
                                        <div class="img-holder"><a href=""> <img class="img-grid" src="{{ URL::asset('public/front/images/property-image05.jpg') }}" alt=""> </a></div>
                                        <div class="text-holder"> <span class="property-price"> $2760000<span class="prop-price-type"> <span class="price-type">Fixed price</span></span> </span>
                                            <div class="post-title">
                                                <h4><a href="#">Apna Bungalow</a></h4>
                                            </div>
                                            <ul class="post-category-list">
                                                <li> <i class="icon-bed2"></i> 3 Bedrooms </li>
                                                <li> <i class="icon-man-woman"></i> 3 Bathrooms </li>
                                                <li> <i class="icon-directions_car"></i> 1 Garage </li>
                                                <li> <i class="icon-transform"></i> 1360 SqFt </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="portfolio grid-fading animated col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <div class="property-grid v1 ">
                                        <div class="img-holder"> <a href="#"> <img class="img-grid" src="{{ URL::asset('public/front/images/property-image06.jpg') }}" alt=""> </a> </div>
                                        <div class="text-holder"> <span class="property-price"> $2760000<span class="prop-price-type"> <span class="price-type">Fixed price</span></span> </span>
                                            <div class="post-title">
                                                <h4><a href="#">Superior Quality House To Sale</a></h4>
                                            </div>
                                            <ul class="post-category-list">
                                                <li> <i class="icon-bed2"></i> 3 Bedrooms </li>
                                                <li> <i class="icon-man-woman"></i> 2 Bathrooms </li>
                                                <li> <i class="icon-directions_car"></i> 1 Garage </li>
                                                <li> <i class="icon-transform"></i> 925 SqFt </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-section page-sec-78912 parallex-bg">
        <div class="container ">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 ">
                    <div class="property-search">
                        <div class="element-title align-left ">
                            <h2>About Pin Board</h2>
                        </div>
                        <ul class="property-list">
                            <li>Property Management in London</li>
                            <li>Attractive Landscaped Gardens</li>
                            <li>City Centre Within Easy Reach</li>
                            <li>About Pinboard Homevillas</li>
                            <li>Sale Jobs in London</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 ">
                    <div class="property-search">
                        <div class="element-title align-left ">
                            <h2>Our Estate Agencies</h2>
                        </div>
                        <ul class="property-list">
                            <li>Central London estate members</li>
                            <li>East London estate members</li>
                            <li>North London estate members</li>
                            <li>South London estate members</li>
                            <li>Surrey estate members</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 ">
                    <div class="property-search">
                        <div class="element-title align-left ">
                            <h2>Popular Searches</h2>
                        </div>
                        <ul class="property-list">
                            <li>London Property for Sale</li>
                            <li>New Homes in London</li>
                            <li>London Shortlist Homes</li>
                            <li>London Property Listings</li>
                            <li>New Commercial Property </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 ">
                    <div class="property-search">
                        <div class="element-title align-left ">
                            <h2>Property List</h2>
                        </div>
                        <ul class="property-list">
                            <li>New Homes in London</li>
                            <li>Home Valuation Service</li>
                            <li>Area Guides for Client</li>
                            <li>Daily Rental Reports</li>
                            <li>Search in Homevillas</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-section page-sec-78913 parallex-bg">
        <div class="container ">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="section-title">
                        <h2 style="color:#ff6500!important;">NEWS AND STORIES</h2>
                    </div>
                </div>
                <div class="section-fullwidth col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="blog blog-grid simple">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <div class="blog-post">
                                    <div class="img-holder"> <a href="#"><img src="{{ URL::asset('public/front/images/property-image20.jpg') }}" class="attachment-wp_rem_cs_media_3 size-wp_rem_cs_media_3 wp-post-image" alt=""></a> </div>
                                    <div class="text-holder">
                                        <ul class="post-options">
                                            <li><a href="#">February 9, 2017</a></li>
                                            <li><a href="#" class="comments-link"> 0 comment</a> </li>
                                        </ul>
                                        <div class="post-title">
                                            <h4><a href="#" title="take away you can get from">take away you can get ...</a></h4>
                                        </div>
                                        <p> Integer mattis magna volutpat euismod habitant mi faucibus elementum proin mi, lobortis iaculis dolor torquent...</p>
                                        <div class="button-holder"> <a href="#" class="btn-readmore">Read Article<i class=" icon-arrow-right3 "></i></a> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <div class="blog-post">
                                    <div class="img-holder"> <a href="#"><img src="{{ URL::asset('public/front/images/property-image10.jpg') }}" class="attachment-wp_rem_cs_media_3 size-wp_rem_cs_media_3 wp-post-image" alt=""></a> </div>
                                    <div class="text-holder">
                                        <ul class="post-options">
                                            <li><a href="#">February 9, 2017</a></li>
                                            <li><a href="#" class="comments-link"> 0 comment</a> </li>
                                        </ul>
                                        <div class="post-title">
                                            <h4><a href="#" title="One thing that is really make this blog standout">One thing that is really ...</a></h4>
                                        </div>
                                        <p> Integer mattis magna volutpat euismod habitant mi faucibus elementum proin mi, lobortis iaculis dolor torquent...</p>
                                        <div class="button-holder"> <a href="#" class="btn-readmore">Read Article<i class=" icon-arrow-right3 "></i></a> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <div class="blog-post">
                                    <div class="img-holder"> <a href="#"><img src="{{ URL::asset('public/front/images/property-image18.jpg') }}" class="attachment-wp_rem_cs_media_3 size-wp_rem_cs_media_3 wp-post-image" alt=""></a> </div>
                                    <div class="text-holder">
                                        <ul class="post-options">
                                            <li><a href="#">February 9, 2017</a></li>
                                            <li><a href="#" class="comments-link"> 0 comment</a> </li>
                                        </ul>
                                        <div class="post-title">
                                            <h4><a href="#" title="this week i thought it would be good to kick things">this week i thought it ...</a></h4>
                                        </div>
                                        <p> Integer mattis magna volutpat euismod habitant mi faucibus elementum proin mi, lobortis iaculis dolor torquent...</p>
                                        <div class="button-holder"> <a href="#" class="btn-readmore">Read Article<i class=" icon-arrow-right3 "></i></a> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-section page-sec-78914 parallex-bg">
        <div class="container ">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="icon-boxes fancy  top-center">
                        <div class="img-holder"><a href="#"> <img src="{{ URL::asset('public/front/images/home-service1.png') }}" alt="Describe Your Task"> </a></div>
                        <div class="text-holder">
                            <h5><a href="#">Describe Your Task</a></h5>
                            <p>HomeVillas have theme search, user can manually draw lines on map, all ads property listings in that area show up as a result.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="icon-boxes fancy  top-center">
                        <div class="img-holder"><a href="#"> <img src=" {{ URL::asset('public/front/images/home-service2.png') }}" alt="Choose a Tasker"> </a></div>
                        <div class="text-holder">
                            <h5><a href="#">Choose a Tasker</a></h5>
                            <p>User can schedule a viewing date and time for property with agent as per convenience. Agent can respond the schedule as per choice. </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="icon-boxes fancy  top-center">
                        <div class="img-holder"><a href="#"> <img src="{{ URL::asset('public/front/images/home-service3.png') }}" alt="Live Smarter"> </a></div>
                        <div class="text-holder">
                            <h5><a href="#">Live Smarter</a></h5>
                            <p>Home Villa built for the best SEO practices. All links and elements are SEO friendly, yet putting your site high in ranks.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-section page-sec-78915 parallex-bg">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="section-title" style="text-align:center!important;">
                    <h2>Testimonails</h2>
                    <span>We have the right properties needs.</span> </div>
            </div>
            <div class="testimonial-slider">
                <div class="swiper-container swiper-container-horizontal">
                    <div class="col-md-12" style="padding:0px !important;">
                        <div class="testimonial-carousel">
                            <div class="swiper-slide">
                                <div class="testimonial fancy">
                                    <div class="img-holder"> <img src="{{ URL::asset('public/front/images/testimonial-bg-1.jpg') }}" alt=""> </div>
                                    <div class="testimonial-description">
                                        <div class="img-holder"> <img src="{{ URL::asset('public/front/images/testimonial-thumbnail1.jpg') }}" alt=""> </div>
                                        <div class="text-holder">
                                            <p><span style="color: #ffffff;">Without doubt the best and most up to date method of selling a house. Everything has been user friendly and fits modern busy lifestyles. So impressed.</span></p>
                                            <div class="authore-detail"><span class="authore-name">Stuart Jarvis, </span>
                                                <address style="color: #99c522 !important;">
                                                    Streets Ahead, Croydon Central
                                                </address>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial fancy">
                                    <div class="img-holder"> <img src="{{ URL::asset('public/front/images/testimonial-bg-1.jpg') }}" alt=""> </div>
                                    <div class="testimonial-description">
                                        <div class="img-holder"> <img src=" {{ URL::asset('public/front/images/testimonial-thumbnail2.jpg') }}" alt=""> </div>
                                        <div class="text-holder">
                                            <p><span style="color: #ffffff;">Without doubt the best and most up to date method of selling a house. Everything has been user friendly and fits modern busy lifestyles. So impressed.</span></p>
                                            <div class="authore-detail"><span class="authore-name">Stuart Jarvis, </span>
                                                <address style="color: #99c522 !important;">
                                                    Streets Ahead, Croydon Central
                                                </address>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@stop
