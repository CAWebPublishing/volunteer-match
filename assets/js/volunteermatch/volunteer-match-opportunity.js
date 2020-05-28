jQuery(document).ready(function(){
	$ = jQuery.noConflict();
	
	var volunteer_match_opportunity = $('#volunteer-match-opportunity');

	if( volunteer_match_opportunity.length ){
		
		// Volunteer Match Shortcode Attributes
		var forms_id = $('div#volunteer-match-opportunity input[name="volunteer_match_form_id"]');

		// Volunteer Match DOMs
		var volunteer_match_form_nonce = $('div#volunteer-match-opportunity input[name="volunteer_match_opportunity_nonce"]');
		
		// Associated Form
		var associated_form = {};

		// Form Params
		var oppID, oppTitle, oppLocation, oppIsCovid19, parentOrgID, parentName, interests, categories = {};

		if( forms_id.length ){
			associated_form = $( `#${forms_id.val()}` );
			// associated form opportunity inputs
			oppID = associated_form.find('.oppID input');
			oppTitle = associated_form.find('.title input');
			oppLocation = associated_form.find('.location input');
			oppIsCovid19 = associated_form.find('.specialFlag input');
			parentOrgID = associated_form.find('.parentOrgID input');
			parentName = associated_form.find('.parentName input');
			interests = associated_form.find('.interests input');
			categories = associated_form.find('.categories input');
			container = associated_form.find(' .container input');
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
									if( show_notify.length && true == show_notify.val() ){
										alert( `Thank you, ${firstName.val()} ${lastName.val()} for you interest in volunteering.`);
									}
								}
							 })
						}
						
					}
				});
		}

		volunteer_match_location.keypress( function(e){
			volunteer_match_form.find('.was-validated').removeClass('was-validated');

			//if the input is not a digit don't type anything
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				return false;
			}
		});

		function update_opp_form_inputs( opp ){
			// update oppID if found
			if( oppID.length ){
				oppID.val(opp.id);
			}
			// update oppTitle if found
			if( oppTitle.length ){
				oppTitle.val(opp.title);
			}
			// update oppIsCovid19 if found
			if( oppIsCovid19.length ){
				oppIsCovid19.val(volunteer_match_covid19.prop('checked'));
			}
			
			// if there is parentOrg information
			if( undefined !== opp.parentOrg ){
				// update parentOrgID if found
				if( parentOrgID.length ){
					parentOrgID.val(opp.parentOrg.id);
				}
				// update parentName if found
				if( parentName.length ){
					parentName.val(opp.parentOrg.name);
				}
			}

			// update oppLocation if found
			if( oppLocation.length ){
				var addr = `${opp.location.street1},${opp.location.city},${opp.location.region},${opp.location.postalCode}`;
				oppLocation.val(addr);
			}

			// update oppLocation if found
			if( zip.length ){
				zip.val(volunteer_match_location.val());
			}

			var cats = $('input[name="volunteer_match_interests[]"]:checked');

			if( cats.length ){
				var interest = '';
				var category = '';

				$.each( cats.slice(1), function(i, c){
					interest += `,${c.title}`;
					category += `,${c.value}`;
				});

				// update interests if found
				if( interests.length ){
					interests.val($(cats[0]).html() + interest);
				}
				// updated categories if found
				if( categories.length ){
					categories.val($(cats[0]).val() + category);
				}
			}

			// update container if found
			if( container.length ){
				container.val( opp.container );
			}

		}

	}
});
