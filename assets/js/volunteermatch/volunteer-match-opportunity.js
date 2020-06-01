jQuery(document).ready(function(){
	$ = jQuery.noConflict();
	
	var volunteer_match_opportunity = $('#volunteer-match-opportunity');

	if( volunteer_match_opportunity.length ){
		
		// VolunteerMatch Shortcode Attributes
		var forms_id = volunteer_match_opportunity.attr('data-target');
		var show_notify = $('input[name="volunteer_match_opp_show_notify"]');

		// Associated Form
		var associated_form = {};

		// Form Params
		var zip, firstName, lastName, oppID, oppTitle, oppLocation, oppIsCovid19, parentOrgID, parentName, interests, categories = {};

		if( forms_id.length ){
			associated_form = $( `#${forms_id}` );
			// associated form opportunity inputs
			firstName = associated_form.find('.firstName input');
			lastName = associated_form.find('.lastName input');
			zip = associated_form.find('.zip input');
			oppID = associated_form.find('.oppID input');
			oppTitle = associated_form.find('.title input');
			oppLocation = associated_form.find('.location input');
			oppIsCovid19 = associated_form.find('.specialFlag input');
			parentOrgID = associated_form.find('.parentOrgID input');
			parentName = associated_form.find('.parentName input');
			interests = associated_form.find('.interests input');
			categories = associated_form.find('.categories input');
			container = associated_form.find(' .container input');

			update_opp_form_inputs();
		}

		if ( associated_form.length ){
			var submit_button = associated_form.find('button[type="submit"]');
				$(submit_button).on('click', function(e){
					associated_form.validate();

					if( associated_form.valid() ){
						e.preventDefault();

						if( zip.length ){
							// submit form
							var fd = new FormData( associated_form.get( 0 ) );
							fd.append( 'action', 'wpforms_submit' );

							jQuery.ajax({
								type: 'POST',
								dataType: 'json',
								url: volunteer_match_args.ajaxurl,
								data: fd,
								cache: false,
								contentType: false,
								processData: false,
								success: function( submitted ){
									if( show_notify.length && "true" == show_notify.val() ){
										var f = firstName.length ? firstName.val() : '';
										var l = lastName.length ? lastName.val() : '';
										var n = f.length && l.length ? `${firstName.val()} ${lastName.val()}` : `${firstName.val()}${lastName.val()}`

										n = n.length ? `, ${n} ` : ' ';

										alert( `Thank you${n}for you interest in volunteering.`);
									}
									associated_form.addClass('hidden');
								}
							 })
						}
						
					}
				});

				zip.keypress( function(e){
					volunteer_match_form.find('.was-validated').removeClass('was-validated');
		
					//if the input is not a digit don't type anything
					if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
						return false;
					}
				});
		
		
		}


		function update_opp_form_inputs(){
			var id = volunteer_match_opportunity.find('input[name="volunteer_match_opp_id"]');
			var opp_title = volunteer_match_opportunity.find('input[name="volunteer_match_opp_title"]');
			var is_covid = volunteer_match_opportunity.find('input[name="volunteer_match_opp_is_covid"]');
			var parent_org_id = volunteer_match_opportunity.find('input[name="volunteer_match_opp_parent_org_id"]');
			var parent_name = volunteer_match_opportunity.find('input[name="volunteer_match_opp_parent_org_name"]');
			var opp_location = volunteer_match_opportunity.find('input[name="volunteer_match_opp_location"]');
			var opp_container = volunteer_match_opportunity.find('input[name="volunteer_match_opp_container"]');
			var category = volunteer_match_opportunity.find('input[name="volunteer_match_opp_categories"]');
			var ints = volunteer_match_opportunity.find('input[name="volunteer_match_opp_interests"]');

			// update oppID if found
			if( oppID.length ){
				oppID.val(id.val());
			}
			// update oppTitle if found
			if( oppTitle.length ){
				oppTitle.val(opp_title.val());
			}
			// update oppIsCovid19 if found
			if( oppIsCovid19.length ){
				oppIsCovid19.val(is_covid.val());
			}
			
			// update parentOrgID if found
			if( parentOrgID.length ){
				parentOrgID.val(parent_org_id.val());
			}
	
			// update parentName if found
			if( parentName.length ){
				parentName.val(parent_name.val());
			}

			// update oppLocation if found
			if( oppLocation.length ){
				oppLocation.val(opp_location.val());
			}

			// update interests if found
			if( interests.length ){
				interests.val(ints.val());
			}

			// updated categories if found
			if( categories.length ){
				categories.val(category.val());
			}

			// update container if found
			if( container.length ){
				container.val( opp_container.val() );
			}

		}

	}
});
