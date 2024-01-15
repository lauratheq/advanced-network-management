<?php
/**
 * Adds the rowactions
 *
 * @package anmfm
 */

// check if WordPress is loaded.
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Adds the rowaction to the plugin overview
 *
 * @wp-hook network_admin_plugin_action_links
 *
 * @param array  $actions the current actions.
 * @param string $plugin_file the current pluginfile.
 *
 * @return array
 */
function anmfm_network_admin_plugin_action_links( $actions, $plugin_file ) {

	// remove our plugin.
	if ( ANMFM_PLUGIN_BASENAME === $plugin_file ) {
		return $actions;
	}

	// build edit link.
	$edit_link = network_admin_url( 'plugins.php' );
	$edit_link = add_query_arg(
		array(
			'page'   => 'plugin-visibility',
			'action' => 'edit-plugin-visibility',
			'plugin' => $plugin_file,
		),
		$edit_link
	);
	$edit_link = wp_nonce_url( $edit_link, 'manage-plugins' );

	// add action before delete.
	if ( isset( $actions['delete'] ) ) {
		$delete_action = $actions['delete'];
		unset( $actions['delete'] );
		$actions['visibility'] = '<span class="edit"><a href="' . $edit_link . '">' . __( 'Manage Plugin', 'advanced-network-management' ) . '</a></span>';
	}
	$actions['visibility'] = '<span class="edit"><a href="' . $edit_link . '">' . __( 'Manage Plugin', 'advanced-network-management' ) . '</a></span>';
	if ( isset( $delete_action ) ) {
		$actions['delete'] = $delete_action;
	}

	return $actions;
}
