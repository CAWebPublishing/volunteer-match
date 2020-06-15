jQuery(document).ready(function(){
	$ = jQuery.noConflict();

	var age_groups = ['groups', 'kids', 'seniors', 'teens'];

	$('#volunteer-match-add-great-for').click( function(e){
		e.preventDefault();
		var ol = $('#volunteer-match-current-great-for');
		var li = document.createElement('LI');
		var row = document.createElement('DIV');
		var col1 = document.createElement('DIV');
		var col2 = document.createElement('DIV');

		var great_for_label = document.createElement('LABEL');
		var great_for_select = document.createElement('SELECT');
		var remove_great_for_button = document.createElement('BUTTON');

		var age_group_label = document.createElement('LABEL');
		var age_group_select = document.createElement('SELECT');

		$(row).addClass('row');
		$(col1).addClass('col-6');
		$(col2).addClass('col-6');

		$(great_for_label).html('Age Group: <span class="text-danger">*</span>');
		$(great_for_label).addClass('mb-1');

		$(great_for_select).addClass('custom-select d-block');
		
		$(great_for_select).attr('name', `volunteer_match_great_for[]`);

		volunteer_match_args.age_groups.forEach( function(val, i){
			var o = document.createElement('OPTION');

			$(o).attr('value', val);
			$(o).html(val);

			if( ! i ){
				$(o).attr('selected', 'selected');
			}

			$(great_for_select).append(o);
		})


		$(remove_great_for_button).addClass('btn btn-danger mt-2 remove-great-for');
		$(remove_great_for_button).html('Remove');
		remove_great_for_button.addEventListener('click', function(){ remove_great_for_grouping($(this));} );

		$(age_group_label).html('Group: ( Select all that apply. )');
		$(age_group_label).addClass('d-block mb-1');

		$(age_group_select).addClass('custom-select');
		$(age_group_select).attr('multiple', 'multiple');

		do{
			var rand = Math.floor( Math.random() * 1000 );

		}while( $(`select[name="volunteer_match_great_for_age_groups[${rand}][]"]`).length )

		$(age_group_select).attr('name', `volunteer_match_great_for_age_groups[${rand}][]`);

		age_groups.forEach( function(val, i){
			var o = document.createElement('OPTION');

			$(o).attr('value', val);
			$(o).html(val);

			if( ! i ){
				$(o).attr('selected', 'selected');
			}

			$(age_group_select).append(o);
		})

		$(col1).append(great_for_label);
		$(col1).append(great_for_select);
		$(col1).append(remove_great_for_button);

		$(col2).append(age_group_label);
		$(col2).append(age_group_select);

		$(row).append(col1);
		$(row).append(col2);

		$(li).append(row);

		ol.append(li);
	});

	$('#volunteer-match-great-for .remove-great-for').click( function(){remove_great_for_grouping(this);} )

	function remove_great_for_grouping( button ){
		$(button).parent().parent().parent().remove();
	}
});