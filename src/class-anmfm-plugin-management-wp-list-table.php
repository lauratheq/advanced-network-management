<?php
/**
 * Plugin management list
 *
 * @package anmfm
 */

// check if WordPress is loaded.
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

// We need the list table.
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List table class
 */
class ANMFM_Plugin_Management_WP_List_Table extends \WP_List_Table {
	/**
	 * The plugins basename
	 *
	 * @var string
	 */
	protected $plugin_basename = '';

	/**
	 * Builds the class object
	 *
	 * @return  void
	 */
	public function __construct() {

		// check the nonce.
		$nonce = isset( $_GET['_wpnonce'] ) ? sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ) : false;
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'manage-plugins' ) ) {
			wp_die( 'Something went wront', 'advanced-network-management' );
		}

		// check for the plugins basename.
		if ( isset( $_GET['plugin'] ) ) {
			$this->plugin_basename = isset( $_GET['plugin'] ) ? wp_kses_post( wp_unslash( $_GET['plugin'] ) ) : false;
		} else {
			wp_die( 'Something went wront', 'advanced-network-management' );
		}

		parent::__construct(
			array(
				'singular' => __( 'Site', 'advanced-network-management' ),
				'plural'   => __( 'Sites' ),
				'ajax'     => false,
			)
		);
	}

	/**
	 * Message to show if no designation found
	 *
	 * @return void
	 */
	public function no_items() {
		esc_attr_e( 'No Sites found', 'advanced-network-management' );
	}

	/**
	 * Get the column names
	 *
	 * @return  array
	 */
	public function get_columns() {
		$columns = array(
			'cb'         => '<input type="checkbox" />',
			'sites'      => __( 'Sites' ),
			'status'     => __( 'Status', 'advanced-network-management' ),
			'visibility' => __( 'Visibility', 'advanced-network-management' ),
		);

		return $columns;
	}

	/**
	 * Displays extra controls
	 *
	 * @param   string $which the current position of the table.
	 *
	 * @return  void
	 */
	protected function extra_tablenav( $which ) {
		// don't render on top.
		if ( 'bottom' !== $which ) {
			return;
		}

		?>
		<div class="alignleft actions bulkactions">
			<select name="bulk-action" id="bulk-action">
				<option value="-1"><?php esc_attr_e( 'Bulk actions' ); ?></option>
				<option value="hide-for-all-selected"><?php esc_attr_e( 'Hide for selected sites', 'advanced-network-management' ); ?></option>
				<option value="unhide-for-all-selected"><?php esc_attr_e( 'Unhide for selected sites', 'advanced-network-management' ); ?></option>
				<?php if ( ! is_plugin_active_for_network( $this->plugin_basename ) ) { ?>
					<option value="activate-on-all-selected"><?php esc_attr_e( 'Activate on selected sites', 'advanced-network-management' ); ?></option>
					<option value="deactivate-on-all-selected"><?php esc_attr_e( 'Deactivate on selected sites', 'advanced-network-management' ); ?></option>
				<?php } ?>
			</select>
			<input type="submit" id="doaction" class="button action" value="Apply">
		</div>
		<div class="clear"></div>
		<?php
	}

	/**
	 * Displays the checkboxes
	 *
	 * @param   object  WP_Site $site the WordPress site.
	 *
	 * @return  string
	 */
	protected function column_cb( $site ) {
		return sprintf( '<input type="checkbox" name="blog_ids[]" value="%d" />', $site->blog_id );
	}

	/**
	 * Render the sites description column
	 *
	 * @param   object  WP_Site $site the WordPress site.
	 *
	 * @return  string
	 */
	public function column_sites( $site ) {

		$edit_link = get_admin_url( $site->blog_id, 'plugins.php' );
		switch_to_blog( $site->blog_id );
		$site_name = get_bloginfo( 'name' );
		restore_current_blog();

		$html  = '<strong>' . $site_name . '</strong>';
		$html .= '<div class="row-actions visible"><span class="edit"><a href="' . $edit_link . '">' . __( 'Open site plugins', 'advanced-network-management' ) . '</a></span></div>';

		return $html;
	}

	/**
	 * Render the actions column
	 *
	 * @param   object  WP_Site $site the WordPress site.
	 *
	 * @return  string
	 */
	public function column_visibility( $site ) {

		$base_edit_link = network_admin_url( 'plugins.php' );
		$base_edit_link = add_query_arg(
			array(
				'page'    => 'plugin-visibility',
				'action'  => 'edit-plugin-visibility',
				'plugin'  => $this->plugin_basename,
				'blog_id' => $site->blog_id,
			),
			$base_edit_link
		);

		$visibility        = __( 'Hidden', 'advanced-network-management' );
		$visibility_status = anmfm_get_plugin_visibility( $site->blog_id, $this->plugin_basename );
		if ( $visibility_status ) {
			$visibility = __( 'Visible' );
		}

		// change visibility.
		$visibility_status = anmfm_get_plugin_visibility( $site->blog_id, $this->plugin_basename );
		if ( $visibility_status ) {
			$visibility_link = add_query_arg(
				array(
					'action' => 'hide-plugin',
				),
				$base_edit_link
			);
			$visibility_link = wp_nonce_url( $visibility_link, 'manage-plugins' );
			$visibility_link = '<a href="' . $visibility_link . '">' . __( 'Hide', 'advanced-network-management' ) . '</a>';
		} else {
			$visibility_link = add_query_arg(
				array(
					'action' => 'unhide-plugin',
				),
				$base_edit_link
			);
			$visibility_link = wp_nonce_url( $visibility_link, 'manage-plugins' );
			$visibility_link = '<a href="' . $visibility_link . '">' . __( 'Unhide', 'advanced-network-management' ) . '</a>';
		}

		$html  = '<p>' . $visibility . '</p>';
		$html .= '<p>' . $visibility_link . '</p>';

		return $html;
	}

	/**
	 * Render the status column
	 *
	 * @param   object  WP_Site $site the WordPress site.
	 *
	 * @return  string
	 */
	public function column_status( $site ) {

		$base_edit_link = network_admin_url( 'plugins.php' );
		$base_edit_link = add_query_arg(
			array(
				'page'    => 'plugin-visibility',
				'action'  => 'edit-plugin-visibility',
				'plugin'  => $this->plugin_basename,
				'blog_id' => $site->blog_id,
			),
			$base_edit_link
		);

		switch_to_blog( $site->blog_id );
		$is_plugin_active = is_plugin_active( $this->plugin_basename );
		$status           = __( 'Deactivated', 'advanced-network-management' );
		if ( $is_plugin_active ) {
			$status = __( 'Activated', 'advanced-network-management' );
		}
		restore_current_blog();

		// change activation status.
		if ( ! is_plugin_active_for_network( $this->plugin_basename ) ) {

			switch_to_blog( $site->blog_id );
			$plugin_status = is_plugin_active( $this->plugin_basename );
			if ( $plugin_status ) {
				$activation_link = add_query_arg(
					array(
						'action' => 'deactivate-plugin',
					),
					$base_edit_link
				);
				$activation_link = wp_nonce_url( $activation_link, 'manage-plugins' );
				$activation_link = '<a href="' . $activation_link . '">' . __( 'Deactivate' ) . '</a>';
			} else {
				$activation_link = add_query_arg(
					array(
						'action' => 'activate-plugin',
					),
					$base_edit_link
				);
				$activation_link = wp_nonce_url( $activation_link, 'manage-plugins' );
				$activation_link = '<a href="' . $activation_link . '">' . __( 'Activate' ) . '</a>';
			}
			restore_current_blog();
			$html  = '<p>' . $status . '</p>';
			$html .= '<p>' . $activation_link . '</p>';
		} else {
			$html = __( 'Plugin is network activated', 'advanced-network-management' );
		}

		return $html;
	}

	/**
	 * Prepare the class items
	 *
	 * @return  void
	 */
	public function prepare_items() {

		$this->_column_headers = array( $this->get_columns(), array(), array() );

		$this->set_pagination_args(
			array(
				'total_items' => $this->get_sites_count(),
				'per_page'    => -1,
			)
		);

		$this->items = $this->get_sites();
	}

	/**
	 * Get all sites
	 *
	 * @return      array
	 */
	protected function get_sites() {
		$sites = get_sites();
		return $sites;
	}

	/**
	 * Get count of all sites
	 *
	 * @return      array
	 */
	protected function get_sites_count() {
		return (int) count( $this->get_sites() );
	}
}

