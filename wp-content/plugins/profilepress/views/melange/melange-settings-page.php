<?php
require_once 'melange-wp-list-table.php';

/**
 * Melange Form Builder
 */
Class Melange_Form_Builder {

	private $melange_errors;

	private $plugin_menu_item;

	function __construct() {

		add_action( 'admin_menu', array( $this, 'register_settings_page' ) );
		add_filter( 'set-screen-option', array( $this, 'save_screen_option' ), 10, 3 );
		add_action( 'admin_print_scripts', array( $this, 'js_confirm_melange_builder' ) );

	}

	static function get_instance() {
		static $instance;
		if ( ! isset( $instance ) ) {
			$instance = new Melange_Form_Builder;
		}

		return $instance;
	}

	function register_settings_page() {

		$hook = add_submenu_page(
			'pp-config',
			__( 'Melange Form - ProfilePress', 'profilepress' ),
			__( 'Melange', 'profilepress' ),
			'manage_options',
			'pp-melange',
			array( $this, 'melange_page' )
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
			'id'      => __( 'help_tab_login-form', 'profilepress' ),
			'title'   => __( 'Melange form shortcodes', 'profilepress' ),
			'content' => require( PROFILEPRESS_ROOT . 'help-tab/melange.php' ),
		) );
		$screen->add_help_tab( array(
			'id'      => __( 'help_tab_global', 'profilepress' ),
			'title'   => __( 'Global shortcodes', 'profilepress' ),
			'content' => require( PROFILEPRESS_ROOT . 'help-tab/global.php' ),
		) );
		$screen->add_help_tab( array(
			'id'      => __( 'help_tab_zocial', 'profilepress' ),
			'title'   => __( 'Social login buttons', 'profilepress' ),
			'content' => require( PROFILEPRESS_ROOT . 'help-tab/social-buttons.php' ),
		) );
	}

	function melange_page() {
		// if we are in edit state, display the table
		if ( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ) {

			// save melange edit. note: method called before the static edit page
			// so generated error will display at the top of page
			$this->save_add_edit_melange_builder( 'edit', absint( @$_GET['melange'] ) );

			$this->melange_edit_page();
		}
		elseif ( isset( $_GET['melange'] ) && $_GET['melange'] == 'new' ) {

			$this->save_add_edit_melange_builder( 'add' );

			$this->melange_add_page();
		} // if we are not in edit state, display the table
		else {
			self::melange_index_page();
		}
	}

	/**
	 * @param $operation
	 * @param string $id
	 */
	function save_add_edit_melange_builder( $operation, $id = '' ) {
		if ( current_user_can( 'administrator' ) && (isset( $_POST['add_melange'] ) || isset( $_POST['edit_melange'] ) )) {
			$title              = @esc_attr( $_POST['mfb_title'] );
			$structure          = @stripslashes( $_POST['mfb_structure'] );
			$css                = @stripslashes( $_POST['mfb_css'] );
			$registration_msg   = @stripslashes( $_POST['mfb_success_registration'] );
			$password_reset_msg = @stripslashes( $_POST['mfb_success_password_reset'] );
			$edit_profile_msg   = @stripslashes( $_POST['mfb_success_edit_profile'] );
			$make_widget        = @esc_attr( $_POST['mfb_make_widget'] );
			$disable_username_requirement          = esc_attr( @$_POST['mfb_disable_username_requirement'] );


			// catch and save form generated errors in property @melange_errors
			if ( empty( $_POST['mfb_title'] ) ) {
				$this->melange_errors = __('Title is empty', 'profilepress');
			}
			elseif ( empty( $_POST['mfb_structure'] ) ) {
				$this->melange_errors = __('Melange Design is missing', 'profilepress');
			}

			if ( isset( $this->melange_errors ) ) {
				return;
			}

			if ( isset( $_POST['edit_melange'] ) && check_admin_referer( 'edit_melange_builder', '_wpnonce' ) && $operation == 'edit' ) {

				//insert revision
				PROFILEPRESS_sql::insert_revision( $id, 'melange' );

				PROFILEPRESS_sql::sql_update_melange_builder( $id, $title, $structure, $css, $registration_msg, $edit_profile_msg, $password_reset_msg, date( 'Y-m-d' ) );

				// call the appropriate wrapper function to record/save the "make widget" field
				if ( empty( $make_widget ) ) {
					// record/save the "make widget" field
					PROFILEPRESS_sql::sql_delete_pp_builder_widget( $id, 'melange' );
				}

				else {
					// record/save the "make widget" field
					PROFILEPRESS_sql::sql_add_pp_builder_widget( $id, 'melange' );
				}

				if ( empty( $disable_username_requirement ) ) {
					delete_option("pp_disable_username_requirement_melange_$id");
				}
				else {
				    update_option("pp_disable_username_requirement_melange_$id", 'yes');
				}

				wp_redirect( add_query_arg( 'melange-edited', 'true' ) );
				exit;
			}

			if ( isset( $_POST['add_melange'] ) && check_admin_referer( 'add_melange_builder', '_wpnonce' ) && $operation == 'add' ) {
				global $wpdb;

				$id = PROFILEPRESS_sql::sql_insert_melange_builder( $title, $structure, $css, $registration_msg, $edit_profile_msg, $password_reset_msg, date( 'Y-m-d' ) );

				if ( !$id ) {
					$this->melange_errors = __('Error creating a new Melange. Please try again', 'profilepress');
					return;
				}

				if ( isset( $make_widget ) && ! empty( $make_widget ) ) {

					// record/save the "make widget" field
					PROFILEPRESS_sql::sql_add_pp_builder_widget( $wpdb->insert_id, 'melange' );
				}
				
				if ( !empty( $disable_username_requirement ) ) {
					update_option("pp_disable_username_requirement_melange_$id", 'yes');
				}

				wp_redirect(
					sprintf(
						'?page=%s&action=%s&melange=%s&_wpnonce=%s&melange-added=true',
						MELANGE_SETTINGS_PAGE_SLUG, 'edit',
						absint( $id ),
						wp_create_nonce( 'pp_edit_melange' )
					)
				);
				exit;
			}
		}
	}

	public function melange_edit_page() {
		echo '<div class="wrap">
	<h2>' . __( 'Melange Form Builder', 'profilepress' ) . '</h2>';

		if ( isset( $this->melange_errors ) ) {
			echo '<div id="message" class="error notice is-dismissible"><p><strong>' . $this->melange_errors . '</strong></p></div>';
		}

		if ( @$_GET['melange-edited'] ) {
			echo '<div id="message" class="updated notice is-dismissible"><p><strong>' . __( 'Melange Edited.', 'profilepress' ) . '</strong></p></div>';
		}

		if ( @$_GET['melange-added'] ) {
			echo '<div id="message" class="updated notice is-dismissible"><p><strong>' . __( 'New Melange Added.', 'profilepress' ) . '</strong></p></div>';
		}

		require_once 'include.edit-melange.php';
	}

	/**
	 * Called to add new form
	 */
	public function melange_add_page() {
		?>
		<div class="wrap">
		<h2><?php _e( 'Melange Form Builder', 'profilepress' ); ?></h2>

		<?php if ( isset( $this->melange_errors ) ) { ?>
			<div id="message" class="error notice is-dismissible"><p><strong><?php echo $this->melange_errors; ?>. </strong>
				</p></div>
		<?php
		}
		require_once 'include.add-melange.php';
	}

	/**
	 * Settings page index page.
	 */
	static function melange_index_page() {
		?>
		<div class="wrap">
			<h2><?php _e( 'Melange Form Builder', 'profilepress' ); ?>
				<a class="add-new-h2" href="<?php echo esc_url( add_query_arg( 'melange', 'new' ) ); ?>"><?php _e( 'Add New', 'profilepress' ); ?></a>
			</h2>

			<?php
			// include settings tab
			require_once VIEWS . '/include.settings-page-tab.php'; ?>

			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<?php
							global $melange_list_table;
							$melange_list_table->prepare_items();
							?>
							<form method="post">
								<input type="hidden" name="page" value="ttest_list_table">
								<?php $melange_list_table->display(); ?>
							</form>
						</div>
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
		global $melange_list_table;
		$option = 'per_page';
		$args   = array(
			'label'   => __( 'Melange forms', 'profilepress' ),
			'default' => 10,
			'option'  => 'melange_per_page',
		);
		add_screen_option( $option, $args );

		$melange_list_table = new Melange_Builder_List_Table;

	}

	function save_screen_option( $status, $option, $value ) {
		return $value;
	}

	/** Add an alert before a melange builder is deleted */
	public function js_confirm_melange_builder() {
		?>
		<script type="text/javascript">
			function pp_del_melange(page, action, melange, _wpnonce) {
				if (confirm("Are you sure you want to delete this?")) {
					window.location.href = '?page=' + page + '&action=' + action + '&melange=' + melange + '&_wpnonce=' + _wpnonce;
				}
			}
		</script>
	<?php
	}
}

Melange_Form_Builder::get_instance();