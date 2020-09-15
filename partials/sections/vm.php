<div id="volunteer-match-settings" class="row mr-3 bg-white collapse show" data-parent="#volunteer-match-options-form">
			<!-- VM API Key -->
			<div class="form-group col-lg-7">
				<strong><label for="volunteer_match_api_key" class="mb-0">VolunteerMatch API Key</label></strong>
				<small class="form-text text-muted mt-0">For more information on How to Get an API Key, visit <a href="https://github.com/volunteermatch/vm-contrib/tree/master/graphql#getting-an-api-key" target="_blank">here</a>.</small>
				<input type="text" class="form-control" id="volunteer_match_api_key" name="volunteer_match_api_key" value="<?php print esc_attr( $volunteer_match_api_key ); ?>">
			</div>
			<!-- EndPoint Key -->
			<div class="form-group col-lg-7">
				<strong><label for="volunteer_match_endpoint_key" class="mb-0">Endpoint Key</label></strong>
				<small class="form-text text-muted mt-0">If endpoint is requires a key to allow access, enter key here.</small>
				<input type="text" class="form-control" id="volunteer_match_endpoint_key" name="volunteer_match_endpoint_key" value="<?php print esc_attr( $volunteer_match_endpoint_key ); ?>">
			</div>
			<!-- VM Opportunities EndPoint -->
			<div class="form-group col-lg-7">
				<strong><label for="volunteer_match_opp_endpoint" class="mb-0">VolunteerMatch Opportunities Endpoint</label></strong>
				<small class="form-text text-muted mt-0">If endpoint is not set, the VolunteerMatch EndPoints will be used. For more information on Making Calls visit <a href="https://github.com/volunteermatch/vm-contrib/tree/master/graphql#making-calls" target="_blank">here</a>.</small>
				<input type="text" class="form-control" id="volunteer_match_opp_endpoint" name="volunteer_match_opp_endpoint" value="<?php print esc_url( $volunteer_match_opp_endpoint ); ?>">
			</div>
			<!-- VM Opportunites EndPoint Using GraphQL -->
			<div id="vm-opp-graphql" class="form-group col-lg-7<?php print empty($volunteer_match_opp_endpoint) ? ' hidden' : '';?>">
				<strong>Is using <a href="https://graphql.org/">GraphQL</a>?</strong>
				<div class="form-check form-check-inline pl-0">
					<label for="volunteer_match_opp_endpoint_graphql" class="mb-0">
						<input type="checkbox" class="form-control" id="volunteer_match_opp_endpoint_graphql" name="volunteer_match_opp_endpoint_graphql" <?php print $volunteer_match_opp_endpoint_graphql;?>>
					</label>
				</div>
				<small class="form-text text-muted">Whether or not the endpoint is using GraphQL.</small>
			</div>
			<!-- VM Opportunites EndPoint Environment -->
			<div id="vm-opp-environment" class="form-group col-lg-7 mb-0<?php print ! empty($volunteer_match_opp_endpoint) ? ' hidden' : '';?>" role="radiogroup" aria-label="VolunteerMatch Environment">
				<strong class="d-block">VolunteerMatch Opportunities EndPoint Environment</strong>
				<small class="form-text text-muted mt-0">All testing should be done in the staging environment. For more information on Making Calls visit <a href="https://github.com/volunteermatch/vm-contrib/tree/master/graphql#making-calls" target="_blank">here</a>.</small>
				<div class="form-check form-check-inline pl-0">
					<label for="volunteer_match_opp_endpoint_environment_staging">
						<input type="radio" class="form-control" id="volunteer_match_opp_endpoint_environment_staging" name="volunteer_match_opp_endpoint_environment" value="staging"<?php print "staging" === $volunteer_match_opp_endpoint_environment ? ' checked' : '';?>>Staging
					</label>
				</div>
				<div class="form-check form-check-inline pl-0">
					<label for="volunteer_match_opp_endpoint_environment_production">
						<input type="radio" class="form-control" id="volunteer_match_opp_endpoint_environment_production" name="volunteer_match_opp_endpoint_environment" value="production"<?php print "production" === $volunteer_match_opp_endpoint_environment ? ' checked' : '';?>>Production
					</label>
				</div>
			</div>
			<!-- VM createConnection EndPoint -->
			<div class="form-group col-lg-7">
				<strong class="d-block"><label for="volunteer_match_create_connection_endpoint" class="mb-0">VolunteerMatch Create Connection Endpoint</label></strong>
				<small class="form-text text-muted mt-0">If endpoint is not set, the VolunteerMatch EndPoints will be used. For more information on Making Calls visit <a href="https://github.com/volunteermatch/vm-contrib/tree/master/graphql#making-calls" target="_blank">here</a>.</small>
				<input type="text" class="form-control" id="volunteer_match_create_connection_endpoint" name="volunteer_match_create_connection_endpoint" value="<?php print esc_url( $volunteer_match_create_connection_endpoint ); ?>">
			</div>
			<!-- VM CreateConnection EndPoint Using GraphQL -->
			<div id="vm-create-connection-graphql" class="form-group col-lg-7<?php print empty($volunteer_match_create_connection_endpoint) ? ' hidden' : '';?>">
				<strong>Is using <a href="https://graphql.org/">GraphQL</a>?</strong>
				<div class="form-check form-check-inline pl-0">
					<label for="volunteer_match_create_connection_endpoint_graphql" class="mb-0">
						<input type="checkbox" class="form-control" id="volunteer_match_create_connection_endpoint_graphql" name="volunteer_match_create_connection_endpoint_graphql" <?php print $volunteer_match_create_connection_endpoint_graphql;?>>
					</label>
				</div>
				<small class="form-text text-muted">Whether or not the endpoint is using GraphQL.</small>
			</div>
			<!-- VM CreateConnection EndPoint Environment -->
			<div id="vm-create-connection-environment" class="form-group col-lg-7<?php print ! empty($volunteer_match_create_connection_endpoint) ? ' hidden' : '';?>" role="radiogroup" aria-label="VolunteerMatch Environment">
				<strong class="d-block">VolunteerMatch Create Connection EndPoint Environment</strong>
				<small class="form-text text-muted mt-0">All testing should be done in the staging environment. For more information on Making Calls visit <a href="https://github.com/volunteermatch/vm-contrib/tree/master/graphql#making-calls" target="_blank">here</a>.</small>
				<div class="form-check form-check-inline pl-0">
					<label for="volunteer_match_create_connection_endpoint_environment_staging">
						<input type="radio" class="form-control" id="volunteer_match_create_connection_endpoint_environment_staging" name="volunteer_match_create_connection_endpoint_environment" value="staging"<?php print "staging" === $volunteer_match_create_connection_endpoint_environment ? ' checked' : '';?>>Staging
					</label>
				</div>
				<div class="form-check form-check-inline pl-0">
					<label for="volunteer_match_create_connection_endpoint_environment_production">
						<input type="radio" class="form-control" id="volunteer_match_create_connection_endpoint_environment_production" name="volunteer_match_create_connection_endpoint_environment" value="production"<?php print "production" === $volunteer_match_create_connection_endpoint_environment ? ' checked' : '';?>>Production
					</label>
				</div>
			</div>
		</div>