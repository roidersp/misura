<?php

require_once BOGO_PLUGIN_DIR . '/admin/includes/user.php';
require_once BOGO_PLUGIN_DIR . '/admin/includes/post.php';
require_once BOGO_PLUGIN_DIR . '/admin/includes/nav-menu.php';
require_once BOGO_PLUGIN_DIR . '/admin/includes/widgets.php';

add_action( 'admin_init', 'bogo_upgrade' );

function bogo_upgrade() {
	$old_ver = bogo_get_prop( 'version' );
	$new_ver = BOGO_VERSION;

	if ( $old_ver != $new_ver ) {
		require_once BOGO_PLUGIN_DIR . '/admin/includes/upgrade.php';
		do_action( 'bogo_upgrade', $new_ver, $old_ver );
		bogo_set_prop( 'version', $new_ver );
	}
}

add_action( 'admin_enqueue_scripts', 'bogo_admin_enqueue_scripts' );

function bogo_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_style( 'bogo-admin',
		plugins_url( 'admin/includes/css/admin.css', BOGO_PLUGIN_BASENAME ),
		array(), BOGO_VERSION, 'all' );

	if ( is_rtl() ) {
		wp_enqueue_style( 'bogo-admin-rtl',
			plugins_url( 'admin/includes/css/admin-rtl.css', BOGO_PLUGIN_BASENAME ),
			array(), BOGO_VERSION, 'all' );
	}

	wp_enqueue_script( 'bogo-admin',
		plugins_url( 'admin/includes/js/admin.js', BOGO_PLUGIN_BASENAME ),
		array( 'jquery' ), BOGO_VERSION, true );

	$local_args = array(
		'saveAlert' => __(
			"The changes you made will be lost if you navigate away from this page.",
			'bogo' ),
		'rest_api' => array(
			'url' => trailingslashit( rest_url( 'bogo/v1' ) ),
			'nonce' => wp_create_nonce( 'wp_rest' ) ) );

	if ( 'nav-menus.php' == $hook_suffix ) {
		$nav_menu_id = absint( get_user_option( 'nav_menu_recently_edited' ) );
		$nav_menu_items = wp_get_nav_menu_items( $nav_menu_id );
		$locales = array();

		foreach ( (array) $nav_menu_items as $item ) {
			$locales[$item->db_id] = $item->bogo_locales;
		}

		$local_args = array_merge( $local_args, array(
			'availableLanguages' => bogo_available_languages( array(
				'exclude_enus_if_inactive' => true,
				'orderby' => 'value' ) ),
			'locales' => $locales,
			'selectorLegend' => __( 'Displayed on pages in', 'bogo' ),
			'cbPrefix' => 'menu-item-bogo-locale' ) );
	}

	if ( 'options-general.php' == $hook_suffix ) {
		$local_args = array_merge( $local_args, array(
			'defaultLocale' => bogo_get_default_locale() ) );
	}

	if ( 'post.php' == $hook_suffix && ! empty( $GLOBALS['post'] ) ) {
		$post = $GLOBALS['post'];
		$local_args = array_merge( $local_args, array(
			'post_id' => $post->ID ) );
	}

	$local_args['pagenow'] = isset( $_GET['page'] ) ? trim( $_GET['page'] ) : '';

	wp_localize_script( 'bogo-admin', '_bogo', $local_args );
}

add_action( 'admin_menu', 'bogo_admin_menu' );

function bogo_admin_menu() {
	add_menu_page( __( 'Languages', 'bogo' ), __( 'Languages', 'bogo' ),
		'bogo_manage_language_packs', 'bogo', 'bogo_tools_page',
		'dashicons-translation', 73 ); // between Users (70) and Tools (75)

	$tools = add_submenu_page( 'bogo',
		__( 'Language Packs', 'bogo' ),
		__( 'Language Packs', 'bogo' ),
		'bogo_manage_language_packs', 'bogo', 'bogo_tools_page' );

	add_action( 'load-' . $tools, 'bogo_load_tools_page' );

	$texts = add_submenu_page( 'bogo',
		__( 'Terms Translation', 'bogo' ),
		__( 'Terms Translation', 'bogo' ),
		'bogo_edit_terms_translation', 'bogo-texts', 'bogo_texts_page' );

	add_action( 'load-' . $texts, 'bogo_load_texts_page' );
}

