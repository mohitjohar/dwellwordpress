<?php
/**
 * Dwell44 functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Dwell44
 */

if ( ! function_exists( 'dwell44_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function dwell44_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Dwell44, use a find and replace
		 * to change 'dwell44' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'dwell44', get_template_directory() . '/languages' );

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
			'menu-1' => esc_html__( 'Primary', 'dwell44' ),
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
		add_theme_support( 'custom-background', apply_filters( 'dwell44_custom_background_args', array(
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
add_action( 'after_setup_theme', 'dwell44_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function dwell44_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'dwell44_content_width', 640 );
}
add_action( 'after_setup_theme', 'dwell44_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function dwell44_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'dwell44' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'dwell44' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'dwell44_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function dwell44_scripts() {
	wp_enqueue_style( 'dwell44-style', get_stylesheet_uri() );

	wp_enqueue_script( 'dwell44-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'dwell44-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'dwell44_scripts' );

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

function sm_custom_meta() {
    add_meta_box( 'sm_meta', __( 'Featured Posts', 'sm-textdomain' ), 'sm_meta_callback', 'post' );
}
function sm_meta_callback( $post ) {
    $featured = get_post_meta( $post->ID );
    ?>
 
	<p>
    <div class="sm-row-content">
        <label for="meta-checkbox">
            <input type="checkbox" name="meta-checkbox" id="meta-checkbox" value="yes" <?php if ( isset ( $featured['meta-checkbox'] ) ) checked( $featured['meta-checkbox'][0], 'yes' ); ?> />
            <?php _e( 'Featured this post', 'sm-textdomain' )?>
        </label>
        
    </div>
</p>
 
    <?php
}
add_action( 'add_meta_boxes', 'sm_custom_meta' );

/**
 * Saves the custom meta input
 */
function sm_meta_save( $post_id ) {
 
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'sm_nonce' ] ) && wp_verify_nonce( $_POST[ 'sm_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
 
 // Checks for input and saves
if( isset( $_POST[ 'meta-checkbox' ] ) ) {
    update_post_meta( $post_id, 'meta-checkbox', 'yes' );
} else {
    update_post_meta( $post_id, 'meta-checkbox', '' );
}
 
}
add_action( 'save_post', 'sm_meta_save' );
// Limit Word
function word_limit($text, $length = 64, $tail = "...") {
    $text = trim($text);
    $txtl = strlen($text);
    if($txtl > $length) {
        for($i=1;$text[$length-$i]!=" ";$i++) {
            if($i == $length) {
                return substr($text, 0, $length) . $tail;
            }
        }
        $text = substr($text, 0, $length-$i+1) . $tail;
    }
    return $text;
}
/* = Clean up wp-header
---------------------------------------------------- */

remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_generator');

/* = Disable emojis
---------------------------------------------------- */

function ovg_disable_emojis(){
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	add_filter('tiny_mce_plugins', 'ovg_disable_emojis_tinymce');
}

add_action('init', 'ovg_disable_emojis');

// Remove the tinymce emoji plugin
function ovg_disable_emojis_tinymce($plugins){
	if (is_array($plugins)) {
		return array_diff($plugins, array('wpemoji'));
	} else {
		return array();
	}
}

/**
 * Page Slug Body Class
 */
function add_slug_body_class( $classes ) {
	global $post;
	if ( isset( $post ) ) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}
	return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );
// Social Sharing Button
function social_sharing_buttons($content="") {
	global $post;
	if(is_singular() || is_home()){

		// Get current page URL
		$shareURL = urlencode(get_permalink());

		// Get current page title
		$shareTitle = str_replace( ' ', '%20', get_the_title());

		// Get Post Thumbnail for pinterest
		$shareThumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );

		// Construct sharing URL without using any script
		$twitterURL = 'https://twitter.com/intent/tweet?text='.$shareTitle.'&amp;url='.$shareURL.'&amp;via=Bigos';
		$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$shareURL;
		$googleURL = 'https://plus.google.com/share?url='.$shareURL;

		// Based on popular demand added Pinterest too
		$pinterestURL = 'https://pinterest.com/pin/create/button/?url='.$shareURL.'&amp;media='.$shareThumbnail[0].'&amp;description='.$shareTitle;

		// Add sharing button at the end of page/page content
		$variable .= '<div class="share-social">';
		$variable .= '<a class="share-link share-googleplus" href="'.$googleURL.'" target="_blank"><img src="'.get_bloginfo('template_url').'/img/google-share.png" /></a>';
		$variable .= '<a class="share-link share-facebook" href="'.$facebookURL.'" target="_blank"><img src="'.get_bloginfo('template_url').'/img/facebook-share.png" /></a>';
		$variable .= '<a class="share-link share-twitter" href="'. $twitterURL .'" target="_blank"><img src="'.get_bloginfo('template_url').'/img/twitter-share.png" /></a>';
		$variable .= '</div>';

		return $variable.$content;
	}else{
		// if not a post/page then don't include sharing button
		return $variable.$content;
	}
}
/**
 * Filter the except length to 20 words.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function wpdocs_custom_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );
/**
 * Filter the excerpt "read more" string.
 *
 * @param string $more "Read more" excerpt string.
 * @return string (Maybe) modified "read more" excerpt string.
 */
function wpdocs_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'wpdocs_excerpt_more' );
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
}
add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );
add_filter('show_admin_bar', '__return_false');

/*
* Creating a function to create our CPT
*/
 
function custom_post_type_founder() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Founders', 'Post Type General Name', 'twentythirteen' ),
        'singular_name'       => _x( 'Founder', 'Post Type Singular Name', 'twentythirteen' ),
        'menu_name'           => __( 'Founders', 'twentythirteen' ),
        'parent_item_colon'   => __( 'Parent Founder', 'twentythirteen' ),
        'all_items'           => __( 'All Founders', 'twentythirteen' ),
        'view_item'           => __( 'View Founder', 'twentythirteen' ),
        'add_new_item'        => __( 'Add New Founder', 'twentythirteen' ),
        'add_new'             => __( 'Add New', 'twentythirteen' ),
        'edit_item'           => __( 'Edit Founder', 'twentythirteen' ),
        'update_item'         => __( 'Update Founder', 'twentythirteen' ),
        'search_items'        => __( 'Search Founder', 'twentythirteen' ),
        'not_found'           => __( 'Not Found', 'twentythirteen' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'twentythirteen' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'founders', 'twentythirteen' ),
        'description'         => __( 'Founder news and reviews', 'twentythirteen' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( 'genres' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
    );
     
    // Registering your Custom Post Type
    register_post_type( 'founders', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'custom_post_type_founder', 0 );
function template_category_template_redirect()
{
    if( is_category())
    {
        wp_redirect( site_url('/blog/') );
        die;
    }
}
add_action( 'template_redirect','template_category_template_redirect' );

   /*-----------------------------------------------------------*/
    /*   Add User Social Links (functions.php)
    /*-----------------------------------------------------------*/
    function cfw_add_user_social_links( $user_contact ) {

    /* Add user contact methods */
    $user_contact['twitter']   = __('Twitter Link', 'textdomain');
    $user_contact['facebook']  = __('Facebook Link', 'textdomain');
    $user_contact['linkedin']  = __('LinkedIn Link', 'textdomain');
    $user_contact['instagram'] = __('Instagram Link', 'textdomain');
    $user_contact['youtube']  = __('Youtube Link', 'textdomain');

    return $user_contact;
}
add_filter('user_contactmethods', 'cfw_add_user_social_links');

function cfw_get_user_social_links() {
    $return  = '<ul class="list-inline">';
    if(!empty(get_the_author_meta('linkedin'))) {
        $return .= '<li><a href="'.get_the_author_meta('linkedin').'" title="LinkedIn" target="_blank" id="linkedin"><i class="fab fa-linkedin"></i></a></li>';
    }
    if(!empty(get_the_author_meta('twitter'))) {
        $return .= '<li><a href="'.get_the_author_meta('twitter').'" title="Twitter" target="_blank" id="twitter"><i class="fab fa-twitter"></i></a></li>';
    }
    if(!empty(get_the_author_meta('facebook'))) {
        $return .= '<li><a href="'.get_the_author_meta('facebook').'" title="Facebook" target="_blank" id="facebook"><i class="fab fa-facebook-f"></i></a></li>';
    }   
    if(!empty(get_the_author_meta('instagram'))) {
        $return .= '<li><a href="'.get_the_author_meta('instagram').'" title="Instagram" target="_blank" id="instagram"><i class="fab fa-instagram"></i></a></li>';
    }
 if(!empty(get_the_author_meta('youtube'))) {
        $return .= '<li><a href="'.get_the_author_meta('youtube').'" title="Youtube" target="_blank" id="youtube"><i class="fab fa-youtube"></i></a></li>';
    }
    $return .= '</ul>';

    return $return;
}
function first_paragraph($content){
  // Testing to see if the content is a Page or Custom Post Type of school, if so, display the text normally (without the class = intro).
  if ( is_page() || ('school' == get_post_type() ) ) {
    return preg_replace('/<p([^>]+)?>/', '<p$1>', $content, 1);
  } else {
    return preg_replace('/<p([^>]+)?>/', '<p$1 class="intro">', $content, 1);
  }
}
add_filter('the_content', 'first_paragraph');