<?php

	//Inserción de jQuery
	if (!is_admin()) add_action("wp_enqueue_scripts", "my_jquery_enqueue", 11);
	function my_jquery_enqueue() {
	   wp_deregister_script('jquery');
	   wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js", false, null);
	   wp_enqueue_script('jquery');
	}

	// Soporte para menús con wp_nav_menu( array('menu' => 'Nombre_del_menu' ));
	add_theme_support('menus');

	// Soporte para imagen destacada
	add_theme_support('post-thumbnails');

	// Soporte para sidebar
	if ( function_exists('register_sidebar') )
		register_sidebar(array('name'=>'nombre_de_la_sidebar',
		'before_title' => '<div class="nombre_de_la_clase_titulo">',
		'after_title' => '</div>',
		'before_widget' => '<div class="contenedor_del_widget">',
		'after_widget' => '</div>',
	));

	// shortcode in widgets
	if ( !is_admin() ){
	    add_filter('widget_text', 'do_shortcode', 11);
	}

	// make TinyMCE allow iframes
	add_filter('tiny_mce_before_init', create_function( '$a',
	'$a["extended_valid_elements"] = "iframe[id|class|title|style|align|frameborder|height|longdesc|marginheight|marginwidth|name|scrolling|src|width]"; return $a;') );

	add_filter('mce_buttons','wysiwyg_editor');
	function wysiwyg_editor($mce_buttons) {
	    $pos = array_search('wp_more',$mce_buttons,true);
	    if ($pos !== false) {
	        $tmp_buttons = array_slice($mce_buttons, 0, $pos+1);
	        $tmp_buttons[] = 'wp_page';
	        $mce_buttons = array_merge($tmp_buttons, array_slice($mce_buttons, $pos+1));
	    }
	    return $mce_buttons;
	}

	function mqw_mas_botones($buttons) {
	 $buttons[] = 'hr';
	 $buttons[] = 'del';
	 $buttons[] = 'sub';
	 $buttons[] = 'sup';
	 $buttons[] = 'fontselect';
	 $buttons[] = 'fontsizeselect';
	 $buttons[] = 'cleanup';
	 $buttons[] = 'styleselect';
	 return $buttons;
	}
	add_filter("mce_buttons_3", "mqw_mas_botones");

	//Añadir columna de IDs
	add_filter('manage_posts_columns', 'posts_columns_id', 5);
	add_action('manage_posts_custom_column', 'posts_custom_id_columns', 5, 2);
	add_filter('manage_pages_columns', 'posts_columns_id', 5);
	add_action('manage_pages_custom_column', 'posts_custom_id_columns', 5, 2);

	function posts_columns_id($defaults){
		$defaults['wps_post_id'] = __('ID');
		return $defaults;
	}
	function posts_custom_id_columns($column_name, $id){
		if($column_name === 'wps_post_id'){
			echo $id;
		}
	}

/************** Custom post products ***********************/
	
// La función no será utilizada antes del 'init'.
add_action( 'init', 'my_custom_init' );


function my_custom_init() {
        $labels = array(
        'name' => _x( 'Productos', 'post type general name' ),
        'singular_name' => _x( 'Producto', 'post type singular name' ),
        'add_new' => _x( 'Añadir nuevo', 'book' ),
        'add_new_item' => __( 'Añadir nuevo producto' ),
        'edit_item' => __( 'Editar producto' ),
        'new_item' => __( 'Nuevo producto' ),
        'view_item' => __( 'Ver producto' ),
        'menu_name'     => __('Productos', 'Productos'),
        'search_items' => __( 'Buscar productos' ),
        'not_found' =>  __( 'No se han encontrado productos' ),
        'not_found_in_trash' => __( 'No se han encontrado productos en la papelera' ),
        'parent_item_colon' => ''
    );
 
    // Creamos un array para $args
    $args = array( 'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => null,
        'menu_icon' => 'dashicons-products', 
        'supports' => array( 'title', 'author', 'thumbnail', 'excerpt' ),
        'has_archive'   => true,
    );
 
    register_post_type( 'productos', $args ); 
}