function bogo_load_tools_page() {
	require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );

	$action = isset( $_GET['action'] ) ? $_GET['action'] : '';

	if ( 'install_translation' == $action ) {
		check_admin_referer( 'bogo-tools' );

		if ( ! current_user_can( 'bogo_manage_language_packs' ) ) {
			wp_die( __( "You are not allowed to install translations.", 'bogo' ) );
		}

		$locale = isset( $_GET['locale'] ) ? $_GET['locale'] : null;

		if ( wp_download_language_pack( $locale ) ) {
			$redirect_to = add_query_arg(
				array( 'locale' => $locale, 'message' => 'install_success' ),
				menu_page_url( 'bogo', false ) );
		} else {
			$redirect_to = add_query_arg(
				array( 'locale' => $locale, 'message' => 'install_failed' ),
				menu_page_url( 'bogo', false ) );
		}

		wp_safe_redirect( $redirect_to );
		exit();
	}

	if ( 'set_site_lang' == $action ) {
		check_admin_referer( 'bogo-tools' );

		if ( ! current_user_can( 'bogo_manage_language_packs' ) ) {
			wp_die( __( "You are not allowed to manage translations.", 'bogo' ) );
		}

		$locale = isset( $_GET['locale'] ) ? $_GET['locale'] : null;

		if ( 'en_US' == $locale || ! bogo_is_available_locale( $locale ) ) {
			$locale = '';
		}

		if ( update_option( 'WPLANG', $locale ) ) {
			$redirect_to = add_query_arg(
				array( 'locale' => $locale, 'message' => 'site_lang_success' ),
				menu_page_url( 'bogo', false ) );
		} else {
			$redirect_to = add_query_arg(
				array( 'locale' => $locale, 'message' => 'site_lang_failed' ),
				menu_page_url( 'bogo', false ) );
		}

		wp_safe_redirect( $redirect_to );
		exit();
	}

	if ( 'delete_translation' == $action ) {
		check_admin_referer( 'bogo-tools' );

		if ( ! current_user_can( 'bogo_manage_language_packs' ) ) {
			wp_die( __( "You are not allowed to delete translations.", 'bogo' ) );
		}

		$locale = isset( $_GET['locale'] ) ? $_GET['locale'] : null;

		if ( bogo_delete_language_pack( $locale ) ) {
			$redirect_to = add_query_arg(
				array( 'locale' => $locale, 'message' => 'delete_success' ),
				menu_page_url( 'bogo', false ) );
		} else {
			$redirect_to = add_query_arg(
				array( 'locale' => $locale, 'message' => 'delete_failed' ),
				menu_page_url( 'bogo', false ) );
		}

		wp_safe_redirect( $redirect_to );
		exit();
	}

	if ( 'deactivate_enus' == $action ) {
		check_admin_referer( 'bogo-tools' );

		bogo_set_prop( 'enus_deactivated', true );

		$redirect_to = add_query_arg(
			array( 'message' => 'enus_deactivated' ),
			menu_page_url( 'bogo', false ) );

		wp_safe_redirect( $redirect_to );
		exit();
	}

	if ( 'activate_enus' == $action ) {
		check_admin_referer( 'bogo-tools' );

		bogo_set_prop( 'enus_deactivated', false );

		$redirect_to = add_query_arg(
			array( 'message' => 'enus_activated' ),
			menu_page_url( 'bogo', false ) );

		wp_safe_redirect( $redirect_to );
		exit();
	}
}

function bogo_load_texts_page() {
	$action = isset( $_POST['action'] ) ? $_POST['action'] : '';

	if ( 'save' == $action ) {
		check_admin_referer( 'bogo-edit-text-translation' );

		$locale = isset( $_POST['locale'] ) ? $_POST['locale'] : null;

		if ( ! current_user_can( 'bogo_edit_terms_translation' ) ) {
			wp_die( __( "You are not allowed to edit translations.", 'bogo' ) );
		}

		$entries = array();

		foreach ( $_POST as $p_key => $p_val ) {
			if ( in_array( $p_key, array( 'action', 'locale', 'submit' ) )
			|| substr( $p_key, 0, 1 ) == '_' ) {
				continue;
			}

			$entries[] = array(
				'singular' => $p_key,
				'translations' => array( $p_val ),
				'context' => preg_replace( '/:.*$/', '', $p_key ) );
		}

		if ( Bogo_POMO::export( $locale, $entries ) ) {
			$message = 'translation_saved';
		} else {
			$message = 'translation_failed';
		}

		$redirect_to = add_query_arg(
			array( 'locale' => $locale, 'message' => $message ),
			menu_page_url( 'bogo-texts', false ) );

		wp_safe_redirect( $redirect_to );
		exit();
	}
}

