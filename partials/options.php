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

$volunteer_match_opp_endpoint               = get_option( 'volunteer_match_opp_endpoint', '' );
$volunteer_match_opp_endpoint_environment = get_option('volunteer_match_opp_endpoint_environment', 'staging');
$volunteer_match_opp_endpoint_graphql = get_option('volunteer_match_opp_endpoint_graphql', false) ? ' checked' : '';

$volunteer_match_create_connection_endpoint = get_option( 'volunteer_match_create_connection_endpoint', '' );
$volunteer_match_create_connection_endpoint_environment = get_option('volunteer_match_create_connection_endpoint_environment', 'staging');
$volunteer_match_create_connection_endpoint_graphql = get_option('volunteer_match_create_connection_endpoint_graphql', false) ? ' checked' : '';

$volunteer_match_categories = volunteer_match_categories();
$volunteer_match_interests  = get_option( 'volunteer_match_interests', array() );

?>
<div class="container-fluid mt-4">
	<form id="volunteer-match-options-form" action="<?php print esc_url( admin_url( 'admin.php?page=volunteer-match-options' ) ); ?>" method="POST">
		<input type="hidden" name="volunteer_match_settings_nonce" value="<?php echo esc_attr( $vm_nonce ); ?>" />
		<div class="row">
			<ul class="list-group list-group-horizontal">
				<li class="list-group-item"><a href="#volunteer-match-settings" data-toggle="collapse" aria-expanded="true" aria-controls="volunteer-match-settings" class="text-decoration-none">Settings</a></li>
				<li class="list-group-item"><a href="#volunteer-match-interests" data-toggle="collapse" aria-expanded="false" aria-controls="volunteer-match-interests" class="text-decoration-none">Interests</a></li>
			</ul>
		</div>
		<?php
			include_once 'sections/vm.php';
		?>
		<div id="volunteer-match-interests" class="row mr-3 bg-white collapse" data-parent="#volunteer-match-options-form">
			<div class="form-group col-lg-12">
				<button id="volunteer-match-add-interest" class="btn btn-sm btn-primary my-2">Add Interest</button>
				<ol id="volunteer-match-current-interests">
					<?php
					foreach ( $volunteer_match_interests as $vm_i => $vm_int ) :
						$vm_title = $vm_int['title'];
						$vm_cats  = explode( ',', $vm_int['cats'] );
						?>
					<li>
						<div class="row">
							<div class="col-6">
								<label class="mb-1">Topic: <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="volunteer_match_interests[]" value="<?php print esc_attr( $vm_title ); ?>" required>
								<button class="btn btn-danger mt-2 remove-topic">Remove</button>
							</div>
							<div class="col-6">
								<label class="d-block mb-1">Categories: ( Select all that apply. )</label>
								<select class="custom-select" multiple name="volunteer_match_interests_cats[<?php print esc_attr( $vm_i ); ?>][]">
									<?php
									foreach ( $volunteer_match_categories as $vm_c => $vm_cat ) :
										$vm_selected = in_array( $vm_cat, $vm_cats, true ) ? ' selected' : '';
										?>

										<option value="<?php print esc_attr( $vm_cat ); ?>"<?php print esc_attr( $vm_selected ); ?>><?php print esc_attr( $vm_cat ); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</li>
					<?php endforeach; ?>
				</ol>
			</div>	
		</div>
		<div class="row mr-3 bg-white">
			<div class="form-group col-lg-6">
				<input type="submit" name="volunteer_match_options_submit" class="button button-primary" value="Save Changes">
			</div>
		</div>
		<input type="hidden" name="volunteer_match_submit" >
	</form>
</div>
