<?php

add_action( 'pdp_cron_update_pricelists_daily', 'pdp_autoupdate_pricelists' );

function pdp_autoupdate_pricelists(){
	if( carbon_get_theme_option( 'prices_autoupdate_enabled' ) === 'true' ){
		pdp_fetch_pricelists();
	}
}