<?php
/**
 * Shortcodes
 *
 * @link https://codex.wordpress.org/Shortcode_API
 * @package VolunteerMatch
 */

add_shortcode( 'volunteer_match', 'volunteer_match_func' );
add_shortcode( 'volunteer_match_opportunity', 'volunteer_match_opportunity_func' );

/**
 * Renders VolunteerMatch Search Dashboard
 *
 * @param  array $attr Attributes for this shortcode.
 *               $attr['hidden'] Whether or not to hide the Dashboard, default is false.
 *               $attr['landing_page'] Page associated with the VolunteerMatch Opportunity shortcode.
 *               $attr['font'] Font to use for the VolunteerMatch Dashboard.
 *               $attr['id'] ID to a form connected to the dashboard.
 *               $attr['wpforms'] WPForms ID for the form connected to the dashboard, if $attr['id'] is set then $attr['id'] is used instead.
 *               $attr['notify'] Notify when form is submitted, default if false.
 *               $attr['disclaimer'] Disclaimer text displayed above opportunities list, default is 'By checking Sign Up, your contact information will be shared with the host organization and you will receive an email.'
 *               $attr['disclaimer_font_size'] Disclaimer text font size in pixels.
 *               $attr['interests'] Whether the interests should be compacted or full, default is compact.
 *               $attr['interests'] = compact, shows a button with a menu of interests.
 *               $attr['interests'] = full, shows all interest on front display.
 *               $attr['radius'] = Search Radius options, comma separated values.
 *               $attr['default_radius'] = Default Search Radius, default is 20.
 *               $attr['border'] Border color for the Opportunity List.
 *               $attr['button_color'] Button background color.
 *               $attr['button_failed_color'] Button background color when signup failed.
 *               $attr['button_font_color'] Button text color.
 *               $attr['button_size'] Button size, default md. Options sm, md, lg.
 *               $attr['button_text'] Sign Up button text, default 'Sign Up'.
 *               $attr['button_failed_text'] Sign Up button text when connection fails, default 'Failed'.
 *               $attr['button_connection_text'] Sign Up button text when connection succeeds, default 'Connected'.
 *               $attr['button_connection_exist_text'] Sign Up button text when connection alreadt exists, default 'Connection Exists'.
 *               $attr['link_color'] Link text color.
 *               $attr['location'] Whether or not to show opportunity location, default is true.
 *               $attr['description'] Whether or not to show opportunity descriptions, default is true.
 *               $attr['description_type'] Whether or not to show opportunity descriptions in HTML or plaintext format, default is HTML.
 *               $attr['description_expanded_icon'] WordPress Dashicon when description is expanded, default 'arrow-down-alt2'.
 *               $attr['description_collapsed_icon'] WordPress Dashicon when description is collapsed, default 'arrow-up-alt2'.
 *               $attr['date'] Whether or not to show opportunity date, default is true.
 *               $attr['title'] Whether or not to show opportunity title, default is true.
 *               $attr['mission'] Whether or not to show opportunity parent organization mission, default is true.
 *               $attr['parent_org'] Whether or not to show opportunity parent organization, default is true.
 * @return string
 */
function volunteer_match_func( $attr ) {
	$nonce = wp_create_nonce( 'volunteer_match_search_opportunities' );

	$hidden      = isset( $attr['hidden'] ) && 'true' === $attr['hidden'] ? ' hidden' : '';
	$font_family = isset( $attr['font'] ) ? sprintf( 'font-family:%1$s;', $attr['font'] ) : '';

	$classes = " class=\"container p-0$hidden\"";
	$styles  = ! empty( $font_family ) ? " style=\"$font_family\"" : '';

	$search_row = volunteer_match_search_row( $attr );

	$search_options = volunteer_match_search_options( $attr, $nonce );

	$search_extras = volunteer_match_extra_inputs( $attr, $nonce );

	$search_results = volunteer_match_search_results( $attr );

	$volunteer_match_container = sprintf(
		'<div id="volunteer-match"%1$s%2$s><form class="needs-validation mb-3" novalidate="novalidate">%3$s%4$s%5$s</form>%6$s</div>',
		$classes,
		$styles,
		$search_row,
		$search_options,
		$search_extras,
		$search_results
	);

	return wp_kses( $volunteer_match_container, volunteer_match_allowed_html( array(), true ) );
}

