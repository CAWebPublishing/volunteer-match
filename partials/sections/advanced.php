<div id="volunteer-match-advanced" class="row mr-3 bg-white collapse" data-parent="#volunteer-match-options-form">
	<!-- VM Radius -->
	<div class="form-group col-lg-7">
		<strong><label for="volunteer_match_radius" class="mb-0">VolunteerMatch Radius</label></strong>
		<small class="form-text text-muted mt-0">Add multiple radiuses separated by a comma.</small>
		<input type="text" class="form-control" id="volunteer_match_radius" name="volunteer_match_radius" value="<?php print esc_attr( $volunteer_match_radius ); ?>">
	</div>
	<!-- VM Bootstrap Support -->
	<div class="form-group col-lg-7">
				<strong>Enable Bootstrap Support?</strong>
				<div class="form-check form-check-inline pl-0">
					<label for="volunteer_match_bootstrap_support" class="mb-0">
						<input type="checkbox" class="form-control" id="volunteer_match_bootstrap_support" name="volunteer_match_bootstrap_support" <?php print $volunteer_match_bootstrap_support;?>>
					</label>
				</div>
				<small class="form-text text-muted">Some of the functionality requires Bootstrap. If your theme doesn't come with Bootstrap please check here.</small>
			</div>
</div>
