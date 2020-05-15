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

							<option value="<?php print esc_attr( $vm_cat ); ?>" <?php print esc_attr( $vm_selected ); ?>><?php print esc_attr( $vm_cat ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</div>