/**
 * Adds hidden inputs to the VolunteerMatch Search Form
 *
 * @param  array  $attr Attributes for the shortcode.
 *                $attr['hidden'] Whether or not to hide the Dashboard, default is false.
 *                $attr['id'] ID to a form connected to the dashboard.
 *                $attr['wpforms'] WPForms ID for the form connected to the dashboard, if $attr['id'] is set then $attr['id'] is used instead.
 *                $attr['landing_page'] Page associated with the VolunteerMatch Opportunity shortcode.
 *                $attr['notify'] Notify when form is submitted, default if false.
 *                $attr['button_color'] Button background color.
 *                $attr['button_failed_color'] Button background color when signup failed.
 *                $attr['button_font_color'] Button text color.
 *                $attr['button_size'] Button size, default md. Options sm, md, lg.
 *                $attr['button_text'] Sign Up button text, default 'Sign Up'.
 *                $attr['button_failed_text'] Sign Up button text when connection fails, default 'Failed'.
 *                $attr['button_connection_text'] Sign Up button text when connection succeeds, default 'Connected'.
 *                $attr['button_connection_exist_text'] Sign Up button text when connection alreadt exists, default 'Connection Exists'.
 *                $attr['link_color'] Link text color.
 *                $attr['location'] Whether or not to show opportunity location, default is true.
 *                $attr['description'] Whether or not to show opportunity descriptions, default is true.
 *                $attr['description_type'] Whether or not to show opportunity descriptions in HTML or plaintext format, default is HTML.
 *                $attr['description_expanded_icon'] WordPress Dashicon when description is expanded, default 'arrow-down-alt2'.
 *                $attr['description_collapsed_icon'] WordPress Dashicon when description is collapsed, default 'arrow-up-alt2'.
 *                $attr['date'] Whether or not to show opportunity date, default is true.
 *                $attr['title'] Whether or not to show opportunity title, default is true.
 *                $attr['parent_org'] Whether or not to show opportunity parent organization, default is true.
 *                $attr['mission'] Whether or not to show opportunity parent organization mission, default is true.
 * @param  string $nonce Form nonce value.
 * @return string
 */
