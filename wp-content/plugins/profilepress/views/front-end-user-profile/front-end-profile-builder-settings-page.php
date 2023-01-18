<?php
ob_start();
require_once 'front-end-profile-builder-wp-list-table.php';

/**
 * Edit User Profile Form Builder
 */
Class Front_End_Profile_Builder {

	private $user_profile_builder_errors, $plugin_menu_item;

	/** constructor */
	private function __construct() {
		add_action( 'admin_menu', array( $this, 'user_profile_settings_page' ) );
		add_filter( 'set-screen-option', array( $this, 'save_screen_option' ), 10, 3 );
		add_action( 'admin_print_scripts', array( $this, 'js_confirm_front_end_profile' ) );

	}

	/** Singleton instance */
	public static function get_instance() {
		static $instance;
		if ( ! isset( $instance ) ) {
			$instance = new Front_End_Profile_Builder;
		}

		return $instance;
	}

	/**
	 * Install front-end profile form starter themes.
	 */
	public function install_starter_themes() {
		if ( isset( $_GET['install-starter-theme'] ) && $_GET['install-starter-theme'] == 'front-end-profile' ) {
			if ( current_user_can( 'administrator' ) && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'install_starter_theme' ) ) {
				front_end_profile\Front_End_Profile_Base::instance();
				// remove_query_arg is made to prevent recursive install of starter themes.
				wp_redirect( remove_query_arg( 'install-starter-theme', add_query_arg( 'starter-theme-install', 'success' ) ) );
				exit;
			}
		}
	}

	function user_profile_settings_page() {

		$hook = add_submenu_page( 'pp-config',
			'Front-end Profile - ProfilePress',
			'Front-end Profile',
			'manage_options',
			'pp-user-profile',
			array( $this, 'user_profile_builder_page' ) );

		add_action( "load-$hook", array( $this, 'add_options' ) );

		//help tab
		add_action( "load-$hook", array( $this, 'help_tab' ) );
		$this->plugin_menu_item = $hook;
	}

	function user_profile_builder_page() {
		// if we are in edit state, display the table
		if ( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ) {

			// save user_profile. note: method called before the static edit page
			// so generated error will display at the top of page
			$this->save_add_user_profile_builder( 'edit', absint( $_GET['user-profile'] ) );

			$this->user_profile_builder_edit_page();

		}
		elseif ( isset( $_GET['user-profile-builder'] ) && $_GET['user-profile-builder'] == 'new' ) {

			$this->save_add_user_profile_builder( 'add' );

			$this->user_profile_builder_add_page();
		} // if we are not in edit state, display the table
		else {
			self::user_profile_builder_index_page();
		}
	}

	/** Help tab */
	public function help_tab() {
		$screen = get_current_screen();
		if ( $screen->id != $this->plugin_menu_item ) {
			return;
		}
		$screen->add_help_tab( array(
			'id'      => 'help_tab_login-form',
			'title'   => 'Front-end profile shortcodes',
			'content' => require( PROFILEPRESS_ROOT . 'help-tab/front-end-profile.php' ),
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
	 * Save the editing and addition of "edit profile"
	 *
	 * @param $operation
	 * @param string $id
	 */
	public function save_add_user_profile_builder( $operation, $id = '' ) {
		if ( current_user_can( 'administrator' ) && (isset( $_POST['add_user_profile'] ) || isset( $_POST['edit_user_profile'] )) ) {
			$title     = @sanitize_text_field( $_POST['fep_title'] );
			$structure = @stripslashes( $_POST['fep_structure'] );
			$css       = @stripslashes( $_POST['fep_css'] );


			// catch and save form generated errors in property @user_profile_builder_errors
			if ( empty( $title ) ) {
				$this->user_profile_builder_errors = 'Title is empty';
			}
			elseif ( empty( $structure ) ) {
				$this->user_profile_builder_errors = 'Design is missing';
			}

			if ( isset( $this->user_profile_builder_errors ) ) {
				return;
			}

			if ( isset( $_POST['edit_user_profile'] ) && check_admin_referer( 'edit_user_profile_page', '_wpnonce' ) && $operation == 'edit' ) {

				//insert revision
				PROFILEPRESS_sql::insert_revision( $id, 'front_end_profile' );

				PROFILEPRESS_sql::sql_update_user_profile_builder( $id, $title, $structure, $css, date( 'Y-m-d' ) );
				wp_redirect( esc_url_raw( add_query_arg( 'user-profile-edited', 'true' ) ) );
				exit;
			}

			if ( isset( $_POST['add_user_profile'] ) && check_admin_referer( 'add_user_profile_page', '_wpnonce' ) && $operation == 'add' ) {
				$id = PROFILEPRESS_sql::sql_insert_user_profile_builder( $title, $structure, $css, date( 'Y-m-d' ) );
				wp_redirect(
					sprintf(
						'?page=%s&action=%s&user-profile=%s&_wpnonce=%s&user-profile-added=true',
						USER_PROFILE_BUILDER_SETTINGS_PAGE_SLUG, 'edit',
						absint( $id ),
						wp_create_nonce( 'pp_edit_user_profile' )
					)
				);
				exit;
			}
		}
	}

	public function user_profile_builder_edit_page() {
		?>
		<div class="wrap">
		<h2><?php _e('Front-end User Profile', 'profilepress'); ?> <a class="add-new-h2" href="<?php echo esc_url( add_query_arg( 'user-profile-builder', 'new', admin_url('admin.php?page=' . USER_PROFILE_BUILDER_SETTINGS_PAGE_SLUG) ) ); ?>"><?php _e( 'Add New', 'profilepress' ); ?></a>
			</h2>

		<?php if ( isset( $this->user_profile_builder_errors ) ) {
			echo '<div id="message" class="error notice is-dismissible"><p><strong>' . $this->user_profile_builder_errors . '</strong></p></div>';
		}

		if ( isset( $_GET['user-profile-edited'] ) && ( $_GET['user-profile-edited'] ) ) {
			echo '<div id="message" class="updated notice is-dismissible"><p><strong>Changes Saved. </strong></p></div>';
		}

		if ( isset( $_GET['user-profile-added'] ) && ( $_GET['user-profile-added'] ) ) {
			echo '<div id="message" class="updated notice is-dismissible"><p><strong>Added Successfully. </strong></p></div>';
		}

		require_once 'include.edit-front-end-profile-builder.php';
	}


public function user_profile_builder_add_page() {
	?>
	<div class="wrap">
	<h2>Front-end User Profile</h2>

	<?php if ( isset( $this->user_profile_builder_errors ) ) { ?>
	<div id="message" class="error notice is-dismissible"><p><strong><?php echo $this->user_profile_builder_errors; ?>. </strong>
		</p></div>
<?php
}

	require_once 'include.add-front-end-profile-builder.php';
}

	static function user_profile_builder_index_page() {
		?>
		<div class="wrap">
			<h2><?php _e('Front-end User Profile', 'profilepress'); ?> <a class="add-new-h2" href="<?php echo esc_url( add_query_arg( 'user-profile-builder', 'new', admin_url('admin.php?page=' . USER_PROFILE_BUILDER_SETTINGS_PAGE_SLUG) ) ); ?>"><?php _e( 'Add New', 'profilepress' ); ?></a>
			</h2>

			<?php
			// include settings tab
			require_once VIEWS . '/include.settings-page-tab.php'; ?>

			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<?php
							global $user_profile_list_table;
							$user_profile_list_table->prepare_items();
							?>
							<form method="post">
								<?php
								$user_profile_list_table->display(); ?>
							</form>
						</div>
						<br>
						<a title="<?php _e( 'Click to install starter front-end profile themes', 'profilepress' ); ?>" class="button-primary" href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=' . USER_PROFILE_BUILDER_SETTINGS_PAGE_SLUG . '&install-starter-theme=front-end-profile' ), 'install_starter_theme' ); ?>">
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

	// save the screen option values


	function add_options() {
		global $user_profile_list_table;
		$option = 'per_page';
		$args   = array(
			'label'   => 'Front-end User Profile',
			'default' => 10,
			'option'  => 'user_profile_builder_per_page',
		);
		add_screen_option( $option, $args );

		$user_profile_list_table = new Front_End_Profile_Builder_List_Table;

	}

	/** save screen option */
	function save_screen_option( $status, $option, $value ) {
		return $value;
	}

	/** Add an alert before a edit_profile builder is deleted */
	public function js_confirm_front_end_profile() {
		?>
		<script type="text/javascript">
			function pp_del_front_end_profile(page, action, front_end_profile, _wpnonce) {
				if (confirm("Are you sure you want to delete this?")) {
					window.location.href = '?page=' + page + '&action=' + action + '&user-profile=' + front_end_profile + '&_wpnonce=' + _wpnonce;
				}
			}
		</script>
	<?php
	}
}

Front_End_Profile_Builder::get_instance();