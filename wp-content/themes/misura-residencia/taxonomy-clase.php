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
             $imagen_tax = get_field("imagen_con_hover",$term_single);
        ?>
          <div class="linea_menu_item"><a href="<?= $term_single->slug; ?>"><img src="<?= $imagen_tax["url"]; ?>" alt="<?= $term_single->name; ?>" /></a></div>
        
        <?php         
          } 
            
        ?>
      </div>
    </div>
    <div id="productos_banner">
      
        <?php 
          $query = get_queried_object();
          $banner = get_field("banner", get_queried_object());
        ?>
       <img src="<?= $banner["url"] ?>" alt="<?= $banner["alt"] ?>"  />
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

<?php 
  
$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );    
$current_cat = $term->term_id;
    
  echo "<br>";
  var_dump($current_cat);  
  
  echo "<pre>";
  var_dump($term);
  echo "</pre>";
 
  
  $post_type = 'productos';
  $tax = "linea";
  
  $tax_terms = get_terms( $tax, 'orderby=name&order=ASC');

  echo "<pre>";
  var_dump($tax_terms);
  echo "</pre>";
  
  if ($tax_terms) {
        foreach ($tax_terms  as $tax_term) {
          
          echo("post type: ".$post_type);
          echo("<br>");

            $args = array(
                'post_type'         => $post_type,
                "$tax"              => $tax_term->slug,
                'post_status'       => 'publish',
                'posts_per_page'    => -1,
            );
            
            echo "<pre>";
            var_dump($args);
            echo "</pre>";

            $my_query = null;
            $my_query = new WP_Query($args);

            if( $my_query->have_posts() ) : ?>

                <h2><?php echo $tax_term->name; // Group name (taxonomy) ?></h2>

                <?php while ( $my_query->have_posts() ) : $my_query->the_post(); ?>
                    <?php $term_list = wp_get_post_terms($post->ID, 'category', array("fields" => "ids")); // Get post categories IDs?>

                    <?php if (in_array($current_cat, $term_list) ): // Display only posts that have current category ID ?>
                        <h3><?php the_title(); ?></h3>
                    <?php endif; // if in_array ?>

                <?php endwhile; // end of loop ?>

            <?php endif; // if have_posts()
            wp_reset_query();

        } // end foreach #tax_terms
    } // end if tax_terms
  
  
?>


<?php get_footer(); ?>




<!--
 <?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); 
  
  
  
?>

<bR>

<?php the_title(); ?>

<br>

  <?php endwhile; ?>
<?php endif; ?>
-->