function volunteer_match_extra_inputs( $attr, $nonce ) {
	$nonce = sprintf( '<input type="hidden" name="volunteer_match_search_opportunities_nonce" value="%1$s">', $nonce );

	$hidden = isset( $attr['hidden'] ) ? sprintf( '<input type="hidden" name="volunteer_match_hidden" value="%1$s">', $attr['hidden'] ) : '';

	$forms_id = isset( $attr['id'] ) ? sprintf( '<input type="hidden" name="volunteer_match_form_id" value="%1$s">', $attr['id'] ) : '';
	$forms_id = empty( $id ) && isset( $attr['wpforms'] ) ? sprintf( '<input type="hidden" name="volunteer_match_form_id" value="wpforms-form-%1$s">', $attr['wpforms'] ) : '';

	$button_color        = isset( $attr['button_color'] ) ? sprintf( '<input type="hidden" name="volunteer_match_button_color" value="%1$s">', $attr['button_color'] ) : '';
	$button_failed_color = isset( $attr['button_failed_color'] ) ? sprintf( '<input type="hidden" name="volunteer_match_button_failed_color" value="%1$s">', $attr['button_failed_color'] ) : '';
	$button_size         = isset( $attr['button_size'] ) ? sprintf( '<input type="hidden" name="volunteer_match_button_size" value="%1$s">', $attr['button_size'] ) : '';
	$button_font_color   = isset( $attr['button_font_color'] ) ? sprintf( '<input type="hidden" name="volunteer_match_button_font_color" value="%1$s">', $attr['button_font_color'] ) : '';

	$button_text                  = sprintf( '<input type="hidden" name="volunteer_match_button_text" value="%1$s">', isset( $attr['button_text'] ) ? $attr['button_text'] : 'Sign Up' );
	$button_failed_text           = sprintf( '<input type="hidden" name="volunteer_match_button_failed_text" value="%1$s">', isset( $attr['button_failed_text'] ) ? $attr['button_failed_text'] : 'Failed' );
	$button_connection_text       = sprintf( '<input type="hidden" name="volunteer_match_button_connection_text" value="%1$s">', isset( $attr['button_connection_text'] ) ? $attr['button_connection_text'] : 'Connected' );
	$button_connection_exist_text = sprintf( '<input type="hidden" name="volunteer_match_button_connection_exist_text" value="%1$s">', isset( $attr['button_connection_exist_text'] ) ? $attr['button_connection_exist_text'] : 'Connection Exists' );
	$link_color                   = isset( $attr['link_color'] ) ? sprintf( '<input type="hidden" name="volunteer_match_link_color" value="%1$s">', $attr['link_color'] ) : '';

	$notify = isset( $attr['notify'] ) && 'true' === $attr['notify'] ? sprintf( '<input type="hidden" name="volunteer_match_show_notify" value="true">', $attr['notify'] ) : '';

	$parent_org                 = isset( $attr['parent_org'] ) && 'false' === $attr['parent_org'] ? '<input type="hidden" name="volunteer_match_show_parent_org" value="false">' : '';
	$mission                    = isset( $attr['mission'] ) && 'false' === $attr['mission'] ? '<input type="hidden" name="volunteer_match_show_mission" value="false">' : '';
	$date                       = isset( $attr['date'] ) && 'false' === $attr['date'] ? '<input type="hidden" name="volunteer_match_show_date" value="false">' : '';
	$title                      = isset( $attr['title'] ) && 'false' === $attr['title'] ? '<input type="hidden" name="volunteer_match_show_title" value="false">' : '';
	$location                   = isset( $attr['location'] ) && 'false' === $attr['location'] ? '<input type="hidden" name="volunteer_match_show_location" value="false">' : '';
	$description                = isset( $attr['description'] ) && 'false' === $attr['description'] ? '<input type="hidden" name="volunteer_match_show_description" value="false">' : '';
	$description_type           = isset( $attr['description_type'] ) && 'plaintext' === $attr['description_type'] ? '<input type="hidden" name="volunteer_match_description_type" value="plaintext">' : '<input type="hidden" name="volunteer_match_description_type" value="HTML">';
	$description_expanded_icon  = sprintf( '<input type="hidden" name="volunteer_match_description_expanded_icon" value="%1$s">', isset( $attr['description_expanded_icon'] ) ? $attr['description_expanded_icon'] : 'arrow-up-alt2' );
	$description_collapsed_icon = sprintf( '<input type="hidden" name="volunteer_match_description_collapsed_icon" value="%1$s">', isset( $attr['description_collapsed_icon'] ) ? $attr['description_collapsed_icon'] : 'arrow-down-alt2' );

	$response_page = '<input type="hidden" value="1" name="volunteer_match_response_page">';
	$landing_page  = isset( $attr['landing_page'] ) ? sprintf( '<input type="hidden" value="%1$s" name="volunteer_match_landing_page">', $attr['landing_page'] ) : '';

	return "$nonce" .
			"$notify" .
			"$hidden" .
			"$landing_page" .
			"$forms_id" .
			"$button_color" .
			"$button_size" .
			"$button_failed_color" .
			"$button_font_color" .
			"$button_text" .
			"$button_failed_text" .
			"$button_connection_text" .
			"$button_connection_exist_text" .
			"$link_color" .
			"$description" .
			"$description_type" .
			"$description_expanded_icon" .
			"$description_collapsed_icon" .
			"$location" .
			"$date" .
			"$parent_org" .
			"$mission" .
			"$title" .
			"$response_page";
}

/**
 * Adds Location and Keyword inputs to VolunteerMatch Search Form
 *
 * @param  array $attr Attributes for the shortcode.
 * @return string
 */
function volunteer_match_search_row( $attr ) {
	$location = '
	<div class="form-group col-6">
		<label for="volunteer_match_location">
			Zip Code <span class="text-danger">*</span>
		</label>
		<input id="volunteer_match_location" type="text" name="volunteer_match_location" class="form-control" required>
	</div>';

	$keyword = '
	<div class="form-group col-6">
		<label for="volunteer_match_keyword">Narrow your search</label>
		<input id="volunteer_match_keyword" type="text" name="volunteer_match_keyword" placeholder="Keyword" class="form-control">
	</div>';

	return sprintf( '<div class="form-row">%1$s%2$s</div>', $location, $keyword );
}

/**
 * Adds VolunteerMatch Search Options
 *
 * @param  array  $attr Attributes for the shortcode.
 *                $attr['button_color'] Button background color.
 *                $attr['button_size'] Button size, default md. Options sm, md, lg.
 *                $attr['button_font_color'] Button text color.
 * @param  string $nonce Form nonce value.
 * @return string
 */
