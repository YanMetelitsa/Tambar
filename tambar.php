<?php

/*
 * Plugin Name:       Tambar – Bottom Admin Bar
 * Description:       Plugin that allows to change position of the admin bar.
 * Version:           2.2.0
 * Tested up to:      6.6.1
 * Requires at least: 6.4
 * Author:            Yan Metelitsa
 * Author URI:        https://yanmet.com/
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       tambar
 */

/** Exit if accessed directly */
if ( !defined( 'ABSPATH' ) ) exit;

/** Get plugin data */
if( !function_exists( 'get_plugin_data' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
$tambar_plugin_data = get_plugin_data( __FILE__ );

/** Define constants */
define( 'TAMBAR_DIR',        plugin_dir_path( __FILE__ ) );
define( 'TAMBAR_ASSETS_DIR', plugin_dir_url(  __FILE__ ) . 'assets/' );
define( 'TAMBAR_VERSION',    $tambar_plugin_data[ 'Version' ] );

/** Connects scripts */
add_action( 'wp_enqueue_scripts', function () {
	if ( is_admin_bar_showing() ) {
		wp_enqueue_style(  'tambar-styles',  TAMBAR_ASSETS_DIR . 'css/tambar.css', [], TAMBAR_VERSION, 'all' );
		wp_enqueue_script( 'tambar-scripts', TAMBAR_ASSETS_DIR . 'js/tambar.js',   [], TAMBAR_VERSION, false );
	}
});

/** Adds options link */
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), function ( $links ) {
	$url          = get_admin_url( null, 'options-general.php?page=tambar' );
	$options_link = '<a href="' . $url . '">' . __( 'Settings', 'tambar' ) . '</a>';

	array_unshift( $links, $options_link );

	return $links;
});

/** Adds options page */
add_action( 'admin_menu', function () {
	add_options_page(
		__( 'Tambar settings', 'tambar' ),				// Page title
		__( 'Tambar', 'tambar' ),						// Menu item title
		'manage_options',								// Capabilities
		'tambar',										// Slug
		function ( $args ) {							// Print call back
			include TAMBAR_DIR . 'parts/page.php';
		},
	);
});

/** Registers options */
add_action( 'init', function () {
	register_setting(
		'tambar',								// Option group
		'tambar_desktop_position',				// Option slug
		[
			'default' => 'bottom',				// Default value
			'type'    => 'string',				// Value type
		]
	);
	register_setting(
		'tambar',								// Option group
		'tambar_mobile_position',				// Option name
		[
			'default' => 'bottom',				// Default value
			'type'    => 'string',				// Value type
		]
	);

	register_setting(
		'tambar',								// Option group
		'tambar_is_switcher_enable',			// Option slug
		[
			'default' => true,					// Default value
			'type'    => 'boolean',				// Value type
		]
	);
	register_setting(
		'tambar',								// Option group
		'tambar_desktop_switcher_position',		// Option slug
		[
			'default' => 'left',				// Default value
			'type'    => 'string',				// Value type
		]
	);
	register_setting(
		'tambar',								// Option group
		'tambar_mobile_switcher_position',		// Option slug
		[
			'default' => 'left',				// Default value
			'type'    => 'string',				// Value type
		]
	);
});

