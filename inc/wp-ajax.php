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
 * @param  array $attr Attributes for this shortcode.
 *               $attr['ids'] ID to a form connected to the dashboard.
 * @return object
 */
function volunteer_match_return_opportunities( $attr = array() ) {
	$volunteer_match_api_key = get_option( 'volunteer_match_api_key', '' );
	$nonce                   = isset( $_POST['volunteer_match_search_opportunities_nonce'] ) &&
		wp_verify_nonce( sanitize_key( $_POST['volunteer_match_search_opportunities_nonce'] ), 'volunteer_match_search_opportunities' );

	// if no API Key supplied terminate.
	// or invalid nonce.
	if ( empty( $volunteer_match_api_key ) || ( ! $nonce && ! isset( $attr['ids'] ) ) ) {
		wp_die(); /* this is required to terminate immediately and return a proper response */
	}

	$volunteer_match_opp_endpoint = get_option( 'volunteer_match_opp_endpoint', '' );
	$volunteer_match_endpoint_key = get_option( 'volunteer_match_endpoint_key', '' );
	$volunteer_match_endpoint_key = ! empty( $volunteer_match_endpoint_key ) ? "&key=$volunteer_match_endpoint_key" : '';

	$volunteer_match_opp_endpoint_graphql     = get_option( 'volunteer_match_opp_endpoint_graphql', false );
	$volunteer_match_opp_endpoint_environment = get_option( 'volunteer_match_opp_endpoint_environment', 'staging' );

	$post_args['headers'] = array(
		'Content-Type' => 'application/json',
		'x-api-key'    => "$volunteer_match_api_key",
	);

	// if no Opportunity EndPoint set, set to appropriate VolunteerMatch EndPoints.
	if ( empty( $volunteer_match_opp_endpoint ) ) {
		$volunteer_match_opp_endpoint = 'staging' === $volunteer_match_opp_endpoint_environment ?
		'https://graphql.stage.volunteermatch.org/graphql' :
		'https://graphql.volunteermatch.org/graphql';

		// VolunteerMatch API endpoints use GraphQL.
		$volunteer_match_opp_endpoint_graphql = true;
	}

	// if requesting specific opportunities.
	if ( isset( $attr['ids'] ) ) {
		$search_input = sprintf( 'input:{ids:[%1$s]}', $attr['ids'] );
		$location     = 'ids=' . $attr['ids'];
		$virtual      = '';
		$is_covid19   = '';
		$radius       = '';
		$keyword      = '';
		$interests    = '';
		$categories   = '';
		$page         = '';
		$great_for    = '';

		// requesting all opportunities.
	} else {
		// Get requested parameters.
		$location   = isset( $_POST['volunteer_match_location'] ) ? sanitize_text_field( wp_unslash( $_POST['volunteer_match_location'] ) ) : '';
		$virtual    = isset( $_POST['volunteer_match_type'] ) && 'virtual' === sanitize_text_field( wp_unslash( $_POST['volunteer_match_type'] ) ) ? 'true' : 'false';
		$is_covid19 = isset( $_POST['volunteer_match_covid19'] ) ? 'true' : 'false';
		$radius     = isset( $_POST['volunteer_match_radius'] ) ? sanitize_text_field( wp_unslash( $_POST['volunteer_match_radius'] ) ) : '';
		$keyword    = isset( $_POST['volunteer_match_keyword'] ) && ! empty( $_POST['volunteer_match_keyword'] ) ? sanitize_text_field( wp_unslash( $_POST['volunteer_match_keyword'] ) ) : '';
		$interests  = isset( $_POST['volunteer_match_interests'] ) ? $_POST['volunteer_match_interests'] : array();
		$categories = array();
		$great_for  = isset( $_POST['volunteer_match_great_for'] ) ? $_POST['volunteer_match_great_for'] : array();
		$age_groups = array();

		foreach ( $interests as $i => $interest ) {
			$categories = array_merge( $categories, explode( ',', $interest ) );
		}
		$categories = array_unique( $categories );
		$categories = ! empty( $categories ) ? implode( ',', $categories ) : '';

		foreach ( $great_for as $g => $for ) {
			$age_groups = array_merge( $age_groups, explode( ',', $for ) );
		}
		$age_groups = array_unique( $age_groups );
		$age_groups = ! empty( $age_groups ) ? implode( ',', $age_groups ) : '';

		$page = isset( $_POST['volunteer_match_response_page'] ) ? sanitize_text_field( wp_unslash( $_POST['volunteer_match_response_page'] ) ) : '1';

		// create input query for GraphQL endpoints.
		$search_input = sprintf(
			'input:{location:\"%1$s\", specialFlag:\"%2$s\", virtual: %3$s, pageNumber: %4$s, categories: [%5$s], sortCriteria: %6$s, greatFor: [%7$s]}',
			$location,
			'true' === $is_covid19 ? 'covid19' : '',
			$virtual,
			$page,
			$categories,
			'relevance',
			$age_groups
		);

		// create URL params for API endpoints.
		$location   = "location=$location";
		$is_covid19 = "&isCovid19=$is_covid19";
		$virtual    = "&virtual=$virtual";
		$page       = "&pageNumber=$page";
		$categories = ! empty( $categories ) ? "&categories=$categories" : '';
		$keyword    = ! empty( $keyword ) ? "&keywords=$keyword" : '';
		$radius     = ! empty( $radius ) ? "&radius=$radius" : '';
		$age_groups = ! empty( $age_groups ) ? "&greatFor=$age_groups" : '';
	}

	// if endpoint is GraphQL.
	if ( $volunteer_match_opp_endpoint_graphql ) {
		$date_range                  = 'dateRange{endDate,endTime,ongoing,singleDayOpps,startDate,startTime}';
		$location_object             = 'location{street1,street2,city,country,postalCode,region,virtual, geoLocation{accuracy,latitude,longitude}}';
		$parent_org                  = "parentOrg{id,phoneNumber,imageUrl,url,mission,name,description,$location_object}";
		$requirements                = 'requirements{bgCheck,drLicense,minimumAge,orientation}';
		$custom_fields               = 'customFields{fieldId,fieldLabel,fieldType,required,choices}';
		$opportunity_location_object = "{resultsSize,currentPage,opportunities{id,title,categories,imageUrl,specialFlag,greatFor,container,description,plaintextDescription,volunteersNeeded,$date_range,$custom_fields,$requirements,$parent_org,$location_object}}";

		$post_args['body'] = "{ \"query\" : \"{ searchOpportunities($search_input)$opportunity_location_object }\" }";

		$response = wp_remote_post( $volunteer_match_opp_endpoint, $post_args );
	} else {
		$volunteer_match_opp_endpoint .= "?$location$virtual$is_covid19$categories$keyword$radius$page$age_groups$volunteer_match_endpoint_key";
		$response                      = wp_remote_get( $volunteer_match_opp_endpoint, $post_args );
	}

	if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
		// if endpoint if GraphQL.
		$result = wp_remote_retrieve_body( $response );

		if ( $volunteer_match_opp_endpoint_graphql ) {
			$result = json_decode( $result, true );
			$result = isset( $result['data']['searchOpportunities'] ) ? $result['data']['searchOpportunities'] : $result;
			$result = wp_json_encode( $result );
		}
	} else {
		$result['error']    = true;
		$result['response'] = wp_remote_retrieve_body( $response );
	}

	if ( wp_doing_ajax() ) {
		wp_send_json( $result );
		wp_die();
	} else {
		return $result;
	}

}

