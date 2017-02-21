	  <footer>
    	<div id="logo_footer">
      	 <img src="<?= get_template_directory_uri(); ?>/assets/images/logo-misura.png" alt="" />
    	</div>
    	<div class="row">
      	<div class="col s5">
        	
      	</div>
      	<div class="col s2">
        	<div class="footer_social">
          	<div class="social_item"></div>
          	<div class="social_item"></div>
        	</div>
      	</div>
      	<div class="col s5"></div>
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