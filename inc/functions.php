<?php
/**
 * Volunteer Match Helper Functions
 *
 * @package VolunteerMatch
 */

/**
 * Load Minified Version of a file
 *
 * @param  string $f File to load.
 * @param  mixed  $ext Extension of file, default css.
 *
 * @return string
 */
function volunteer_match_get_min_file( $f, $ext = 'css' ) {
	// if a minified version exists.
	if ( file_exists( VOLUNTEER_MATCH_DIR . str_replace( ".$ext", ".min.$ext", $f ) ) ) {
		return VOLUNTEER_MATCH_URL . str_replace( ".$ext", ".min.$ext", $f );
	} else {
		return VOLUNTEER_MATCH_URL . $f;
	}
}

/**
 * Allowed HTML for wp_kses
 *
 * @link https://codex.wordpress.org/Function_Reference/wp_kses
 * @param  mixed $exclude HTML tags to exclude.
 * @param  mixed $form Whether or not to include form fields.
 * @return array
 */
function volunteer_match_allowed_html( $exclude = array(), $form = false ) {
	$attr = array(
		'id'    => array(),
		'class' => array(),
		'style' => array(),
		'role'  => array(),
	);

	$anchors = array(
		'href'   => array(),
		'title'  => array(),
		'target' => array(),
	);

	$imgs = array(
		'src' => array(),
		'alt' => array(),
	);

	$aria = array(
		'aria-label'      => array(),
		'aria-labelledby' => array(),
		'aria-expanded'   => array(),
		'aria-haspopup'   => array(),
	);

	$data = array(
		'data-toggle' => array(),
		'data-target' => array(),
	);

	$tags = array(
		'div'    => array_merge( $attr, $aria, $data ),
		'p'      => $attr,
		'br'     => array(),
		'span'   => $attr,
		'a'      => array_merge( $attr, $aria, $data ),
		'button' => array_merge( $attr, $aria, $data ),
		'img'    => array_merge( $attr, $imgs ),
		'strong' => $attr,
		'bold'   => $attr,
		'i'      => $attr,
		'h1'     => $attr,
		'h2'     => $attr,
		'h3'     => $attr,
		'h4'     => $attr,
		'h5'     => $attr,
		'h6'     => $attr,
		'ol'     => $attr,
		'ul'     => $attr,
		'li'     => $attr,
		'style'  => array(),
	);

	// Whether to include form fields or not.
	if ( $form ) {
		$form_attrs = array(
			'action'     => array(),
			'method'     => array(),
			'enctype'    => array(),
			'novalidate' => array(),
		);

		$input_attrs = array(
			'for'      => array(),
			'type'     => array(),
			'name'     => array(),
			'value'    => array(),
			'title'    => array(),
			'checked'  => array(),
			'selected' => array(),
			'required' => array(),
			'pattern'  => array(),
		);

		$form_tags = array(
			'form'     => array_merge( $attr, $form_attrs ),
			'label'    => array_merge( $attr, $input_attrs ),
			'input'    => array_merge( $attr, $input_attrs ),
			'li'       => array_merge( $attr, $input_attrs ),
			'select'   => array_merge( $attr, $input_attrs ),
			'option'   => array_merge( $attr, $input_attrs ),
		);

		$tags = array_merge( $tags, $form_tags );
	}

	add_filter( 'safe_style_css', 'volunteer_match_safe_style_css' );

	return array_diff_key( $tags, array_flip( $exclude ) );
}

/**
 * Safe Style CSS
 *
 * @see https://developer.wordpress.org/reference/functions/safecss_filter_attr/
 *
 * @param  mixed $styles A string of CSS rules.
 * @return array
 */
function volunteer_match_safe_style_css( $styles ) {
	$styles[] = 'list-style-position';

	return $styles;
}

