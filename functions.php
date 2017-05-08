<?php
/**
 * dev functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package dev
 */

if ( ! function_exists( 'dev_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function dev_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on dev, use a find and replace
	 * to change 'dev' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'dev', get_template_directory() . '/languages' );

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

    // Add image size
    add_image_size( 'services-page', 1200, 800, true ); // name, width, height, crop

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'menu-1' => esc_html__( 'Primary', 'dev' ),
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
	add_theme_support( 'custom-background', apply_filters( 'dev_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif;
add_action( 'after_setup_theme', 'dev_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function dev_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'dev_content_width', 640 );
}
add_action( 'after_setup_theme', 'dev_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function dev_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'dev' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'dev' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
//add_action( 'widgets_init', 'dev_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function dev_scripts() {
	wp_enqueue_style( 'dev-style', get_stylesheet_uri() );

	wp_enqueue_style( 'dev-style-main', get_template_directory_uri() . '/assets/css/main.css' );

	wp_enqueue_script( 'dev-jquery', get_template_directory_uri() . '/assets/js/plugins/jquery.min.js', array(), '3.2.0', true );

    wp_enqueue_script( 'dev-js-easing',get_template_directory_uri() . '/assets/js/plugins/moveto.min.js', array(), '1.6.0', true );

    wp_enqueue_script( 'dev-slick', get_template_directory_uri() . '/assets/js/plugins/slick.min.js', array(), '1.6.0', true );

    wp_enqueue_script( 'dev-js-royalslider',get_template_directory_uri() . '/assets/js/plugins/royalslider.js', array(), '2.0', true );

	wp_enqueue_script( 'dev-js-scripts', get_template_directory_uri() . '/assets/js/min/scripts.js', array(), '01', true);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'dev_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';


if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(array(
        'page_title' 	=> 'Theme General Settings',
        'menu_title'	=> 'Theme Settings',
        'menu_slug' 	=> 'theme-general-settings',
        'capability'	=> 'edit_posts',
        'redirect'		=> false
    ));
}