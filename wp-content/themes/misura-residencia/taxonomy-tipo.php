<?php 
  
$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );    
$current_cat = $term->term_id;
    
  echo("rest");
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
                'category__in'      => $current_cat // Only posts in current category (category.php)
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