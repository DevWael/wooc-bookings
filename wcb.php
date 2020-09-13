<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/DevWael
 * @since             1.0.0
 * @package           Wcb
 *
 * @wordpress-plugin
 * Plugin Name:       Woocomemrce Booking System
 * Plugin URI:        https://github.com/DevWael/wooc-bookings
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0.1
 * Author:            Ahmad Wael
 * Author URI:        https://github.com/DevWael
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wcb
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WCB_VERSION', '1.0.0' );
define( 'WCB_DIR', plugin_dir_path( __FILE__ ) );
define( 'WCB_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wcb-activator.php
 */
function activate_wcb() {
	require_once WCB_DIR . 'includes/class-wcb-activator.php';
	Wcb_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wcb-deactivator.php
 */
function deactivate_wcb() {
	require_once WCB_DIR . 'includes/class-wcb-deactivator.php';
	Wcb_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wcb' );
register_deactivation_hook( __FILE__, 'deactivate_wcb' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require WCB_DIR . 'includes/class-wcb.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wcb() {

	$plugin = new Wcb();
	$plugin->run();

}

run_wcb();