function bogo_tools_page() {
	$enus_deactivated = bogo_is_enus_deactivated();
	$can_install = wp_can_install_language_pack();

	$default_locale = bogo_get_default_locale();
	$available_locales = bogo_available_locales();

?>
<div class="wrap">

<h1><?php echo esc_html( __( 'Language Packs', 'bogo' ) ); ?></h1>

<?php bogo_admin_notice(); ?>

<table id="bogo-languages-table" class="widefat">
<thead>
	<tr><th><?php echo esc_html( __( 'Language', 'bogo' ) ); ?></th></tr>
</thead>
<tfoot>
	<tr><th><?php echo esc_html( __( 'Language', 'bogo' ) ); ?></th></tr>
</tfoot>
<tbody id="translations">
	<tr class="active" id="language-1"><td>
		<strong><?php echo esc_html( bogo_get_language( $default_locale ) ); ?></strong>
		[<?php echo esc_html( $default_locale ); ?>]
		<div class="status"><?php echo esc_html( __( 'Site Language', 'bogo' ) ); ?></div>
	</td></tr>

<?php
	$count = 1;

	foreach ( $available_locales as $locale ) {
		if ( $locale == $default_locale ) {
			continue;
		}

		$count += 1;
		$class = 'active';

		if ( ! $language = bogo_get_language( $locale ) ) {
			$language = $locale;
		}

		if ( 'en_US' == $locale ) {
			$status = $enus_deactivated
				? __( 'Inactive', 'bogo' ) : __( 'Active', 'bogo' );
		} else {
			$status = __( 'Installed', 'bogo' );
		}

		if ( 'en_US' == $locale ) {
			if ( $enus_deactivated ) {
				$activate_link = menu_page_url( 'bogo', false );
				$activate_link = add_query_arg(
					array( 'action' => 'activate_enus' ),
					$activate_link );
				$activate_link = wp_nonce_url( $activate_link, 'bogo-tools' );
				$activate_link = sprintf(
					'<a href="%1$s" class="activate" aria-label="%3$s">%2$s</a>',
					$activate_link,
					esc_html( __( 'Activate', 'bogo' ) ),
					esc_attr(
						sprintf( __( 'Activate %s language pack', 'bogo' ), $language ) ) );

				$row_actions = array( $activate_link );

				$class = '';
			} else {
				$sitelang_link = menu_page_url( 'bogo', false );
				$sitelang_link = add_query_arg(
					array( 'action' => 'set_site_lang', 'locale' => $locale ),
					$sitelang_link );
				$sitelang_link = wp_nonce_url( $sitelang_link, 'bogo-tools' );
				$sitelang_link = sprintf(
					'<a href="%1$s" class="sitelang" aria-label="%3$s">%2$s</a>',
					$sitelang_link,
					esc_html( __( 'Set as Site Language', 'bogo' ) ),
					esc_attr(
						sprintf( __( 'Set %s as Site Language', 'bogo' ), $language ) ) );

				$deactivate_link = menu_page_url( 'bogo', false );
				$deactivate_link = add_query_arg(
					array( 'action' => 'deactivate_enus' ),
					$deactivate_link );
				$deactivate_link = wp_nonce_url( $deactivate_link, 'bogo-tools' );
				$deactivate_link = sprintf(
					'<a href="%1$s" class="deactivate" aria-label="%3$s">%2$s</a>',
					$deactivate_link,
					esc_html( __( 'Deactivate', 'bogo' ) ),
					esc_attr( sprintf(
						__( 'Deactivate %s language pack', 'bogo' ), $language ) ) );

				$row_actions = array( $sitelang_link, $deactivate_link );
			}
		} elseif ( $can_install ) {
			$sitelang_link = menu_page_url( 'bogo', false );
			$sitelang_link = add_query_arg(
				array( 'action' => 'set_site_lang', 'locale' => $locale ),
				$sitelang_link );
			$sitelang_link = wp_nonce_url( $sitelang_link, 'bogo-tools' );
			$sitelang_link = sprintf(
				'<a href="%1$s" class="sitelang" aria-label="%3$s">%2$s</a>',
				$sitelang_link,
				esc_html( __( 'Set as Site Language', 'bogo' ) ),
				esc_attr(
					sprintf( __( 'Set %s as Site Language', 'bogo' ), $language ) ) );

			$delete_link = menu_page_url( 'bogo', false );
			$delete_link = add_query_arg(
				array( 'action' => 'delete_translation', 'locale' => $locale ),
				$delete_link );
			$delete_link = wp_nonce_url( $delete_link, 'bogo-tools' );

			$delete_confirm = sprintf( __( "You are about to delete %s language pack.\n  'Cancel' to stop, 'OK' to delete.", 'bogo' ), $language );

			$delete_link = sprintf( '<a href="%1$s" class="delete" onclick="if (confirm(\'%3$s\')){return true;} return false;" aria-label="%4$s">%2$s</a>',
				$delete_link,
				esc_html( __( 'Delete', 'bogo' ) ),
			 	esc_js( $delete_confirm ),
				esc_attr(
					sprintf( __( 'Delete %s language pack', 'bogo' ), $language ) ) );

			$row_actions = array( $sitelang_link, $delete_link );
		}
?>
	<tr class="<?php echo esc_attr( $class ); ?>" id="language-<?php echo absint( $count ); ?>">
		<td>
		<strong><?php echo esc_html( bogo_get_language( $locale ) ); ?></strong>
		[<?php echo esc_html( $locale ); ?>]
		<div class="status"><?php echo esc_html( $status ); ?></div>
		<div class="row-actions"><?php echo implode( ' | ', $row_actions ); ?></div>
		</td>
	</tr>
<?php
	}

	foreach ( wp_get_available_translations() as $locale => $translation ) {
		if ( in_array( $locale, $available_locales ) ) {
			continue;
		}

		$count += 1;

		$install_link = '';

		if ( $can_install ) {
			$install_link = menu_page_url( 'bogo', false );
			$install_link = add_query_arg(
				array( 'action' => 'install_translation', 'locale' => $locale ),
				$install_link );
			$install_link = wp_nonce_url( $install_link, 'bogo-tools' );
			$install_link = sprintf( '<a href="%1$s" class="install" aria-label="%3$s">%2$s</a>',
				$install_link,
				esc_html( __( 'Install', 'bogo' ) ),
				esc_attr(
					sprintf( __( 'Install %s language pack', 'bogo' ),
						bogo_get_language( $locale ) ) ) );

			$row_actions = array( $install_link );
		}
?>
	<tr id="language-<?php echo absint( $count ); ?>"><td>
		<strong><?php echo esc_html( bogo_get_language( $locale ) ); ?></strong>
		[<?php echo esc_html( $locale ); ?>]
		<div class="row-actions"><?php echo implode( ' | ', $row_actions ); ?></div>
	</td></tr>
<?php
	}
?>

</tbody>
</table>

</div>
<?php
}

