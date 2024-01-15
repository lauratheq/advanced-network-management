<?php
/**
 * Some helpers
 *
 * @package anmfm
 */

// check if WordPress is loaded.
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Fetches the plugin visibility
 *
 * @param   int    $blog_id    the blog id.
 * @param   string $plugin     the plugins basename.
 *
 * @return  boolean
 */
function anmfm_get_plugin_visibility( $blog_id, $plugin ) {

	$visibility_options = get_site_option( 'anmfm_visibility_settings', array() );

	// if the array is empty, we just asume every plugin is visibile.
	if ( empty( $visibility_options ) ) {
		return true;
	}

	// if the array for the blog id is empty as well, we asume that the plugin is not hidden.
	if ( ! isset( $visibility_options[ $blog_id ] ) || empty( $visibility_options[ $blog_id ] ) ) {
		return true;
	}

	// if the plugin is in the array we hide it.
	if ( in_array( $plugin, $visibility_options[ $blog_id ], true ) ) {
		return false;
	}

	// if everything else fail, we asume the plugin is not hidden.
	return true;
}

/**
 * Hides a plugin for a blog
 *
 * @param   int    $blog_id    the blog id.
 * @param   string $plugin     the plugins basename.
 *
 * @return  void
 */
function anmfm_hide_plugin( $blog_id, $plugin ) {

	$visibility_options = get_site_option( 'anmfm_visibility_settings', array() );
	if ( ! isset( $visibility_options[ $blog_id ] ) || ! in_array( $plugin, $visibility_options[ $blog_id ], true ) ) {
		$visibility_options[ $blog_id ][] = $plugin;
	}
	update_site_option( 'anmfm_visibility_settings', $visibility_options );
}

/**
 * Unhides a plugin for a blog
 *
 * @param   int    $blog_id    the blog id.
 * @param   string $plugin     the plugins basename.
 *
 * @return  void
 */
function anmfm_unhide_plugin( $blog_id, $plugin ) {

	$visibility_options = get_site_option( 'anmfm_visibility_settings', array() );
	if ( in_array( $plugin, $visibility_options[ $blog_id ], true ) ) {
		$visibility_options[ $blog_id ] = array_diff( $visibility_options[ $blog_id ], array( $plugin ) );
	}

	update_site_option( 'anmfm_visibility_settings', $visibility_options );
}

/**
 * Activates a plugin for a blog
 *
 * @param   int    $blog_id    the blog id.
 * @param   string $plugin     the plugins basename.
 *
 * @return  void
 */
function anmfm_activate_plugin( $blog_id, $plugin ) {
	switch_to_blog( $blog_id );
	activate_plugins( $plugin, '', false, true );
	restore_current_blog();
}

/**
 * Deactivates a plugin for a blog
 *
 * @param   int    $blog_id    the blog id.
 * @param   string $plugin     the plugins basename.
 *
 * @return  void
 */
function anmfm_deactivate_plugin( $blog_id, $plugin ) {
	switch_to_blog( $blog_id );
	deactivate_plugins( $plugin, true );
	restore_current_blog();
}
