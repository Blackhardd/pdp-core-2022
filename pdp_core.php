<?php

/**
 *
 * @link              https://www.instagram.com/lovu_volnu/
 * @since             1.0.0
 * @package           PDP_Core
 *
 * @wordpress-plugin
 * Plugin Name:       PIED-DE-POULE Core
 * Plugin URI:        https://www.instagram.com/lovu_volnu/
 * Description:       Core functionality plugin.
 * Version:           1.0.1
 * Author:            Alexander Piskun
 * Author URI:        https://www.instagram.com/lovu_volnu/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pdp_core
 * Domain Path:       /languages
 */

if( !defined( 'WPINC' ) ) :
	die;
endif;


define( 'PDP_CORE_VERSION', '1.0.2' );
define( 'PDP_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );


require PDP_PLUGIN_PATH . 'includes/class-pdp_core.php';
require PDP_PLUGIN_PATH . 'pdp_core-functions.php';
require PDP_PLUGIN_PATH . 'pdp_core-cron.php';


/**
 *  Initialize CRON tasks on plugin activation.
 */

register_activation_hook( __FILE__, 'pdp_init_cron' );

function pdp_init_cron(){
	if( !wp_next_scheduled( 'pdp_cron_update_pricelists_daily' ) ){
		wp_clear_scheduled_hook( 'pdp_cron_update_pricelists_daily' );
		wp_schedule_event( strtotime( date( 'Y-m-d', time() ) . ' 02:00:00' ), 'daily', 'pdp_cron_update_pricelists_daily' );
	}

	if( !wp_next_scheduled( 'pdp_cron_update_instagram_feed_daily' ) ){
		wp_clear_scheduled_hook( 'pdp_cron_update_instagram_feed_daily' );
		wp_schedule_event( strtotime( date( 'Y-m-d', time() ) . ' 03:00:00' ), 'daily', 'pdp_cron_update_instagram_feed_daily' );
	}
}


/**
 *  Clear CRON tasks on plugin deactivation.
 */

register_deactivation_hook( __FILE__, 'pdp_clear_cron' );

function pdp_clear_cron(){
	wp_clear_scheduled_hook( 'pdp_cron_update_pricelists_daily' );
	wp_clear_scheduled_hook( 'pdp_cron_update_instagram_feed_daily' );
}

$plugin = new PDP_Core();
$plugin->run();