function bogo_texts_page() {
	$locale_to_edit = isset( $_GET['locale'] ) ? $_GET['locale'] : '';

	if ( ! Bogo_POMO::import( $locale_to_edit ) ) {
		Bogo_POMO::reset();
	}

?>
<div class="wrap">

<h1><?php echo esc_html( __( 'Terms Translation', 'bogo' ) ); ?></h1>

<?php bogo_admin_notice(); ?>

<form action="" method="post" id="bogo-translatable-text-strings">
<input type="hidden" name="action" value="save" />
<?php wp_nonce_field( 'bogo-edit-text-translation' ); ?>

<p>
<select name="locale" id="select-locale">
	<option value=""><?php echo esc_html( __( '-- Select Language to Edit --', 'bogo' ) ); ?></option>
<?php
	$available_locales = bogo_available_locales(
		array( 'current_user_can_access' => true ) );

	foreach ( $available_locales as $locale ) {
		echo sprintf( '<option value="%1$s"%3$s>%2$s</option>',
			esc_attr( $locale ),
			esc_html( bogo_get_language( $locale ) ),
			$locale == $locale_to_edit ? ' selected="selected"' : '' );
	}
?>
</select>
</p>

<?php
	if ( bogo_is_available_locale( $locale_to_edit ) ) :
		$strings = array(
			array(
				'name' => 'blogname',
				'original' => get_option( 'blogname' ),
				'translated' => bogo_translate( 'blogname',
					'blogname', get_option( 'blogname' ) ),
				'context' => __( 'Site Title', 'bogo' ) ),
			array(
				'name' => 'blogdescription',
				'original' => get_option( 'blogdescription' ),
				'translated' => bogo_translate( 'blogdescription',
					'blogdescription', get_option( 'blogdescription' ) ),
				'context' => __( 'Tagline', 'bogo' ) ) );

		remove_filter( 'get_term', 'bogo_get_term_filter' );

		foreach ( (array) get_taxonomies( array(), 'objects' ) as $taxonomy ) {
			$tax_labels = get_taxonomy_labels( $taxonomy );
			$terms = get_terms( array(
				'taxonomy' => $taxonomy->name,
				'orderby' => 'slug',
				'hide_empty' => false ) );

			foreach ( (array) $terms as $term ) {
				$name = sprintf( '%s:%d', $taxonomy->name, $term->term_id );
				$strings[] = array(
					'name' => $name,
					'original' => $term->name,
					'translated' => bogo_translate( $name,
						$taxonomy->name, $term->name ),
					'context' => $tax_labels->name );
			}
		}
?>
<table class="wp-list-table widefat fixed striped" id="bogo-translation-table">
<thead>
	<tr>
		<th scope="col"><?php echo esc_html( __( 'Original', 'bogo' ) ); ?></th>
		<th scope="col"><?php echo esc_html( __( 'Translation', 'bogo' ) ); ?></th>
		<th scope="col" style="width: 20%;"><?php echo esc_html( __( 'Context', 'bogo' ) ); ?></th>
	</tr>
</thead>
<tfoot>
	<tr>
		<th scope="col"><?php echo esc_html( __( 'Original', 'bogo' ) ); ?></th>
		<th scope="col"><?php echo esc_html( __( 'Translation', 'bogo' ) ); ?></th>
		<th scope="col"><?php echo esc_html( __( 'Context', 'bogo' ) ); ?></th>
	</tr>
</tfoot>
<tbody>
<?php
		foreach( $strings as $string ) :
?>
	<tr>
		<td><?php echo esc_html( $string['original'] ); ?></td>
		<td><input name="<?php echo esc_attr( $string['name'] ); ?>" type="text" id="<?php echo esc_attr( $string['name'] ); ?>" value="<?php echo esc_attr( $string['translated'] ); ?>" class="large-text" /></td>
		<td><?php echo esc_html( $string['context'] ); ?></td>
	</tr>
<?php
		endforeach;
?>
</tbody>
</table>

<?php submit_button(); ?>
<?php
	endif;
?>
</form>
</div>
<?php
}

