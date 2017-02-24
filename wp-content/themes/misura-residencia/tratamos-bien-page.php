<?php
/*
Template Name: Te tratamos bien
*/
?>
<?php get_header(); ?>
<main id="tratamos">
  <div class="container">
    <div class="custom_header">
      <img src="<?= get_template_directory_uri(); ?>/assets/images/Te-tratamos-title.png" alt="Te-tratamos-title"  />
      <div style="clear: both"></div>
      <span>
        Activa todos tus sentidos, un nuevo mundo te espera.<br>
Hay un nuevo “bienestar” en el aire: El bienestar Misura que te consciente y te trata bien
      </span>
    </div>
    <div class="tratamos_contenido">
      <?php
        // check if the repeater field has rows of data
        if( have_rows('contenido') ):
         	// loop through the rows of data
            while ( have_rows('contenido') ) : the_row();
            ?>
             <div class="row norow">
               <div class="col s12 m6">
                 <img src="<?= get_sub_field( "imagen_izq" )["url"] ?>" alt="<?= get_sub_field( "imagen_izq" )["alt"] ?>" />
               </div>
               <div class="col s12 m6">
                 <img src="<?= get_sub_field( "imagen_der" )["url"] ?>" alt="<?= get_sub_field( "imagen_der" )["alt"] ?>" />
               </div>
             </div>
        <?php
            endwhile;
        
        else :
        
            // no rows found
        
        endif;
        
        ?>
    </div>
  </div>
</main>
<?php get_footer(); ?>
