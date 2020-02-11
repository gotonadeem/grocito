@extends('front.layout.front')
@section('content')
    <div class="container">
    <h2 class="shoping-cart-text mt-3 text-center mb-4">Review & Rating</h2>
        <div class="col-6 m-auto">
            <div class="rating-review">
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label>Select Rating</label>
                    </div>
                    <div class="col-sm-8">
                        <div class="rating rating-star"></div>
                        <span class="error" style="display: none" id="rating-error"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label>Comment</label>
                    </div>
                    <div class="col-sm-8">
                        <textarea rows="4" class="form-control" id="review_msg"></textarea>
                        <span class="error" style="display: none" id="review-msg-error"></span>
                    </div>

                </div>
                <div class="form-group row">
                    <div class="col-sm-4">
                    </div>
                    <div class="col-sm-8">
                        <input type="submit" class="btn custom-btn" onclick="SubmitRatingReview()">
                    </div>
                </div>
            </div>
        </div>

    </div>


@section('scripts')
    <script language="javascript" src="{{ URL::asset('public/js/jquery.star.rating.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('.rating').addRating();
        })
        function SubmitRatingReview() {
            var rating =$('#rating').val();
            var review_msg =$('#review_msg').val();
            var productId =  '<?php echo $productId; ?>';
            var orderId =  '<?php echo $orderId; ?>';
            if(rating ==0){
                $('#rating-error').html('Please select at least one star!').show();
                return false;
            }
            $('#rating-error').hide();
            if(review_msg ==''){
                $('#review-msg-error').html('This field is required!').show();
                return false;
            }
            $('#review-msg-error').hide();
            $('.loader-div').show();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: BASE_URL+'/user/update-user-rating-review',
                type: 'POST',
                data: {rating: rating ,review_msg:review_msg,productId:productId,orderId:orderId},
                success: function (data) {
                    window.location.href = '/order-view/'+orderId;
                    $('.loader-div').hide();
                },
                error: function () {
                    console.log('There is some error in user deleting. Please try again.');
                }
            });
        }
    </script>

@stop
@endSection