/** Adds options sections and fields */
add_action( 'admin_init', function () {
	/** Add sections */
	add_settings_section(
		'tambar_section_position',						// Section slug
		__( 'Admin bar position', 'tambar' ),			// Section title
		function ( $args ) {							// Print call back
			include TAMBAR_DIR . 'parts/section.php';
		},
		'tambar',										// Section page slug
	);
	add_settings_section(
		'tambar_section_switcher',						// Section slug
		__( 'Admin bar switcher', 'tambar' ),			// Section title
		function ( $args ) {							// Print call back
			include TAMBAR_DIR . 'parts/section.php';
		},
		'tambar',										// Section page slug
	);

	/** Add fields */
	add_settings_field(
		'tambar_desktop_position',						// Option slug
		__( 'Desktop', 'tambar' ),						// Option title
		function ( $args ) {							// Print call back
			include TAMBAR_DIR . 'parts/select.php';
		},
		'tambar',										// Option page
		'tambar_section_position',						// Option section
		[
			'label_for' => 'tambar_desktop_position',
			'values'    => [
				'top'    => __( 'Top', 'tambar' ),
				'bottom' => __( 'Bottom', 'tambar' ),
			],
		],
	);
	add_settings_field(
		'tambar_mobile_position',						// Option slug
		__( 'Mobile', 'tambar' ),						// Option title
		function ( $args ) {							// Print call back
			include TAMBAR_DIR . 'parts/select.php';
		},
		'tambar',										// Option page
		'tambar_section_position',						// Option section
		[
			'label_for' => 'tambar_mobile_position',
			'values'    => [
				'top'    => __( 'Top', 'tambar' ),
				'bottom' => __( 'Bottom', 'tambar' ),
			],
		],
	);

	add_settings_field(
		'tambar_is_switcher_enable',					// Option slug
		__( 'Enable', 'tambar' ),						// Option title
		function ( $args ) {							// Print call back
			include TAMBAR_DIR . 'parts/checkbox.php';
		},
		'tambar',										// Option page
		'tambar_section_switcher',						// Option section
		[
			'label_for' => 'tambar_is_switcher_enable',
			'label'     => __( 'Is admin bar switcher visible', 'tambar' ),
		],
	);
	add_settings_field(
		'tambar_desktop_switcher_position',				// Option slug
		__( 'Desktop', 'tambar' ),						// Option title
		function ( $args ) {							// Print call back
			include TAMBAR_DIR . 'parts/select.php';
		},
		'tambar',										// Option page
		'tambar_section_switcher',						// Option section
		[
			'label_for' => 'tambar_desktop_switcher_position',
			'values'    => [
				'left'  => __( 'Left', 'tambar' ),
				'right' => __( 'Right', 'tambar' ),
			],
			'description' => __( 'Position of the admin bar switcher on desktop', 'tambar' ),
		],
	);
	add_settings_field(
		'tambar_mobile_switcher_position',				// Option slug
		__( 'Mobile', 'tambar' ),						// Option title
		function ( $args ) {							// Print call back
			include TAMBAR_DIR . 'parts/select.php';
		},
		'tambar',										// Option page
		'tambar_section_switcher',						// Option section
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

/** Sets body class */
add_filter( 'body_class', function ( $classes ) {
	if ( is_admin_bar_showing() ) {
		/** Admin bar classes */
		$desktop_position   = get_option( 'tambar_desktop_position' );
		$mobile_position    = get_option( 'tambar_mobile_position' );
		
		$classes[] = 'tambar-desktop-' . $desktop_position;
		$classes[] = 'tambar-mobile-' . $mobile_position;
		
		/** Switcher classes */
		$switcher_desktop_position = get_option( 'tambar_desktop_switcher_position' );
		$switcher_mobile_position  = get_option( 'tambar_mobile_switcher_position' );
	
		$classes[] = 'tambar-desktop-switcher-' . $switcher_desktop_position;
		$classes[] = 'tambar-mobile-switcher-' . $switcher_mobile_position;
		
		/** Show/hide switcher and admin bar classes */
		$is_switcher_enable = get_option( 'tambar_is_switcher_enable' );
	
		if ( isset( $_COOKIE[ 'tambar-is-hidden' ] ) && boolval( $_COOKIE[ 'tambar-is-hidden' ] ) ) {
			if ( $is_switcher_enable ) {
				$classes[] = 'tambar-hidden';
			}
		}
	}

	return $classes;
});

/** Prints switcher */
add_action( 'wp_before_admin_bar_render', function () {
	$is_switcher_enable = get_option( 'tambar_is_switcher_enable' );

	if ( $is_switcher_enable ) {
		echo '<div id="tambar-switcher" onclick="tambarSwitcherClick()"><span></span></div>';
	}
});