// Lo enganchamos en la acción init y llamamos a la función create_book_taxonomies() cuando arranque
add_action( 'init', 'create_book_taxonomies', 0 );
 
// Creamos dos taxonomías, género y autor para el custom post type "libro"
function create_book_taxonomies() {
        // Añadimos nueva taxonomía y la hacemos jerárquica (como las categorías por defecto)
        $labels = array(
        'name' => _x( 'Lineas', 'taxonomy general name' ),
        'singular_name' => _x( 'Linea', 'taxonomy singular name' ),
        'search_items' =>  __( 'Buscar por Linea' ),
        'all_items' => __( 'Todos los Lineas' ),
        'parent_item' => __( 'Linea padre' ),
        'parent_item_colon' => __( 'Linea padre:' ),
        'edit_item' => __( 'Editar Linea' ),
        'update_item' => __( 'Actualizar Linea' ),
        'add_new_item' => __( 'Añadir nuevo Linea' ),
        'new_item_name' => __( 'Nombre del nuevo Linea' ),
        
);
register_taxonomy( 'linea', array( 'productos' ), array(
        'hierarchical' => true,
        'label' => 'News Categories',
        'labels' => $labels, /* Aquí es donde se utiliza la variable $labels que hemos creado arriba*/
        'show_ui' => true,
        'query_var' => true,
        'show_in_nav_menus' => true,
        'rewrite' => array( 'slug' => 'linea', 'with_front' => false ),
));
// Añado otra taxonomía, esta vez no es jerárquica, como las etiquetas.
$labels = array(
         'name' => _x( 'Tipos', 'taxonomy general name' ),
        'singular_name' => _x( 'Tipo', 'taxonomy singular name' ),
        'search_items' =>  __( 'Buscar por Tipos' ),
        'all_items' => __( 'Todos los Tipos' ),
        'parent_item' => __( 'Tipo padre' ),
        'parent_item_colon' => __( 'Tipo padre:' ),
        'edit_item' => __( 'Editar Tipo' ),
        'update_item' => __( 'Actualizar Tipo' ),
        'add_new_item' => __( 'Añadir nuevo Tipo' ),
        'new_item_name' => __( 'Nombre del nuevo Tipo' ),
        'show_in_nav_menus' => true
);
 
register_taxonomy( 'tipo', array( 'productos' ), array(
        'hierarchical' => true,
        'labels' => $labels, /* Aquí es donde se utiliza la variable $labels que hemos creado arriba*/
        'show_ui' => true,
        'query_var' => true,
        'show_in_nav_menus' => true,
        'rewrite' => array( 'slug' => 'productos', 'with_front' => false ),
));

flush_rewrite_rules();

/*************** Custom post products ********************/
}

	// Registrar options page con ACF
	if( function_exists('acf_add_options_sub_page') ) {

		acf_add_options_sub_page($pagename);
	}

	// Registrar scripts en el tema
	add_action( 'wp_enqueue_scripts', 'registrar_scripts', 1 );
	function registrar_scripts() {
		//Scripts
		//Prefixfree
		wp_register_script( 'prefixfree', get_template_directory_uri() . '/assets/js/prefixfree.min.js', array( 'jquery' ), '1.0', true );
		// Bootstrap
		wp_register_script( 'materialize', get_template_directory_uri() . '/assets/js/materialize.min.js', array( 'jquery' ), '1.0', true );
		wp_register_script( 'slick', get_template_directory_uri() . '/assets/js/slick.min.js', array( 'jquery' ), '1.0', true );
		//CSS
		//Bootstrap
		wp_register_style( 'normalize', get_template_directory_uri() . '/assets/css/normalize.css' );
		wp_register_style( 'materialize', get_template_directory_uri() . '/assets/css/materialize.min.css' );
		wp_register_style( 'slick', get_template_directory_uri() . '/assets/css/slick.css' );
		wp_register_style( 'slick-theme', get_template_directory_uri() . '/assets/css/slick-theme.css' );
		// Para usar ajax
		/*
		$php_array = array( 'admin_ajax' => admin_url( 'admin-ajax.php' ) );
	 	wp_localize_script( $scriptenelqueusamosajax, 'php_array', $php_array );
	 	*/
	}

	// Añadir los scripts al DOM
	add_action( 'wp_enqueue_scripts', 'theme_enqueue_scripts' );
	function theme_enqueue_scripts() {
		// Scripts
		wp_enqueue_script('prefixfree');
		wp_enqueue_script('materialize');
		wp_enqueue_script('slick');
		// CSS
		wp_enqueue_style('normalize');
		wp_enqueue_style('materialize');
		wp_enqueue_style('slick');
		wp_enqueue_style('slick-theme');
	}

  // Google fonts
  
  function wpb_add_google_fonts() {
    wp_enqueue_style( 'wpb-google-fonts', 'https://fonts.googleapis.com/css?family=Roboto', false ); 
  }
  
