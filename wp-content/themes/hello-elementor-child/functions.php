<?php
/**
 * Theme functions and definitions for Hello Elementor Child
 * Modular architecture bootstrap
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'KTD_THEME_DIR' ) ) {
	define( 'KTD_THEME_DIR', function_exists( 'get_stylesheet_directory' ) ? get_stylesheet_directory() : __DIR__ );
}
if ( ! defined( 'KTD_THEME_URI' ) ) {
	define( 'KTD_THEME_URI', function_exists( 'get_stylesheet_directory_uri' ) ? get_stylesheet_directory_uri() : '' );
}

// Load modular architecture components
require_once KTD_THEME_DIR . '/inc/enqueue.php';
require_once KTD_THEME_DIR . '/inc/wpo.php';
require_once KTD_THEME_DIR . '/inc/woocommerce.php';
require_once KTD_THEME_DIR . '/inc/auth.php';
require_once KTD_THEME_DIR . '/inc/chatbot.php';
require_once KTD_THEME_DIR . '/inc/blog.php';