/**
 * Create a VolunteerMatch connection using the VolunteerMatch createConnection function API endpoint
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

	$volunteer_match_create_connection_endpoint_environment = get_option( 'volunteer_match_create_connection_endpoint_environment', 'staging' );
	$volunteer_match_create_connection_endpoint_graphql     = get_option( 'volunteer_match_create_connection_endpoint_graphql', false ) ? ' checked' : '';

	$first_name                   = isset( $_POST['firstName'] ) ? sanitize_text_field( wp_unslash( $_POST['firstName'] ) ) : '';
	$last_name                    = isset( $_POST['lastName'] ) ? sanitize_text_field( wp_unslash( $_POST['lastName'] ) ) : '';
	$email                        = isset( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
	$phone_number                 = isset( $_POST['phoneNumber'] ) ? sanitize_text_field( wp_unslash( $_POST['phoneNumber'] ) ) : '';
	$zip                          = isset( $_POST['zip'] ) ? sanitize_text_field( wp_unslash( $_POST['zip'] ) ) : '';
	$opp_id                       = isset( $_POST['oppId'] ) ? sanitize_text_field( wp_unslash( $_POST['oppId'] ) ) : '';
	$volunteer_match_endpoint_key = get_option( 'volunteer_match_endpoint_key', '' );

	$post_args['headers'] = array(
		'Accept'       => 'application/json',
		'Content-Type' => 'application/json',
		'x-api-key'    => $volunteer_match_api_key,
	);

	// if no Opportunity EndPoint or,
	// Opportunity is using GraphQL, setup query.
	if ( empty( $volunteer_match_create_connection_endpoint ) || $volunteer_match_create_connection_endpoint_graphql ) {
		$volunteer         = sprintf( '{firstName:\"%1$s\",lastName:\"%2$s\",email:\"%3$s\",phoneNumber:\"%4$s\",zipCode:\"%5$s\",acceptTermsAndConditions:true}', $first_name, $last_name, $email, $phone_number, $zip );
		$connection_input  = "input:{oppId:$opp_id,volunteer:$volunteer}";
		$post_args['body'] = "{ \"query\" : \"mutation{ createConnection($connection_input){oppId,volunteer{firstName,lastName,email,phoneNumber,zipCode}} }\" }";
	} else {
		$post_args['body'] = array(
			'firstName'                => $first_name,
			'lastName'                 => $last_name,
			'email'                    => $email,
			'phoneNumber'              => $phone_number,
			'zipCode'                  => $zip,
			'oppId'                    => $opp_id,
			'acceptTermsAndConditions' => 'true',
		);

		if ( ! empty( $volunteer_match_endpoint_key ) ) {
			$post_args['body']['key'] = $volunteer_match_endpoint_key;
		}
	}

	// if no Opportunity EndPoint set, set to appropriate VolunteerMatch EndPoints.
	if ( empty( $volunteer_match_create_connection_endpoint ) ) {
		$volunteer_match_create_connection_endpoint = 'staging' === $volunteer_match_create_connection_endpoint_environment ?
		'https://graphql.stage.volunteermatch.org/graphql' :
		'https://graphql.volunteermatch.org/graphql';

		$response = wp_remote_post( $volunteer_match_create_connection_endpoint, $post_args );
	} else {
		if ( $volunteer_match_create_connection_endpoint_graphql ) {
			$response = wp_remote_post( $volunteer_match_create_connection_endpoint, $post_args );
		} else {
			$response = wp_remote_get( $volunteer_match_create_connection_endpoint, $post_args );
		}
	}

	$code = wp_remote_retrieve_response_code( $response );
	$body = wp_remote_retrieve_body( $response );

	if ( 200 === $code ) {
		wp_send_json( $body );
	} else {
		$res['error']      = true;
		$res['error_code'] = $code;

		if ( 400 === $code ) {
			$res['response'] = "You've already connected with this opportunity.";
		} else {
			$res['response'] = $body;
		}

		wp_send_json( $res );
	}

	wp_die(); /* this is required to terminate immediately and return a proper response */

}
