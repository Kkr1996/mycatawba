<?php

require_once CLASSES . '/class.front-end-avatar-url.php';

/**
 * Front-end user profile shortcode parser
 */
class Front_End_Profile_Builder_Shortcode_Parser {
	/** @var WP_User user_data */
	static private $user_data;

	/**
	 * Define all front-end profile sub-shortcode.
	 *
	 * @param $user
	 */
	protected function __construct( $user ) {
		self::$user_data = $user;

		add_shortcode( 'profile-username', array( $this, 'profile_username' ) );

		add_shortcode( 'profile-email', array( $this, 'profile_email' ) );

		add_shortcode( 'profile-website', array( $this, 'profile_website' ) );

		add_shortcode( 'profile-nickname', array( $this, 'profile_nickname' ) );

		add_shortcode( 'profile-display-name', array( $this, 'profile_display_name' ) );

		add_shortcode( 'profile-first-name', array( $this, 'profile_first_name' ) );

		add_shortcode( 'profile-last-name', array( $this, 'profile_last_name' ) );

		add_shortcode( 'profile-bio', array( $this, 'profile_bio' ) );

		add_shortcode( 'profile-cpf', array( $this, 'profile_custom_profile_field' ) );

		add_shortcode( 'profile-file', array( $this, 'profile_user_uploaded_file' ) );

		add_shortcode( 'profile-avatar-url', array( $this, 'user_avatar_url' ) );

		add_shortcode( 'profile-hide-empty-data', array( $this, 'hide_empty_data' ) );

		add_shortcode( 'post-count', array( $this, 'post_count' ) );

		add_shortcode( 'comment-count', array( $this, 'get_comment_count' ) );

		add_shortcode( 'profile-post-list', array( $this, 'author_post_list' ) );

		add_shortcode( 'profile-date-registered', array( $this, 'date_user_registered' ) );

		add_shortcode( 'jcarousel-author-posts', array( $this, 'pp_jcarousel_author_posts' ) );

		/**
		 * @param object $user WP_User object
		 */
		do_action( 'pp_register_profile_shortcode', $user );
	}

	public function date_user_registered() {
		return date( 'F jS, Y', strtotime( self::$user_data->user_registered ) );
	}

	public function author_post_list( $attributes ) {
		$attributes = shortcode_atts( array( 'limit' => 10 ), $attributes );

		$user_id = self::$user_data->ID;
		$limit   = absint( $attributes['limit'] );

		$cache_key = "pp_profile_post_list_{$user_id}_{$limit}";
		$output    = get_transient( $cache_key );

		if ( $output === false ) {

			$posts = get_posts( array(
				'author'         => $user_id,
				'posts_per_page' => $limit,
				'offset'         => 0
			) );

			$output = '';

			if ( ! empty( $posts ) ) {

				$output .= "<ul class='pp-user-post-list'>";
				/** @var WP_Post $post */
				foreach ( $posts as $post ) {
					$output .= sprintf( '<li class="pp-user-post-item"><a href="%s"><h3 class="pp-post-item-head">%s</h3></a></li>', get_permalink( $post->ID ), $post->post_title );
				}

				$output .= "</ul>";

				set_transient( $cache_key, $output, HOUR_IN_SECONDS );
			}
		}

		return $output;
	}

	/**
	 * Profile username
	 *
	 * @return mixed
	 */
	public function profile_username() {

		$capitalization = apply_filters( 'pp_capitalize_username', true );

		$username = self::$user_data->user_login;

		$username = $capitalization ? ucwords( $username ) : $username;

		return apply_filters( 'pp_profile_username', $username, self::$user_data );
	}


	/**
	 * User email
	 *
	 * @return mixed
	 */
	public function profile_email() {

		return apply_filters( 'pp_profile_email', self::$user_data->user_email, self::$user_data );

	}


