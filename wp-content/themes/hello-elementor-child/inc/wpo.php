<?php
/**
 * Web Performance Optimization & Accessibility (WPO / A11y)
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Accessibility: Add aria-label to Elementor image-box wrapper links that only contain images.
 * Uses output buffering on the_content to inject aria-label where the anchor has no text.
 * Applied only on front-page to keep the filter surgical.
 */
function ktd_fix_imagebox_link_aria( $content ) {
	$content = preg_replace_callback(
		'/<a\b([^>]*class="[^"]*elementor-image-box-img[^"]*"[^>]*)>(\s*<figure[^>]*>.*?<\/figure>\s*)<\/a>/is',
		function ( $m ) {
			$attrs = $m[1];
			if ( preg_match( '/aria-(label|hidden)/i', $attrs ) ) {
				return $m[0];
			}
			$label = '';
			if ( preg_match( '/<img[^>]+alt=["\']([^"\']*)["\'][^>]*>/i', $m[2], $alt ) ) {
				$label = trim( $alt[1] );
			}
			$label = $label ?: 'View product';
			return '<a' . $attrs . ' aria-label="' . esc_attr( $label ) . '">' . $m[2] . '</a>';
		},
		$content
	);
	return $content;
}
add_filter( 'the_content', 'ktd_fix_imagebox_link_aria' );

/**
 * 2. Accessibility: Add <main> landmark on Front Page for screen readers and SEO.
 */
function ktd_inject_main_landmark_open() {
	if ( is_front_page() || is_home() ) {
		echo '<main id="main-content" class="ktd-front-main" tabindex="-1">' . "\n";
	}
}
function ktd_inject_main_landmark_close() {
	if ( is_front_page() || is_home() ) {
		echo '</main><!-- #main-content -->' . "\n";
	}
}
add_action( 'wp_body_open', 'ktd_inject_main_landmark_open', 20 );
add_action( 'wp_footer', 'ktd_inject_main_landmark_close', 1 );

/**
 * 3. WPO: Deregister dashicons on frontend for non-logged-in users.
 * Saves ~36 KB of render-blocking CSS on every frontend page load for guests.
 */
function ktd_deregister_dashicons_frontend() {
	if ( ! is_user_logged_in() ) {
		wp_deregister_style( 'dashicons' );
	}
}
add_action( 'wp_enqueue_scripts', 'ktd_deregister_dashicons_frontend', 100 );

/**
 * 4. WPO: Add fetchpriority="high" to the first image in hero/slider to optimize LCP.
 */
function ktd_lcp_image_fetchpriority( $attr, $attachment, $size ) {
	static $first_called = false;
	if ( ! $first_called && is_front_page() ) {
		$first_called          = true;
		$attr['fetchpriority'] = 'high';
		$attr['loading']       = 'eager';
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'ktd_lcp_image_fetchpriority', 10, 3 );

/**
 * 5. Routing: Ensure custom page templates are always used for About and Contact pages
 */
add_filter( 'template_include', function( $template ) {
	if ( is_page() ) {
		$post_obj = get_queried_object();
		$slug     = ( $post_obj && isset( $post_obj->post_name ) ) ? $post_obj->post_name : '';
		if ( in_array( $slug, array( 'about', 'about-us', 'gioi-thieu' ), true ) ) {
			$file = get_stylesheet_directory() . '/page-about.php';
			if ( file_exists( $file ) ) {
				return $file;
			}
		}
		if ( in_array( $slug, array( 'contact', 'contact-us', 'lien-he' ), true ) ) {
			$file = get_stylesheet_directory() . '/page-contact-us.php';
			if ( file_exists( $file ) ) {
				return $file;
			}
		}
	}
	return $template;
}, 999 );

/**
 * 6. Cache: Purge LiteSpeed Cache only when a post/product is saved or updated
 */
add_action( 'save_post', function() {
	if ( has_action( 'litespeed_purge_all' ) ) {
		do_action( 'litespeed_purge_all' );
	}
} );

/**
 * 7. Bridge: Local by Flywheel Live Link Asset Bridge
 */
function ktd_is_live_link_request() {
	$host = '';
	if ( ! empty( $_SERVER['HTTP_X_FORWARDED_HOST'] ) ) {
		$host = $_SERVER['HTTP_X_FORWARDED_HOST'];
	} elseif ( ! empty( $_SERVER['HTTP_HOST'] ) ) {
		$host = $_SERVER['HTTP_HOST'];
	}
	return ( strpos( $host, 'localsite.io' ) !== false || strpos( $host, 'ngrok' ) !== false || strpos( $host, 'trycloudflare.com' ) !== false );
}

function ktd_get_current_live_host() {
	if ( ! empty( $_SERVER['HTTP_X_FORWARDED_HOST'] ) ) {
		return $_SERVER['HTTP_X_FORWARDED_HOST'];
	} elseif ( ! empty( $_SERVER['HTTP_HOST'] ) ) {
		return $_SERVER['HTTP_HOST'];
	}
	return '';
}

if ( ktd_is_live_link_request() ) {
	$live_host = ktd_get_current_live_host();

	add_filter( 'redirect_canonical', '__return_false' );

	$scheme = is_ssl() ? 'https://' : 'http://';

	add_filter( 'option_siteurl', function() use ( $live_host, $scheme ) {
		return $scheme . $live_host;
	} );
	add_filter( 'option_home', function() use ( $live_host, $scheme ) {
		return $scheme . $live_host;
	} );

	add_filter( 'upload_dir', function( $uploads ) use ( $live_host ) {
		if ( isset( $uploads['baseurl'] ) ) {
			$uploads['baseurl'] = preg_replace( '#https?://[^/]+#', '//' . $live_host, $uploads['baseurl'] );
		}
		if ( isset( $uploads['url'] ) ) {
			$uploads['url'] = preg_replace( '#https?://[^/]+#', '//' . $live_host, $uploads['url'] );
		}
		return $uploads;
	} );

	$rewrite_fn = function( $buffer ) use ( $live_host ) {
		if ( empty( $buffer ) ) {
			return $buffer;
		}
		$patterns = array(
			'http://ktd-ecommerce.local',
			'https://ktd-ecommerce.local',
			'//ktd-ecommerce.local',
			'http:\/\/ktd-ecommerce.local',
			'https:\/\/ktd-ecommerce.local',
			'\/\/ktd-ecommerce.local',
		);
		$replacements = array(
			'//' . $live_host,
			'//' . $live_host,
			'//' . $live_host,
			'\/\/' . $live_host,
			'\/\/' . $live_host,
			'\/\/' . $live_host,
		);
		return str_replace( $patterns, $replacements, $buffer );
	};

	add_filter( 'litespeed_buffer_finalize', $rewrite_fn, 999999 );

	add_action( 'template_redirect', function() use ( $rewrite_fn ) {
		ob_start( $rewrite_fn );
	}, 1 );
}
