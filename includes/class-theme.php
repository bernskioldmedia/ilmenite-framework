<?php
/**
 * Ilmenite Theme Class
 *
 * This file is responsible for setting up the theme class, loading features,
 * functions and extensions which are typically placed in other classes and
 * files and then loaded in via this.
 *
 * We do this as opposed to using a functions.php approach because it makes our
 * code more modular and easy to maintain and use.
 *
 * @author Bernskiold Media <info@bernskioldmedia.com>
 * @package BernskioldMedia\ClientName\Theme
 */
namespace BernskioldMedia\ClientName\Theme;

/**
 * Class Ilmenite_Theme
 *
 * @package BernskioldMedia\ClientName\Theme
 */
class Ilmenite_Theme {

	/**
	 * Theme URL
	 *
	 * @var string
	 */
	public $theme_url = '';

	/**
	 * Theme Directory Path
	 *
	 * @var string
	 */
	public $theme_dir = '';

	/**
	 * Theme Version Number
	 *
	 * @var string
	 */
	public $theme_version = '';

	/**
	 * Theme Name
	 *
	 * @var string
	 */
	public $theme_name = '';

	/**
	 * Theme Slug
	 *
	 * @var string
	 */
	public $theme_slug = '';

	/**
	 * Helper Functions Class Instance
	 *
	 * @var object
	 */
	public $helper;

	/**
	 * Template Functions Class Instance
	 *
	 * @var object
	 */
	public $template;

	/**
	 * Transient Queries Class Instance
	 *
	 * @var object
	 */
	public $transient;

	/**
	 * The single instance of the class
	 *
	 * @var object
	 */
	protected static $_instance = null;

	/**
	 * Class Instance
	 *
	 * @return object Instance Object.
	 */
	public static function instance() {

	    if ( is_null( self::$_instance ) ) {
	    	self::$_instance = new self();
	    }

		return self::$_instance;
	}

	/**
	 * Initializes theme framework. Load required files and
	 * call necessary functions.
	 **/
	public function __construct() {

		// Theme Version.
		$this->theme_version = '1.0';

		// Theme Name.
		$this->theme_name = esc_html__( 'THEMENAMEHERE', 'THEMETEXTDOMAIN' );

		// Theme Slug.
		$this->theme_slug = 'THEMETEXTDOMAIN';

		// Theme Directory.
		$this->theme_dir = get_template_directory();

		// Theme URI.
		$this->theme_uri = get_template_directory_uri();

		// Add WordPress default add_theme_support.
		add_action( 'after_setup_theme', array( $this, 'theme_support' ) );

		// Load functions.
		add_action( 'init', array( $this, 'classes' ) );

		// Load custom dashboard widgets.
		add_action( 'admin_init', array( $this, 'dashboard_widgets' ) );

		// Remove non-necessary dashboard widgets.
		add_action( 'wp_dashboard_setup', array( $this, 'remove_dashboard_widgets' ) );

		// Add custom image sizes.
		add_action( 'after_setup_theme', array( $this, 'custom_image_sizes' ) );

		// Add navigation menus.
		add_action( 'after_setup_theme', array( $this, 'register_navigation_menus' ) );

		// Customize Admin.
		add_filter( 'admin_footer_text', array( $this, 'change_admin_footer_text' ) );
		add_action( 'admin_menu', array( $this, 'admin_no_footer_version' ) );

		// Sidebars.
		require_once( $this->theme_dir . '/includes/sidebars.php' );

	}

	/**
	 * Add theme support for: add_theme_support variables
	 * Also registers default sidebar
	 **/
	public function theme_support() {

		if ( function_exists( 'add_theme_support' ) ) {

			// Add default posts and comments RSS feed links to head.
			add_theme_support( 'automatic-feed-links' );

			/*
			 * Enable support for Post Thumbnails on posts and pages.
			 *
			 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
			 */
			add_theme_support( 'post-thumbnails' );

			// Add theme support for custom CSS in the TinyMCE visual editor
			add_editor_style( '/assets/css/editor-style.css' );

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

			/*
			 * Let WordPress manage the document title.
			 * By adding theme support, we declare that this theme does not use a
			 * hard-coded <title> tag in the document head, and expect WordPress to
			 * provide it for us.
			 */
			add_theme_support( 'title-tag' );

		}

		/*
		 * Make the theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 */
		load_theme_textdomain( 'THEMETEXTDOMAIN', get_template_directory() . '/languages' );

	}

