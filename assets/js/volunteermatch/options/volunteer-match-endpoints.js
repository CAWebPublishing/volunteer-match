jQuery(document).ready(function(){
	$ = jQuery.noConflict();

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

});