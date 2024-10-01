<?php

/*
 * Plugin Name:       Tambar – Bottom Admin Bar
 * Description:       Easily change the position of the admin bar on the frontend.
 * Version:           2.2.1
 * Tested up to:      6.6.2
 * Requires at least: 6.4
 * Author:            Yan Metelitsa
 * Author URI:        https://yanmet.com/
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       tambar
 */

// Exits if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// Gets plugin data.
if ( !function_exists( 'get_plugin_data' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
$tambar_plugin_data = get_plugin_data( __FILE__ );

// Defines plugin constants.
define( 'TAMBAR_DIR',        plugin_dir_path( __FILE__ ) );
define( 'TAMBAR_ASSETS_DIR', plugin_dir_url(  __FILE__ ) . 'assets/' );
define( 'TAMBAR_VERSION',    $tambar_plugin_data[ 'Version' ] );

// Connects styles and scripts.
add_action( 'wp_enqueue_scripts', function () {
	if ( is_admin_bar_showing() ) {
		wp_enqueue_style(  'tambar-styles',  TAMBAR_ASSETS_DIR . 'css/tambar.css', [], TAMBAR_VERSION, 'all' );
		wp_enqueue_script( 'tambar-scripts', TAMBAR_ASSETS_DIR . 'js/tambar.js',   [], TAMBAR_VERSION, false );
	}
});

// Adds settings link to plugin's card on Plugins page.
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), function ( $links ) {
	array_unshift( $links, sprintf( '<a href="%s">%s</a>',
		menu_page_url( 'tambar', false ),
		__( 'Settings', 'tambar' ),
	));

	return $links;
});

// Adds settings page.
add_action( 'admin_menu', function () {
	add_options_page(
		__( 'Tambar Settings', 'tambar' ),
		__( 'Tambar', 'tambar' ),
		'manage_options',
		'tambar',
		fn ( $args ) => include TAMBAR_DIR . 'parts/page.php',
	);
});

// Registers settings options.
add_action( 'init', function () {
	// Position.
	register_setting( 'tambar', 'tambar_desktop_position', [
		'default' => 'bottom',
		'type'    => 'string',
	]);
	register_setting( 'tambar', 'tambar_mobile_position', [
		'default' => 'bottom',
		'type'    => 'string',
	]);

	// Swticher.
	register_setting( 'tambar', 'tambar_is_switcher_enable', [
		'default' => true,
		'type'    => 'boolean',
	]);
	register_setting( 'tambar', 'tambar_desktop_switcher_position', [
		'default' => 'left',
		'type'    => 'string',
	]);
	register_setting( 'tambar', 'tambar_mobile_switcher_position', [
		'default' => 'left',
		'type'    => 'string',
	]);
});

// Adds options sections and fields.
add_action( 'admin_init', function () {
	// Position.
	add_settings_section( 'tambar_section_position', __( 'Admin bar position', 'tambar' ),
		fn ( $args ) => include TAMBAR_DIR . 'parts/section.php',
		'tambar',
	);
	add_settings_field( 'tambar_desktop_position', __( 'Desktop', 'tambar' ),
		fn ( $args ) => include TAMBAR_DIR . 'parts/select.php',
		'tambar',
		'tambar_section_position',
		[
			'label_for' => 'tambar_desktop_position',
			'values'    => [
				'top'    => __( 'Top', 'tambar' ),
				'bottom' => __( 'Bottom', 'tambar' ),
			],
		],
	);
	add_settings_field( 'tambar_mobile_position', __( 'Mobile', 'tambar' ),
		fn ( $args ) => include TAMBAR_DIR . 'parts/select.php',
		'tambar',
		'tambar_section_position',
		[
			'label_for' => 'tambar_mobile_position',
			'values'    => [
				'top'    => __( 'Top', 'tambar' ),
				'bottom' => __( 'Bottom', 'tambar' ),
			],
		],
	);

	// Switcher.
	add_settings_section( 'tambar_section_switcher', __( 'Admin bar switcher', 'tambar' ),
		fn ( $args ) => include TAMBAR_DIR . 'parts/section.php',
		'tambar',
	);
	add_settings_field( 'tambar_is_switcher_enable', __( 'Enable', 'tambar' ),
		fn ( $args ) => include TAMBAR_DIR . 'parts/checkbox.php',
		'tambar',
		'tambar_section_switcher',
		[
			'label_for' => 'tambar_is_switcher_enable',
			'label'     => __( 'Is admin bar switcher visible', 'tambar' ),
		],
	);
	add_settings_field( 'tambar_desktop_switcher_position', __( 'Desktop', 'tambar' ),
		fn ( $args ) => include TAMBAR_DIR . 'parts/select.php',
		'tambar',
		'tambar_section_switcher',
		[
			'label_for' => 'tambar_desktop_switcher_position',
			'values'    => [
				'left'  => __( 'Left', 'tambar' ),
				'right' => __( 'Right', 'tambar' ),
			],
			'description' => __( 'Position of the admin bar switcher on desktop', 'tambar' ),
		],
	);
	add_settings_field( 'tambar_mobile_switcher_position', __( 'Mobile', 'tambar' ),
		fn ( $args ) => include TAMBAR_DIR . 'parts/select.php',
		'tambar',
		'tambar_section_switcher',
		[
			'label_for' => 'tambar_mobile_switcher_position',
			'values'    => [
				'left'  => __( 'Left', 'tambar' ),
				'right' => __( 'Right', 'tambar' ),
			],
			'description' => __( 'Position of the admin bar switcher on mobile', 'tambar' ),
		],
	);
});

// Sets <body> tag classes.
add_filter( 'body_class', function ( $classes ) {
	if ( is_admin_bar_showing() ) {
		// Admin bar classes.
		$desktop_position = get_option( 'tambar_desktop_position' );
		$mobile_position  = get_option( 'tambar_mobile_position' );
		
		$classes[] = "tambar-desktop-$desktop_position";
		$classes[] = "tambar-mobile-$mobile_position";
		
		// Checks switcher options.
		$is_switcher_enable = get_option( 'tambar_is_switcher_enable' );
	
		if ( $is_switcher_enable ) {
			// Switcher classes.
			$switcher_desktop_position = get_option( 'tambar_desktop_switcher_position' );
			$switcher_mobile_position  = get_option( 'tambar_mobile_switcher_position' );
		
			$classes[] = "tambar-desktop-switcher-$switcher_desktop_position";
			$classes[] = "tambar-mobile-switcher-$switcher_mobile_position";

			// Hides via cookie value.
			if ( isset( $_COOKIE[ 'tambar-is-hidden' ] ) && boolval( $_COOKIE[ 'tambar-is-hidden' ] ) ) {
				$classes[] = 'tambar-hidden';
			}
		}
	}

	return $classes;
});

// Prints switcher.
add_action( 'wp_before_admin_bar_render', function () {
	if ( is_admin() ) return;
	
	$is_switcher_enable = get_option( 'tambar_is_switcher_enable' );

	if ( $is_switcher_enable ) {
		include TAMBAR_DIR . 'parts/switcher.php';
	}
});