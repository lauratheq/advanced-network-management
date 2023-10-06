<?php
/**
 * Removes plugin from the plugin list
 *
 * @package apmfm
 */

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
function apmfm_remove_from_plugin_list( $plugins ) {

	// Don't change anything in the network admin area.
	if ( is_network_admin() ) {
		return $plugins;
	}

	// remove our plugin from the list.
	unset( $plugins['all'][ APMFM_PLUGIN_BASENAME ] );
	unset( $plugins['search'][ APMFM_PLUGIN_BASENAME ] );
	unset( $plugins['active'][ APMFM_PLUGIN_BASENAME ] );
	unset( $plugins['inactive'][ APMFM_PLUGIN_BASENAME ] );
	unset( $plugins['recently_activated'][ APMFM_PLUGIN_BASENAME ] );
	unset( $plugins['upgrade'][ APMFM_PLUGIN_BASENAME ] );
	unset( $plugins['mustuse'][ APMFM_PLUGIN_BASENAME ] );
	unset( $plugins['dropins'][ APMFM_PLUGIN_BASENAME ] );
	unset( $plugins['paused'][ APMFM_PLUGIN_BASENAME ] );

	// get the hidden plugins for this blog.
	$blog_id          = get_current_blog_id();
	$plugin_visiblity = get_site_option( 'apmfm_visibility_settings', array() );
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
