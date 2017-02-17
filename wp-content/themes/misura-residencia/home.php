<?php
/*
Template Name: Home
*/
?>
<?php get_header(); ?>
<main class="container">
<section id="home_slider" class="row">
    	<div class="slider-home col s12">
      	
      	<?php
        // check if the repeater field has rows of data
        if( have_rows('slider') ):
        
         	// loop through the rows of data
            while ( have_rows('slider') ) : the_row();
            
            ?>
             <div class="slider-item" >
               <img src="<?= get_sub_field( "imagen" )["url"] ?>" alt="" />
             </div>

        <?php
            endwhile;
        
        else :
        
            // no rows found
        
        endif;
        
        ?>

      </div>
  	</section>
  	<section id="media_content" class="row">
    	<div class="col s6">
      	<a href="<?= get_field( "url_1" ) ?>"><img src="<?= get_field( "imagen_1" )["url"] ?>" alt="" /></a>
    	</div>
    	<div class="col s6">
      	<a href="<?= get_field( "url_2" ) ?>"><img src="<?= get_field( "imagen_2" )["url"] ?>" alt="" /></a>
    	</div>
    	<div class="col s12">
      	<a href="<?= get_field( "url_3" ) ?>"><img src="<?= get_field( "imagen_3" )["url"] ?>" alt="" /></a>
    	</div>
    	<div class="col s7">
      	<a href="<?= get_field( "url_4" ) ?>"><img src="<?= get_field( "imagen_4" )["url"] ?>" alt="" /></a>
    	</div>
    	<div class="col s5 padding_l">
      	<a href="<?= get_field( "url_5" ) ?>"><img src="<?= get_field( "imagen_5" )["url"] ?>" alt="" /></a>
    	</div>
  	</section>
  	<section id="social-hub" class="row">
    	<div class="titulo_image">
      	<img src="<?= get_template_directory_uri(); ?>/assets/images/social-hub.png" alt="social-hub"/>
    	</div>
    	<div id="social-cont" class="col s12">
    	<?php
        // check if the repeater field has rows of data
        if( have_rows('social_hub') ):
        
         	// loop through the rows of data
            while ( have_rows('social_hub') ) : the_row();
            
            ?>
             <div class="social-item col s3 social_<?= get_sub_field( "red_social" ) ?>" >
               <a href="<?= get_sub_field( "url" ) ?>"><img src="<?= get_sub_field( "imagen" )["url"] ?>" alt="" /></a>
             </div>

        <?php
            endwhile;
             while ( have_rows('social_hub') ) : the_row();
            
            ?>
             <div class="social-item col s3 social_<?= get_sub_field( "red_social" ) ?>" >
               <a href="<?= get_sub_field( "url" ) ?>"><img src="<?= get_sub_field( "imagen" )["url"] ?>" alt="" /></a>
             </div>

        <?php
            endwhile;
        
        else :
        
            // no rows found
        
        endif;
        
        ?>
    	</div>
    	<div id="social_more">
      	
    	</div>
  	</section>
  	</main>
  	<section id="contacto">
    	<div class="container">
      	<div id="contact_form">
        	<div id="contact_text">
          	Nos interesa tu opinión e inquietudes. ¡Escríbenos! 
          	<div>
            	<?php echo do_shortcode( '[contact-form-7 id="96" title="Contact form 1"]' ) ?>
          	</div>
        	</div>
      	</div>
    	</div>
  	</section>
  	
  	
<?php get_footer(); ?>