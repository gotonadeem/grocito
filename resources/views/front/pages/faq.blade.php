@extends('front.layout.front')
@section('content')
  <section class="banner ab-us"><img src="{{URL::asset('public/images/grocerybanner.jpg')}}" class="img-fluid"><h2 class="text-center">FAQ</h2></section>

  <section class="Faq-content my-5">
    <div class="container">
      <div class="row pb-5">
        <div class="col-md-7 col-sm-7">
          <div class="return-cancel">
            <h5>Frequently Asked Questions</h5>
             @foreach($data as $vs)
			   <div class="set"> <a href="JavaScript:Void(0);">{{$vs->title}}<i class="fa fa-plus"></i> </a>
				  <div class="content">
				  {!! $vs->description!!}
				  </div>
				</div>
			@endforeach
			
           </div>
        </div>
        <div class="col-md-5 col-sm-5">
          <div class="faq-img text-center">
            <img src="{{URL::asset('public/images/faq-tips.png')}}" class="img-fluid">
          </div>
        </div>
      </div>
    </div>
  </section>
@endSection

