<?php
/*
*/
?>

<?php get_header(); ?>

<main id="productos">
  <div id="productos_header" class="productos_header">
    <div id="linea_menu" class="container">
      <div class="linea_menu_cont">
         <?php 
          $taxonomy = 'linea';
          $tax_terms = get_terms($taxonomy, array('hide_empty' => false));
                                
          foreach($tax_terms as $term_single) { 
/*
            echo "<pre>";
             var_dump($term_single);
             echo "</pre>";
             echo "<pre>";
*/
             $imagen_tax = get_field("imagen_con_hover",$term_single);

        ?>
          <div class="linea_menu_item"><a href="<?= $term_single->slug; ?>"><img src="<?= $imagen_tax["url"]; ?>" alt="<?= $term_single->name; ?>" /></a></div>
        
        <?php         
          } 
            
        ?>
      </div>
    </div>
    <div id="productos_banner">
      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/banner-principal.png" alt="banner-principal"  />
    </div>
    <div id="clase_menu" class="container">
      <div class="clase_menu_cont">
         <?php 
          $taxonomy = 'clase';
          $tax_terms = get_terms($taxonomy, array('hide_empty' => false));
                                
          foreach($tax_terms as $term_single) { 

          $icono = get_field("icono",$term_single);
        ?>
          <div class="clase_menu_item">
            <a href="<?= $term_single->slug; ?>">
              <div class="clase_icon"><img src="<?= $icono["url"]; ?>" alt="<?= $term_single->name; ?>" /></div>
              <div class="clase_name"><?= $term_single->name; ?></div>            
            </a>
          </div>
        
        <?php         
          } 
            
        ?>
      </div>
    </div>
</main>


<?php get_footer(); ?>