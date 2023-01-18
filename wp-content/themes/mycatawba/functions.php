<?php
/**
 * MyCatawba functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package MyCatawba
 */

if ( ! function_exists( 'mycatawba_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function mycatawba_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on MyCatawba, use a find and replace
		 * to change 'mycatawba' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'mycatawba', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'Main Menu' => esc_html__( 'Primary', 'mycatawba' ),
		) );
        register_nav_menus( array(
			'Footer' => esc_html__( 'Footer', 'mycatawba' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'mycatawba_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'mycatawba_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function mycatawba_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'mycatawba_content_width', 640 );
}
add_action( 'after_setup_theme', 'mycatawba_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function mycatawba_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'mycatawba' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'mycatawba' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
    
    register_sidebar( array(
		'name'          => esc_html__( 'Footer Description', 'mycatawba' ),
		'id'            => 'footer-desc',
		'description'   => esc_html__( 'Edit your footer description here.', 'mycatawba' ),
		'before_widget' => '<div class="footer-desc">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title" style="display:none;">',
		'after_title'   => '</h2>',
	) );
    
    register_sidebar( array(
		'name'          => esc_html__( 'Contact Information', 'mycatawba' ),
		'id'            => 'contact-info',
		'description'   => esc_html__( 'Edit your contact information here.', 'mycatawba' ),
		'before_widget' => '<div class="contact-info">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title" style="display:none;">',
		'after_title'   => '</h2>',
	) );
    
    register_sidebar( array(
		'name'          => esc_html__( 'Copyright', 'mycatawba' ),
		'id'            => 'copyright',
		'description'   => esc_html__( 'Edit your copyright content here.', 'mycatawba' ),
		'before_widget' => '<div class="copyright">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title" style="display:none;">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'mycatawba_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function mycatawba_scripts() {
	wp_enqueue_style( 'mycatawba-style', get_stylesheet_uri() );
    
    wp_enqueue_style( 'bootstrap4-css', get_template_directory_uri() . '/assets/css/bootstrap.min.css' );
    
    wp_enqueue_style( 'slick-css', get_template_directory_uri() . '/assets/css/slick.css' );
    
    wp_enqueue_style( 'slick-theme-css', get_template_directory_uri() . '/assets/css/slick-theme.css' );
    
    wp_enqueue_style( 'fonts-css', get_template_directory_uri() . '/assets/css/fonts.css' );
    
    wp_enqueue_style( 'custom-css', get_template_directory_uri() . '/assets/css/custom.css' );

	wp_enqueue_script( 'mycatawba-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'mycatawba-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), '20151215', true );
    
    wp_enqueue_script( 'jquery-js', get_template_directory_uri() . '/assets/js/jquery.min.js');
    
    wp_enqueue_script( 'popper-js', get_template_directory_uri() . '/assets/js/popper.min.js');
    
    wp_enqueue_script( 'bootstrap4-js', get_template_directory_uri() . '/assets/js/bootstrap.min.js');  
    
    wp_enqueue_script( 'slick-js', get_template_directory_uri() . '/assets/js/slick.min.js');    
    
    wp_enqueue_script( 'custom-js', get_template_directory_uri() . '/assets/js/custom.js');

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'mycatawba_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}
function custom_remove_dashboard () {
    global $current_user, $menu, $submenu;
    get_currentuserinfo();

    if( ! in_array( 'administrator', $current_user->roles ) ) {
        reset( $menu );
        $page = key( $menu );
        while( ( __( 'Dashboard' ) != $menu[$page][0] ) && next( $menu ) ) {
            $page = key( $menu );
        }
        if( __( 'Dashboard' ) == $menu[$page][0] ) {
            unset( $menu[$page] );
        }
        reset($menu);
        $page = key($menu);
        while ( ! $current_user->has_cap( $menu[$page][1] ) && next( $menu ) ) {
            $page = key( $menu );
        }
        if ( preg_match( '#wp-admin/?(index.php)?$#', $_SERVER['REQUEST_URI'] ) &&
            ( 'index.php' != $menu[$page][2] ) ) {
            	if (!current_user_can('subscriber')) {
	            	wp_redirect( get_option( 'siteurl' ) . '/wp-admin/index.php');
            	} else {
	            	wp_redirect( get_option( 'siteurl' ) . '/dashboard');
            	}
        }
    }
}
add_action('admin_menu', 'custom_remove_dashboard');

function create_badge() {
	$labels = array(
		'name'               => __( 'Badges' ),
		'singular_name'      => __( 'Badges' ),
		'add_new'            => __( 'Add New Badge' ),
		'add_new_item'       => __( 'Add New' ),
		'edit_item'          => __( 'Edit Badge' ),
		'new_item'           => __( 'Add New Badge' ),
		'view_item'          => __( 'View Badge' ),
		'search_items'       => __( 'Search Badge' ),
		'not_found'          => __( 'No Badge found' ),
		'not_found_in_trash' => __( 'No Badges found in trash' )
	);
	$supports = array(
		'title',
		'editor',
		'thumbnail',
		'revisions',
        'comments',
	);
	$args = array(
		'labels'               => $labels,
		'supports'             => $supports,
		'public'               => true,
		'capability_type'      => 'post',
		'rewrite'              => array( 'slug' => 'badges' ),
		'has_archive'          => true,
		'menu_position'        => 30,
		'menu_icon'            => 'dashicons-tickets-alt',
        'has_archive'   => true
	);
	register_post_type( 'badges', $args );
}
add_action( 'init', 'create_badge' );

function user_rewards() {
	$labels = array(
		'name'               => __( 'Rewards' ),
		'singular_name'      => __( 'Rewards' ),
		'add_new'            => __( 'Add New Reward' ),
		'add_new_item'       => __( 'Add New' ),
		'edit_item'          => __( 'Edit Reward' ),
		'new_item'           => __( 'Add New Reward' ),
		'view_item'          => __( 'View Reward' ),
		'search_items'       => __( 'Search Rewards' ),
		'not_found'          => __( 'No Rewards found' ),
		'not_found_in_trash' => __( 'No Rewards found in trash' )
	);
	$supports = array(
		'title',
		'editor',
		'thumbnail',
		'revisions',
	);
	$args = array(
		'labels'               => $labels,
		'supports'             => $supports,
		'public'               => true,
		'capability_type'      => 'post',
		'rewrite'              => array( 'slug' => 'rewards' ),
		'has_archive'          => true,
		'menu_position'        => 30,
		'menu_icon'            => 'dashicons-awards',
        'has_archive'   => true
	);
	register_post_type( 'rewards', $args );
}
add_action( 'init', 'user_rewards' );


function reviews() {
	$labels = array(
		'name'               => __( 'Reviews' ),
		'singular_name'      => __( 'Review' ),
		'add_new'            => __( 'Add New Review' ),
		'add_new_item'       => __( 'Add New' ),
		'edit_item'          => __( 'Edit Review' ),
		'new_item'           => __( 'Add New Review' ),
		'view_item'          => __( 'View Review' ),
		'search_items'       => __( 'Search Reviews' ),
		'not_found'          => __( 'No reviews found' ),
		'not_found_in_trash' => __( 'No reviews found in trash' )
	);
	$supports = array(
		'title',
		'editor',
	);
	$args = array(
		'labels'               => $labels,
		'supports'             => $supports,
		'public'               => true,
		'capability_type'      => 'post',
		'rewrite'              => array( 'slug' => 'reviews' ),
		'has_archive'          => true,
		'menu_position'        => 30,
		'menu_icon'            => 'dashicons-star-filled',
        'has_archive'   => true,
        'register_meta_box_cb' => 'custom_reviews'
	);
	register_post_type( 'reviews', $args );
}
add_action( 'init', 'reviews' );

add_filter( 'wp_default_scripts', $af = static function( &$scripts) {
    if(!is_admin()) {
        $scripts->remove( 'jquery');
        $scripts->add( 'jquery', false, array( 'jquery-core' ), '1.12.4' );
    }    
}, PHP_INT_MAX );
unset( $af );
if ( ! current_user_can( 'manage_options' ) ) {
    show_admin_bar( false );
}

add_filter('manage_edit-reviews_columns', 'my_columns');

function my_columns($columns) {    
    $columns['userid'] = 'Review by';
    $columns['rating'] = 'Rating';
    return $columns;
}

add_action('manage_posts_custom_column',  'my_show_columns');
function my_show_columns($name) {
    global $post;
    switch ($name) {
        case 'userid':
            $views = get_post_meta($post->ID, 'user', true);
            echo $views;
            break;
        case 'rating': 
            $views = get_post_meta($post->ID, 'rating', true);
            if($views){
                echo $views. '<span class="dashicons-before dashicons-star-filled"></span>';
            }            
            break;
    }
}


//Meta Box Reviews
/*function custom_reviews() {	
    add_meta_box(
		'ratings',
		'Ratings',
		'ratings',
		'reviews',
		'normal',
		'default'
	);    
}

function ratings($post){ 
    wp_nonce_field( 'save_quote_meta', 'custom_nonce' );  ?>
    <input type="hidden" name="userid" value="<?php echo $post->ID ?>">    
    <input type="hidden" name="rating" value="">
<?php }

function myplugin_meta_save($post_id, $post){   
    if ( isset($_POST['userid']) ) { // if we get new data
        update_post_meta($post_id, "userid", $_POST['userid'] );
    }  
    if ( isset($_POST['rating']) ) { // if we get new data
        update_post_meta($post_id, "rating", $_POST['rating'] );
    }     
}
add_action( 'save_post', 'myplugin_meta_save', 10, 2 );*/
add_action('admin_head', 'backend_css');
function backend_css() {
  echo '<style>
    #ratings {display: none;}
    .post-type-reviews #wp-content-wrap, .post-type-reviews #postdivrich, .post-type-reviews #titlediv .inside{display: none;}
    .post-type-reviews input:disabled, .post-type-reviews textarea:disabled{color: #000 !important;}
    .post-type-reviews td.column-rating, .post-type-reviews td.column-userid{font-weight: bold; }
    .post-type-reviews .column-rating .dashicons-star-filled:before{color: #FFC02B;font-size: 18px;padding-left: 5px;}
  </style>';
}

add_action( 'admin_print_scripts', function() {
    // I'm using NOWDOC notation to allow line breaks and unescaped quotation marks.
    echo <<<'EOT'
<script>
jQuery(function($){
    $('.post-type-reviews #titlewrap input, .post-type-reviews .postbox-container input, .post-type-reviews .postbox-container textarea').attr('disabled', true);
});
</script>
EOT;
}, PHP_INT_MAX );