function volunteer_match_search_options( $attr, $nonce ) {
	$button_color      = isset( $attr['button_color'] ) ? sprintf( ' background-color:%1$s;', $attr['button_color'] ) : '';
	$button_font_color = isset( $attr['button_font_color'] ) ? sprintf( ' color:%2$s;', $attr['button_font_color'] ) : '';
	$button_style      = ! empty( $button_color ) || ! empty( $button_font_color ) ? sprintf( ' style="%1$s%2$s"', $button_color, $button_font_color ) : '';
	$button_size       = isset( $attr['button_size'] ) ? sprintf( ' btn-%1$s', $attr['button_size'] ) : ' btn-md';

	$local   = '
	<div class="form-check pl-0">
		<label for="volunteer_match_type_local">
			<input id="volunteer_match_type_local" type="radio" value="local" name="volunteer_match_type" checked> Local (you\'ll go to a physical location)
		</label>
	</div>';
	$virtual = '
	<div class="form-check pl-0">
		<label for="volunteer_match_type_virtual">
			<input id="volunteer_match_type_virtual" type="radio" value="virtual" name="volunteer_match_type"> Virtual (you can do it from a computer, your home or anywhere!)
		</label>
	</div>';
	$col1    = sprintf(
		'<div class="col" role="radiogroup" aria-label="Opportunity Types">
			<strong>Opportunity Type</strong>
			%1$s%2$s
		</div>',
		$local,
		$virtual
	);

	$covid = '
	<div class="form-check pl-0">
		<label for="volunteer_match_covid19">COVID-19 Related 
			<input id="volunteer_match_covid19" type="checkbox" name="volunteer_match_covid19" checked>
		</label>
	</div>';

	$interests = volunteer_match_interest_menu( $attr );

	$col2 = sprintf( '<div class="mx-4">%1$s%2$s</div>', $covid, $interests );

	$radius    = volunteer_match_radius_options( $attr );
	$great_for = volunteer_match_great_for_menu( $attr, $nonce );

	$col3 = sprintf(
		'
	<div class="col">
		<label>Radius %1$s miles</label>
		<button id="volunteer-match-search" class="float-right btn btn-primary%2$s"%3$s>Search</button>
		%4$s
	</div>',
		$radius,
		$button_size,
		$button_style,
		$great_for
	);

	return sprintf( '<div class="form-row">%1$s%2$s%3$s</div>', $col1, $col2, $col3 );

}

/**
 * Adds VolunteerMatch Search Results structure
 *
 * @param  array $attr Attributes for the shortcode.
 *               $attr['disclaimer'] Disclaimer text displayed above opportunities list, default is 'By checking Sign Up, your contact information will be shared with the host organization and you will receive an email.'.
 *               $attr['disclaimer_font_size'] Disclaimer text font size in pixels.
 *               $attr['border'] Border color for the Opportunity List.
 * @return string
 */
function volunteer_match_search_results( $attr ) {
	$disclaimer           = isset( $attr['disclaimer'] ) ? $attr['disclaimer'] : '';
	$disclaimer_font_size = isset( $attr['disclaimer_font_size'] ) ? sprintf( ' style="font-size:%1$spx;"', $attr['disclaimer_font_size'] ) : '';
	$border_color         = isset( $attr['border'] ) ? ' border border-' . $attr['border'] : '';
	$d                    = ! empty( $disclaimer ) ? $disclaimer : 'By checking Sign Up, your contact information will be shared with the host organization and you will receive an email.';

	return sprintf(
		'<div id="volunteer-match-opps" class="hidden">
		<div class="row no-gutters">
			<div class="col-lg-12">
				<h3 class="font-weight-bold pb-0 mr-3">Opportunities</h3>
				<i class="volunteer-match-info-disclaimer"%1$s>%2$s</i>
			</div>
			<div class="col text-right">
				<span class="current-page-view"></span>
				<div class="pagination d-inline-block"></div>
			</div>
		</div>
		<ol id="volunteer-match-opp-list" class="p-3 pl-5 overflow-auto%3$s"></ol>
		</div>',
		$disclaimer_font_size,
		$d,
		$border_color
	);

}

/**
 * Adds VolunteerMatch Interest Menu

 * @param  array $attr Attributes for this shortcode.
 *               $attr['interests'] Whether the interests should be compacted or full, default is compact.
 *               $attr['interests'] = compact, shows a button with a menu of interests.
 *               $attr['interests'] = full, shows all interest on front display.
 *               $attr['button_color'] Button background color.
 *               $attr['button_font_color'] Button text color.
 *               $attr['button_size'] Button size, default md. Options sm, md, lg.
 *
 * @return string
 */
function volunteer_match_interest_menu( $attr ) {
	$compact   = isset( $attr['interests'] ) && 'full' === $attr['interests'] ? false : true;
	$interests = get_option( 'volunteer_match_interests', array() );

	$menu = '';

	$check_class = $compact ? 'col' : 'form-check pl-0';

	foreach ( $interests as $i => $data ) {
		$menu .= sprintf(
			'<div>
				<label class="%1$s" for="interest-%2$s">
					<input id="interest-%2$s" type="checkbox" name="volunteer_match_interests[]" value="%3$s" title="%4$s"> %4$s
				</label>
			</div>',
			$check_class,
			$i,
			$data['cats'],
			$data['title']
		);
	}

	if ( $compact ) {
		$button_color      = isset( $attr['button_color'] ) ? sprintf( ' background-color:%1$s;', $attr['button_color'] ) : '';
		$button_font_color = isset( $attr['button_font_color'] ) ? sprintf( ' color:%2$s;', $attr['button_font_color'] ) : '';
		$button_style      = ! empty( $button_color ) || ! empty( $button_font_color ) ? sprintf( ' style="%1$s%2$s"', $button_color, $button_font_color ) : '';
		$button_size       = isset( $attr['button_size'] ) ? sprintf( ' btn-%1$s', $attr['button_size'] ) : ' btn-md';

		return sprintf(
			'<div class="dropdown" role="group" aria-label="Interested in menu">
				<button 
					class="btn btn-primary dropdown-toggle%1$s" 
					id="volunteer-match-interest-button" 
					data-toggle="dropdown" 
					aria-haspopup="true" 
					aria-expanded="false"%2$s>I am interested in...
				</button>
				<div class="dropdown-menu" aria-labelledby="volunteer-match-interest-button">
				%3$s
				</div>
			</div>',
			$button_size,
			$button_style,
			$menu
		);

	}

	return sprintf( '<div><strong>I am interested in...</strong>%1$s</div>', $menu );

}

/**
 * Adds VolunteerMatch Great For Menu
 *
 * @param  array  $attr Attributes for this shortcode.
 *                $attr['greatfor'] Whether the Good For menu should be compacted or full, default is compact.
 *                $attr['greatfor'] = compact, shows a button with a menu of Good For choices.
 *                $attr['greatfor'] = full, shows all Good For choices on front display.
 *                $attr['button_color'] Button background color.
 *                $attr['button_font_color'] Button text color.
 *                $attr['button_size'] Button size, default md. Options sm, md, lg.
 * @param  string $nonce Form nonce value.
 *
 * @return string
 */
function volunteer_match_great_for_menu( $attr, $nonce ) {
	$compact    = isset( $attr['greatfor'] ) && 'full' === $attr['greatfor'] ? false : true;
	$age_groups = array( 'groups', 'kids', 'seniors', 'teens' );

	$menu = '';

	$check_class = $compact ? 'col' : 'form-check pl-0';

	$verified = wp_verify_nonce( $nonce, 'volunteer_match_search_opportunities' );

	$default_great_fors = isset( $_GET['vm-gf'] ) ? sanitize_text_field( wp_unslash( $_GET['vm-gf'] ) ) : '';
	$default_great_fors = explode( ',', $default_great_fors );

	foreach ( $age_groups as $i => $group ) {
		$checked = in_array( $group, $default_great_fors, true ) ? ' checked' : '';
		$menu   .= sprintf(
			'<div>
				<label class="%1$s" for="great-for-%2$s">
					<input id="great-for-%2$s" type="checkbox" name="volunteer_match_great_for[]" value="%2$s" title="%3$s"%4$s> %3$s
				</label>
			</div>',
			$check_class,
			$group,
			ucfirst( $group ),
			$checked
		);
	}

	if ( $compact ) {
		$button_color      = isset( $attr['button_color'] ) ? sprintf( ' background-color:%1$s;', $attr['button_color'] ) : '';
		$button_font_color = isset( $attr['button_font_color'] ) ? sprintf( ' color:%2$s;', $attr['button_font_color'] ) : '';
		$button_style      = ! empty( $button_color ) || ! empty( $button_font_color ) ? sprintf( ' style="%1$s%2$s"', $button_color, $button_font_color ) : '';
		$button_size       = isset( $attr['button_size'] ) ? sprintf( ' btn-%1$s', $attr['button_size'] ) : ' btn-md';

		return sprintf(
			'<div class="dropdown" role="group" aria-label="Good For menu">
				<button 
					class="btn btn-primary dropdown-toggle%1$s" 
					id="volunteer-match-great-for-button" 
					data-toggle="dropdown" 
					aria-haspopup="true" 
					aria-expanded="false"%2$s>Good For...
				</button>
				<div class="dropdown-menu" aria-labelledby="volunteer-match-great-for-button">
				%3$s
				</div>
			</div>',
			$button_size,
			$button_style,
			$menu
		);

	}

	return sprintf( '<div><strong>Good For...</strong>%1$s</div>', $menu );

}

/**
 * Adds VolunteerMatch Radius Options
 *
 * @param  mixed $attr Attributes for this shortcode.
 *               $attr['radius'] = Search Radius options, comma separated values.
 *               $attr['default_radius'] = Default Search Radius, default is 20.
 * @return string
 */
function volunteer_match_radius_options( $attr ) {
	$default_radius = isset( $attr['default_radius'] ) ? (int) $attr['default_radius'] : '20';

	$radius            = get_option( 'volunteer_match_radius', array() );
	$additional_radius = isset( $attr['radius'] ) ? explode( ',', $attr['radius'] ) : array();

	$radius = array_unique( array_merge( $radius, $additional_radius ) );

	$options = '';

	foreach ( $radius as $r => $rad ) {
		$selected = $default_radius === $rad ? ' selected' : '';
		$options .= sprintf( '<option value="%1$s"%2$s>%1$s</option>', $rad, $selected );
	}

	if ( ! empty( $options ) ) {
		return sprintf( '<select id="volunteer-match-radius-options" name="volunteer_match_radius">%1$s</select>', $options );
	}
}

/**
 * Renders VolunteerMatch Opportunity Details
 *
 * @param  array $attr Attributes for this shortcode.
 *               $attr['opp_id'] Opportunity ID to render.
 *               $attr['id'] ID to a form connected to the dashboard.
 *               $attr['wpforms'] WPForms ID for the form connected to the dashboard, if $attr['id'] is set then $attr['id'] is used instead.
 *               $attr['notify'] Notify when form is submitted, default if false.
 *               $attr['description_type'] Whether or not to show opportunity descriptions in HTML or plaintext format, default is HTML.
 *               $attr['show_when'] Whether or not to show opportunity date, default is true.
 *               $attr['show_where'] Whether or not to show opportunity location, default is true.
 *               $attr['show_great_for'] Whether or not to show opportunity great for, default is true.
 *               $attr['show_skills'] Whether or not to show opportunity skills, default is true.
 *
 * @return string
 */
function volunteer_match_opportunity_func( $attr ) {
	$nonce = wp_create_nonce( 'volunteer_match_opportunity' );
	$nonce = isset( $nonce ) && wp_verify_nonce( sanitize_key( $nonce ), 'volunteer_match_opportunity' );

	$forms_id = isset( $attr['id'] ) ? sprintf( ' data-target="%1$s"', $attr['id'] ) : '';
	$forms_id = empty( $id ) && isset( $attr['wpforms'] ) ? sprintf( ' data-target="wpforms-form-%1$s"', $attr['wpforms'] ) : '';

	$opportunity = isset( $_GET['volunteer_opp_id'] ) ? sanitize_text_field( wp_unslash( $_GET['volunteer_opp_id'] ) ) : '';
	$opportunity = empty( $opportunity ) && isset( $attr['opp_id'] ) ? $attr['opp_id'] : $opportunity;

	$opportunity = volunteer_match_return_opportunities( array( 'ids' => $opportunity ) );
	$opportunity = is_string( $opportunity ) ? json_decode( $opportunity, true ) : '';

	$display = ! empty( $opportunity ) ? volunteer_match_display_opportunity( $opportunity, $attr ) : '';

	return wp_kses( sprintf( '<div id="volunteer-match-opportunity" class="row"%1$s>%2$s</div>', $forms_id, $display ), volunteer_match_allowed_html( array(), true ) );
}

/**
 * Display a VolunteerMatch Opportunity
 *
 * @param  object $opportunity Opportunity Object.
 * @param  array  $attr Attributes for the shortcode.
 *                $attr['description_type'] Whether or not to show opportunity descriptions in HTML or plaintext format, default is HTML.
 *               $attr['notify'] Notify when form is submitted, default if false.
 *               $attr['show_when'] Whether or not to show opportunity date, default is true.
 *               $attr['show_where'] Whether or not to show opportunity location, default is true.
 *               $attr['show_great_for'] Whether or not to show opportunity great for, default is true.
 *               $attr['show_skills'] Whether or not to show opportunity skills, default is true.
 *
 * @return string
 */
function volunteer_match_display_opportunity( $opportunity, $attr ) {
	if ( isset( $opportunity['resultsSize'] ) && $opportunity['resultsSize'] && isset( $opportunity['opportunities'][0] ) ) {
		$opp = $opportunity['opportunities'][0];

		$title       = sprintf( '<p class="h3 m-0 opportunity-title">%1$s</p>', $opp['title'] );
		$parent_name = sprintf( '<p class="opportunity-parentOrg-name">%1$s</p>', $opp['parentOrg']['name'] );

		$image = isset( $opp['imageUrl'] ) ? sprintf( '<img src="%1$s" alt="%2$s Image" class="float-left mr-2 opportunity-image" />', $opp['imageUrl'], $opp['title'] ) : '';

		$header = sprintf( '<div class="header overflow-auto my-2">%1$s%2$s%3$s</div>', $image, $title, $parent_name );

		$description = isset( $attr['description_type'] ) && 'plaintext' === $attr['description_type'] ? $opp['plaintextDescription'] : $opp['description'];
		$description = sprintf( '<div class="opportunity-description">%1$s</div>', $description );

		// where.
		$where = volunteer_match_opportunity_location( $opp, $attr );

		// when.
		$when = volunteer_match_opportunity_dates( $opp, $attr );

		// skills.
		$skills = volunteer_match_opportunity_skills( $opp, $attr );

		// greatFor.
		$great_for = volunteer_match_opportunity_great_for( $opp, $attr );

		$hidden_fields = volunteer_match_opportunity_hidden_fields( $opp, $attr );

		$col1_class = 'col-lg-12';
		$col2       = '';

		if ( ! empty( $when ) ||
			! empty( $where ) ||
			! empty( $skills ) ||
			! empty( $great_for ) ) {
			$col1_class = 'col-lg-9';
			$col2       = sprintf( '<div class="col-lg-3">%1$s%2$s%3$s%4$s</div>', $when, $where, $skills, $great_for );
		}

		$col1 = sprintf( '<div class="%1$s">%2$s%3$s%4$s</div>', $col1_class, $header, $description, $hidden_fields );

		return "$col1$col2";
	}

	return 'No opportunity matched the requested ID.';
}

/**
 * Add VolunteerMatch Opportunity hidden fields
 *
 * @param  object $opp Opportunity Object.
 * @param  array  $attr Attributes for this shortcode.
 *                $attr['notify'] Notify when form is submitted, default if false.
 *
 * @return string
 */
function volunteer_match_opportunity_hidden_fields( $opp, $attr ) {
	$location = $opp['location'];

	// id.
	$id = sprintf( '<input type="hidden" name="volunteer_match_opp_id" value="%1$s">', $opp['id'] );

	// title.
	$opp_title = sprintf( '<input type="hidden" name="volunteer_match_opp_title" value="%1$s">', $opp['title'] );

	// is_covid.
	$is_covid = ! empty( $opp['specialFlag'] ) && in_array( 'covid19', $opp['specialFlag'] ) ? 'true' : 'false';
	$is_covid = sprintf( '<input type="hidden" name="volunteer_match_opp_is_covid" value="%1$s">', $is_covid );

	// parentOrgId.
	$parent_org_id = sprintf( '<input type="hidden" name="volunteer_match_opp_parent_org_id" value="%1$s">', $opp['parentOrg']['id'] );

	// parentName.
	$parent_name = sprintf( '<input type="hidden" name="volunteer_match_opp_parent_org_name" value="%1$s">', $opp['parentOrg']['name'] );

	// location.
	$opp_location = '';

	if ( ! $location['virtual'] ) {
		$opp_location = array_filter(
			array(
				$location['street1'],
				$location['street2'],
				$location['city'],
				$location['region'],
				$location['postalCode'],
			)
		);

		$opp_location = implode( ',', $opp_location );
	}

	$opp_location = sprintf( '<input type="hidden" name="volunteer_match_opp_location" value="%1$s">', $opp_location );

	// container.
	$container = sprintf( '<input type="hidden" name="volunteer_match_opp_container" value="%1$s">', $opp['container'] );

	// categories & interests.
	$categories = isset( $opp['categories'] ) && ! empty( $opp['categories'] ) ? $opp['categories'] : '';
	$ints       = get_option( 'volunteer_match_interests', array() );
	$interests  = array();

	if ( ! empty( $categories ) ) {
		foreach ( $ints as $i => $data ) {
			$cats  = explode( ',', $data['cats'] );
			$title = $data['title'];

			foreach ( $cats as $c => $cat ) {
				if ( in_array( $cat, $categories ) ) {
					$interests[] = $title;
					break;
				}
			}
		}
	}

	$categories = sprintf( '<input type="hidden" name="volunteer_match_opp_categories" value="%1$s">', implode( ',', $categories ) );
	$interests  = sprintf( '<input type="hidden" name="volunteer_match_opp_interests" value="%1$s">', implode( ',', $interests ) );

	// notify.
	$notify = isset( $attr['notify'] ) && 'true' === $attr['notify'] ? sprintf( '<input type="hidden" name="volunteer_match_opp_show_notify" value="true">', $attr['notify'] ) : '';

	return "$id$opp_title$is_covid$parent_org_id$parent_name$opp_location$categories$interests$container$notify";
}

/**
 * Display an opportunities location property
 *
 * @param  object $opp Opportunity Object.
 * @param  array  $attr Attributes for the shortcode.
 *               $attr['show_where'] Whether or not to show opportunity location, default is true.
 * @return string
 */
function volunteer_match_opportunity_location( $opp, $attr ) {
	$l = '';
	if ( ! isset( $attr['show_where'] ) || 'false' !== $attr['show_where'] ) {
		$location = $opp['location'];

		if ( $location['virtual'] ) {
			$location = '<p>Virtual</p>';
		} else {
			$location = array_filter(
				array(
					$location['street1'],
					$location['street2'],
					$location['city'],
					$location['region'],
					$location['postalCode'],
				)
			);

			$location = ! empty( $location ) ? sprintf( '<a href="https://www.google.com/maps/place/%1$s">%1$s</a>', implode( ', ', $location ) ) : '<p>N/A</p>';
		}
		$l = sprintf( '<div class="opportunity-location mt-2"><p class="h4 m-0">Where</p>%1$s</div>', $location );
	}

	return $l;
}

/**
 * Display an opportunities dates property
 *
 * @param  object $opp Opportunity Object.
 * @param  array  $attr Attributes for the shortcode.
 *               $attr['show_when'] Whether or not to show opportunity date, default is true.
 * @return string
 */
function volunteer_match_opportunity_dates( $opp, $attr ) {
	$when = '';
	if ( ! isset( $attr['show_when'] ) || 'false' !== $attr['show_when'] ) {
		if ( ! $opp['dateRange']['ongoing'] ) {

			$sdate = ! empty( $opp['dateRange']['startDate'] ) ? gmdate( 'M j, Y', strtotime( $opp['dateRange']['startDate'] ) ) : '';
			$stime = ! empty( $opp['dateRange']['startTime'] ) ? gmdate( 'h:i a', strtotime( $opp['dateRange']['startTime'] ) ) : '';
			$edate = ! empty( $opp['dateRange']['endDate'] ) ? gmdate( 'M j, Y', strtotime( $opp['dateRange']['endDate'] ) ) : '';
			$etime = ! empty( $opp['dateRange']['endTime'] ) ? gmdate( 'h:i a', strtotime( $opp['dateRange']['endTime'] ) ) : '';

			if ( ! empty( $sdate ) && ! empty( $stime ) ) {
				$stime = " @ $stime";
			}

			if ( ! empty( $edate ) && ! empty( $etime ) ) {
				$etime = " @ $etime";
			}

			if ( ! empty( $sdate ) && ! empty( $edate ) ) {
				$edate = " - $edate";
			}
			$date = "$sdate$stime$edate$etime";
		} else {
			$date = 'Ongoing';
		}

		$when = sprintf( '<div class="opportunity-date mt-2"><p class="h4 m-0">When</p>%1$s</div>', $date );
	}
	return $when;
}

/**
 * Display an opportunities skills property
 *
 * @param  object $opp Opportunity Object.
 * @param  array  $attr Attributes for the shortcode.
 *               $attr['show_skills'] Whether or not to show opportunity skills, default is true.
 * @return string
 */
function volunteer_match_opportunity_skills( $opp, $attr ) {
	$s = '';
	if ( ! isset( $attr['show_skills'] ) || 'false' !== $attr['show_skills'] ) {
		$s = ! empty( $opp['skillsNeeded'] ) ? implode( ', ', explode( ';', $opp['skillsNeeded'] ) ) : 'None';
		$s = sprintf( '<div class="opportunity-skills mt-2"><p class="h4 m-0">Skills</p><p>%1$s</p></div>', $s );
	}
	return $s;
}

/**
 * Display an opportunities greatFor property
 *
 * @param  object $opp Opportunity Object.
 * @param  array  $attr Attributes for the shortcode.
 *               $attr['show_great_for'] Whether or not to show opportunity great for, default is true.
 * @return string
 */
function volunteer_match_opportunity_great_for( $opp, $attr ) {
	$g = '';
	if ( ! isset( $attr['show_great_for'] ) || 'false' !== $attr['show_great_for'] ) {
		$g = ! empty( $opp['greatFor'] ) ? implode( ', ', array_map( 'ucfirst', $opp['greatFor'] ) ) : '';
		$g = ! empty( $g ) ? sprintf( '<div class="opportunity-great-for mt-2"><p class="h4 m-0">Great For</p><p>%1$s</p></div>', $g ) : '';
	}
	return $g;
}
