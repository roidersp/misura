	  <footer>
    	<div id="logo_footer">
      	 <img src="<?= get_template_directory_uri(); ?>/assets/images/logo-misura.png" alt="" />
    	</div>
    	<div class="container">
      	<div class="row footer_menu_cont">
        	<div class="col s5 footer_menu">
          	<div class="footer-item"><a href="#"> Aviso de privacidad</a></div>
        	</div>
        	<div class="col s2">
          	<div class="footer_social">
            	<div class="social_item"><a href="https://www.facebook.com/MisuraMX/"><img src="<?= get_template_directory_uri(); ?>/assets/images/sm-footer-fb.png" alt="sm-footer-fb" /></a></div>
            	<div class="social_item"><a href="https://www.instagram.com/misuramex/"><img src="<?= get_template_directory_uri(); ?>/assets/images/sm-footer-inst.png" alt="sm-footer-inst" /></a></div>
          	</div>
        	</div>
        	<div class="col s5"></div>
      	</div>
    	</div>
  	</footer>
	</body>
	
	<?php wp_footer(); ?>
		<script>
    	$(document).ready(function(){
  $('.slider-home').slick({
      autoplay: true,
      dots: true
    });
  });
		
  	</script>
</html>