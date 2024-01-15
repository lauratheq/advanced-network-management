<?php
/**
 * Registering the submenu page
 *
 * @package anmfm
 */

// check if WordPress is loaded.
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Adds a submenu to the plugins on the network admin area
 *
 * @wp-hook network_admin_menu
 *
 * @return  void
 */
function anmfm_add_visibility_submenu() {

	add_submenu_page(
		'plugins.php',
		__( 'Manage Plugin', 'advanced-network-management' ),
		__( 'Manage Plugin', 'advanced-network-management' ),
		'manage_network',
		'plugin-visibility',
		'anmfm_visibility_overview'
	);
}

/**
 * Removes our submenu page because we don't need the overview
 * site. Plugins overview page manages all of this for us.
 *
 * @wp-hook admin_head
 *
 * @return  void
 */
function anmfm_remove_submenu_page() {
	remove_submenu_page( 'plugins.php', 'plugin-visibility' );
}

/**
 * Displays the plugin overview for the visibility
 *
 * @called-by   anmfm_add_visibility_submenu
 *
 * @return    void
 */
function anmfm_visibility_overview() {
	$plugins = get_plugins();
	$nonce   = isset( $_GET['_wpnonce'] ) ? sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ) : false;
	if ( ! $nonce || ! wp_verify_nonce( $nonce, 'manage-plugins' ) ) {
		wp_die( 'Something went wront', 'advanced-network-management' );
	}
	$plugin  = isset( $_GET['plugin'] ) ? wp_kses_post( wp_unslash( $_GET['plugin'] ) ) : false;
	$action  = isset( $_GET['action'] ) ? sanitize_key( wp_unslash( $_GET['action'] ) ) : false;
	$blog_id = isset( $_GET['blog_id'] ) ? sanitize_key( wp_unslash( $_GET['blog_id'] ) ) : false;
	unset( $plugins[ ANMFM_PLUGIN_BASENAME ] );

	// check if queried plugin exists.
	if ( ! $plugin || ! isset( $plugins[ $plugin ] ) ) {
		?>
		<div class="error">
			<p><?php esc_html_e( 'Could not find plugin', 'advanced-network-management' ); ?></p>
		</div>
		<?php
		return;
	}

	// check actions.
	if ( $action ) {
		switch ( $action ) {
			case 'hide-plugin':
				anmfm_hide_plugin( $blog_id, $plugin );
				?>
				<div class="updated">
					<p><?php esc_html_e( 'Plugin visibility updated', 'advanced-network-management' ); ?></p>
				</div>
				<?php
				break;
			case 'unhide-plugin':
				anmfm_unhide_plugin( $blog_id, $plugin );
				?>
				<div class="updated">
					<p><?php esc_html_e( 'Plugin visibility updated', 'advanced-network-management' ); ?></p>
				</div>
				<?php
				break;
			case 'activate-plugin':
				anmfm_activate_plugin( $blog_id, $plugin );
				?>
				<div class="updated">
					<p><?php esc_html_e( 'Plugin activated', 'advanced-network-management' ); ?></p>
				</div>
				<?php
				break;
			case 'deactivate-plugin':
				anmfm_deactivate_plugin( $blog_id, $plugin );
				?>
				<div class="updated">
					<p><?php esc_html_e( 'Plugin deactivated', 'advanced-network-management' ); ?></p>
				</div>
				<?php
				break;
		}
	}

	// check bulk actions.
	if ( isset( $_POST['anmfm-bulk-edit'] ) && isset( $_POST['bulk-action'] ) && ! empty( $_POST['blog_ids'] ) ) {
		$blog_ids = array_values( array_map( 'sanitize_text_field', wp_unslash( $_POST['blog_ids'] ) ) );

		switch ( $_POST['bulk-action'] ) {
			case 'hide-for-all-selected':
				foreach ( $blog_ids as $blog_id ) {
					anmfm_hide_plugin( $blog_id, $plugin );
				}
				?>
				<div class="updated">
					<p><?php esc_html_e( 'Plugins hidden', 'advanced-network-management' ); ?></p>
				</div>
				<?php
				break;
			case 'unhide-for-all-selected':
				foreach ( $blog_ids as $blog_id ) {
					anmfm_unhide_plugin( $blog_id, $plugin );
				}
				?>
				<div class="updated">
					<p><?php esc_html_e( 'Plugins unhidden', 'advanced-network-management' ); ?></p>
				</div>
				<?php
				break;
			case 'activate-on-all-selected':
				foreach ( $blog_ids as $blog_id ) {
					anmfm_activate_plugin( $blog_id, $plugin );
				}
				?>
				<div class="updated">
					<p><?php esc_html_e( 'Plugins activated', 'advanced-network-management' ); ?></p>
				</div>
				<?php
				break;
			case 'deactivate-on-all-selected':
				foreach ( $blog_ids as $blog_id ) {
					anmfm_deactivate_plugin( $blog_id, $plugin );
				}
				?>
				<div class="updated">
					<p><?php esc_html_e( 'Plugins deactivated', 'advanced-network-management' ); ?></p>
				</div>
				<?php
				break;
		}
	}

	$plugin = $plugins[ $plugin ];
	?>
	<div id="poststuff" class="wrap">
		<h1><?php esc_html_e( 'Manage Plugin', 'advanced-network-management' ); ?>: <?php echo esc_html( $plugin['Name'] ); ?></h1>

		<div id="post-body" class="metabox-holder columns-2">
			<div class="metabox-holder columns-2">
				<div id="postbox-container-1" class="postbox-container">

					<div class="tablenav">&nbsp;</div>
					<div class="postbox">
						<div class="postbox-header">
							<h2><?php esc_html_e( 'Plugin Information', 'advanced-network-management' ); ?></h2>
						</div>
						<div class="inside">
							<p><?php echo esc_html( $plugin['Description'] ); ?></p>
							<p><strong><?php esc_html_e( 'Version:' ); ?></strong> <?php echo esc_html( $plugin['Version'] ); ?></p>
							<p><strong><?php esc_html_e( 'Author:' ); ?></strong> <a href="<?php echo esc_html( $plugin['AuthorURI'] ); ?>"><?php echo esc_html( $plugin['Author'] ); ?></a></p>
							<p><a href="<?php echo esc_html( $plugin['PluginURI'] ); ?>"><?php esc_html_e( 'Visit plugin site' ); ?></a></p>
						</div>
					</div>

				</div>

				<div id="postbox-container-2" class="postbox-container">
					<form id="anmfm-plugin-management-form" method="post">
						<input type="hidden" name="anmfm-bulk-edit" value="true"/>
						<?php
						$list_table = new ANMFM_Plugin_Management_WP_List_Table();
						$list_table->prepare_items();
						$list_table->display();
						?>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php
}
