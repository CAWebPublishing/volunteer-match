<div id="volunteer-match-great-for" class="row mr-3 bg-white collapse" data-parent="#volunteer-match-options-form">
	<div class="form-group col-lg-12">
		<button id="volunteer-match-add-great-for" class="btn btn-sm btn-primary my-2">Add Age Grouping</button>
		<ol id="volunteer-match-current-great-for">
			<?php
			foreach ( $volunteer_match_great_for as $vm_i => $vm_int ) :
				$vm_title = $vm_int['title'];
				$vm_age_groups  = explode( ',', $vm_int['age_groups'] );
				?>
			<li>
				<div class="row">
					<div class="col-6">
						<label class="mb-1">Age Group: <span class="text-danger">*</span></label>
						<input readonly type="text" class="form-control" name="volunteer_match_great_for[]" value="<?php print esc_attr( $vm_title ); ?>" required>
						<button class="btn btn-danger mt-2 remove-great-for">Remove</button>
					</div>
					<div class="col-6">
						<label class="d-block mb-1">Group: ( Select all that apply. )</label>
						<select class="custom-select" multiple name="volunteer_match_great_for_age_groups[<?php print esc_attr( $vm_i ); ?>][]">
					<?php
					foreach ( $volunteer_match_age_groups as $vm_ag => $vm_age_group ) :
						$vm_selected = in_array( $vm_age_group, $vm_age_groups, true ) ? ' selected' : '';
						?>

							<option value="<?php print esc_attr( $vm_age_group ); ?>" <?php print esc_attr( $vm_selected ); ?>><?php print esc_attr( $vm_age_group ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</div>
