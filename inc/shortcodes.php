<?php
/**
 * Shortcodes
 *
 * @link https://codex.wordpress.org/Shortcode_API
 * @package VolunteerMatch
 */

add_shortcode( 'volunteer_match', 'volunteer_match_func' );

/**
 * Renders Volunteer Match Search Dashboard
 *
 * @param  array $attr Attributes for this shortcode.
 *               $attr['hidden'] Whether or not to hide the Dashboard, default is false.
 *               $attr['font'] Font to use for the Volunteer Match Dashboard.
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
 *               $attr['description_expanded_icon'] WordPress Dashicon when description is expanded, default 'arrow-down-alt2'.
 *               $attr['description_collapsed_icon'] WordPress Dashicon when description is collapsed, default 'arrow-up-alt2'.
 *               $attr['date'] Whether or not to show opportunity date, default is true.
 *               $attr['title'] Whether or not to show opportunity title, default is true.
 *               $attr['mission'] Whether or not to show opportunity parent organization mission, default is true.
 *               $attr['parent_org'] Whether or not to show opportunity parent organization, default is true.
 * @return html
 */
function volunteer_match_func( $attr ) {

	$hidden      = isset( $attr['hidden'] ) && 'true' === $attr['hidden'] ? ' hidden' : '';
	$font_family = isset( $attr['font'] ) ? sprintf( 'font-family:%1$s;', $attr['font'] ) : '';

	$classes = " class=\"container p-0$hidden\"";
	$styles  = ! empty( $font_family ) ? " style=\"$font_family\"" : '';

	$search_row = volunteer_match_search_row( $attr );

	$search_options = volunteer_match_search_options( $attr );

	$search_extras = volunteer_match_extra_inputs( $attr );

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
 * Adds hidden inputs to the Volunteer Match Search Form
 *
 * @param  array $attr Attributes for the shortcode.
 *               $attr['id'] ID to a form connected to the dashboard.
 *               $attr['wpforms'] WPForms ID for the form connected to the dashboard, if $attr['id'] is set then $attr['id'] is used instead.
 *               $attr['notify'] Notify when form is submitted, default if false.
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
 *               $attr['description_expanded_icon'] WordPress Dashicon when description is expanded, default 'arrow-down-alt2'.
 *               $attr['description_collapsed_icon'] WordPress Dashicon when description is collapsed, default 'arrow-up-alt2'.
 *               $attr['date'] Whether or not to show opportunity date, default is true.
 *               $attr['title'] Whether or not to show opportunity title, default is true.
 *               $attr['parent_org'] Whether or not to show opportunity parent organization, default is true.
 *               $attr['mission'] Whether or not to show opportunity parent organization mission, default is true.
 * @return string
 */
function volunteer_match_extra_inputs( $attr ) {
	$nonce = wp_create_nonce( 'volunteer_match_search_opportunities' );
	$nonce = sprintf( '<input type="hidden" name="volunteer_match_search_opportunities_nonce" value="%1$s">', $nonce );

	$hidden = isset( $attr['hidden'] ) ? sprintf( '<input type="hidden" name="volunteer_match_hidden" value="%1$s">', $attr['hidden'] ) : '';

	$forms_id = isset( $attr['id'] ) ? sprintf( '<input type="hidden" name="volunteer_match_form_id" value="%1$s">', $attr['id'] ) : '';
	$forms_id = empty( $id ) && isset( $attr['wpforms'] ) ? sprintf( '<input type="hidden" name="volunteer_match_form_id" value="wpforms-form-%1$s">', $attr['wpforms'] ) : '';

	$button_color        = isset( $attr['button_color'] ) ? sprintf( '<input type="hidden" name="volunteer_match_button_color" value="%1$s">', $attr['button_color'] ) : '';
	$button_failed_color = isset( $attr['button_failed_color'] ) ? sprintf( '<input type="hidden" name="volunteer_match_button_failed_color" value="%1$s">', $attr['button_failed_color'] ) : '';
	$button_size         = isset( $attr['button_size'] ) ? sprintf( '<input type="hidden" name="volunteer_match_button_size" value="%1$s">', $attr['button_size'] ) : '';
	$button_font_color   = isset( $attr['button_font_color'] ) ? sprintf( '<input type="hidden" name="volunteer_match_button_font_color" value="%1$s">', $attr['button_font_color'] ) : '';

	$button_text = sprintf( '<input type="hidden" name="volunteer_match_button_text" value="%1$s">', isset( $attr['button_text'] ) ? $attr['button_text'] : 'Sign Up' );
	$button_failed_text = sprintf( '<input type="hidden" name="volunteer_match_button_failed_text" value="%1$s">', isset( $attr['button_failed_text'] ) ? $attr['button_failed_text'] : 'Failed' );
	$button_connection_text = sprintf( '<input type="hidden" name="volunteer_match_button_connection_text" value="%1$s">', isset( $attr['button_connection_text'] ) ? $attr['button_connection_text'] : 'Connected' );
	$button_connection_exist_text =  sprintf( '<input type="hidden" name="volunteer_match_button_connection_exist_text" value="%1$s">', isset( $attr['button_connection_exist_text'] ) ? $attr['button_connection_exist_text'] : 'Connection Exists' );
    $link_color          = isset( $attr['link_color'] ) ? sprintf( '<input type="hidden" name="volunteer_match_link_color" value="%1$s">', $attr['link_color'] ) : '';

	$notify = isset( $attr['notify'] ) && 'true' === $attr['notify'] ? sprintf( '<input type="hidden" name="volunteer_match_show_notify" value="true">', $attr['notify'] ) : '';

	$parent_org  = isset( $attr['parent_org'] ) && 'false' === $attr['parent_org'] ? sprintf( '<input type="hidden" name="volunteer_match_show_parent_org" value="false">', $attr['parent_org'] ) : '';
	$mission     = isset( $attr['mission'] ) && 'false' === $attr['mission'] ? sprintf( '<input type="hidden" name="volunteer_match_show_mission" value="false">', $attr['mission'] ) : '';
	$date        = isset( $attr['date'] ) && 'false' === $attr['date'] ? sprintf( '<input type="hidden" name="volunteer_match_show_date" value="false">', $attr['date'] ) : '';
	$title       = isset( $attr['title'] ) && 'false' === $attr['title'] ? sprintf( '<input type="hidden" name="volunteer_match_show_title" value="false">', $attr['title'] ) : '';
	$location    = isset( $attr['location'] ) && 'false' === $attr['location'] ? sprintf( '<input type="hidden" name="volunteer_match_show_location" value="false">', $attr['location'] ) : '';
	$description = isset( $attr['description'] ) && 'false' === $attr['description'] ? sprintf( '<input type="hidden" name="volunteer_match_show_description" value="false">', $attr['description'] ) : '';
	$description_expanded_icon = sprintf( '<input type="hidden" name="volunteer_match_description_expanded_icon" value="%1$s">', isset( $attr['description_expanded_icon'] ) ? $attr['description_expanded_icon'] : 'arrow-up-alt2' );
	$description_collapsed_icon = sprintf( '<input type="hidden" name="volunteer_match_description_collapsed_icon" value="%1$s">', isset( $attr['description_collapsed_icon'] ) ? $attr['description_collapsed_icon'] : 'arrow-down-alt2' );
   
	$response_page = '<input type="hidden" value="1" name="volunteer_match_response_page">';

	return "$nonce" .
			"$notify" .
			"$hidden" .
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
 * Adds Location and Keyword inputs to Volunteer Match Search Form
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
 * Adds Volunteer Match Search Options
 *
 * @param  array $attr Attributes for the shortcode.
 *               $attr['button_color'] Button background color.
 *               $attr['button_size'] Button size, default md. Options sm, md, lg.
 *               $attr['button_font_color'] Button text color.
 * @return string
 */
function volunteer_match_search_options( $attr ) {
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
	$col2      = sprintf( '<div class="col">%1$s%2$s</div>', $covid, $interests );

	$radius = volunteer_match_radius_options( $attr );

	$col3 = sprintf(
		'
	<div class="col">
		<label>Radius %1$s miles</label>
		<button id="volunteer-match-search" class="float-right btn btn-primary%2$s"%3$s>Search</button>
	</div>',
		$radius,
		$button_size,
		$button_style
	);

	return sprintf( '<div class="form-row">%1$s%2$s%3$s</div>', $col1, $col2, $col3 );

}

/**
 * Adds Volunteer Match Search Results structure
 *
 * @param  array $attr Attributes for the shortcode.
 *               $attr['disclaimer'] Disclaimer text displayed above opportunities list, default is 'By checking Sign Up, your contact information will be shared with the host organization and you will receive an email.'.
 *               $attr['disclaimer_font_size'] Disclaimer text font size in pixels.
 *               $attr['border'] Border color for the Opportunity List.
 * @return string
 */
function volunteer_match_search_results( $attr ) {
	$disclaimer   = isset( $attr['disclaimer'] ) ? $attr['disclaimer'] : '';
	$disclaimer_font_size   = isset( $attr['disclaimer_font_size'] ) ? sprintf(' style="font-size:%1$spx;"', $attr['disclaimer_font_size'] ) : '';
	$border_color = isset( $attr['border'] ) ? ' border border-' . $attr['border'] : '';
	$d            = ! empty( $disclaimer ) ? $disclaimer : 'By checking Sign Up, your contact information will be shared with the host organization and you will receive an email.';

	return sprintf(
		'<div id="volunteer-match-opps" class="hidden">
		<div class="row no-gutters">
			<h3 class="font-weight-bold pb-0 mr-3">Opportunities</h3>
			<div class="col text-right">
				<span class="current-page-view"></span>
				<div class="pagination d-inline-block"></div>
			</div>
		</div>
		<i class="volunteer-match-info-disclaimer"%1$s>%2$s</i>
		<ol id="volunteer-match-opp-list" class="p-3 pl-5 overflow-auto%3$s"></ol>
		</div>',
		$disclaimer_font_size,
		$d,
		$border_color
	);

}

/**
 * Adds Volunteer Match Interest Menu

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
			'<div class="%1$s">
				<label for="interest-%2$s">
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
