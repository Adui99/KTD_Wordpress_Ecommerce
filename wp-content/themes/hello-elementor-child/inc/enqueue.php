<?php
/**
 * Script and Stylesheet Enqueue Management
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue scripts and styles with conditional page loading
 */
function hello_elementor_child_scripts() {
	$theme_dir = get_stylesheet_directory();
	$theme_uri = get_stylesheet_directory_uri();

	// 1. Parent theme stylesheet
	wp_enqueue_style(
		'hello-elementor-parent-style',
		get_template_directory_uri() . '/style.css',
		array(),
		wp_get_theme( 'hello-elementor' )->get( 'Version' )
	);

	// 2. Google Fonts (Montserrat for Headings & Be Vietnam Pro for Body)
	wp_enqueue_style(
		'ktd-font-montserrat',
		'https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800;900&display=swap',
		array(),
		'1.0.0'
	);
	wp_enqueue_style(
		'ktd-font-bevietnam',
		'https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap',
		array(),
		'1.0.0'
	);

	// 3. Child theme style header declaration
	$style_css = $theme_dir . '/style.css';
	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_uri(),
		array( 'hello-elementor-parent-style', 'ktd-font-montserrat', 'ktd-font-bevietnam' ),
		file_exists( $style_css ) ? filemtime( $style_css ) : '1.0.0'
	);

	// 4. Core Bundle (Design tokens, Reset, Shared UI, Header, Navigation, Footer)
	$core_css = $theme_dir . '/assets/css/core.css';
	if ( file_exists( $core_css ) ) {
		wp_enqueue_style(
			'ktd-core',
			$theme_uri . '/assets/css/core.css',
			array( 'hello-elementor-child-style' ),
			filemtime( $core_css )
		);
	} else {
		// Fallback to separate base & layout if core.css is missing
		$base_css = $theme_dir . '/assets/css/base.css';
		if ( file_exists( $base_css ) ) {
			wp_enqueue_style( 'ktd-base', $theme_uri . '/assets/css/base.css', array( 'hello-elementor-child-style' ), filemtime( $base_css ) );
		}
		$layout_css = $theme_dir . '/assets/css/layout.css';
		if ( file_exists( $layout_css ) ) {
			wp_enqueue_style( 'ktd-layout', $theme_uri . '/assets/css/layout.css', array( 'ktd-base' ), filemtime( $layout_css ) );
		}
	}

	$global_dependency = file_exists( $core_css ) ? 'ktd-core'
		: ( file_exists( $theme_dir . '/assets/css/layout.css' ) ? 'ktd-layout'
			: ( file_exists( $theme_dir . '/assets/css/base.css' ) ? 'ktd-base'
				: 'hello-elementor-child-style' ) );
	// Fallback chain đảm bảo $global_dependency luôn trỏ đến một handle đã được đăng ký

	// 5. Native Chatbot Assets (Enqueued globally)
	$chatbot_css = $theme_dir . '/assets/css/chatbot.css';
	if ( file_exists( $chatbot_css ) ) {
		wp_enqueue_style(
			'ktd-chatbot-style',
			$theme_uri . '/assets/css/chatbot.css',
			array( $global_dependency ),
			filemtime( $chatbot_css )
		);
	}

	$chatbot_js = $theme_dir . '/assets/js/chatbot.js';
	if ( file_exists( $chatbot_js ) ) {
		wp_enqueue_script(
			'ktd-chatbot-script',
			$theme_uri . '/assets/js/chatbot.js',
			array(),
			filemtime( $chatbot_js ),
			true
		);
		wp_localize_script(
			'ktd-chatbot-script',
			'ktdChatConfig',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'ktd_chat_nonce' ),
			)
		);
	}

	// 6. Conditional Page-Specific Modules
	// A. Homepage
	$home_css = $theme_dir . '/assets/css/home.css';
	if ( is_front_page() && file_exists( $home_css ) ) {
		wp_enqueue_style( 'ktd-home', $theme_uri . '/assets/css/home.css', array( $global_dependency ), filemtime( $home_css ) );
	}

	// A2. Blog & Articles (Archive, Category, Tag, Single Post)
	$blog_css = $theme_dir . '/assets/css/blog.css';
	$is_blog_page = ( is_home() || is_category() || is_tag() || is_singular( 'post' ) || ( is_archive() && ! ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) ) );
	if ( $is_blog_page && file_exists( $blog_css ) ) {
		wp_enqueue_style( 'ktd-blog', $theme_uri . '/assets/css/blog.css', array( $global_dependency ), filemtime( $blog_css ) );
	}

	// B. Shop & Category Archives
	$shop_css = $theme_dir . '/assets/css/shop.css';
	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag() ) && file_exists( $shop_css ) ) {
		wp_enqueue_style( 'ktd-shop', $theme_uri . '/assets/css/shop.css', array( $global_dependency ), filemtime( $shop_css ) );
	}

	// C. Single Product Page
	$single_css = $theme_dir . '/assets/css/single-product.css';
	if ( function_exists( 'is_product' ) && is_product() && file_exists( $single_css ) ) {
		wp_enqueue_style( 'ktd-single-product', $theme_uri . '/assets/css/single-product.css', array( $global_dependency ), filemtime( $single_css ) );
	}

	// D. Cart Page
	$cart_css = $theme_dir . '/assets/css/cart.css';
	if ( function_exists( 'is_cart' ) && is_cart() && file_exists( $cart_css ) ) {
		wp_enqueue_style( 'ktd-cart', $theme_uri . '/assets/css/cart.css', array( $global_dependency ), filemtime( $cart_css ) );
	}

	// E. Checkout & Order Received Pages
	$checkout_css = $theme_dir . '/assets/css/checkout.css';
	if ( function_exists( 'is_checkout' ) && is_checkout() && file_exists( $checkout_css ) ) {
		wp_enqueue_style( 'ktd-checkout', $theme_uri . '/assets/css/checkout.css', array( $global_dependency ), filemtime( $checkout_css ) );
	}

	// F. My Account Page
	$account_css = $theme_dir . '/assets/css/my-account.css';
	if ( function_exists( 'is_account_page' ) && is_account_page() && file_exists( $account_css ) ) {
		wp_enqueue_style( 'ktd-my-account', $theme_uri . '/assets/css/my-account.css', array( $global_dependency ), filemtime( $account_css ) );
	}

	// G. About Us Page
	$about_css = $theme_dir . '/assets/css/page-about.css';
	if ( ( is_page( 'about' ) || is_page( 'gioi-thieu' ) || is_page_template( 'page-about.php' ) ) && file_exists( $about_css ) ) {
		wp_enqueue_style( 'ktd-page-about', $theme_uri . '/assets/css/page-about.css', array( $global_dependency ), filemtime( $about_css ) );
	}

	// H. Contact Us Page
	$contact_css = $theme_dir . '/assets/css/page-contact.css';
	if ( ( is_page( 'contact' ) || is_page( 'contact-us' ) || is_page( 'lien-he' ) || is_page_template( 'page-contact-us.php' ) || is_page_template( 'page-contact.php' ) ) && file_exists( $contact_css ) ) {
		wp_enqueue_style( 'ktd-page-contact', $theme_uri . '/assets/css/page-contact.css', array( $global_dependency ), filemtime( $contact_css ) );
	}

	// 7. Custom JS for mobile toggle and floating scroll button
	$js_file = $theme_dir . '/assets/js/theme-custom.js';
	wp_enqueue_script(
		'ktd-theme-custom',
		$theme_uri . '/assets/js/theme-custom.js',
		array(),
		file_exists( $js_file ) ? filemtime( $js_file ) : '1.0.0',
		true
	);
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_scripts', 20 );

/**
 * Preconnect hints cho Google Fonts — giảm render-blocking và cải thiện LCP
 * Phải chạy trước wp_enqueue_scripts để được inject vào <head> sớm nhất
 */
function ktd_add_google_fonts_preconnect() {
	echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action( 'wp_head', 'ktd_add_google_fonts_preconnect', 1 );
