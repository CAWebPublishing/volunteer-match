jQuery(document).ready(function(){
	$ = jQuery.noConflict();
	
	var volunteer_match = $('#volunteer-match');

	if( volunteer_match.length ){
		var google_place_link = 'https://www.google.com/maps/place/';

		// Volunteer Match Shortcode Attributes
		var hidden_match = $('div#volunteer-match input[name="volunteer_match_hidden"]');
		var forms_id = $('div#volunteer-match input[name="volunteer_match_form_id"]');
		var link_color = $('div#volunteer-match input[name="volunteer_match_link_color"]');
		var button_size = $('input[name="volunteer_match_button_size"]'); 
		var button_color = $('input[name="volunteer_match_button_color"]'); 
		var button_failed_color = $('input[name="volunteer_match_button_failed_color"]'); 
		var button_font_color = $('input[name="volunteer_match_button_font_color"]'); 
		var button_failed_text = $('input[name="volunteer_match_button_failed_text"]'); 
		var button_connection_text = $('input[name="volunteer_match_button_connection_text"]'); 
		var button_connection_exist_text = $('input[name="volunteer_match_button_connection_exist_text"]'); 
		var button_text = $('input[name="volunteer_match_button_text"]'); 
		var show_parent_org = $('input[name="volunteer_match_show_parent_org"]');
		var show_title = $('input[name="volunteer_match_show_title"]');
		var show_location = $('input[name="volunteer_match_show_location"]');
		var show_description = $('input[name="volunteer_match_show_description"]');
		var description_type = $('input[name="volunteer_match_description_type"]');
		var description_expanded_icon = $('input[name="volunteer_match_description_expanded_icon"]');
		var description_collapsed_icon = $('input[name="volunteer_match_description_collapsed_icon"]');
		var show_mission = $('input[name="volunteer_match_show_mission"]');
		var show_notify = $('input[name="volunteer_match_show_notify"]');
		var show_dates = $('input[name="volunteer_match_show_date"]');
		var response_page = $('input[name="volunteer_match_response_page"]');

		// Volunteer Match DOMs
		var volunteer_match_form = $('div#volunteer-match form');
		var volunteer_match_opps = $('div#volunteer-match #volunteer-match-opps');
		var volunteer_match_opps_list = $('div#volunteer-match #volunteer-match-opp-list');
		var volunteer_match_opps_pagination = $('div#volunteer-match #volunteer-match-opps .pagination');
		var volunteer_match_opps_current_page_view = $('div#volunteer-match #volunteer-match-opps .current-page-view');
		var volunteer_match_form_nonce = $('div#volunteer-match input[name="volunteer_match_search_opportunities_nonce"]');
		var volunteer_match_location = $('div#volunteer-match input[name="volunteer_match_location"]');
		var volunteer_match_covid19 = $('div#volunteer-match input[name="volunteer_match_covid19"]');
		var volunteer_match_local_type = $('div#volunteer-match input[name="volunteer_match_type"][value="local"]');
		var volunteer_match_search_button = $('button#volunteer-match-search');
		
		// Associated Form
		var associated_form = {};

		// Form Params
		var firstName, lastName, email, phoneNumber, zip, acceptTerms = {};
		var oppID, oppTitle, oppLocation, oppIsCovid19, parentOrgID, parentName, interests, categories = {};

		if( forms_id.length ){
			associated_form = $( `#${forms_id.val()}` );
			// associated form opportunity inputs
			firstName = associated_form.find('.firstName input');
			lastName = associated_form.find('.lastName input');
			email = associated_form.find('.email input');
			phoneNumber = associated_form.find('.phoneNumber input');
			zip = associated_form.find('.zip input');
			acceptTerms = associated_form.find('.acceptTerms input');
			oppID = associated_form.find('.oppID input');
			oppTitle = associated_form.find('.title input');
			oppLocation = associated_form.find('.location input');
			oppIsCovid19 = associated_form.find('.specialFlag input');
			parentOrgID = associated_form.find('.parentOrgID input');
			parentName = associated_form.find('.parentName input');
			interests = associated_form.find('.interests input');
			categories = associated_form.find('.categories input');
		}

		if ( associated_form.length ){
			if( ! hidden_match.length || "true" !== hidden_match.val() ){
				associated_form.addClass('hidden');
			}else{
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

							associated_form.addClass('hidden');
							volunteer_match.removeClass('hidden');
							
							volunteer_match_location.val( zip.val() );

							volunteer_match_search_button.click();
						}
						
					}
				});
			}
		}

		volunteer_match_location.keypress( function(e){
			volunteer_match_form.find('.was-validated').removeClass('was-validated');

			//if the input is not a digit don't type anything
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				return false;
			}
		});

		volunteer_match_search_button.click( function(e){ search_for_opportunities(e, undefined); } );

		function search_for_opportunities( e, page){
			e.preventDefault();

			if( undefined !== page ){
				response_page.val($(page).attr('href').replace('#', ''));
			}else{
				response_page.val(1);
			}

			volunteer_match_form.validate();

			if ( associated_form.length ){
				associated_form.addClass('hidden');
			}
	
			if( volunteer_match_form.valid() ){
				volunteer_match_opps.removeClass('hidden');
				volunteer_match_opps_pagination.empty();
				volunteer_match_opps_current_page_view.empty();
				
				volunteer_match_opps_list.empty();
				volunteer_match_opps_list.append('Looking for opportunities...<div class="spinner-border text-secondary" role="status"><span class="sr-only">Looking for opportunities</span></div>');
	
				var fd = new FormData( volunteer_match_form.get(0) );
				fd.append("action", "volunteer_match_return_opportunities");
	
				jQuery.ajax({
					type: 'POST',
					url: volunteer_match_args.ajaxurl,
					contentType: false,
					processData: false,
					data: fd,
					success: function(response) {
						if( "string" === typeof response && response.trim() ){
							var volunteer_match_search_response = JSON.parse( response );
						
							if( volunteer_match_search_response.numberOfResults || volunteer_match_search_response.resultsSize ){
								add_pagination(volunteer_match_search_response );
			
								volunteer_match_opps_list.empty();
	
								volunteer_match_search_response.opportunities.forEach(function(opp){
									volunteer_match_add_opp(opp);
								});
							}else if( response.error ){
								var error_msg = undefined !== conn.response ? ` Error Message: ${conn.response}` : '';

								volunteer_match_opps_list.html(error_msg);
							}else{
								volunteer_match_opps_list.html('<span>No Opportunities Available.</span>');
							}
						}else{
							volunteer_match_opps_list.html('<span>An error occurred while retrieving opportunities.</span>');
						}

					},
				});
				
			}else{
				$( 'div#volunteer-match form input:invalid' ).parent().addClass('was-validated')
			}
		}

		function add_pagination( res ){
			var pagination_nav = document.createElement('NAV');
			var page_list = document.createElement('UL');

			var startPage = res.currentPage + 1;
			var endPage = startPage + 9;
			var minPage = endPage - 10;
			var totalPages = Math.ceil( res.resultsSize / 100 );

			volunteer_match_opps_current_page_view.html('Viewing Page ' + res.currentPage + ' of ' + totalPages);

			$(page_list).addClass('d-flex pagination pagination-sm mb-0 p-0');

			if( startPage > 2 ){
				var prev_10_li = document.createElement('LI');
				var prev_10_a = document.createElement('A');
				var previous = startPage - 11 ? startPage - 11 : 1;
				$(prev_10_li).addClass('page-item');

				$(prev_10_a).addClass('page-link');
				$(prev_10_a).attr('href', `#${previous}`);
				$(prev_10_a).attr('title', 'Previous 10 Pages');
				$(prev_10_a).html('<span aria-hidden="true">&lsaquo;</span><span class="sr-only">Previous 10 Pages</span>');
				prev_10_a.addEventListener( 'click', function(e){ search_for_opportunities(e, this); });

				$(prev_10_li).append(prev_10_a);

				
				if( minPage > 10 ){
					var first_10_li = document.createElement('LI');
					var first_10_a = document.createElement('A');
	
					$(first_10_li).addClass('page-item');
	
					$(first_10_a).addClass('page-link');
					$(first_10_a).attr('href', `#1`);
					$(first_10_a).attr('title', 'First 10 Pages');
					$(first_10_a).html('<span aria-hidden="true">&laquo;</span><span class="sr-only">First 10 Pages</span>');
					first_10_a.addEventListener( 'click', function(e){ search_for_opportunities(e, this); });
	
					$(first_10_li).append(first_10_a);
	
					$(page_list).append(first_10_li);
				}

				$(page_list).append(prev_10_li);
	
			}
			
			for(var p = startPage; p <= endPage && p <= totalPages; p++){
				var page_li = document.createElement('LI');
				var page_a = document.createElement('A');

				$(page_li).addClass('page-item');

				$(page_a).addClass('page-link');
				$(page_a).attr('href', `#${p}`);
				$(page_a).attr('title', `Page ${p}`);
				$(page_a).html(p);
				page_a.addEventListener( 'click', function(e){ search_for_opportunities(e, this); });

				$(page_li).append(page_a);
				$(page_list).append(page_li);
			}

			if( endPage < totalPages ){
				var last_10_li = document.createElement('LI');
				var last_10_a = document.createElement('A');

				$(last_10_li).addClass('page-item');

				$(last_10_a).addClass('page-link');
				$(last_10_a).attr('href', `#${totalPages - 10}`);
				$(last_10_a).attr('title', 'Last 10 Pages');
				$(last_10_a).html('<span aria-hidden="true">&raquo;</span><span class="sr-only">Last 10 Pages</span>');
				last_10_a.addEventListener( 'click', function(e){ search_for_opportunities(e, this); });

				$(last_10_li).append(last_10_a);

				if( (endPage + 1) < (totalPages - 10) ){
					var next_10_li = document.createElement('LI');
					var next_10_a = document.createElement('A');
	
					$(next_10_li).addClass('page-item');
	
					$(next_10_a).addClass('page-link');
					$(next_10_a).attr('href', `#${endPage + 1}`);
					$(next_10_a).attr('title', 'Next 10 Pages');
					$(next_10_a).html('<span aria-hidden="true">&rsaquo;</span><span class="sr-only">Next 10 Pages</span>');
					next_10_a.addEventListener( 'click', function(e){ search_for_opportunities(e, this); });
	
					$(next_10_li).append(next_10_a);

					$(page_list).append(next_10_li);
				}
				$(page_list).append(last_10_li);
			}
			$(pagination_nav).append(page_list);

			if( totalPages > 1 ){
				volunteer_match_opps_pagination.append(pagination_nav);
			}

			return volunteer_match_opps_pagination;
		}

		function volunteer_match_add_opp(opp){
			// Structural Variables
			var li = document.createElement('LI');
			var div_row = document.createElement('DIV');
			var col1 = document.createElement('DIV');
			var col2 = document.createElement('DIV');

			// Field Variables
			var sign_up = document.createElement('BUTTON');
			
			$(li).addClass( `opp-match-item-${opp.id}`);

			// Row
			$(div_row).addClass('row mb-2');

			// Columns
			$(col1).addClass('col-10');
			$(col2).addClass('col-2');

			// Sign Up Button
			$(sign_up).addClass('float-right btn btn-primary opp-match-sign-up');
			if( button_size.length ){
				$(sign_up).addClass( button_size.val() );
			}else{
				$(sign_up).addClass( 'btn-md' );
			}
			$(sign_up).html( button_text.val() );
			$(sign_up).attr('id', `sign-up-${opp.id}` );
			if( button_color.length ){
				$(sign_up).css('background-color', button_color.val() );
			}
			if( button_font_color.length ){
				$(sign_up).css('color', button_font_color.val() );
			}
			sign_up.addEventListener('click', function(){ sign_up_for_opp( $(this), opp) } );
			$(col2).append(sign_up);

			// Append
			if( ! show_parent_org.length && undefined !== opp.parentOrg){
				// Parent Org
				var parentOrg = document.createElement('STRONG');

				$(parentOrg).html(`Organization: ${opp.parentOrg.name}`);
				$(parentOrg).addClass('d-block opp-match-parent-org-name');

				$(col1).append(parentOrg);
			}

			if( ! show_mission.length && undefined !== opp.parentOrg){
				// Parent Mission
				var parentOrgDiv = document.createElement('DIV');
				var parentOrg = document.createElement('STRONG');
				var parentMissionDiv = document.createElement('DIV');

				$(parentOrgDiv).addClass('opp-match-parent-org-mission');

				$(parentOrg).html(`Mission:`);

				$(parentMissionDiv).html(`${opp.parentOrg.mission}`);

				$(parentOrgDiv).append(parentOrg);
				$(parentOrgDiv).append(parentMissionDiv);

				$(col1).append(parentOrgDiv);
			}
			
			if( ! show_title.length ){
				// Title
				var titleDiv = document.createElement('DIV');
				var title = document.createElement('STRONG');
				var titleSpan = document.createElement('SPAN');

				$(titleDiv).addClass('opp-match-title');

				$(title).html(`Opportunity: `);
				
				$(titleSpan).html(`${opp.title}`);

				$(titleDiv).append(title);
				$(titleDiv).append(titleSpan);
				$(col1).append(titleDiv);
			}

			if( ! show_dates.length && undefined !== opp.dateRange ){
				var dateDiv = document.createElement('DIV');
				// Dates
				$(dateDiv).addClass('opp-match-dates');
				$(dateDiv).append( add_opp_dates( opp ) );

				$(col1).append( dateDiv );
			}

			if( volunteer_match_local_type.prop('checked') ){
				// Location
				if( ! show_location.length ){
					var locationDiv = document.createElement('DIV');
					
					$(locationDiv).addClass('opp-match-location');
					$(locationDiv).append( add_opp_location( opp ) );

					$(col1).append( locationDiv );
				}
			}

			if( ! show_description.length ){
				var descDiv = document.createElement('DIV');
				// Description
				$(descDiv).addClass('opp-match-description');
				$(descDiv).append( add_opp_description( opp ) );

				$(col1).append( descDiv );
			}

			$(div_row).append(col1);
			$(div_row).append(col2);

			$(li).append(div_row);

			volunteer_match_opps_list.append(li);

		}

		function add_opp_dates( opp ){
			if( opp.dateRange.ongoing ){
				return '<strong>Dates: </strong><span>Ongoing</span>';
			}else{
				var s = null !== opp.dateRange.startDate ? `${opp.dateRange.startDate}` : '';
				var e = null !== opp.dateRange.endDate ? `${opp.dateRange.endDate}` : '';
				var format = { month: 'long', day: 'numeric', year: 'numeric'}
				if( s.trim() && null !== opp.dateRange.startDate){
					s = new Date(s).toLocaleDateString('en-US', format);
					s = ` ${s}`
				}

				if ( e.trim() && null !== opp.dateRange.endDate){
					e = new Date(e).toLocaleDateString('en-US', format);
					e = ` ${e}`
				}

				if( s.trim() && e.trim() ){
					s += ' -';
				}

				return `<strong>Dates:</strong><span>${s}${e}</span>`;

			}
		}

		function add_opp_location( opp ){
			var location_icon = document.createElement('SPAN');
			var location_link = document.createElement('A');
	
			// Location
			var addr = `${opp.location.street1},${opp.location.city},${opp.location.region},${opp.location.postalCode}`;

			$(location_icon).addClass('dashicons dashicons-location');
			$(location_link).attr( 'href', `${google_place_link}${addr}`);
			$(location_link).attr( 'target', '_blank');
			if( link_color.length ){
				$(location_link).css( 'color', link_color.val() );
			}
			
			$(location_link).html(addr);
			$(location_link).prepend(location_icon);
			
			return location_link;
		}

		function add_opp_description( opp ){
			var col = document.createElement('DIV');
	
			var description_anchor = document.createElement('A')
			var description_icon = document.createElement('SPAN');
			var description = document.createElement('DIV');

			// Description
			$(description).attr( 'id', `#opp-description-${opp.id}` );
			$(description).addClass('collapse');

			if( 'plaintext' === description_type.val() ){
				$(description).html(opp.plaintextDescription);
			}else{
				$(description).html(opp.description);
			}
			$(description).collapse({toggle:false});
			
			$(description_anchor).addClass( 'text-reset' );
			$(description_anchor).attr( 'data-toggle', 'collapse');
			$(description_anchor).attr( 'href', `#opp-description-${opp.id}` );
			$(description_anchor).attr( 'aria-expanded', 'false');
			$(description_anchor).attr( 'aria-controls', `opp-description-${opp.id}`);
			$(description_anchor).html( 'Description');
			
			$(description_icon).addClass('dashicons align-middle ' + 'dashicons-' + description_expanded_icon.val() + ' dashicons-' + description_collapsed_icon.val() );
			$(description_anchor).append( description_icon );

			$(col).append(description_anchor);
			$(col).append(description);

			// Events
			description_anchor.addEventListener( 'click', function(e){
				$(description).collapse('toggle');
				$(description_icon).toggleClass('dashicons-' + description_collapsed_icon.val() );
			});
			return col;
		}

		function sign_up_for_opp( current_button, opp ){
			current_button.prepend('<div class="spinner-border-sm spinner-border mr-2" role="status"><span class="sr-only">Signing up...</span></div>');

			// if there is a form associated
			if( associated_form.length ){

				// create connection
				var vmfd = new FormData();
				vmfd.append( 'action', 'volunteer_match_create_connection' );
				vmfd.append('firstName', firstName.val() );
				vmfd.append('lastName', lastName.val() );
				vmfd.append('email', email.val() );
				vmfd.append('phoneNumber', phoneNumber.val() );
				vmfd.append('zip', zip.val() );
				vmfd.append('oppId', opp.id );
				vmfd.append('volunteer_match_search_opportunities_nonce', volunteer_match_form_nonce.val() );

				jQuery.ajax({
					type: 'POST',
					url: volunteer_match_args.ajaxurl,
					data: vmfd,
					cache: false,
					contentType: false,
					processData: false,
					success: function( conn ){

						if( undefined === conn.error ){
							current_button.html( button_connection_text.val() );

							// update any opportunity inputs that may exist
							update_opp_form_inputs( opp );

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
									alert( `Thank you for you interest in ${opp.title}.`);
								}
							 })
						}else{
							var error_msg = undefined !== conn.response ? conn.response : '';
							

							if( 400 === conn.error_code ){
								alert(error_msg);
								current_button.html(button_connection_exist_text.val());
							}else{
								alert(`An error occurred while signing up for ${opp.title}, please try again later. Error Message: ${error_msg}`);
								
								current_button.html(button_failed_text.val());
								current_button.removeClass('btn-primary');
								current_button.addClass('btn-secondary');
	
								if( button_failed_color.length ){
									current_button.css('background-color', button_failed_color.val() );
								}
								}
							
						}
					}
				 })
			}

		}

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

		}

	}
});
