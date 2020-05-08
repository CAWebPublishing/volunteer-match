<?php
/**
 * WP Ajax
 *
 * @see https://codex.wordpress.org/AJAX_in_Plugins
 * @package VolunteerMatch
 */

add_action( 'wp_ajax_volunteer_match_return_opportunities', 'volunteer_match_return_opportunities' );
add_action( 'wp_ajax_nopriv_volunteer_match_return_opportunities', 'volunteer_match_return_opportunities' );
add_action( 'wp_ajax_volunteer_match_create_connection', 'volunteer_match_create_connection' );
add_action( 'wp_ajax_nopriv_volunteer_match_create_connection', 'volunteer_match_create_connection' );

/**
 * Return List of Opportunities from VolunteerMatch EndPoint
 *
 * @return void
 */
function volunteer_match_return_opportunities() {
	$volunteer_match_api_key = get_option( 'volunteer_match_api_key', '' );
	$nonce                   = isset( $_POST['volunteer_match_search_opportunities_nonce'] ) &&
		wp_verify_nonce( sanitize_key( $_POST['volunteer_match_search_opportunities_nonce'] ), 'volunteer_match_search_opportunities' );

	// if no API Key supplied terminate.
	// or invalid nonce.
	if ( empty( $volunteer_match_api_key ) || ! $nonce ) {
		wp_die(); /* this is required to terminate immediately and return a proper response */
	}
	$volunteer_match_opp_endpoint = get_option( 'volunteer_match_opp_endpoint', '' );

	$location   = isset( $_POST['volunteer_match_location'] ) ? 'location=' . sanitize_text_field( wp_unslash( $_POST['volunteer_match_location'] ) ) : '';
	$virtual    = isset( $_POST['volunteer_match_type'] ) && 'virtual' === sanitize_text_field( wp_unslash( $_POST['volunteer_match_type'] ) ) ? '&virtual=true' : '&virtual=false';
	$is_covid19 = isset( $_POST['volunteer_match_covid19'] ) ? '&isCovid19=true' : '&isCovid19=false';
	$radius     = isset( $_POST['volunteer_match_radius'] ) ? '&radius=' . sanitize_text_field( wp_unslash( $_POST['volunteer_match_radius'] ) ) : '';
	$keyword    = isset( $_POST['volunteer_match_keyword'] ) && ! empty( $_POST['volunteer_match_keyword'] ) ? '&keywords=' . sanitize_text_field( wp_unslash( $_POST['volunteer_match_keyword'] ) ) : '';

	$interests  = isset( $_POST['volunteer_match_interests'] ) ? sanitize_text_field( wp_unslash( $_POST['volunteer_match_interests'] ) ) : array();
	$categories = array();

	foreach ( $interests as $i => $interest ) {
		$categories = array_merge( $categories, explode( ',', $interest ) );
	}
	$categories = array_unique( $categories );
	$categories = ! empty( $categories ) ? sprintf( '&categories=%1$s', implode( ',', $categories ) ) : '';

	$page = isset( $_POST['volunteer_match_response_page'] ) ? '&pageNumber=' . sanitize_text_field( wp_unslash( $_POST['volunteer_match_response_page'] ) ) : '1';

	if ( ! empty( $volunteer_match_opp_endpoint ) ) {
		$volunteer_match_opp_endpoint .= '?' .
										"$location" .
										"$virtual" .
										"$is_covid19" .
										"$keyword" .
										"$categories" .
										"$radius" .
										"$page";
	} else {
		$volunteer_match_opp_endpoint = '';
	}

	$response = wp_remote_get( $volunteer_match_opp_endpoint );

	if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
		wp_send_json( wp_remote_retrieve_body( $response ) );
	} else {
		$res['error']    = true;
		$res['response'] = wp_remote_retrieve_body( $response );
		wp_send_json( $res );
	}

	wp_die(); /* this is required to terminate immediately and return a proper response */
}

/**
 * Create a volunteer match connection using the VolunteerMatch createConnection function API endpoint
 *
 * @return void
 */
function volunteer_match_create_connection() {
	$volunteer_match_api_key = get_option( 'volunteer_match_api_key', '' );
	$nonce                   = isset( $_POST['volunteer_match_search_opportunities_nonce'] ) &&
		wp_verify_nonce( sanitize_key( $_POST['volunteer_match_search_opportunities_nonce'] ), 'volunteer_match_search_opportunities' );

	// if no API Key supplied terminate.
	if ( empty( $volunteer_match_api_key ) || ! $nonce ) {
		$res['error']    = true;
		$res['response'] = 'An error occurred during the signup process.';
		wp_send_json( $res );
		wp_die(); /* this is required to terminate immediately and return a proper response */
	}

	$volunteer_match_create_connection_endpoint = get_option( 'volunteer_match_create_connection_endpoint', '' );

	$body['firstName']                = isset( $_POST['firstName'] ) ? sanitize_text_field( wp_unslash( $_POST['firstName'] ) ) : '';
	$body['lastName']                 = isset( $_POST['lastName'] ) ? sanitize_text_field( wp_unslash( $_POST['lastName'] ) ) : '';
	$body['email']                    = isset( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
	$body['phoneNumber']              = isset( $_POST['phoneNumber'] ) ? sanitize_text_field( wp_unslash( $_POST['phoneNumber'] ) ) : '';
	$body['zip']                      = isset( $_POST['zip'] ) ? sanitize_text_field( wp_unslash( $_POST['zip'] ) ) : '';
	$body['oppId']                    = isset( $_POST['oppId'] ) ? sanitize_text_field( wp_unslash( $_POST['oppId'] ) ) : '';
	$body['acceptTermsAndConditions'] = 'true';

	$headers['Accept']       = 'application/json';
	$headers['Content-Type'] = 'application/json';
	$headers['x-api-key']    = $volunteer_match_api_key;

	$response = wp_remote_get(
		$volunteer_match_create_connection_endpoint,
		array(
			'headers' => $headers,
			'body'    => $body,
		)
	);

	if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
		wp_send_json( wp_remote_retrieve_body( $response ) );
	} else {
		$res['error']    = true;
		$res['response'] = wp_remote_retrieve_body( $response );
		wp_send_json( $res );
	}

	wp_die(); /* this is required to terminate immediately and return a proper response */

}
