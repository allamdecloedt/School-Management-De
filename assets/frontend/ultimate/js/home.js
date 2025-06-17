
$(document).ready(function () {
  $(".owl-carousel").owlCarousel({
    center: true,
    loop: true,       
    margin: 50,       
    autoplay: true,   
    nav: true,
    autoWidth: true,    
    items: 3,              
    responsive: {
      0: {
        items: 1,          
      },
      600: {
        items: 2,         
      },
      1000: {
        items: 3, 
        margin: 50
      }
    }
  });
});


