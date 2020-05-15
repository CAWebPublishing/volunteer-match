jQuery(document).ready(function(){
	$ = jQuery.noConflict();

	$('#volunteer-match-add-interest').click( function(e){
		e.preventDefault();
		var ol = $('#volunteer-match-current-interests');
		var li = document.createElement('LI');
		var row = document.createElement('DIV');
		var col1 = document.createElement('DIV');
		var col2 = document.createElement('DIV');

		var interest_label = document.createElement('LABEL');
		var interest_input = document.createElement('INPUT');
		var remove_interest_button = document.createElement('BUTTON');

		var category_label = document.createElement('LABEL');
		var category_select = document.createElement('SELECT');

		$(row).addClass('row');
		$(col1).addClass('col-6');
		$(col2).addClass('col-6');

		$(interest_label).html('Topic: <span class="text-danger">*</span>');
		$(interest_label).addClass('mb-1');

		$(interest_input).attr('type', 'text');
		$(interest_input).addClass('form-control');
		$(interest_input).attr('name', 'volunteer_match_interests[]');
		$(interest_input).attr('required', 'required');
		
		$(remove_interest_button).addClass('btn btn-danger mt-2 remove-topic');
		$(remove_interest_button).html('Remove');
		remove_interest_button.addEventListener('click', function(){ remove_interest_topic($(this));} );

		$(category_label).html('Categories: ( Select all that apply. )');
		$(category_label).addClass('d-block mb-1');

		$(category_select).addClass('custom-select');
		$(category_select).attr('multiple', 'multiple');

		do{
			var rand = Math.floor( Math.random() * 1000 );

		}while( $(`select[name="volunteer_match_interests_cats[${rand}][]"]`).length )

		$(category_select).attr('name', `volunteer_match_interests_cats[${rand}][]`);

		volunteer_match_args.categories.forEach( function(val, i){
			var o = document.createElement('OPTION');

			$(o).attr('value', val);
			$(o).html(val);

			if( ! i ){
				$(o).attr('selected', 'selected');
			}

			$(category_select).append(o);
		})

		$(col1).append(interest_label);
		$(col1).append(interest_input);
		$(col1).append(remove_interest_button);

		$(col2).append(category_label);
		$(col2).append(category_select);

		$(row).append(col1);
		$(row).append(col2);

		$(li).append(row);

		ol.append(li);
	});

	$('#volunteer-match-interests .remove-topic').click( function(){remove_interest_topic(this);} )

	$('#volunteer_match_opp_endpoint').on('input', function(e){
		if( $(this).val().trim() ){
			$('#vm-opp-graphql').removeClass('hidden');
			$('#vm-opp-environment').addClass('hidden');
		}else{
			$('#vm-opp-graphql').addClass('hidden');
			$('#vm-opp-environment').removeClass('hidden');
		}
	});

	$('#volunteer_match_create_connection_endpoint').on('input', function(e){
		if( $(this).val().trim() ){
			$('#vm-create-connection-graphql').removeClass('hidden');
			$('#vm-create-connection-environment').addClass('hidden');
		}else{
			$('#vm-create-connection-graphql').addClass('hidden');
			$('#vm-create-connection-environment').removeClass('hidden');
		}
	});

	function remove_interest_topic( button ){
		$(button).parent().parent().parent().remove();
	}
});