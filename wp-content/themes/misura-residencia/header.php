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
      	<div class="left header_nav">
          <div class="nav_cont">
            <div href="#" class="nav_item">Valores</div>
            <div href="#" class="nav_item">Productos</div>
          </div>
        </div>
        <div class="center" id="logo">
          <img src="<?= get_template_directory_uri(); ?>/assets/images/logo-misura.png" alt="" />
        </div>
        <div class="right header_nav">
          <div class="nav_cont">
            <div class="nav_item">Te tratamos bien</div>
            <div class="nav_item">Respuestas Misura</div>
          </div>
        </div>
      </div>
  	</header>
  	
  	
  