add_action( 'wp_enqueue_scripts', 'wpb_add_google_fonts' );




	/* Opcionales

	// Login Nuevo
	function my_login_stylesheet() {
	    wp_enqueue_style( 'custom-login', get_template_directory_uri() . '/assets/css/style-login.css' );
	}
	add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );


	// Usar ajax
	add_action( 'wp_ajax_$nombrecall', '$nombrecall_init' );
	add_action( 'wp_ajax_nopriv_$nombrecall', '$nombrecall_init' );

	function $nombrecall_init() {

	}

	// Modificar Login css
	function my_login_stylesheet() {
	    wp_enqueue_style( 'custom-login', get_template_directory_uri() . '/assets/css/style-login.css' );
	}
	add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );


	//Añadir google analytics
	add_action('wp_footer', 'add_googleanalytics');
	function add_googleanalytics() { ?>
	// Paste your Google Analytics code from Step 6 here
	<?php }

	// Cambiar largo del excerpt
	function new_excerpt_length($length) {
	return 100;
	}
	add_filter('excerpt_length', 'new_excerpt_length');


	// Añadir soporte para posrt formats
	add_theme_support( 'post-formats', array( 'chat', 'audio', 'video', 'status', 'quote', 'link', 'gallery', 'aside' ) );

	// Cambiar tamaño de the post thumbnail
	add_image_size('home', 200, 217, true);
	add_image_size('medium', 600, 300, true);


	// Detectar navegadores
	function browser_body_class($classes) {
	global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
	if($is_lynx) $classes[] = ‘lynx’;
	elseif($is_gecko) $classes[] = ‘gecko’;
	elseif($is_opera) $classes[] = ‘opera’;
	elseif($is_NS4) $classes[] = ‘ns4’;
	elseif($is_safari) $classes[] = ‘safari’;
	elseif($is_chrome) $classes[] = ‘chrome’;
	elseif($is_IE) $classes[] = ‘ie’;
	else $classes[] = ‘unknown’;

	if($is_iphone) $classes[] = ‘iphone’;
	return $classes;
	}
	add_filter(‘body_class’,’browser_body_class’);

	// Cambiar nombres de los roles
	function wps_change_role_name() {
    global $wp_roles;
    if ( ! isset( $wp_roles ) )
        $wp_roles = new WP_Roles();
	    $wp_roles->roles[$rol]['name'] = '$nombreRol';
	    $wp_roles->role_names[$rol] = '$nombreRol';
	}
	add_action('init', 'wps_change_role_name');

	// Redireccionar directo al post si es el único en una categoría o tag
	function redirect_to_post(){
	    global $wp_query;
	    if( is_archive() && $wp_query->post_count == 1 ){
	        the_post();
	        $post_url = get_permalink();
	        wp_redirect( $post_url );
	    }

	} add_action('template_redirect', 'redirect_to_post');

	// Agregar search a un menú
	add_filter('wp_nav_menu_items', 'add_search_form', 10, 2);
	function add_search_form($items, $args) {
	if( $args->theme_location == 'MENU-NAME' )
	        $items .= '<li class="search"><form role="search" method="get" id="searchform" action="'.home_url( '/' ).'"><input type="text" value="search" name="s" id="s" /><input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" /></form></li>';
	        return $items;
	}
	*/

?>