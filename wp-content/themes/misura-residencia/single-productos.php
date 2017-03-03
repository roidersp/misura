
<?php get_header(); ?>
	<!-- Inicio Loop -->
	<main id="producto">
	<?php while(have_posts()) : the_post(); ?>
	<?php 
  	$term_id =  get_the_terms(get_the_ID(), "linea")[0]->term_id; 
    $producto_main = get_field("background", "linea_".$term_id);
    $producto_globo_text = get_field("circle_text", "linea_".$term_id);
    $producto_globo_empaque = get_field("back_circle", "linea_".$term_id);
    
  ?>
    <article>
      <section class="producto_main" style="background-image: url('<?= $producto_main["url"] ?>')">
        <div class="producto_main_cont_out">
        <div class="producto_main_cont">
          
          <div class="producto_globo_text" style="background-image: url('<?= $producto_globo_text["url"] ?>') " >
            <div class="producto_globo_text_out">
            <div class="producto_globo_text_in">
              <?= the_field("introduccion") ?></div></div></div>
          <div class="producto_globo_empaque" style="background-image: url('<?= $producto_globo_empaque["url"] ?>');">
            <?php the_post_thumbnail( "full" ) ?>
          </div>
        </div>
        </div>
      </section>
      <?php
        $globo = get_field( "globo_pregunta" );
      ?>
      <section class="por_que">
        <div class="por_que_title"><img src="<?= $globo["url"] ?>" alt="<?= $globo["alt"] ?>"></div>
        
      </section>
    </article>
	<?php endwhile; ?>
	</main>
	<!-- Final loop -->
<?php get_footer(); ?>