<?php
/*
Template Name: Productos
*/
?>

<?php //get_header(); ?>


<div style="width: 100%;display: table; background-color: white;padding: 40px;">
  
  <?php 
    $taxonomy = 'tipo';
    
    $tax_terms = get_terms($taxonomy, array('hide_empty' => false));
    
    echo "<pre>";
    var_dump($tax_terms);
    echo "</pre>";
    
    foreach($tax_terms as $term_single) { 
       echo "<pre>";
    var_dump($term_single);
    echo "</pre>";     
        echo $term_single->slug." - ".$term_single->name;  
        echo "<br><br>";        
    } 
      
  ?>
  
</div>

<?php //get_footer(); ?>