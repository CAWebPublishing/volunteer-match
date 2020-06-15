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
		'VolunteerMatch',
		'VolunteerMatch',
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
	update_option( 'volunteer_match_opp_endpoint_graphql', $volunteer_match_opp_endpoint_graphql );
	update_option( 'volunteer_match_opp_endpoint_environment', $values['volunteer_match_opp_endpoint_environment'] );

	update_option( 'volunteer_match_create_connection_endpoint', esc_url_raw( $values['volunteer_match_create_connection_endpoint'] ) );

	$volunteer_match_create_connection_endpoint_graphql = isset( $values['volunteer_match_create_connection_endpoint_graphql'] ) ? true : false;
	update_option( 'volunteer_match_create_connection_endpoint_graphql', $volunteer_match_create_connection_endpoint_graphql );
	update_option( 'volunteer_match_create_connection_endpoint_environment', $values['volunteer_match_create_connection_endpoint_environment'] );

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

	$age_groupings = array();
	if ( isset( $values['volunteer_match_great_for'] ) ) {
		$great_fors = $values['volunteer_match_great_for'];
		$age_groups = $values['volunteer_match_great_for_age_groups'];
		foreach ( $great_fors as $i => $great_for ) {
			$groups         = array_shift( $age_groups );
			$age_groupings[] = array(
				'age_groups'  => implode( ',', $groups ),
				'title' => $great_for,
			);
		}
	}
	update_option( 'volunteer_match_great_for', $age_groupings );

	update_option( 'volunteer_match_radius', explode( ',', $values['volunteer_match_radius'] ) );

	$volunteer_match_bootstrap_support = isset( $values['volunteer_match_bootstrap_support'] ) ? true : false;
	update_option( 'volunteer_match_bootstrap_support', $volunteer_match_bootstrap_support );

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

/**
 * Return array of field labels from WPForms with fields that have the great-for css in their class
 *
 * @return array
 */
function volunteer_match_wpforms_age_groups() {
	if ( ! function_exists( 'wpforms' ) ) {
		return array();
	}

	$forms = wpforms()->form->get();
	$tmp   = array();

	foreach ( $forms as $f => $obj ) {
		$obj_decoded = wpforms_decode( $obj->post_content );
		$fields      = isset( $obj_decoded['fields'] ) ? $obj_decoded['fields'] : array();

		foreach ( $fields as $i => $field ) {
			if ( ! empty( $field['css'] ) &&
			false !== strpos( $field['css'], 'great-for' ) &&
			isset( $field['choices'] ) && ! empty( $field['choices'] ) ) {
				foreach ( $field['choices'] as $c => $choice ) {
					if( ! in_array( $choice['label'], $tmp ) ){
						array_push( $tmp, $choice['label'] );
					}
				}
			}
		}
	}

	return $tmp;
}
