<?php
/**
 * Options Page
 *
 * @package VolunteerMatch
 */

$vm_nonce = wp_create_nonce( 'volunteer_match_settings' );

// if saving.
if ( isset( $_POST['volunteer_match_submit'] ) ) {
	volunteer_match_save_options( $_POST );
}

// Volunteer Match Options.
$volunteer_match_api_key                    = get_option( 'volunteer_match_api_key', '' );
$volunteer_match_endpoint_key                    = get_option( 'volunteer_match_endpoint_key', '' );

$volunteer_match_opp_endpoint               = get_option( 'volunteer_match_opp_endpoint', '' );
$volunteer_match_opp_endpoint_environment = get_option('volunteer_match_opp_endpoint_environment', 'staging');
$volunteer_match_opp_endpoint_graphql = get_option('volunteer_match_opp_endpoint_graphql', false) ? ' checked' : '';

$volunteer_match_create_connection_endpoint = get_option( 'volunteer_match_create_connection_endpoint', '' );
$volunteer_match_create_connection_endpoint_environment = get_option('volunteer_match_create_connection_endpoint_environment', 'staging');
$volunteer_match_create_connection_endpoint_graphql = get_option('volunteer_match_create_connection_endpoint_graphql', false) ? ' checked' : '';

$volunteer_match_categories = volunteer_match_categories();
$volunteer_match_interests  = get_option( 'volunteer_match_interests', array() );

$volunteer_match_radius = get_option( 'volunteer_match_radius', array() );
$volunteer_match_radius = implode( ',', $volunteer_match_radius);

$volunteer_match_bootstrap_support = get_option('volunteer_match_bootstrap_support', false) ? ' checked' : '';

?>
<div class="container-fluid mt-4">
	<form id="volunteer-match-options-form" action="<?php print esc_url( admin_url( 'admin.php?page=volunteer-match-options' ) ); ?>" method="POST">
		<input type="hidden" name="volunteer_match_settings_nonce" value="<?php echo esc_attr( $vm_nonce ); ?>" />
		<div class="row">
			<ul class="list-group list-group-horizontal">
				<li class="list-group-item"><a href="#volunteer-match-settings" data-toggle="collapse" aria-expanded="true" aria-controls="volunteer-match-settings" class="text-decoration-none">Settings</a></li>
				<li class="list-group-item"><a href="#volunteer-match-interests" data-toggle="collapse" aria-expanded="false" aria-controls="volunteer-match-interests" class="text-decoration-none">Interests</a></li>
				<li class="list-group-item"><a href="#volunteer-match-advanced" data-toggle="collapse" aria-expanded="false" aria-controls="volunteer-match-interests" class="text-decoration-none">Advanced</a></li>
			</ul>
		</div>
		<?php
			include_once 'sections/vm.php';
			include_once 'sections/interests.php';
			include_once 'sections/advanced.php';
		?>
		
		<div class="row mr-3 bg-white">
			<div class="form-group col-lg-6">
				<input type="submit" name="volunteer_match_options_submit" class="button button-primary" value="Save Changes">
			</div>
		</div>
		<input type="hidden" name="volunteer_match_submit" >
	</form>
</div>