	/**
	 * Return user avatar image url
	 *
	 * @param array $atts shortcode attributes
	 *
	 * @return string image url
	 */
	public function user_avatar_url( $atts ) {
		$atts = shortcode_atts(
			array(
				'size' => '',
				'url'  => '',
			),
			$atts
		);

		// avatar size
		$size = ! empty( $atts['size'] ) ? $atts['size'] : null;

		// default image url if no user profile/ avatar is present
		$default_url = ! empty( $atts['url'] ) ? $atts['url'] : null;

		$user_id = self::$user_data->ID;

		return apply_filters( 'pp_profile_avatar_url', ProfilePress_Avatar_Url::get_avatar_url( $user_id, $size, $default_url ), self::$user_data );

	}


	/**
	 * User website URL
	 *
	 * @return mixed
	 */
	public function profile_website() {
		return apply_filters( 'pp_profile_website', self::$user_data->user_url, self::$user_data );
	}


	/**
	 * Nickname of user
	 *
	 * @return mixed
	 */
	public function profile_nickname() {

		return apply_filters( 'pp_profile_nickname', self::$user_data->nickname, self::$user_data );

	}


	/**
	 * Display name of profile
	 *
	 * @return mixed
	 */
	public function profile_display_name() {

		return apply_filters( 'pp_profile_display_name', self::$user_data->display_name, self::$user_data );

	}


	/**
	 * Profile first name
	 *
	 * @return mixed
	 */
	public function profile_first_name() {

		return apply_filters( 'pp_profile_first_name', self::$user_data->first_name, self::$user_data );

	}


	/**
	 * Last name of user.
	 *
	 * @return mixed
	 */
	public function profile_last_name() {

		return apply_filters( 'pp_profile_last_name', self::$user_data->last_name, self::$user_data );

	}


	/**
	 * Description/bio of user.
	 *
	 * @return mixed
	 */
	public function profile_bio() {
		return apply_filters( 'pp_profile_bio', self::$user_data->description, self::$user_data );
	}


	/**
	 * Custom profile data of user.
	 *
	 * @param $atts array shortcode attributes
	 *
	 * @return string
	 */
	public function profile_custom_profile_field( $atts ) {
		$atts = shortcode_atts(
			array(
				'key' => '',
			),
			$atts
		);

		$key = $atts['key'];

		if ( empty( $key ) ) {
			return __( 'Field key is missing', 'profilepress' );
		}

		$data = self::$user_data->{$key};

		if ( is_array( $data ) ) {
			$data = implode( ', ', array_filter( $data, function ( $val ) {
				return ! empty( $val );
			} ) );
		}

		return apply_filters( 'pp_profile_cpf', $data, self::$user_data );
	}

	public function profile_user_uploaded_file( $atts ) {
		$atts = self::normalize_attributes( $atts );

		$atts = shortcode_atts(
			array(
				'key' => '',
				'raw' => false,
			),
			$atts
		);

		$key = $atts['key'];

		$user_upload_data = get_user_meta( self::$user_data->ID, 'pp_uploaded_files', true );

		if ( empty( $user_upload_data ) ) {
			return;
		}

		$filename = $user_upload_data[ $key ];
		if ( empty( $filename ) ) {
			return;
		}

		$link = PP_FILE_UPLOAD_URL . $filename;

		if ( ! empty( $atts['raw'] ) && ( $atts['raw'] === true || $atts['raw'] == 'true' ) ) {
			$return = $link;
		} else {
			$return = "<a href='$link'>$filename</a>";
		}

		return apply_filters( 'pp_profile_file', $return, self::$user_data );

	}

	/**
	 * Return number of post written by a user
	 *
	 * @return int
	 */
	public function post_count() {
		return apply_filters( 'pp_profile_post_count', count_user_posts( self::$user_data->ID ), self::$user_data );
	}

