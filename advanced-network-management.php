<?php
/**
 * Plugin Name: Advanced Network Management for Multisite
 * Plugin URI:  https://github.com/lauratheq/advanced-network-management
 * Description: This plugin helps managing WordPress plugins accross a multisite network. It provides advanced visibility options and provides the possibility to force de-/activation.
 * Version:     0.1
 * Author:      Laura Herzog
 * Author URI:  https://github.com/lauratheq/
 * Text Domain: advanced-network-management
 * Domain Path: /languages
 * License:     GPL v3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Network:     true
 *
 * @package     anmfm
 */

// check if WordPress is loaded.
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

// we only need the following stuff in the admin area.
if ( ! is_admin() ) {
	return;
}

// set needed constant.
define( 'ANMFM_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Initialize the plugin.
 *
 * @wp-hook plugins_loaded
 *
 * @return void
 */
function anmfm_init() {
	// get the helpers.
	require_once __DIR__ . '/src/helper.php';

	// load the textdomain.
	require_once __DIR__ . '/src/load-plugin-textdomain.php';
	add_action( 'init', 'anmfm_load_plugin_textdomain' );

	// stuff only needed in single admin area.
	if ( is_admin() ) {
		// remove from the plugin list.
		require_once __DIR__ . '/src/remove-from-plugin-list.php';
		add_action( 'plugins_list', 'anmfm_remove_from_plugin_list' );
	}

	// stuff only needed in network admin area.
	if ( ! is_network_admin() ) {
		return;
	}

	// add the edit link to the overview page.
	require_once __DIR__ . '/src/add-link-to-rowactions.php';
	add_action( 'network_admin_plugin_action_links', 'anmfm_network_admin_plugin_action_links', 10, 2 );

	// adding the submenu page.
	require_once __DIR__ . '/src/class-anmfm-plugin-management-wp-list-table.php';
	require_once __DIR__ . '/src/visibility-submenu-page.php';
	add_action( 'network_admin_menu', 'anmfm_add_visibility_submenu' );
	add_action( 'admin_head', 'anmfm_remove_submenu_page' );
} add_action( 'plugins_loaded', 'anmfm_init' );
