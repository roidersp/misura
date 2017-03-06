
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
        <div class="container container_big">
          <div class="row">
            <div class="col s6">
              <?php 
                $image_preg = get_field('imagen_pregunta');
                
                if( !empty($image_preg) ): ?>
                	<img src="<?php echo $image_preg['url']; ?>" alt="<?php echo $image_preg['alt']; ?>" />
                <?php endif; ?>
            </div>
            <?php 
                $circulo = get_field('circulo_2',"linea_".$term_id); 
              ?>
            <div class="col s5">
              <div class="texto_preg_cont" style="background-image: url(<?= $circulo["url"]; ?>);">
                <div class="texto_preg_out">
                  <div class="texto_preg_in">
                    <div class="text_preg_main"><?= get_field("por_que") ?></div>
                    <div class="text_preg_second"><?= get_field("texto_por_que") ?></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section id="informacion">
        <div class="container_big">
          <div class="row info_main">
            <div class="col s7">
              <?php 
                $circulo_3 = get_field('circulo_3',"linea_".$term_id); 
              ?>
              <div class="circulo_3" style="background-image: url(<?= $circulo_3["url"]; ?>)">
                <div class="circulo_3_text">
                  <div class="circulo_3_text_in">
                    <?= get_field("texto"); ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col s5">
              <?php 
                $image_globo = get_field('globo_punteado');
                $gif = get_field('gif_galleta');
                
                if( !empty($image_globo) ): ?>
                	<img class="img_globo" src="<?php echo $image_globo['url']; ?>" alt="<?php echo $image_globo['alt']; ?>" />
                <?php endif;  
                  
                  if( !empty($gif) ): ?>
                	<img class="img_gif" src="<?php echo $gif['url']; ?>" alt="<?php echo $gif['alt']; ?>" />
                <?php endif; ?>
            </div>
          </div>
     
        <div class="row  info_nutrimental">
          <div class="col s10 offset-s1">
            <div class="info_title">Información nutrimental:</div>
            <div class="info_porcion">Una porción de <?= get_field("porcion") ?> gr. aporta: </div>
            <table class="table_info">
              <?php

                // check if the repeater field has rows of data
                if( have_rows('tabla_nutricional') ):
                 	// loop through the rows of data
                    while ( have_rows('tabla_nutricional') ) : the_row(); ?>
                      <tr>
                        <td class="tabla_nutricional_izq"><?= get_sub_field("nombre_del_campo"); ?></td>
                        <td class="tabla_nutricional_der"><?= get_sub_field("valor_del_campo"); ?></td>
                      </tr>
                <?php
                  
                    endwhile;
                
                else :
                
                    // no rows found
                
                endif;
              
              ?>
            </table>
            </div>
          </div>
          <div class="row ingredientes">
          <div class="col s5 offset-s2">
            <div class="ingredientes">
              <div class="info_title">Ingredientes: </div>
              <div class="ingredientes_text">
                <?= get_field("ingredientes") ?>
              </div>
            </div>
            <div class="presentacion">
              <div class="info_title">Presentación:  </div>
             <div class="ingredientes_text">
               <?php
                // check if the repeater field has rows of data
                if( have_rows('presentacion') ):
                  $num_re = count(get_field('presentacion'));
                  //echo $num_re;
                  $num_while = 1;
                  $presentacion = "";
                 	// loop through the rows of data
                    while ( have_rows('presentacion') ) : the_row(); 
                    if($num_while == 1){
                      $presentacion .= get_sub_field("gramos")." gr.";
                    }else{
                      if($num_while != $num_re){
                        $presentacion .= ", ".get_sub_field("gramos")." gr." ;
                      }else{
                        $presentacion .= " y ".get_sub_field("gramos")." gr." ;
                      }
                    }
                      $num_while++;
                    endwhile;
                    
                else :
                
                    // no rows found
                
                endif;
                
              ?>
              <p>
                <?= $presentacion; ?>
              </p>
             </div>
            </div>
          </div>
          <div class="col s4 offset-s1">
             <div class="galleta_img">
               <?php 
                $imagen_galleta = get_field('imagen_galleta');
                
                if( !empty($imagen_galleta) ): ?>
                	<img src="<?php echo $imagen_galleta['url']; ?>" alt="<?php echo $imagen_galleta['alt']; ?>" />
                <?php endif; ?>
             </div>
          </div>
        </div>
        </div>
        
      </section>
    </article>
	<?php endwhile; ?>
	</main>
	<!-- Final loop -->
<?php get_footer(); ?>