	public function hide_empty_data( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'field' => '',
			),
			$atts
		);

		$key = ! empty( $atts['field'] ) ? strip_tags( $atts['field'] ) : '';

		switch ( $key ) {
			case 'username':
				$key = 'user_login';
				break;
			case 'email':
				$key = 'user_email';
				break;
			case 'website':
				$key = 'user_url';
				break;
			case 'nickname':
				$key = 'nickname';
				break;
			case 'display_name':
				$key = 'display_name';
				break;
			case 'first_name':
				$key = 'first_name';
				break;
			case 'last_name':
				$key = 'last_name';
				break;
		}

		if ( ! empty( $key ) && ! empty( self::$user_data->$key ) ) {
			return do_shortcode( $content );
		}
	}

	/**
	 * Return the total comment count made by a user
	 */
	public function get_comment_count() {
		global $wpdb;
		$userId = self::$user_data->ID;

		$count = $wpdb->get_var( '
             SELECT COUNT(comment_ID)
             FROM ' . $wpdb->comments . '
             WHERE user_id = "' . $userId . '" AND comment_type = "" AND comment_approved = 1' );

		return apply_filters( 'pp_profile_comment_count', $count, self::$user_data );
	}

	/**
	 * jCarousel author latest post slider
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function pp_jcarousel_author_posts( $atts ) {
		$atts = shortcode_atts(
			array(
				'count'   => 10,
				'default' => ASSETS_URL . '/images/jc_dft_img.jpg',
				'width'   => '',
			),
			$atts
		);

		$posts = get_posts(
			array(
				'post_type'      => array( 'post' ),
				'posts_per_page' => (int) $atts['count'],
				'author'         => self::$user_data->ID,
			)
		);

		$default_img = $atts['default'];
		$width       = ! empty( $atts['width'] ) ? ' style="width: ' . $atts['width'] . ';"' : null;

		ob_start();
		?>
    <div class="jcarousel-wrapper"<?php echo $width; ?>>
        <div class="jcarousel">

			<?php
			if ( empty( $posts ) ) {
				echo '<div class="jc-no-post">' .
				     apply_filters(
					     'jcarousel_no_post',
					     __( 'No post written yet.', 'profilepress' )
				     )
				     . '</div>';
			} else {

				echo '<ul>';
				foreach ( $posts as $post ) {
					$feature_img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium', false );
					$feature_img = $feature_img[0];

					if ( is_null( $feature_img ) ) {
						$feature_img = $default_img;
					}
					?>
                    <li>
                        <a href="<?php echo get_permalink( $post->ID ); ?>">
                            <img src="<?php echo $feature_img; ?>" alt="<?php echo $post->post_title; ?>">

                            <div class="jc-title"><?php echo $post->post_title; ?></div>
                        </a></li>
					<?php
				}
				echo '</ul>';
			}
			?>
        </div>

		<?php
		// hide jcarousel nav link if no post is found
		if ( ! empty( $posts ) ) :
			?>
            <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
            <a href="#" class="jcarousel-control-next">&rsaquo;</a>

            <p class="jcarousel-pagination"></p>            </div>
		<?php
		endif;

		return apply_filters( 'pp_jcarousel_author_posts', ob_get_clean(), self::$user_data );
	}

	/**
	 * Normalize unamed shortcode
	 *
	 * @param array $atts
	 *
	 * @return mixed
	 */
	public static function normalize_attributes( $atts ) {
		if ( is_array( $atts ) ) {
			foreach ( $atts as $key => $value ) {
				if ( is_int( $key ) ) {
					$atts[ $value ] = true;
					unset( $atts[ $key ] );
				}
			}
		}

		return $atts;
	}

	/** Singleton instance */
	public static function get_instance( $user = '' ) {
		static $instance = false;
		$user = isset( $user ) && ! empty( $user ) ? $user : wp_get_current_user();
		if ( ! $instance ) {
			$instance = new self( $user );
		}

		return $instance;
	}
}

add_filter( 'the_content', 'pp_load_front_end_profile' );

/**
 * This check if post/page content doesn't have the parent-profile shortcode and then loads the Front_End_Profile_Builder_Shortcode_Parser class.
 * We are doing this so individual profile shortocdes are available/supported everywhere in WordPress.
 *
 * @param $content
 *
 * @return mixed
 */
function pp_load_front_end_profile( $content ) {
	global $post;

	if ( is_a( $post, 'WP_Post' ) && ! has_shortcode( $post->post_content, 'profilepress-user-profile' ) ) {
		Front_End_Profile_Builder_Shortcode_Parser::get_instance();
	}

	return $content;
}

