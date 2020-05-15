<?php
/**
 * Main Options File
 *
 * @package VolunteerMatch
 */

add_action( 'admin_menu', 'volunteer_match_admin_menu' );

/**
 * Administration Menu Setup
 * Fires before the administration menu loads in the admin.
 *
 * @link https://developer.wordpress.org/reference/hooks/admin_menu/
 * @return void
 */
function volunteer_match_admin_menu() {
	global $submenu;

	/* Add VolunteerMatch Options */
	add_menu_page(
		'Volunteer Match',
		'Volunteer Match',
		'manage_options',
		'volunteer-match-options',
		'volunteer_match_option_page',
		'https://d3bl5qcndhcx94.cloudfront.net/rel193-ab5e81c8/favicon-16x16.png',
		6
	);

}

/**
 * Setup Options Menu
 *
 * @return void
 */
function volunteer_match_option_page() {
	/* The actual menu file */
	require_once VOLUNTEER_MATCH_DIR . '/partials/options.php';
}

/**
 * Save Options
 *
 * @param  array $values Option values.
 *
 * @return void
 */
function volunteer_match_save_options( $values = array() ) {
	update_option( 'volunteer_match_api_key', $values['volunteer_match_api_key'] );
	update_option( 'volunteer_match_opp_endpoint', esc_url_raw( $values['volunteer_match_opp_endpoint'] ) );
	
	$volunteer_match_opp_endpoint_graphql = isset( $values['volunteer_match_opp_endpoint_graphql'] ) ? true : false;
	update_option('volunteer_match_opp_endpoint_graphql', $volunteer_match_opp_endpoint_graphql);
	update_option('volunteer_match_opp_endpoint_environment', $values['volunteer_match_opp_endpoint_environment']);

	update_option( 'volunteer_match_create_connection_endpoint', esc_url_raw( $values['volunteer_match_create_connection_endpoint'] ) );
	
	$volunteer_match_create_connection_endpoint_graphql = isset( $values['volunteer_match_create_connection_endpoint_graphql'] ) ? true : false;
	update_option('volunteer_match_create_connection_endpoint_graphql', $volunteer_match_create_connection_endpoint_graphql);
	update_option('volunteer_match_create_connection_endpoint_environment', $values['volunteer_match_create_connection_endpoint_environment']);

	$interests = array();
	if ( isset( $values['volunteer_match_interests'] ) ) {
		$ints = $values['volunteer_match_interests'];
		$cats = $values['volunteer_match_interests_cats'];
		foreach ( $ints as $i => $int ) {
			$cat         = array_shift( $cats );
			$interests[] = array(
				'cats'  => implode( ',', $cat ),
				'title' => $int,
			);
		}
	}
	update_option( 'volunteer_match_interests', $interests );
	update_option( 'volunteer_match_radius', explode( ',', $values['volunteer_match_radius']) );

	$volunteer_match_bootstrap_support = isset( $values['volunteer_match_bootstrap_support'] ) ? true : false;
	update_option('volunteer_match_bootstrap_support', $volunteer_match_bootstrap_support);

	print '<div class="updated notice is-dismissible"><p><strong>Volunteer Match Settings</strong> have been updated.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
}

/**
 * Return array of Volunteer Match Categories
 *
 * @return array
 */
function volunteer_match_categories() {
	$cats = array(
		'advocacyAndHumanRights',
		'animals',
		'artsAndCulture',
		'boardDevelopment',
		'childrenAndYouth',
		'community',
		'computersAndTechnology',
		'crisisSupport',
		'disabled',
		'disasterRelief',
		'educationAndLiteracy',
		'emergencyAndSafety',
		'employment',
		'environment',
		'gayLesbianBiTrans',
		'healthAndMedicine',
		'homelessAndHousing',
		'hunger',
		'immigrantsAndRefugees',
		'international',
		'justiceAndLegal',
		'mediaAndBroadcasting',
		'politics',
		'raceAndEthnicity',
		'religion',
		'seniors',
		'sportsAndRecreation',
		'veteransAndMilitaryFamilies',
		'women',
	);

	return $cats;
}
