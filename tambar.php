<?php

/*
 * Plugin Name:       Tambar – Bottom Admin Bar
 * Description:       Easily change the admin bar position on your site or hide it for specific user roles.
 * Version:           3.0.2
 * Requires PHP:      7.4
 * Requires at least: 6.0
 * Tested up to:      6.8
 * Author:            Yan Metelitsa
 * Author URI:        https://yanmet.com/
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       tambar
 */

// Exits if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Tambar plugin main class.
 */
final class Tambar {
	/**
	 * Is Tambar initialized.
	 * 
	 * @var bool
	 */
	private static bool $is_initialized = false;

	/**
	 * Tambar version.
	 * 
	 * @var string
	 */
	private static string $version;

	/**
	 * Tambar default options.
	 * 
	 * @var array
	 */
	private static array $default_options = [
		'desktop_position' => 'bottom',
		'mobile_position'  => 'bottom',

		'is_switcher_enable'        => true,
		'desktop_switcher_position' => 'left',
		'mobile_switcher_position'  => 'right',

		'show_for_role' => true,
	];

	/**
	 * Inits Tambar.
	 * 
	 * @return void
	 */
	public static function init () : void {
		// Exits if already initialized.
		if ( self::$is_initialized ) {
			return;
		}

		// Get plugin data.
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin_data = get_plugin_data( __FILE__, true, false );

		self::$version = $plugin_data[ 'Version' ];

		// Manages plugin action links.
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), function ( array $actions ) : array {
			if ( current_user_can( 'manage_options' ) ) {
				$settings_link = sprintf( '<a href="%s">%s</a>',
					menu_page_url( 'tambar', false ),
					__( 'Settings', 'tambar' ),
				);
				
				array_unshift( $actions, $settings_link );
			}

			return $actions;
		});

		// Adds options page.
		add_action( 'admin_menu', function () {
			add_options_page(
				__( 'Tambar Settings', 'tambar' ),
				__( 'Tambar', 'tambar' ),
				'manage_options',
				'tambar',
				fn ( $args ) => include self::get_file_path( 'parts/options-page.php' ),
			);
		});

		// Registers settings.
		add_action( 'admin_init', function () {
			// Position.
			self::register_settings( 'position', __( 'Admin Bar Position', 'tambar' ), [
				'desktop_position' => [
					[
						'title'    => __( 'Desktop', 'tambar' ),
						'template' => 'select',
						'args'     => [
							'values' => [
								'top'    => __( 'Top', 'tambar' ),
								'bottom' => __( 'Bottom', 'tambar' ),
							],
						],
					],
				],
				'mobile_position' => [
					[
						'title'    => __( 'Mobile', 'tambar' ),
						'template' => 'select',
						'args'     => [
							'values' => [
								'top'    => __( 'Top', 'tambar' ),
								'bottom' => __( 'Bottom', 'tambar' ),
							],
						],
					],
				],
			]);
	
			// Switcher.
			self::register_settings( 'switcher', __( 'Admin Bar Switcher', 'tambar' ), [
				'is_switcher_enable' => [
					[
						'title'    => __( 'Enable', 'tambar' ),
						'template' => 'checkbox',
						'args'     => [
							'label' => __( 'Show Switcher', 'tambar' ),
						],
					],
				],
				'desktop_switcher_position' => [
					[
						'title'    => __( 'Desktop', 'tambar' ),
						'template' => 'select',
						'args'     => [
							'values' => [
								'left'  => __( 'Left', 'tambar' ),
								'right' => __( 'Right', 'tambar' ),
							],
						],
					],
				],
				'mobile_switcher_position' => [
					[
						'title'    => __( 'Mobile', 'tambar' ),
						'template' => 'select',
						'args'     => [
							'values' => [
								'left'  => __( 'Left', 'tambar' ),
								'right' => __( 'Right', 'tambar' ),
							],
						],
					],
				],
			]);

			// Roles.
			$roles = wp_roles()->role_names;
			self::register_settings( 'roles', __( 'Show Admin Bar for Roles', 'tambar' ), [
				'show_for_role' => array_combine(
					array_keys( $roles ),
					array_map( function ( $role_slug, $role_name ) {
						return [
							'title'    => translate_user_role( $role_name ),
							'template' => 'checkbox',
							'args'     => [
								'label_for'   => "tambar_show_for_role[$role_slug]",
								'option_name' => 'tambar_show_for_role',
								'option_key'  => $role_slug,
							],
						];
					}, array_keys( $roles ), $roles ),
				),
			]);
		});

		// Enqueues styles and scripts.
		add_action( 'wp_enqueue_scripts', function () {
			if ( is_admin_bar_showing() ) {
				wp_enqueue_style(  'tambar-styles',  self::get_file_url( 'assets/css/tambar.css' ), [], self::$version, 'all' );
				wp_enqueue_script( 'tambar-scripts', self::get_file_url( 'assets/js/tambar.js' ),   [], self::$version, false );
			}
		});

		// Hides admin bar for roles.
		add_filter( 'show_admin_bar', function ( $show_admin_bar ) {
			// Return if user not logged in.
			if ( ! is_user_logged_in() ) {
				return $show_admin_bar;
			}

			// Get data.
			$current_user_roles = wp_get_current_user()->roles;
			$show_for_roles     = self::get_option( 'show_for_role' );

			if ( ! is_array( $show_for_roles ) ) {
				return $show_admin_bar;
			}

			if ( empty( array_intersect( $current_user_roles, array_keys( $show_for_roles ) ) ) ) {
				return false;
			}

			return $show_admin_bar;
		});

		// Sets <body> classes.
		add_filter( 'body_class', function ( $classes ) {
			// Returns if admin bar not showing.
			if ( ! is_admin_bar_showing() ) {
				return $classes;
			}

			// Adds admin bar classes.
			$desktop_position = self::get_option( 'desktop_position' );
			$mobile_position  = self::get_option( 'mobile_position' );
			
			$classes[] = "tambar-desktop-{$desktop_position}";
			$classes[] = "tambar-mobile-{$mobile_position}";
			
			// Returns if switcher not enabled.
			if ( ! self::get_option( 'is_switcher_enable' ) ) {
				return $classes;
			}

			// Adds switcher classes.
			$switcher_desktop_position = self::get_option( 'desktop_switcher_position' );
			$switcher_mobile_position  = self::get_option( 'mobile_switcher_position' );

			$classes[] = "tambar-switcher-desktop-{$switcher_desktop_position}";
			$classes[] = "tambar-switcher-mobile-{$switcher_mobile_position}";

			// Hides admin bar by cookie value.
			if ( isset( $_COOKIE[ 'tambar-is-hidden' ] ) && boolval( $_COOKIE[ 'tambar-is-hidden' ] ) ) {
				$classes[] = 'tambar-hidden';
			}

			return $classes;
		});

		// Prints switcher.
		add_action( 'wp_before_admin_bar_render', function () {
			if ( is_admin() ) {
				return;
			}

			if ( self::get_option( 'is_switcher_enable' ) ) {
				echo '<div id="tambar-switcher" onclick="tambarToggle()"></div>';
			}
		});

		self::$is_initialized = true;
	}

	/**
	 * Retrieves Tambar file path.
	 * 
	 * @param string $file File name.
	 * 
	 * @return string Tambar file path.
	 */
	private static function get_file_path ( string $file ) : string {
		return plugin_dir_path( __FILE__ ) . $file;
	}

	/**
	 * Retrieves Tambar file URL.
	 * 
	 * @param string $file File name.
	 * 
	 * @return string Tambar file URL.
	 */
	private static function get_file_url ( string $file ) : string {
		return plugin_dir_url( __FILE__ ) . $file;
	}

	/**
	 * Registers Tambar settings.
	 * 
	 * @param string $section_slug  Section slug.
	 * @param string $section_title Section title.
	 * @param array {
	 * 		array {
	 * 			title:    string,
	* 			template: string,
	* 			args:     array,
	 * 		}[]
	 * }[] $settings Section settings.
	 * 
	 * @return void
	 */
	private static function register_settings ( string $section_slug, string $section_title, array $settings ) : void {
		// Add section.
		add_settings_section( $section_slug, $section_title, function ( $args ) {
			include self::get_file_path( 'parts/options-section.php' );
		}, 'tambar' );

		// Section settings loop.
		foreach ( $settings as $setting_slug => $setting_fields ) {
			$setting_slug = "tambar_{$setting_slug}";
			
			// Register setting.
			register_setting( 'tambar', $setting_slug, [
				'default' => self::$default_options[ $setting_slug ] ?? '',
			]);
			
			// Add fields.
			foreach ( $setting_fields as $field_postfix => $field ) {	
				$template   = (string) $field[ 'template' ];
				$field_slug = $setting_slug . ( ! is_numeric( $field_postfix ) ? "_{$field_postfix}" : '' );

				add_settings_field( $field_slug, $field[ 'title' ],
					fn ( $args ) => include self::get_file_path( "parts/{$template}.php" ),
					'tambar', $section_slug, array_merge([
						'label_for' => $setting_slug,
					], $field[ 'args' ] ),
				);
			}
		}
	}

	/**
	 * Retrieves Tambar option value or default value.
	 * 
	 * @param string $option Option name.
	 */
	public static function get_option ( string $option ) {
		if ( ! str_starts_with( $option, 'tambar_' ) ) {
			$option = "tambar_{$option}";
		}

		$default_value = self::$default_options[
			preg_replace( '/tambar_/', '', $option, 1 )
		] ?? '';

		return get_option( $option, $default_value );
	}
}

Tambar::init();