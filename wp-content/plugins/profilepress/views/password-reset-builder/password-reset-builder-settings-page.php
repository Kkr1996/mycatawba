<?php
ob_start();
require_once 'password-reset-builder-wp-list-table.php';

/**
 * Password Reset Builder
 */
Class Password_Reset_Form_Builder {

	private $password_reset_builder_errors, $plugin_menu_item;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'password_reset_settings_page' ) );
		add_filter( 'set-screen-option', array( $this, 'save_screen_option' ), 10, 3 );
		add_action( 'admin_print_scripts', array( $this, 'js_confirm_password_reset' ) );

		add_action( 'admin_init', array( $this, 'install_starter_themes' ) );
	}

	public function password_reset_settings_page() {

		$hook = add_submenu_page(
			'pp-config',
			__( 'Password Reset', 'profilepress' ) . ' - ProfilePress',
			__( 'Password Reset', 'profilepress' ),
			'manage_options',
			'pp-password-reset',
			array(
				$this,
				'password_reset_builder_page',
			)
		);

		add_action( "load-$hook", array( $this, 'add_options' ) );

		//help tab
		add_action( "load-$hook", array( $this, 'help_tab' ) );
		$this->plugin_menu_item = $hook;
	}


	/** Help tab */
	public function help_tab() {
		$screen = get_current_screen();
		if ( $screen->id != $this->plugin_menu_item ) {
			return;
		}
		$screen->add_help_tab( array(
			'id'      => 'help_tab_login-form',
			'title'   => __( 'Password-reset shortcodes', 'profilepress' ),
			'content' => require( PROFILEPRESS_ROOT . 'help-tab/password-reset.php' ),
		) );
		$screen->add_help_tab( array(
			'id'      => 'help_tab_global',
			'title'   => __( 'Global shortcodes', 'profilepress' ),
			'content' => require( PROFILEPRESS_ROOT . 'help-tab/global.php' ),
		) );
		$screen->add_help_tab( array(
			'id'      => 'help_tab_zocial',
			'title'   => __( 'Social login buttons', 'profilepress' ),
			'content' => require( PROFILEPRESS_ROOT . 'help-tab/social-buttons.php' ),
		) );
	}


	/**
	 * Install password reset form starter themes.
	 */
	public function install_starter_themes() {
		if ( isset( $_GET['install-starter-theme'] ) && $_GET['install-starter-theme'] == 'password-reset' ) {
			if ( current_user_can( 'administrator' ) && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'install_starter_theme' ) ) {
				password_reset\Password_Reset_Base::instance();
				// remove_query_arg is made to prevent recursive install of starter themes.
				wp_redirect( remove_query_arg( 'install-starter-theme', add_query_arg( 'starter-theme-install', 'success' ) ) );
				exit;
			}
		}
	}

	public function password_reset_builder_page() {
		// if we are in edit state, display the table with a nonce verification
		if ( isset( $_GET['action'] ) && $_GET['action'] == 'edit' && wp_verify_nonce( $_GET['_wpnonce'], 'pp_edit_pass' ) ) {

			// save password reset edit. note: method called before the static edit page
			// so generated error will display at the top of page
			$this->save_add_edit_password_reset_builder( 'edit', absint( $_GET['password-reset'] ) );

			$this->password_reset_builder_edit_page();

		} elseif ( isset( $_GET['password-reset-builder'] ) && $_GET['password-reset-builder'] == 'new' ) {

			$this->save_add_edit_password_reset_builder( 'add' );

			$this->password_reset_builder_add_page();
		} // if we are not in edit state, display the table
		else {
			self::password_reset_builder_index_page();
		}
	}

	/**
	 * @param $operation
	 * @param string $id
	 */
	public function save_add_edit_password_reset_builder( $operation, $id = '' ) {
		if ( current_user_can( 'administrator' ) && ( isset( $_POST['add_password_reset'] ) || isset( $_POST['edit_password_reset'] ) ) ) {
			$title                  = @sanitize_text_field( $_POST['prb_title'] );
			$structure              = @stripslashes( $_POST['prb_structure'] );
			$handler_structure      = @stripslashes( $_POST['prb_handler_structure'] );
			$css                    = @stripslashes( $_POST['prb_css'] );
			$success_password_reset = @stripslashes( $_POST['prb_success_password_reset'] );
			$make_widget            = @esc_attr( $_POST['prb_make_widget'] );


			// catch and save form generated errors in property @password_reset_builder_errors 
			if ( empty( $title ) ) {
				$this->password_reset_builder_errors = __( 'Title is empty', 'profilepress' );
			} elseif ( empty( $structure ) ) {
				$this->password_reset_builder_errors = __( 'Password Reset Form Design is missing', 'profilepress' );
			} elseif ( empty( $handler_structure ) ) {
				$this->password_reset_builder_errors = __( 'Password Reset Handler Form is missing', 'profilepress' );
			}

			if ( isset( $this->password_reset_builder_errors ) ) {
				return;
			}

			if ( isset( $_POST['edit_password_reset'] ) && check_admin_referer( 'edit_password_reset_builder', '_wpnonce' ) && $operation == 'edit' ) {

				//insert revision
				PROFILEPRESS_sql::insert_revision( $id, 'password_reset' );

				PROFILEPRESS_sql::sql_update_password_reset_builder( $id, $title, $structure, $handler_structure, $css, $success_password_reset, date( 'Y-m-d' ) );

				// call the appropriate wrapper function to record/save the "make widget" field
				if ( empty( $make_widget ) ) {
					// record/save the "make widget" field
					PROFILEPRESS_sql::sql_delete_pp_builder_widget( $id, 'password_reset' );
				} else {
					// record/save the "make widget" field
					PROFILEPRESS_sql::sql_add_pp_builder_widget( $id, 'password_reset' );
				}

				wp_redirect( add_query_arg( 'password-reset-edited', 'true' ) );
				exit;
			} elseif ( isset( $_POST['add_password_reset'] ) && check_admin_referer( 'add_password_reset_builder', '_wpnonce' ) && $operation == 'add' ) {

				global $wpdb;

				$id = PROFILEPRESS_sql::sql_insert_password_reset_builder( $title, $structure, $handler_structure, $css, $success_password_reset, date( 'Y-m-d' ) );

				if ( isset( $make_widget ) && ! empty( $make_widget ) ) {

					// record/save the "make widget" field
					PROFILEPRESS_sql::sql_add_pp_builder_widget( $wpdb->insert_id, 'password_reset' );
				}

				wp_redirect(
					sprintf(
						'?page=%s&action=%s&password-reset=%s&_wpnonce=%s&password-reset-added=true',
						PASSWORD_RESET_BUILDER_SETTINGS_PAGE_SLUG, 'edit',
						absint( $id ),
						wp_create_nonce( 'pp_edit_pass' )
					)
				);
				exit;
			}
		}
	}

	function add_options() {
		global $password_reset_list_table;
		$option = 'per_page';
		$args   = array(
			'label'   => __( 'Password Reset forms', 'profilepress' ),
			'default' => 10,
			'option'  => 'password_reset_builder_per_page',
		);
		add_screen_option( $option, $args );

		$password_reset_list_table = new Password_Reset_Builder_List_Table;

	}

	/** save the screen option values */
	function save_screen_option( $status, $option, $value ) {
		return $value;
	}

	/** Singleton poop */
	static function get_instance() {
		static $instance;
		if ( ! isset( $instance ) ) {
			$instance = new Password_Reset_Form_Builder;
		}

		return $instance;
	}

	/** Add an alert before a password-reset builder is deleted */
	public function js_confirm_password_reset() {
		?>
		<script type="text/javascript">
			function pp_del_password_reset(page, action, password_reset, _wpnonce) {
				if (confirm("<?php _e('Are you sure you want to delete this?', 'profilepress'); ?>")) {
					window.location.href = '?page=' + page + '&action=' + action + '&password-reset=' + password_reset + '&_wpnonce=' + _wpnonce;
				}
			}
		</script>
		<?php
	}

	public static function password_reset_builder_index_page() {
		?>
		<div class="wrap">
			<h2><?php _e( 'Password Reset Builder', 'profilepress' ); ?>
				<a class="add-new-h2" href="<?php echo esc_url( add_query_arg( 'password-reset-builder', 'new', admin_url('admin.php?page=' . PASSWORD_RESET_BUILDER_SETTINGS_PAGE_SLUG) ) ); ?>"><?php _e( 'Add New', 'profilepress' ); ?></a>
			</h2>

			<?php
			// include settings tab
			require_once VIEWS . '/include.settings-page-tab.php';?>

			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<?php
							global $password_reset_list_table;
							$password_reset_list_table->prepare_items();
							?>

							<form method="post">
								<?php
								$password_reset_list_table->display(); ?>
							</form>
						</div>
						<br>
						<a title="<?php _e( 'Click to install starter password reset themes', 'profilepress' ); ?>" class="button-primary" href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=' . PASSWORD_RESET_BUILDER_SETTINGS_PAGE_SLUG . '&install-starter-theme=password-reset' ), 'install_starter_theme' ); ?>">
							<?php _e( 'Install Starter Themes', 'profilepress' ); ?>
						</a>
					</div>
					<?php include_once VIEWS . '/include.plugin-settings-sidebar.php'; ?>
				</div>
				<br class="clear">
			</div>
		</div>
	<?php
	}

	public function password_reset_builder_add_page() {
		?>
		<div class="wrap">
		<h2><?php _e('Password Reset Builder', 'profilepress'); ?></h2>

		<?php if ( isset( $this->password_reset_builder_errors ) ) { ?>
			<div id="message" class="error notice is-dismissible"><p><strong><?php echo $this->password_reset_builder_errors; ?>. </strong>
				</p></div>
		<?php
		}

		require_once 'include.add-password-reset-builder.php';
	}

	public function password_reset_builder_edit_page() {
	?>
	<div class="wrap">
	<h2><?php _e( 'Password Reset Builder', 'profilepress' ); ?>
				<a class="add-new-h2" href="<?php echo esc_url( add_query_arg( 'password-reset-builder', 'new', admin_url('admin.php?page=' . PASSWORD_RESET_BUILDER_SETTINGS_PAGE_SLUG) ) ); ?>"><?php _e( 'Add New', 'profilepress' ); ?></a>
	</h2>
	<?php
	// status messages
	if ( isset( $this->password_reset_builder_errors ) ) {
		?>
		<div id="message" class="error notice is-dismissible"><p><strong><?php echo $this->password_reset_builder_errors; ?>. </strong>
			</p></div>
	<?php
	}

	if ( @$_GET['password-reset-edited'] ) {
		echo '<div id="message" class="updated notice is-dismissible"><p><strong>' . __( 'Password Reset Builder Edited', 'profilepress' ) . '</strong></p></div>';
	}

	if ( @$_GET['password-reset-added'] ) {
		echo '<div id="message" class="updated notice is-dismissible"><p><strong>' . __( 'New Password Reset Form Added.', 'profilepress' ) . '</strong></p></div>';
	}

	// include the edit profile template
	require_once 'include.edit-password-reset-builder.php';
}
}

Password_Reset_Form_Builder::get_instance();