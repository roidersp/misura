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
          <div class="linea_menu_item"><a href="<?= site_url(); ?>/productos/linea/<?= $term_single->slug; ?>"><img src="<?= $imagen_tax["url"]; ?>" alt="<?= $term_single->name; ?>" /></a></div>
        
        <?php         
          } 
            
        ?>
      </div>
    </div>
    <div id="productos_banner">
      
        <?php 
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
            <a href="<?= site_url(); ?>/productos/clase/<?= $term_single->slug; ?>">
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
$post_type = 'productos';
$tax = "linea";
  
  $tax_terms = get_terms( $tax, 'orderby=name&order=ASC');
  
  $post_by_term = [];
  
  if ($tax_terms) {
        foreach ($tax_terms  as $tax_term) {

            $args = array(
                'post_type'         => $post_type,
                "$tax"              => $tax_term->slug,
                'post_status'       => 'publish',
                'posts_per_page'    => 10,
            );

            $my_query = null;
            $my_query = new WP_Query($args);
            
            $array = [];
            
            if( $my_query->have_posts() ) : 
                $array["name"] = $tax_term->name;
                $array["image"] = get_field("imagen", "linea_".$tax_term->term_id);
                while ( $my_query->have_posts() ) : $my_query->the_post(); 
                  $term_list = get_the_terms($post->ID, "clase");
                  $in_tax = false;
                  foreach($term_list as $term){
                    if($term != null && $term->term_id == $current_cat) $in_tax = true;
                  }
                  if($in_tax): // Display only posts that have current category ID 
                    $array["post"][] = $post;
                  endif; // if in_array 
                endwhile; // end of loop
                $post_by_term[] = $array;
                
            endif; // if have_posts()
            wp_reset_query();

        } // end foreach #tax_terms
    } // end if tax_terms
  
?>

<div class="container container_tax">
  <div class="tax_title">
     <?php 
          $img_title = get_field("imagen_titulo", get_queried_object());
        ?>
       <img src="<?= $img_title["url"] ?>" alt="<?= $img_title["alt"] ?>"  />
  </div>
  <div class="tax_products">
    <?php foreach( $post_by_term as $item ){ 
      if(isset($item["post"]) && count($item["post"])>0){ ?>
      <div class="tax_products_cont">
        <div class="tax_sec_title"><img src="<?= $item["image"]["url"] ?>" alt="<?= $item["image"]["alt"] ?>" /></div>
        <div class="row">
        <?php foreach( $item["post"] as $post ){ ?>
          <a href="<?php the_permalink(); ?>"><div class="item_product col s3">
            <div class="product_image">
              <?php if ( has_post_thumbnail() ) the_post_thumbnail( 'medium' );   ?> 
            </div>
            <div class="product_name"><?php the_title();?> </div>
          </div>
          </a>
        <?php } ?>
        </div>
      </div>
     <?php  }
    ?> 
    <br><br>
      
    <?php } ?>
  </div>
</div>


<?php get_footer(); ?>