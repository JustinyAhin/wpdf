<?php

/**
 * @link              https://segebdji.com
 * @since             1.0
 * @package           WP Data Filter
 *
 * @wordpress-plugin
 * Plugin Name:       WP Data Filter
 * Plugin URI:        https://github.com/JustinyAhin/wpdf
 * Description:       Create, filter, sort and display users data.
 * Version:           1.0
 * Author:            Justin Ahinon
 * Author URI:        http://segbedji.com
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.en.html
 * Text Domain:       wpdf
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * i18n
 */
	load_plugin_textdomain( 'wpdf', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 

/**
 * Include the plugin functions file
 */
require_once plugin_dir_path( __FILE__ ) . 'admin/wpdf-functions.php';