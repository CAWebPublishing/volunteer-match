<?php
/**
 * Plugin Name: Volunteer Match Shortcode
 * Plugin URI: "https://github.com/Danny-Guzman/volunteer-match/"
 * Description: Adds a shortcode that display a VolunteerMatch.org search dashboard and opportunities results.
 * Author: Danny Guzman
 * Version: 1.0.7
 * Author URI: "https://github.com/Danny-Guzman/"
 *
 * @package VolunteerMatch
 */

define( 'VOLUNTEER_MATCH_URL', plugin_dir_url( __FILE__ ) );
define( 'VOLUNTEER_MATCH_DIR', plugin_dir_path( __FILE__ ) );

add_action( 'init', 'volunteer_match_init' );
add_action( 'admin_init', 'volunteer_match_admin_init' );
add_action( 'wp_enqueue_scripts', 'volunteer_match_wp_enqueue_scripts' );
add_action( 'admin_enqueue_scripts', 'volunteer_match_admin_enqueue_scripts' );

/**
 * Init
 * Triggered before any other hook when a user accesses the admin area.
 * Note, this does not just run on user-facing admin screens.
 * It runs on admin-ajax.php and admin-post.php as well.
 *
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/admin_init
 * @return void
 */
function volunteer_match_init() {
	/* Include Functionality */
	foreach ( glob( __DIR__ . '/inc/*.php' ) as $file ) {
		require_once $file;
	}
}

/**
 * Admin Init
 *
 * Triggered before any other hook when a user accesses the admin area.
 * Note, this does not just run on user-facing admin screens.
 * It runs on admin-ajax.php and admin-post.php as well.
 *
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/admin_init
 * @return void
 */
function volunteer_match_admin_init() {
	require_once VOLUNTEER_MATCH_DIR . 'core/class-volunteer-match-plugin-update.php';
}

/**
 * Enqueue Scripts and Styles on the Front End
 *
 * @link https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
 *
 * @return void
 */
function volunteer_match_wp_enqueue_scripts() {
	$version      = get_plugin_data( __FILE__ )['Version'];
	$frontend_css = volunteer_match_get_min_file( 'css/frontend.css' );
	$frontend_js  = volunteer_match_get_min_file( 'js/frontend.js' );

	// Enqueue Scripts.
	wp_register_script( 'volunteer-match-core-script', $frontend_js, array(), $version, true );

	wp_localize_script( 'volunteer-match-core-script', 'volunteer_match_args', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

	wp_enqueue_script( 'volunteer-match-core-script' );

	// Enqueue FrontEnd Style.
	wp_enqueue_style( 'volunteer-match-frontend-styles', $frontend_css, array(), $version );

}

/**
 * Admin Enqueue Scripts and Styles
 *
 * @link https://developer.wordpress.org/reference/hooks/admin_enqueue_scripts/
 *
 * @param  string $hook The current admin page.
 * @return void
 */
function volunteer_match_admin_enqueue_scripts( $hook ) {
	$pages   = array( 'toplevel_page_volunteer-match-options' );
	$version = get_plugin_data( __FILE__ )['Version'];

	if ( in_array( $hook, $pages, true ) ) {

		$admin_css = volunteer_match_get_min_file( 'css/admin.css' );
		$admin_js  = volunteer_match_get_min_file( 'js/admin.js', 'js' );

		wp_enqueue_script( 'jquery' );

		wp_register_script( 'volunteer-match-admin-scripts', $admin_js, array( 'jquery' ), $version, true );

		wp_localize_script( 'volunteer-match-admin-scripts', 'volunteer_match_args', array( 'categories' => volunteer_match_categories() ) );

		wp_enqueue_script( 'volunteer-match-admin-scripts' );

		/*
		Bootstrap 4 Toggle
		https://gitbrent.github.io/bootstrap4-toggle/
		*/
		wp_enqueue_script( 'volunteer-match-boot1', 'https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js', array( 'jquery' ), '3.6.1', true );

		/* Enqueue Styles */
		wp_enqueue_style( 'volunteer-match-boot1-toggle', 'https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css', array(), '3.6.1' );
		wp_enqueue_style( 'volunteer-match-admin-styles', $admin_css, array(), $version );

	}

}
