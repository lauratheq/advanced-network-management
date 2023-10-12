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
function anmfm_load_plugin_textdomain() {

	load_plugin_textdomain( 'advanced-network-management', false, dirname( ANMFM_PLUGIN_BASENAME ) . '/languages' );
}
