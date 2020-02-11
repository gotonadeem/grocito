@extends('front.layout.front')
@section('content')
    <section class="banner ab-us"><img src="{{URL::asset('public/images/grocerybanner.jpg')}}" class="img-fluid"><h2 class="text-center">CANCELLATIONS AND RETURNS</h2></section>

    <section class="cancel-content my-5">

        <div class="container">

            <div class="pb-5">
                <div class="privacy-policy">
                    <h5>{{ $content->name }}</h5>
                    {!! $content->description!!}
                </div>
            </div>
        </div>

    </section>

@endsection

<script>

    $(document).ready(function() {
        $(".set > a").on("click", function() {
            if ($(this).hasClass("active")) {
                $(this).removeClass("active");
                $(this)
                        .siblings(".content")
                        .slideUp(200);
                $(".set > a i")
                        .removeClass("fa-minus")
                        .addClass("fa-plus");
            } else {
                $(".set > a i")
                        .removeClass("fa-minus")
                        .addClass("fa-plus");
                $(this)
                        .find("i")
                        .removeClass("fa-plus")
                        .addClass("fa-minus");
                $(".set > a").removeClass("active");
                $(this).addClass("active");
                $(".content").slideUp(200);
                $(this)
                        .siblings(".content")
                        .slideDown(200);
            }
        });
    });




</script>