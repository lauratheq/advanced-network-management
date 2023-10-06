<?php
/**
 * Plugin Name: Advanced Plugin Management for Multisite
 * Plugin URI:  https://github.com/lauratheq/advanced-plugin-management
 * Description: This plugin helps managing WordPress plugins accross a multisite network. It provides advanced visibility options and provides the possibility to force de-/activation.
 * Version:     0.1 beta
 * Author:      Laura Herzog
 * Author URI:  https://github.com/lauratheq/
 * Text Domain: advanced-plugin-management
 * Domain Path: /languages
 * License:     GPL v3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Network:     true
 *
 * @package     apmfm
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
define( 'APMFM_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Initialize the plugin.
 *
 * @wp-hook plugins_loaded
 *
 * @return void
 */
function apmfm_init() {
	// get the helpers.
	require_once __DIR__ . '/src/helper.php';

	// load the textdomain.
	require_once __DIR__ . '/src/load-plugin-textdomain.php';
	add_action( 'init', 'apmfm_load_plugin_textdomain' );

	// stuff only needed in single admin area.
	if ( is_admin() ) {
		// remove from the plugin list.
		require_once __DIR__ . '/src/remove-from-plugin-list.php';
		add_action( 'plugins_list', 'apmfm_remove_from_plugin_list' );
	}

	// stuff only needed in network admin area.
	if ( ! is_network_admin() ) {
		return;
	}

	// add the edit link to the overview page.
	require_once __DIR__ . '/src/add-link-to-rowactions.php';
	add_action( 'network_admin_plugin_action_links', 'apmfm_network_admin_plugin_action_links', 10, 2 );

	// adding the submenu page.
	require_once __DIR__ . '/src/class-plugin-management-wp-list-table.php';
	require_once __DIR__ . '/src/visibility-submenu-page.php';
	add_action( 'network_admin_menu', 'apmfm_add_visibility_submenu' );
	add_action( 'admin_head', 'apmfm_remove_submenu_page' );
} add_action( 'plugins_loaded', 'apmfm_init' );

