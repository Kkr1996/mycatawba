<?php
ob_start();
require_once 'edit-profile-builder-wp-list-table.php';

/**
 * Edit User Profile Form Builder
 */
Class Edit_Profile_Builder {

	private $edit_profile_builder_errors, $plugin_menu_item;

	/** constructor */
	function __construct() {
		add_action( 'admin_menu', array( $this, 'edit_profile_settings_page' ) );
		add_filter( 'set-screen-option', array( $this, 'save_screen_option' ), 10, 3 );
		add_action( 'admin_print_scripts', array( $this, 'js_confirm_edit_profile' ) );

		add_action( 'admin_init', array( $this, 'install_starter_themes' ) );

	}

	/** Singleton instance */
	static function get_instance() {
		static $instance;
		if ( ! isset( $instance ) ) {
			$instance = new Edit_Profile_Builder;
		}

		return $instance;
	}

	public function edit_profile_settings_page() {

		$hook = add_submenu_page(
			'pp-config',
			'Edit Profile Form - ProfilePress',
			'Edit Profile Form',
			'manage_options',
			'pp-edit-profile',
			array( $this, 'edit_profile_builder_page' ) );

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
			'title'   => 'Edit-profile shortcodes',
			'content' => require( PROFILEPRESS_ROOT . 'help-tab/edit-profile.php' ),
		) );
		$screen->add_help_tab( array(
			'id'      => 'help_tab_global',
			'title'   => 'Global shortcodes',
			'content' => require( PROFILEPRESS_ROOT . 'help-tab/global.php' ),
		) );
		$screen->add_help_tab( array(
			'id'      => 'help_tab_zocial',
			'title'   => 'Social login buttons',
			'content' => require( PROFILEPRESS_ROOT . 'help-tab/social-buttons.php' ),
		) );
	}

	/**
	 * Install edit profile form starter themes.
	 */
	public function install_starter_themes() {
		if ( isset( $_GET['install-starter-theme'] ) && $_GET['install-starter-theme'] == 'edit-profile' ) {
			if ( current_user_can( 'administrator' ) && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'install_starter_theme' ) ) {
				edit_user_profile\Edit_User_Profile_Base::instance();
				// remove_query_arg is made to prevent recursive install of starter themes.
				wp_redirect( remove_query_arg( 'install-starter-theme', add_query_arg( 'starter-theme-install', 'success' ) ) );
				exit;
			}
		}
	}

	public function edit_profile_builder_page() {
		// if we are in edit state, display the table
		if ( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ) {

			// save edit_profile. note: method called before the static edit page
			// so generated error will display at the top of page
			$this->save_add_edit_profile_builder( 'edit', absint( $_GET['edit-profile'] ) );

			$this->edit_profile_builder_edit_page();

		}
		elseif ( isset( $_GET['edit-profile-builder'] ) && $_GET['edit-profile-builder'] == 'new' ) {

			$this->save_add_edit_profile_builder( 'add' );

			$this->edit_profile_builder_add_page();
		} // if we are not in edit state, display the table
		else {
			self::edit_profile_builder_index_page();
		}
	}

	/**
	 * Save the editing and addition of "edit profile"
	 *
	 * @param $operation
	 * @param string $id
	 */
	function save_add_edit_profile_builder( $operation, $id = '' ) {
		if ( current_user_can( 'administrator' ) && (isset( $_POST['add_edit_profile'] ) || isset( $_POST['edit_user_profile'] )) ) {
			$title                = @sanitize_text_field( $_POST['eup_title'] );
			$structure            = @stripslashes( $_POST['eup_structure'] );
			$css                  = @stripslashes( $_POST['eup_css'] );
			$success_edit_profile = @stripslashes( $_POST['eup_success_edit_profile'] );
			$make_widget          = @esc_attr( $_POST['eup_make_widget'] );


			// catch and save form generated errors in property @edit_profile_builder_errors
			if ( empty( $title ) ) {
				$this->edit_profile_builder_errors = __('Title is empty', 'profilepress');
			}
			elseif ( empty( $structure ) ) {
				$this->edit_profile_builder_errors = __('Design is missing', 'profilepress');
			}

			if ( isset( $this->edit_profile_builder_errors ) ) {
				return;
			}

			if ( isset( $_POST['edit_user_profile'] ) && check_admin_referer( 'edit_user_profile_page', '_wpnonce' ) && $operation == 'edit' ) {

				//insert revision
				PROFILEPRESS_sql::insert_revision($id, 'edit_user_profile');

				PROFILEPRESS_sql::sql_update_edit_profile_builder( $id, $title, $structure, $css, $success_edit_profile, date( 'Y-m-d' ) );

				// call the appropriate wrapper function to record/save the "make widget" field
				if ( empty( $make_widget ) ) {

					// record/save the "make widget" field
					PROFILEPRESS_sql::sql_delete_pp_builder_widget( $id, 'edit_user_profile' );
				}

				else {
					// record/save the "make widget" field
					PROFILEPRESS_sql::sql_add_pp_builder_widget( $id, 'edit_user_profile' );
				}

				wp_redirect( esc_url_raw( add_query_arg( 'edit-profile-edited', 'true' ) ) );
				exit;
			}

			if ( isset( $_POST['add_edit_profile'] ) && check_admin_referer( 'add_edit_user_profile_page', '_wpnonce' ) && $operation == 'add' ) {
				global $wpdb;

				$id = PROFILEPRESS_sql::sql_insert_edit_profile_builder( $title, $structure, $css, $success_edit_profile, date( 'Y-m-d' ) );

				if ( isset( $make_widget ) && ! empty( $make_widget ) ) {

					// record/save the "make widget" field
					PROFILEPRESS_sql::sql_add_pp_builder_widget( $wpdb->insert_id, 'edit_user_profile' );
				}

				wp_redirect(
					sprintf(
						'?page=%s&action=%s&edit-profile=%s&_wpnonce=%s&edit-profile-added=true',
						EDIT_PROFILE_BUILDER_SETTINGS_PAGE_SLUG, 'edit',
						absint( $id ),
						wp_create_nonce( 'pp_edit_edit_profile' )
					)
				);
				exit;
			}
		}
	}


	public function edit_profile_builder_edit_page() {
		?>
		<div class="wrap">
		<h2><?php _e('Front-end Edit Profile', 'profilepress'); ?>
				<a class="add-new-h2" href="<?php esc_attr_e( add_query_arg( 'edit-profile-builder', 'new', admin_url('admin.php?page=' . EDIT_PROFILE_BUILDER_SETTINGS_PAGE_SLUG) ) ); ?>"><?php _e( 'Add New', 'profilepress' ); ?></a>
			</h2>

		<?php
		if ( isset( $this->edit_profile_builder_errors ) ) {
			echo '<div id="message" class="error notice is-dismissible"><p><strong>' . $this->edit_profile_builder_errors . '</strong></p></div>';
		}

		if ( @$_GET['edit-profile-edited'] ) {
			echo '<div id="message" class="updated notice is-dismissible"><p><strong>Changes Saved. </strong></p></div>';
		}

		if ( @$_GET['edit-profile-added'] ) {
			echo '<div id="message" class="updated notice is-dismissible"><p><strong>Added Successfully. </strong></p></div>';
		}

		require_once 'include.edit-profile-builder.php';
	}

	public function edit_profile_builder_add_page() { ?>
		<h2><?php _e('Front-end Edit Profile', 'profilepress'); ?></h2>

		<?php if ( isset( $this->edit_profile_builder_errors ) ) {
			echo '<div id="message" class="error notice is-dismissible"><p><strong>' . $this->edit_profile_builder_errors . '</strong></p></div>';
		}

		require_once 'include.add-edit-profile-builder.php';
	}

	public static function edit_profile_builder_index_page() {
		?>
		<div class="wrap">
			<h2><?php _e('Front-end Edit Profile', 'profilepress'); ?>
				<a class="add-new-h2" href="<?php esc_attr_e( add_query_arg( 'edit-profile-builder', 'new', admin_url('admin.php?page=' . EDIT_PROFILE_BUILDER_SETTINGS_PAGE_SLUG) ) ); ?>"><?php _e( 'Add New', 'profilepress' ); ?></a>
			</h2>

			<?php
			// include settings tab
			require_once VIEWS . '/include.settings-page-tab.php';?>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<?php
							global $edit_profile_list_table;
							$edit_profile_list_table->prepare_items();
							?>

							<form method="post">
								<input type="hidden" name="page" value="ttest_list_table">
								<?php
								$edit_profile_list_table->display(); ?>
							</form>
						</div>
						<br>
						<a title="<?php _e( 'Click to install starter edit profile themes', 'profilepress' ); ?>" class="button-primary" href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=' . EDIT_PROFILE_BUILDER_SETTINGS_PAGE_SLUG . '&install-starter-theme=edit-profile' ), 'install_starter_theme' ); ?>">
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

	public function add_options() {
		global $edit_profile_list_table;
		$option = 'per_page';
		$args   = array(
			'label'   => 'Edit User Profile forms',
			'default' => 10,
			'option'  => 'edit_profile_builder_per_page',
		);
		add_screen_option( $option, $args );

		$edit_profile_list_table = new Edit_Profile_Builder_List_Table;
	}

	/** save screen option */
	function save_screen_option( $status, $option, $value ) {
		return $value;
	}

	/** Add an alert before a edit_profile builder is deleted */
	public function js_confirm_edit_profile() {
		?>
		<script type="text/javascript">
			function pp_del_edit_profile(page, action, edit_profile, _wpnonce) {
				if (confirm("Are you sure you want to delete this?")) {
					window.location.href = '?page=' + page + '&action=' + action + '&edit-profile=' + edit_profile + '&_wpnonce=' + _wpnonce;
				}
			}
		</script>
	<?php
	}
}

Edit_Profile_Builder::get_instance();