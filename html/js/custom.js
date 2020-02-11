
          
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
                    loop: false,
                  }
                }
              })
            })
			
			
			
				// ========product-slider=================
 $(document).ready(function() {
              $('.owl-carousel').owlCarousel({
                loop: true,
                margin: 12,
				autoplay: true,
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
			
			
//==============show-more===========//			

 $('.moreless-button').click(function() {
  $('.viewAll').slideToggle();
  if ($('.moreless-button').text() == "View All") {
    $(this).text("View less")
  } else {
    $(this).text("View All")
  }
});


//===============ub-slider===================//

