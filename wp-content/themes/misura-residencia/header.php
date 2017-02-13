<!--DOCTYPE html-->
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
            <a href="#" class="nav_item">Valores</a>
            <a href="#" class="nav_item">Productos</a>
          </div>
        </div>
        <div class="right header_nav">
          <div class="nav_cont">
            <a href="#" class="nav_item">Te tratamos bien</a>
            <a href="#" class="nav_item">Respuestas Misura</a>
          </div>
        </div>
      </div>
  	</header>
  	<section id="home_slider">
    	<div class="slider-home">
        <div class="slider-item" style="background-image: url(http://lorempixel.com/1920/1080/sports/1)"></div>
        <div class="slider-item" style="background-image: url(http://lorempixel.com/1920/1080/sports/2)"></div>
        <div class="slider-item" style="background-image: url(http://lorempixel.com/1920/1080/sports/3)"></div>
      </div>
  	</section>
  	<section id="media_content">
    	<div class="media_row">
      	<div class="media_item"></div>
    	</div>
  	</section>
  	
  