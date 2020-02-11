
          
// ========main-slider=================
 $(document).ready(function() {
              $('#main-slider').owlCarousel({
                loop: true,
                margin: 0,
				autoplay: true,
                autoplayTimeout: 2000,
                responsiveClass: true,
                responsive: {
                  0: {
                    items: 1,
                    nav: true
                  },
                  600: {
                    items: 1,
                    nav: false
                  },
                  1000: {
                    items: 1,
                    nav: true,
                    loop: true,
					autoplayHoverPause:true
                  }
                }
              })
            })
			

			// ========product-slider=================
 $(document).ready(function() {
              $('.owl-carousel').owlCarousel({
                loop: true,
                margin: 12,
				autoplay: false,
                autoplayTimeout: 2000,
                responsiveClass: true,
                responsive: {
                  0: {
                    items: 2,
                    nav: true
                  },
                  600: {
                    items: 3,
                    nav: false
                  },
                  1000: {
                    items: 5,
                    nav: true,
                    loop: false,
                  }
                }
              })
            })
			
			
// mobile navbar	
$(function () {
 $('.toggle-menu').click(function(){
	$('.exo-menu').toggleClass('display');
	
 });
 
 
 
});


 $('.moreless-button').click(function() {
  $('.viewAll').slideToggle();
  if ($('.moreless-button').text() == "View All") {
    $(this).text("View less")
  } else {
    $(this).text("View All")
  }
});



// Sticky navbar
// =========================
            $(document).ready(function () {
                // Custom function which toggles between sticky class (is-sticky)
                var stickyToggle = function (sticky, stickyWrapper, scrollElement) {
                    var stickyHeight = sticky.outerHeight();
                    var stickyTop = stickyWrapper.offset().top;
                    if (scrollElement.scrollTop() >= stickyTop) {
                        stickyWrapper.height(stickyHeight);
                        sticky.addClass("is-sticky");
                    }
                    else {
                        sticky.removeClass("is-sticky");
                        stickyWrapper.height('auto');
                    }
                };

                // Find all data-toggle="sticky-onscroll" elements
                $('[data-toggle="sticky-onscroll"]').each(function () {
                    var sticky = $(this);
                    var stickyWrapper = $('<div>').addClass('sticky-wrapper'); // insert hidden element to maintain actual top offset on page
                    sticky.before(stickyWrapper);
                    sticky.addClass('sticky');

                    // Scroll & resize events
                    $(window).on('scroll.sticky-onscroll resize.sticky-onscroll', function () {
                        stickyToggle(sticky, stickyWrapper, $(this));
                    });

                    // On page load
                    stickyToggle(sticky, stickyWrapper, $(window));
                });
            });

  //=====================
  
//=============clock-time===============

$('.wishlist').click(function(){
  $(this).toggleClass('fa-heart-o fa-heart');
});


//===========quantity===============

$(document).ready(function(){

    var quantitiy=0;
   $('.quantity-right-plus').click(function(e){
	  
        var id= $(this).attr('id');
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        var quantity = parseInt($('.increment_'+id).val());
        
        // If is not undefined
            
            $('.increment_'+id).val(quantity + 1);

          
            // Increment
        
    });

     $('.quantity-left-minus').click(function(e){
        // Stop acting like a button
        var id= $(this).attr('id');
        e.preventDefault();
        // Get the field name
        var quantity = parseInt($('.increment_'+id).val());
        
        // If is not undefined
      
            // Increment
            if(quantity>0){
            $('.increment_'+id).val(quantity - 1);
            //$('#quantity').val(quantity - 1);
            }
    });
    
});

//========ssticky nav================
$(window).scroll(function(){
    if ($(this).scrollTop() > 150) {
       $('#sticky').addClass('fixi');
    } else {
       $('#sticky').removeClass('fixi');
    }
});


 
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



//Check pincode availabilty
/*$(function() {

    jQuery.validator.addMethod('onlyNumbar', function (value, element) {
        if(/^[0-9]+$/i.test(value))
        {
            return true;
        }
    });
    $("form[name='check_pincode']").validate({
        rules: {
            pincode: {
                required: true,
                onlyNumbar:true,
                minlength: 6,
                maxlength:6,
            },
        },
        // Specify validation error messages
        messages: {
            pincode: "Please enter a valid pincode.",
        },
        submitHandler: function(form) {
            e.preventDefault();
            alert();
            //form.submit();
        }
    });
});*/
$(function () {
    //Check onyl number
    $(document).on("input", ".onlyNumbar", function() {
        this.value = this.value.replace(/\D/g,'');
    });

    $(document).on('click','.checkPinAvailability',function () {
        var pincode = $('.pincode').val();
        if(pincode.length < 6 || pincode.length > 6){
            $("#error").html('Please enter valid pincode').show();
            return false;
        }
        if(pincode.length == 6) {
            $("#error").hide();
            $(".loader-div").show();
             $.ajax({
             headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             url: BASE_URL+'/check-pin-availability',
             type: 'POST',
             data: {pincode: pincode },
             success: function (data) {
                 response=JSON.parse(data);
                 if(response.status){
                     $("#error").hide();
                     var cityStateName = response.city_name+ ', '+response.state_name;
                     $('#add_city').text(cityStateName);
                     location.reload();
                     //$("#success").html(response.message).show();
                 }else{
                     $("#success").hide();
                     $("#error").html(response.message).show();
                 }
             },

             error: function () {
             console.log('There is some error in user deleting. Please try again.');
             }
             });
            //return false;
        }

    });
});


 