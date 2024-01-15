<?php
/**
 * Removes plugin from the plugin list
 *
 * @package anmfm
 */

// check if WordPress is loaded.
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Removes plugins from the plugin list in the non
 * network WordPress sites.
 *
 * @wp-hook plugins_list
 *
 * @param   array $plugins the actual plugin list.
 *
 * @return  array $plugins the modified list
 */
function anmfm_remove_from_plugin_list( $plugins ) {

	// Don't change anything in the network admin area.
	if ( is_network_admin() ) {
		return $plugins;
	}

	// remove our plugin from the list.
	unset( $plugins['all'][ ANMFM_PLUGIN_BASENAME ] );
	unset( $plugins['search'][ ANMFM_PLUGIN_BASENAME ] );
	unset( $plugins['active'][ ANMFM_PLUGIN_BASENAME ] );
	unset( $plugins['inactive'][ ANMFM_PLUGIN_BASENAME ] );
	unset( $plugins['recently_activated'][ ANMFM_PLUGIN_BASENAME ] );
	unset( $plugins['upgrade'][ ANMFM_PLUGIN_BASENAME ] );
	unset( $plugins['mustuse'][ ANMFM_PLUGIN_BASENAME ] );
	unset( $plugins['dropins'][ ANMFM_PLUGIN_BASENAME ] );
	unset( $plugins['paused'][ ANMFM_PLUGIN_BASENAME ] );

	// get the hidden plugins for this blog.
	$blog_id          = get_current_blog_id();
	$plugin_visiblity = get_site_option( 'anmfm_visibility_settings', array() );
	if ( isset( $plugin_visiblity[ $blog_id ] ) ) {
		foreach ( $plugin_visiblity[ $blog_id ] as $plugin ) {
			unset( $plugins['all'][ $plugin ] );
			unset( $plugins['search'][ $plugin ] );
			unset( $plugins['active'][ $plugin ] );
			unset( $plugins['inactive'][ $plugin ] );
			unset( $plugins['recently_activated'][ $plugin ] );
			unset( $plugins['upgrade'][ $plugin ] );
			unset( $plugins['mustuse'][ $plugin ] );
			unset( $plugins['dropins'][ $plugin ] );
			unset( $plugins['paused'][ $plugin ] );
		}
	}

	return $plugins;
}
