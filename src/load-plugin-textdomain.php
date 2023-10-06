<?php
/**
 * Loads the textdomain
 *
 * @package apmfm
 */

/**
 * Loads the plugins textdomain
 *
 * @wp-hook init
 *
 * @return  void
 */
function apmfm_load_plugin_textdomain() {

	load_plugin_textdomain( 'advanced-plugin-management', false, dirname( APMFM_PLUGIN_BASENAME ) . '/languages' );
}
