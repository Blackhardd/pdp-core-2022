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


register_activation_hook( __FILE__, 'activate_pdp_core' );
function activate_pdp_core(){
	require_once PDP_PLUGIN_PATH . 'includes/class-pdp_core-activator.php';
	PDP_Core_Activator::activate();
}


register_deactivation_hook( __FILE__, 'deactivate_pdp_core' );
function deactivate_pdp_core(){
	require_once PDP_PLUGIN_PATH . 'includes/class-pdp_core-deactivator.php';
	PDP_Core_Deactivator::deactivate();
}

require PDP_PLUGIN_PATH . 'includes/class-pdp_core.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pdp_core() {
	$plugin = new PDP_Core();
	$plugin->run();
}
run_pdp_core();


/**
 * Require plugin functions file.
 */
require_once PDP_PLUGIN_PATH . 'pdp_core-functions.php';