function bogo_admin_notice( $reason = '' ) {
	if ( empty( $reason ) && isset( $_GET['message'] ) ) {
		$reason = $_GET['message'];
	}

	if ( 'install_success' == $reason ) {
		$message = __( "Translation installed successfully.", 'bogo' );
	} elseif ( 'install_failed' == $reason ) {
		$message = __( "Translation install failed.", 'bogo' );
	} elseif ( 'site_lang_success' == $reason ) {
		$message = __( "Site language set successfully.", 'bogo' );
	} elseif ( 'site_lang_failed' == $reason ) {
		$message = __( "Setting site language failed.", 'bogo' );
	} elseif ( 'delete_success' == $reason ) {
		$message = __( "Translation uninstalled successfully.", 'bogo' );
	} elseif ( 'delete_failed' == $reason ) {
		$message = __( "Translation uninstall failed.", 'bogo' );
	} elseif ( 'enus_deactivated' == $reason ) {
		$message = __( "English (United States) deactivated.", 'bogo' );
	} elseif ( 'enus_activated' == $reason ) {
		$message = __( "English (United States) activated.", 'bogo' );
	} elseif ( 'translation_saved' == $reason ) {
		$message = __( "Translation saved.", 'bogo' );
	} elseif ( 'translation_failed' == $reason ) {
		$message = __( "Saving translation failed.", 'bogo' );
	} else {
		return false;
	}

	if ( '_failed' == substr( $reason, -7 ) ) {
		echo sprintf(
			'<div class="error notice notice-error is-dismissible"><p>%s</p></div>',
			esc_html( $message ) );
	} else {
		echo sprintf(
			'<div class="updated notice notice-success is-dismissible"><p>%s</p></div>',
			esc_html( $message ) );
	}
}

function bogo_delete_language_pack( $locale ) {
	if ( 'en_US' == $locale
	|| ! bogo_is_available_locale( $locale )
	|| bogo_is_default_locale( $locale ) ) {
		return false;
	}

	if ( ! is_dir( WP_LANG_DIR ) || ! $files = scandir( WP_LANG_DIR ) ) {
		return false;
	}

	$target_files = array(
		sprintf( '%s.mo', $locale ),
		sprintf( '%s.po', $locale ),
		sprintf( 'admin-%s.mo', $locale ),
		sprintf( 'admin-%s.po', $locale ),
		sprintf( 'admin-network-%s.mo', $locale ),
		sprintf( 'admin-network-%s.po', $locale ),
		sprintf( 'continents-cities-%s.mo', $locale ),
		sprintf( 'continents-cities-%s.po', $locale ) );

	foreach ( $files as $file ) {
		if ( '.' === $file[0] || is_dir( $file ) ) {
			continue;
		}

		if ( in_array( $file, $target_files ) ) {
			$result = @unlink( path_join( WP_LANG_DIR, $file ) );

			if ( ! $result ) {
				return false;
			}
		}
	}

	return true;
}
