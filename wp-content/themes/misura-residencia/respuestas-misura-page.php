<?php
/*
Template Name: Respuestas Misura
*/
?>
<?php 
  get_header();
  $num = 1;   
?>
<main id="respuestas">
  <div class="container">
    <div class="custom_header">
      <img src="<?= get_template_directory_uri(); ?>/assets/images/Respuestas-title.png" alt="Te-tratamos-title"  />
      <div style="clear: both"></div>
      <span>
       Hemos reunido las respuestas de las preguntas más comunes en la siguiente lista.<br>
Si ninguna de ellas resuelve la pregunta que tienes, por favor contáctanos
y estaremos felices de responderte.
      </span>
    </div>
    <section class="preguntas" id="compania">
      <div class="preguntas_title">
        Compañía y medio ambiente
      </div>
      <div class="container">
        <ul class="collapsible" data-collapsible="expandable">
         <?php
        // check if the repeater field has rows of data
        if( have_rows('compania') ):
        
         	// loop through the rows of data
            while ( have_rows('compania') ) : the_row();
            ?>
            <li>
              <div class="collapsible-header">
                <div class="pregunta_container">
                  <div class="pregunta_num"><?= $num ?></div>
                  <div class="pregunta_text"><div class="pregunta_text_in"><?= get_sub_field("pregunta"); ?></div></div>
                </div>
              </div>
              <div class="collapsible-body"><span><?= get_sub_field("respuesta"); ?></span></div>
            </li>
        <?php
            $num++;
          
            endwhile;
        
        else :
        
            // no rows found
        
        endif;
        
        ?>
        </ul>
      </div>
    </section>
    
    <section class="preguntas" id="p_productos">
      <div class="preguntas_title">
       Productos
      </div>
      <div class="container">
        <ul class="collapsible" data-collapsible="expandable">
         <?php
        // check if the repeater field has rows of data
        if( have_rows('productos') ):
        
         	// loop through the rows of data
            while ( have_rows('productos') ) : the_row();
            ?>
            <li>
              <div class="collapsible-header">
                <div class="pregunta_container">
                  <div class="pregunta_num"><?= $num ?></div>
                  <div class="pregunta_text"><div class="pregunta_text_in"><?= get_sub_field("pregunta"); ?></div></div>
                </div>
              </div>
              <div class="collapsible-body"><span><?= get_sub_field("respuesta"); ?></span></div>
            </li>
        <?php
            $num++;
          
            endwhile;
        
        else :
        
            // no rows found
        
        endif;
        
        ?>
        </ul>
      </div>
    </section>
    <section class="preguntas" id="p_nutricionales">
      <div class="preguntas_title">
       Propiedades Nutricionales
      </div>
      <div class="container">
        <ul class="collapsible" data-collapsible="expandable">
         <?php
        // check if the repeater field has rows of data
        if( have_rows('propiedades_nutricionales') ):
        
         	// loop through the rows of data
            while ( have_rows('propiedades_nutricionales') ) : the_row();
            ?>
            <li>
              <div class="collapsible-header">
                <div class="pregunta_container">
                  <div class="pregunta_num"><?= $num ?></div>
                  <div class="pregunta_text"><div class="pregunta_text_in"><?= get_sub_field("pregunta"); ?></div></div>
                </div>
              </div>
              <div class="collapsible-body"><span><?= get_sub_field("respuesta"); ?></span></div>
            </li>
        <?php
            $num++;
          
            endwhile;
        
        else :
        
            // no rows found
        
        endif;
        
        ?>
        </ul>
      </div>
    </section>
     
  </div>
</main>
<?php get_footer(); ?>
