<?php

/*
 * Plugin Name:       Tambar – Bottom Admin Bar
 * Description:       Easily change the position of the admin bar on the frontend.
 * Version:           2.3.4
 * Requires PHP:      7.3
 * Requires at least: 6.0
 * Tested up to:      6.8
 * Author:            Yan Metelitsa
 * Author URI:        https://yanmet.com/
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       tambar
 */

// Exits if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

// Gets plugin data.
if ( ! function_exists( 'get_plugin_data' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
$tambar_plugin_data = get_plugin_data( __FILE__, true, false );

// Defines plugin constants.
define( 'TAMBAR_DIR',              plugin_dir_path( __FILE__ ) );
define( 'TAMBAR_ASSETS_DIR',       plugin_dir_url(  __FILE__ ) . 'assets/' );
define( 'TAMBAR_VERSION',          $tambar_plugin_data[ 'Version' ] );
define( 'TAMBAR_DEFAULT_SETTINGS', [
	'tambar_desktop_position' => 'bottom',
	'tambar_mobile_position'  => 'bottom',

	'tambar_is_switcher_enable'        => true,
	'tambar_desktop_switcher_position' => 'left',
	'tambar_mobile_switcher_position'  => 'right',

	'tambar_show_for_role' => true,
]);

// Connects styles and scripts.
add_action( 'wp_enqueue_scripts', function () {
	if ( is_admin_bar_showing() ) {
		wp_enqueue_style(  'tambar-styles',  TAMBAR_ASSETS_DIR . 'css/tambar.css', [], TAMBAR_VERSION, 'all' );
		wp_enqueue_script( 'tambar-scripts', TAMBAR_ASSETS_DIR . 'js/tambar.js',   [], TAMBAR_VERSION, false );
	}
});

// Adds settings link to plugin's card on Plugins page.
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), function ( $links ) {
	if ( current_user_can( 'manage_options' ) ) {
		array_unshift( $links, sprintf( '<a href="%s">%s</a>',
			menu_page_url( 'tambar', false ),
			__( 'Settings', 'tambar' ),
		));
	}

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

// Registers settings and adds options fields.
add_action( 'admin_init', function () {
	// Admin Bar Position section.
	add_settings_section( 'position', __( 'Admin Bar Position', 'tambar' ),
		fn ( $args ) => include TAMBAR_DIR . 'parts/section.php',
		'tambar',
	);

	// phpcs:ignore
	register_setting( 'tambar', 'tambar_desktop_position', [
		'type'    => 'string',
		'default' => TAMBAR_DEFAULT_SETTINGS[ 'tambar_desktop_position' ],
	]);
	add_settings_field( 'tambar_desktop_position', __( 'Desktop', 'tambar' ),
		fn ( $args ) => include TAMBAR_DIR . 'parts/select.php',
		'tambar',
		'position',
		[
			'label_for' => 'tambar_desktop_position',
			'values'    => [
				'top'    => __( 'Top', 'tambar' ),
				'bottom' => __( 'Bottom', 'tambar' ),
			],
		],
	);

	// phpcs:ignore
	register_setting( 'tambar', 'tambar_mobile_position', [
		'type'    => 'string',
		'default' => TAMBAR_DEFAULT_SETTINGS[ 'tambar_mobile_position' ],
	]);
	add_settings_field( 'tambar_mobile_position', __( 'Mobile', 'tambar' ),
		fn ( $args ) => include TAMBAR_DIR . 'parts/select.php',
		'tambar',
		'position',
		[
			'label_for' => 'tambar_mobile_position',
			'values'    => [
				'top'    => __( 'Top', 'tambar' ),
				'bottom' => __( 'Bottom', 'tambar' ),
			],
		],
	);

	// Switcher section.
	add_settings_section( 'switcher', __( 'Switcher', 'tambar' ),
		fn ( $args ) => include TAMBAR_DIR . 'parts/section.php',
		'tambar',
	);

	// phpcs:ignore
	register_setting( 'tambar', 'tambar_is_switcher_enable', [
		'type'    => 'boolean',
		'default' => TAMBAR_DEFAULT_SETTINGS[ 'tambar_is_switcher_enable' ],
	]);
	add_settings_field( 'tambar_is_switcher_enable', __( 'Enable', 'tambar' ),
		fn ( $args ) => include TAMBAR_DIR . 'parts/checkbox.php',
		'tambar',
		'switcher',
		[
			'label_for' => 'tambar_is_switcher_enable',
			'label'     => __( 'Display switcher', 'tambar' ),
		],
	);

	// phpcs:ignore
	register_setting( 'tambar', 'tambar_desktop_switcher_position', [
		'type'    => 'string',
		'default' => TAMBAR_DEFAULT_SETTINGS[ 'tambar_desktop_switcher_position' ],
	]);
	add_settings_field( 'tambar_desktop_switcher_position', __( 'Desktop', 'tambar' ),
		fn ( $args ) => include TAMBAR_DIR . 'parts/select.php',
		'tambar',
		'switcher',
		[
			'label_for' => 'tambar_desktop_switcher_position',
			'values'    => [
				'left'  => __( 'Left', 'tambar' ),
				'right' => __( 'Right', 'tambar' ),
			],
			'description' => __( 'Position of the admin bar switcher on desktop.', 'tambar' ),
		],
	);

	// phpcs:ignore
	register_setting( 'tambar', 'tambar_mobile_switcher_position', [
		'type'    => 'string',
		'default' => TAMBAR_DEFAULT_SETTINGS[ 'tambar_mobile_switcher_position' ],
	]);
	add_settings_field( 'tambar_mobile_switcher_position', __( 'Mobile', 'tambar' ),
		fn ( $args ) => include TAMBAR_DIR . 'parts/select.php',
		'tambar',
		'switcher',
		[
			'label_for' => 'tambar_mobile_switcher_position',
			'values'    => [
				'left'  => __( 'Left', 'tambar' ),
				'right' => __( 'Right', 'tambar' ),
			],
			'description' => __( 'Position of the admin bar switcher on mobile.', 'tambar' ),
		],
	);

	// Roles section.
	add_settings_section( 'roles', __( 'Show Admin Bar for Roles', 'tambar' ),
		fn ( $args ) => include TAMBAR_DIR . 'parts/section.php',
		'tambar',
	);

	// phpcs:ignore
	register_setting( 'tambar', "tambar_show_for_role", [
		'type'    => 'boolean',
		'default' => TAMBAR_DEFAULT_SETTINGS[ 'tambar_show_for_role' ],
	]);

	foreach ( wp_roles()->roles as $role => $role_data ) {
		$role_name = $role_data[ 'name' ];

		add_settings_field( "tambar_show_for_role_$role", translate_user_role( $role_name ),
			fn ( $args ) => include TAMBAR_DIR . 'parts/checkbox.php',
			'tambar',
			'roles',
			[
				'label_for'   => "tambar_show_for_role[$role]",
				'option_name' => 'tambar_show_for_role',
				'option_key'  => $role,
			],
		);
	}
});

// Hides admin bar for roles.
add_filter( 'show_admin_bar', function ( $show_admin_bar ) {
	if ( ! is_user_logged_in() ) {
		return $show_admin_bar;
	}

	$current_user_roles = wp_get_current_user()->roles;
	$show_for_roles     = get_option( 'tambar_show_for_role', TAMBAR_DEFAULT_SETTINGS[ 'tambar_show_for_role' ] );

	if ( ! is_array( $show_for_roles ) ) {
		return $show_admin_bar;
	}

	$show_for_roles = array_keys( $show_for_roles );

	if ( empty( array_intersect( $current_user_roles, $show_for_roles ) ) ) {
		return false;
	}

	return $show_admin_bar;
});

// Sets <body> tag classes.
add_filter( 'body_class', function ( $classes ) {
	if ( ! is_admin_bar_showing() ) {
		return $classes;
	}

	// Adds admin bar classes.
	$desktop_position = get_option( 'tambar_desktop_position', TAMBAR_DEFAULT_SETTINGS[ 'tambar_desktop_position' ] );
	$mobile_position  = get_option( 'tambar_mobile_position', TAMBAR_DEFAULT_SETTINGS[ 'tambar_mobile_position' ] );
	
	$classes[] = "tambar-desktop-$desktop_position";
	$classes[] = "tambar-mobile-$mobile_position";
	
	// Is switcher enable.
	$is_switcher_enable = get_option( 'tambar_is_switcher_enable', TAMBAR_DEFAULT_SETTINGS[ 'tambar_is_switcher_enable' ] );

	if ( ! $is_switcher_enable ) {
		return $classes;
	}

	// Adds switcher classes.
	$switcher_desktop_position = get_option( 'tambar_desktop_switcher_position', TAMBAR_DEFAULT_SETTINGS[ 'tambar_desktop_switcher_position' ] );
	$switcher_mobile_position  = get_option( 'tambar_mobile_switcher_position', TAMBAR_DEFAULT_SETTINGS[ 'tambar_mobile_switcher_position' ] );

	$classes[] = "tambar-desktop-switcher-$switcher_desktop_position";
	$classes[] = "tambar-mobile-switcher-$switcher_mobile_position";

	// Hides via cookie value.
	if ( isset( $_COOKIE[ 'tambar-is-hidden' ] ) && boolval( $_COOKIE[ 'tambar-is-hidden' ] ) ) {
		$classes[] = 'tambar-hidden';
	}

	return $classes;
});

// Prints switcher.
add_action( 'wp_before_admin_bar_render', function () {
	if ( is_admin() ) return;
	
	$is_switcher_enable = get_option( 'tambar_is_switcher_enable', TAMBAR_DEFAULT_SETTINGS[ 'tambar_is_switcher_enable' ] );

	if ( $is_switcher_enable ) {
		include TAMBAR_DIR . 'parts/switcher.php';
	}
});