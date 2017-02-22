<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=9" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
		<?php wp_head(); ?>
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>">
		<link rel="shortcut icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/favicon.png">
	</head>
	
	<body <?php body_class(); ?> >
  	<header>
    	<div class="container">
      	<div id="social_header">
        	<div class="social_header_item">
          	<a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/sm-header-fb.png" alt="sm-header-fb" ></a>
        	</div>
        	<div class="social_header_sep"></div>
        	<div class="social_header_item">
          	<a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/sm-header-inst.png" alt="sm-header-inst" ></a>
        	</div>
      	</div>
      	<div class="left header_nav">
          <div class="nav_cont">
            <div href="#" class="nav_item"><a href="#">Valores</a></div>
            <div href="#" class="nav_item" id="prodructos_nav">
              <a href="">Productos</a>
              <div class="nav_subitem">
                <div class="subnav_cont">
                  <div class="sub_nav_side">
                    <div class="subnav_title">the line for me</div>
                      <?php 
                        $taxonomy = 'tipo';
                        
                        $tax_terms = get_terms($taxonomy, array('hide_empty' => false));
                                              
                        foreach($tax_terms as $term_single) { 
                      ?>
                        <div class="sub_item"><a href="<?= $term_single->slug; ?>"><?= $term_single->name; ?></a></div>
                      
                      <?php         
                        } 
                          
                      ?>
                  </div>
                  <div class="sub_nav_side">
                    <div class="subnav_title">i feel like</div>
                     <?php 
                        $taxonomy = 'linea';
                        
                        $tax_terms = get_terms($taxonomy, array('hide_empty' => false));
                                              
                        foreach($tax_terms as $term_single) { 
                      ?>
                        <div class="sub_item"><a href="<?= $term_single->slug; ?>"><?= $term_single->name; ?></a></div>
                      
                      <?php         
                        } 
                      ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="center" id="logo">
          <a href="/"> <img src="<?= get_template_directory_uri(); ?>/assets/images/logo-misura.png" alt="" /></a>
        </div>
        <div class="right header_nav">
          <div class="nav_cont">
            <div class="nav_item">Te tratamos bien</div>
            <div class="nav_item">Respuestas Misura</div>
          </div>
        </div>
      </div>
  	</header>
  	
  	
  