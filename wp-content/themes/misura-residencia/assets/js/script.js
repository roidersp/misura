social_hub = false;
$(document).ready(function() {
  
  console.log("test");
  
  social_item_h = $(".social-item").height();
  
  $(".social_in").css("height",social_item_h*2);
  
  console.log(social_item_h);
  
  $(document).on("click","#social_more",function(){
   if(social_hub){
      $(this).removeClass("ocultar");
      $(".social_in").css("height",social_item_h*2);
      $(".social_in").removeClass("mostrar");
      social_hub = false;
       $('html, body').animate({
            scrollTop: $("#social-hub").offset().top - 120 + 'px'
        }, 'fast');
   }else{
     $(".social_in").css("height", "auto");
     social_hub = true;
     $(".social_in").addClass("mostrar");
     $(this).addClass("ocultar");
   }
  });
  
  
});