	/**
	 * Set up Custom Image Sizes
	 */
	public function custom_image_sizes() {

		// Example Code.
		// add_image_size( 'large', 1024, '', true );

	}

	/**
	 * Register Navigation Menus
	 */
	public function register_navigation_menus() {

		if ( ! function_exists( 'register_navigation_menus' ) ) {

			$locations = array(
				'primary-menu' => esc_html__( 'Main Navigation', 'THEMETEXTDOMAIN' ),
			);

			register_nav_menus( $locations );

		}

	}

	/**
	 * Loads Core Functions and Classes
	 **/
	public function classes() {

		// Cleanup Functions.
		require_once( $this->theme_dir . '/includes/classes/class-cleanup.php' );
		new Theme_Cleanup;

		// Helper functions.
		require_once( $this->theme_dir . '/includes/classes/class-helper-functions.php' );
		$this->helper = new Theme_Helpers;

		// Transient Queries.
		require_once( $this->theme_dir . '/includes/classes/class-transient-queries.php' );
		$this->transient = new Transient_Queries;

		// Load scripts, styles etc.
		require_once( $this->theme_dir . '/includes/classes/class-scripts-styles.php' );
		new Theme_Scripts_Styles;

		// UI Element Functions.
		require_once( $this->theme_dir . '/includes/classes/class-template-functions.php' );
		$this->template = new Template_Functions;

		// WP Login Customization.
		require_once( $this->theme_dir . '/includes/classes/class-wp-login.php' );
		new Theme_Login;

	}

	/**
	 * Loads admin dashboard widgets
	 **/
	public function dashboard_widgets() {

		// Website Welcome Widget.
		require_once( $this->theme_dir . '/includes/dashboard-widgets/class-bm-dashboard-welcome.php' );

		// RSS Widget Showing Agency Blog Posts.
		require_once( $this->theme_dir . '/includes/dashboard-widgets/class-bm-dashboard-rss.php' );

	}

	public function remove_dashboard_widgets() {

		global $wp_meta_boxes;

		// Hide Some Default Dashboard Widgets.
		unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'] );
		unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );
		unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'] );
		unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'] );
		unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] );
		unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts'] );

	}

	public function change_admin_footer_text() {

		$text = sprintf( __( '%s Website Admin Panel. Website developed by <a href="https://www.bernskioldmedia.com/en/">Bernskiold Media</a>.', 'THEMETEXTDOMAIN' ), bloginfo( 'name' ) );

		echo wp_kses_post( $text );

	}

	public function admin_no_footer_version() {
	    remove_filter( 'update_footer', 'core_update_footer' );
	}

	/**
	 * Get Theme URI
	 */
	public function get_theme_uri() {
		return $this->theme_uri;
	}

	/**
	 * Get Theme Path
	 */
	public function get_theme_path() {
		return $this->theme_dir;
	}

	/**
	 * Get Theme Version
	 */
	public function get_theme_version() {
		return $this->theme_version;
	}

	/**
	 * Get Theme Assets URI
	 */
	public function get_theme_assets_uri() {
		return $this->theme_uri . '/assets';
	}

	/**
	 * Get Theme Images URI
	 */
	public function get_theme_images_uri() {
		return $this->theme_uri . '/assets/images';
	}

	/**
	 * Get Theme Icons URI
	 */
	public function get_theme_icons_uri() {
		return $this->theme_uri . '/assets/icons';
	}

}

/**
 * Theme Function
 *
 * The theme function is used to that we can easily call the methods
 * on this class without having to redefine the Ilmenite_Theme class
 * over and over again in our theme code.
 */
function theme() {
    return Ilmenite_Theme::instance();
}

// Initialize the class instance only once,
// because we need it to run right away.
theme();