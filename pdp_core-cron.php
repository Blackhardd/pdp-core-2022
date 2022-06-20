<?php

add_action( 'pdp_cron_update_pricelists_daily', 'pdp_autoupdate_pricelists' );

function pdp_autoupdate_pricelists(){
	if( get_option( '_prices_autoupdate_enabled' ) === 'true' ){
		pdp_fetch_pricelists();
	}
}

add_action( 'pdp_cron_update_instagram_feed_daily', 'pdp_instagram_feed_update' );

function pdp_instagram_feed_update(){
	$instagram = new PDP_Core_Instagram();
	$instagram->maybe_refresh_token();
	$instagram->fetch_user_media();
}