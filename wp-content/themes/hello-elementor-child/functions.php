<?php
/**
 * Theme functions and definitions for Hello Elementor Child
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Enqueue scripts and styles
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

	// 4. Global Base Module (Tokens, Reset, Shared UI, Scroll-to-top)
	$base_css = $theme_dir . '/assets/css/base.css';
	if ( file_exists( $base_css ) ) {
		wp_enqueue_style(
			'ktd-base',
			$theme_uri . '/assets/css/base.css',
			array( 'hello-elementor-child-style' ),
			filemtime( $base_css )
		);
	}

	// 5. Global Layout Module (Header & Footer)
	$layout_css = $theme_dir . '/assets/css/layout.css';
	if ( file_exists( $layout_css ) ) {
		wp_enqueue_style(
			'ktd-layout',
			$theme_uri . '/assets/css/layout.css',
			array( 'ktd-base' ),
			filemtime( $layout_css )
		);
	}

	// 6. Conditional Page-Specific Modules
	// A. Homepage
	$home_css = $theme_dir . '/assets/css/home.css';
	if ( ( is_front_page() || is_home() ) && file_exists( $home_css ) ) {
		wp_enqueue_style( 'ktd-home', $theme_uri . '/assets/css/home.css', array( 'ktd-layout' ), filemtime( $home_css ) );
	}

	// B. Shop & Category Archives
	$shop_css = $theme_dir . '/assets/css/shop.css';
	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag() ) && file_exists( $shop_css ) ) {
		wp_enqueue_style( 'ktd-shop', $theme_uri . '/assets/css/shop.css', array( 'ktd-layout' ), filemtime( $shop_css ) );
	}

	// C. Single Product Page
	$single_css = $theme_dir . '/assets/css/single-product.css';
	if ( function_exists( 'is_product' ) && is_product() && file_exists( $single_css ) ) {
		wp_enqueue_style( 'ktd-single-product', $theme_uri . '/assets/css/single-product.css', array( 'ktd-layout' ), filemtime( $single_css ) );
	}

	// D. Cart Page
	$cart_css = $theme_dir . '/assets/css/cart.css';
	if ( function_exists( 'is_cart' ) && is_cart() && file_exists( $cart_css ) ) {
		wp_enqueue_style( 'ktd-cart', $theme_uri . '/assets/css/cart.css', array( 'ktd-layout' ), filemtime( $cart_css ) );
	}

	// E. My Account Page
	$account_css = $theme_dir . '/assets/css/my-account.css';
	if ( function_exists( 'is_account_page' ) && is_account_page() && file_exists( $account_css ) ) {
		wp_enqueue_style( 'ktd-my-account', $theme_uri . '/assets/css/my-account.css', array( 'ktd-layout' ), filemtime( $account_css ) );
	}

	// F. About Us Page
	$about_css = $theme_dir . '/assets/css/page-about.css';
	if ( ( is_page( 'about' ) || is_page( 'gioi-thieu' ) || is_page_template( 'page-about.php' ) ) && file_exists( $about_css ) ) {
		wp_enqueue_style( 'ktd-page-about', $theme_uri . '/assets/css/page-about.css', array( 'ktd-layout' ), filemtime( $about_css ) );
	}

	// G. Contact Us Page
	$contact_css = $theme_dir . '/assets/css/page-contact.css';
	if ( ( is_page( 'contact' ) || is_page( 'contact-us' ) || is_page( 'lien-he' ) || is_page_template( 'page-contact-us.php' ) || is_page_template( 'page-contact.php' ) ) && file_exists( $contact_css ) ) {
		wp_enqueue_style( 'ktd-page-contact', $theme_uri . '/assets/css/page-contact.css', array( 'ktd-layout' ), filemtime( $contact_css ) );
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
 * Update WooCommerce Cart Count fragment via AJAX
 */
function ktd_cart_count_fragments( $fragments ) {
	if ( function_exists( 'WC' ) && WC()->cart ) {
		$count = WC()->cart->get_cart_contents_count();
		$fragments['span.ktd-cart-count'] = '<span class="ktd-cart-count">' . esc_html( $count ) . '</span>';
		// Also update aria-label on the cart link so it stays in sync after AJAX
		$aria_label = sprintf(
			_n( 'View Shopping Cart (%d item)', 'View Shopping Cart (%d items)', $count, 'hello-elementor-child' ),
			$count
		);
		$fragments['.ktd-cart-btn'] = '<a href="' . esc_url( wc_get_cart_url() ) . '" class="ktd-action-btn ktd-cart-btn" title="View Cart" aria-label="' . esc_attr( $aria_label ) . '">'
			. '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>'
			. '<span class="ktd-cart-badge"><span class="ktd-cart-count">' . esc_html( $count ) . '</span></span>'
			. '</a>';
	}
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'ktd_cart_count_fragments' );

/**
 * Render floating scroll-to-top button in footer
 */
function ktd_render_floating_navigation() {
	?>
	<div class="ktd-floating-nav" id="ktdFloatingNav" aria-label="Scroll to top navigation">
		<button type="button" class="ktd-float-btn ktd-scroll-top-btn" id="ktdScrollTopBtn" title="Cuộn lên đầu trang" aria-label="Cuộn lên đầu trang">
			<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
				<polyline points="18 15 12 9 6 15"></polyline>
			</svg>
		</button>
	</div>
	<?php
}
add_action( 'wp_footer', 'ktd_render_floating_navigation', 99 );

/**
 * 3.2 A11y: Add aria-label to Elementor image-box wrapper links that only contain images.
 * Uses output buffering on the_content to inject aria-label where the anchor has no text.
 * Applied only on front-page to keep the filter surgical.
 */
function ktd_fix_imagebox_link_aria( $content ) {
	// Match <a ...> that wraps only an img (no visible text) and has no aria-label yet
	$content = preg_replace_callback(
		'/<a\b([^>]*class="[^"]*elementor-image-box-img[^"]*"[^>]*)>(\s*<figure[^>]*>.*?<\/figure>\s*)<\/a>/is',
		function ( $m ) {
			$attrs = $m[1];
			// Already has aria-label or aria-hidden — skip
			if ( preg_match( '/aria-(label|hidden)/i', $attrs ) ) {
				return $m[0];
			}
			// Extract alt from the inner img
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
 * 3.3 A11y: Add <main> landmark on Front Page for screen readers and SEO.
 * Other pages (Shop, Single, Cart, My Account, About, Contact) already have native <main> landmarks.
 * We restrict this to front-page only and avoid .site-main class to prevent Hello Elementor's
 * boxed 1140px styling from constraining the footer.
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
// wp_body_open fires right after <body> tag; priority 20 puts us after header
add_action( 'wp_body_open', 'ktd_inject_main_landmark_open', 20 );
// Close <main> before wp_footer scripts (priority 1 = earliest in wp_footer)
add_action( 'wp_footer', 'ktd_inject_main_landmark_close', 1 );

/**
 * Giai đoạn 2: Deregister dashicons on frontend for non-logged-in users.
 * Dashicons is only needed in wp-admin and for logged-in users with the admin bar.
 * Saves ~36 KB of render-blocking CSS on every frontend page load for guests.
 */
function ktd_deregister_dashicons_frontend() {
	if ( ! is_user_logged_in() ) {
		wp_deregister_style( 'dashicons' );
	}
}
add_action( 'wp_enqueue_scripts', 'ktd_deregister_dashicons_frontend', 100 );

/**
 * Giai đoạn 2: Add fetchpriority="high" to the first image in Elementor carousel/slider
 * to signal it as the LCP element and reduce Largest Contentful Paint time.
 * Uses the wp_get_attachment_image_attributes filter.
 */
function ktd_lcp_image_fetchpriority( $attr, $attachment, $size ) {
	static $first_called = false;
	// Only apply once (first image rendered = the hero LCP image)
	if ( ! $first_called && is_front_page() ) {
		$first_called      = true;
		$attr['fetchpriority'] = 'high';
		$attr['loading']   = 'eager'; // Override lazy-load if set
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'ktd_lcp_image_fetchpriority', 10, 3 );

/**
 * Ensure custom page templates are always used for About and Contact pages
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

// Purge LiteSpeed Cache only when a post/product is saved or updated
add_action( 'save_post', function() {
	if ( has_action( 'litespeed_purge_all' ) ) {
		do_action( 'litespeed_purge_all' );
	}
} );


/**
 * Helper to get min and max prices from published products.
 * Uses a transient cache (1 hour) to avoid repeated heavy JOIN queries.
 */
function ktd_get_shop_price_bounds() {
	$cached = get_transient( 'ktd_price_bounds' );
	if ( false !== $cached ) {
		return $cached;
	}

	global $wpdb;
	$prices = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT MIN(CAST(meta_value AS DECIMAL(10,2))) as min_p,
			        MAX(CAST(meta_value AS DECIMAL(10,2))) as max_p
			 FROM {$wpdb->postmeta} pm
			 INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
			 WHERE pm.meta_key = %s
			   AND pm.meta_value > 0
			   AND p.post_status = %s
			   AND p.post_type = %s",
			'_price',
			'publish',
			'product'
		)
	);

	$min = ( $prices && $prices->min_p !== null ) ? floor( (float) $prices->min_p / 500000 ) * 500000 : 0;
	$max = ( $prices && $prices->max_p !== null ) ? ceil( (float) $prices->max_p / 500000 ) * 500000 : 50000000;
	if ( $min >= $max ) {
		$min = 0;
		$max = 50000000;
	}

	$result = array( 'min' => (int) $min, 'max' => (int) $max );
	set_transient( 'ktd_price_bounds', $result, HOUR_IN_SECONDS );
	return $result;
}

/**
 * WooCommerce Shop Layout Customization: Hero Banner & Sidebar Filter
 */
function ktd_shop_layout_start() {
	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) && ! is_product() ) {
		$shop_url    = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
		$current_cat = is_product_category() ? get_queried_object()->slug : '';
		?>
		<div class="ktd-shop-hero">
			<div class="ktd-page-container">
				<span class="ktd-section-badge">FLAGSHIP SMARTPHONE • CHÍNH HÃNG 100%</span>
				<h1 class="ktd-shop-hero-title">Bộ Sưu Tập Điện Thoại Cao Cấp</h1>
				<p class="ktd-shop-hero-desc">Khám phá các dòng sản phẩm mới nhất từ Apple, Samsung, OPPO với mức giá ưu đãi và chính sách bảo hành 1 đổi 1 trong 30 ngày.</p>
			</div>
		</div>

		<div class="ktd-shop-wrapper ktd-page-container">
			<!-- Mobile Filter Toggle -->
			<div class="ktd-mobile-filter-bar">
				<button type="button" class="ktd-mobile-filter-btn" id="ktdFilterToggle">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
					</svg>
					<span>Lọc Sản Phẩm</span>
				</button>
			</div>

			<div class="ktd-shop-layout">
				<!-- Sidebar Filters -->
				<aside class="ktd-shop-sidebar" id="ktdShopSidebar">
					<!-- Brand Categories -->
					<div class="ktd-sidebar-widget">
						<h3 class="ktd-sidebar-title">Thương Hiệu</h3>
						<ul class="ktd-filter-list">
							<li>
								<a href="<?php echo esc_url( $shop_url ); ?>" class="<?php echo empty( $current_cat ) ? 'active' : ''; ?>">
									Tất cả thương hiệu
								</a>
							</li>
							<?php
							$brands = array(
								'apple'   => 'Apple (iPhone)',
								'samsung' => 'Samsung Galaxy',
								'oppo'    => 'OPPO Store',
							);
							// Batch-fetch all brand terms in a single query instead of one-per-loop
							$brand_terms_list = get_terms( array(
								'taxonomy'   => 'product_cat',
								'slug'       => array_keys( $brands ),
								'hide_empty' => false,
							) );
							$term_map = array();
							if ( ! is_wp_error( $brand_terms_list ) ) {
								foreach ( $brand_terms_list as $t ) {
									$term_map[ $t->slug ] = $t;
								}
							}
							foreach ( $brands as $slug => $label ) {
								$term = isset( $term_map[ $slug ] ) ? $term_map[ $slug ] : null;
								if ( $term ) {
									$term_link = get_term_link( $term );
									$is_active = ( $current_cat === $slug );
									?>
									<li>
										<a href="<?php echo esc_url( $term_link ); ?>" class="<?php echo $is_active ? 'active' : ''; ?>">
											<?php echo esc_html( $label ); ?>
											<span class="ktd-term-count">(<?php echo esc_html( $term->count ); ?>)</span>
										</a>
									</li>
									<?php
								}
							}
							?>
						</ul>
					</div>

					<!-- Price Filter Range (Dual-Handle Slider) -->
					<?php
					$price_bounds      = ktd_get_shop_price_bounds();
					$cur_min           = isset( $_GET['min_price'] ) ? absint( $_GET['min_price'] ) : $price_bounds['min'];
					$cur_max           = isset( $_GET['max_price'] ) ? absint( $_GET['max_price'] ) : $price_bounds['max'];
					$is_price_filtered = isset( $_GET['min_price'] ) || isset( $_GET['max_price'] );
					$reset_price_url   = remove_query_arg( array( 'min_price', 'max_price' ) );
					?>
					<div class="ktd-sidebar-widget ktd-price-filter-widget">
						<div class="ktd-sidebar-title-row">
							<h3 class="ktd-sidebar-title">Khoảng Giá</h3>
							<?php if ( $is_price_filtered ) : ?>
								<a href="<?php echo esc_url( $reset_price_url ); ?>" class="ktd-price-reset-link" title="Đặt lại lọc giá">
									Đặt lại
								</a>
							<?php endif; ?>
						</div>

						<div class="ktd-dual-slider-wrap" 
						     id="ktdDualSliderWrap" 
						     data-min="<?php echo esc_attr( $price_bounds['min'] ); ?>" 
						     data-max="<?php echo esc_attr( $price_bounds['max'] ); ?>" 
						     data-step="500000">
							<div class="ktd-slider-track-box">
								<div class="ktd-slider-bar-bg"></div>
								<div class="ktd-slider-bar-highlight" id="ktdSliderBarHighlight"></div>
								<input type="range" class="ktd-range-thumb ktd-range-min" id="ktdRangeMin" 
								       min="<?php echo esc_attr( $price_bounds['min'] ); ?>" 
								       max="<?php echo esc_attr( $price_bounds['max'] ); ?>" 
								       step="500000" 
								       value="<?php echo esc_attr( $cur_min ); ?>" 
								       aria-label="Giá tối thiểu">
								<input type="range" class="ktd-range-thumb ktd-range-max" id="ktdRangeMax" 
								       min="<?php echo esc_attr( $price_bounds['min'] ); ?>" 
								       max="<?php echo esc_attr( $price_bounds['max'] ); ?>" 
								       step="500000" 
								       value="<?php echo esc_attr( $cur_max ); ?>" 
								       aria-label="Giá tối đa">
							</div>

							<div class="ktd-slider-price-labels">
								<span class="ktd-price-badge" id="ktdPriceMinLabel"><?php echo esc_html( number_format( $cur_min, 0, ',', '.' ) . ' ₫' ); ?></span>
								<span class="ktd-price-sep">–</span>
								<span class="ktd-price-badge" id="ktdPriceMaxLabel"><?php echo esc_html( number_format( $cur_max, 0, ',', '.' ) . ' ₫' ); ?></span>
							</div>

							<button type="button" class="ktd-price-apply-btn" id="ktdPriceApplyBtn">
								<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
								<span>Áp dụng lọc</span>
							</button>
						</div>
					</div>

					<!-- Trust Widget -->
					<div class="ktd-sidebar-widget ktd-sidebar-commit-widget">
						<h3 class="ktd-sidebar-title">Cam Kết Tại KTD</h3>
						<div class="ktd-sidebar-perk">
							<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
							<span>100% Hàng chính hãng nguyên seal</span>
						</div>
						<div class="ktd-sidebar-perk">
							<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
							<span>Bảo hành vàng 1 đổi 1 trong 30 ngày</span>
						</div>
						<div class="ktd-sidebar-perk">
							<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
							<span>Hỗ trợ trả góp 0% lãi suất</span>
						</div>
					</div>
				</aside>

				<!-- Shop Main Products -->
				<div class="ktd-shop-main">
		<?php
	}
}
add_action( 'woocommerce_before_main_content', 'ktd_shop_layout_start', 15 );

function ktd_shop_layout_end() {
	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) && ! is_product() ) {
		?>
				</div> <!-- .ktd-shop-main -->
			</div> <!-- .ktd-shop-layout -->
		</div> <!-- .ktd-shop-wrapper -->
		<?php
	}
}
add_action( 'woocommerce_after_main_content', 'ktd_shop_layout_end', 5 );

/**
 * Render Cart Hero Banner & 3-Step Stepper before WooCommerce container
 */
function ktd_get_cart_hero_html() {
	$home_url   = esc_url( home_url( '/' ) );
	$cart_count = function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	$is_empty   = ( $cart_count === 0 );
	ob_start();
	?>
	<div class="ktd-cart-hero<?php echo $is_empty ? ' is-empty-hero' : ''; ?>">
		<div class="ktd-cart-hero-inner">
			<div class="ktd-cart-hero-left">
				<nav class="ktd-cart-breadcrumbs" aria-label="Breadcrumb">
					<a href="<?php echo $home_url; ?>">Trang chủ</a>
					<span class="ktd-bc-sep">/</span>
					<span class="ktd-bc-current">Giỏ hàng</span>
				</nav>
				<h1 class="ktd-cart-title">
					Giỏ Hàng Của Bạn
					<?php if ( ! $is_empty ) : ?>
						<span class="ktd-cart-item-count">(<?php echo esc_html( $cart_count ); ?> sản phẩm)</span>
					<?php endif; ?>
				</h1>
			</div>

			<?php if ( ! $is_empty ) : ?>
			<div class="ktd-cart-hero-right">
				<!-- 3-Step Order Process Stepper -->
				<div class="ktd-order-stepper" aria-label="Tiến trình đặt hàng">
					<div class="ktd-step-item is-active">
						<div class="ktd-step-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
								<circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/>
								<path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
							</svg>
						</div>
						<div class="ktd-step-text">
							<span class="ktd-step-num">Bước 1</span>
							<span class="ktd-step-name">Giỏ Hàng</span>
						</div>
					</div>

					<div class="ktd-step-connector"></div>

					<div class="ktd-step-item">
						<div class="ktd-step-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
								<rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
							</svg>
						</div>
						<div class="ktd-step-text">
							<span class="ktd-step-num">Bước 2</span>
							<span class="ktd-step-name">Thanh Toán</span>
						</div>
					</div>

					<div class="ktd-step-connector"></div>

					<div class="ktd-step-item">
						<div class="ktd-step-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
								<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
								<polyline points="22 4 12 14.01 9 11.01"/>
							</svg>
						</div>
						<div class="ktd-step-text">
							<span class="ktd-step-num">Bước 3</span>
							<span class="ktd-step-name">Hoàn Tất</span>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

add_filter( 'the_content', function( $content ) {
	if ( function_exists( 'is_cart' ) && is_cart() && in_the_loop() && is_main_query() ) {
		static $rendered = false;
		if ( ! $rendered ) {
			$rendered = true;
			return ktd_get_cart_hero_html() . $content;
		}
	}
	return $content;
}, 1 );

// Remove default WooCommerce empty cart message so our custom cart-empty template displays cleanly
remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );

/**
 * Trust badges in Cart Order Summary
 */
function ktd_cart_trust_badges() {
	?>
	<div class="ktd-cart-trust-badges">
		<div class="ktd-trust-item">
			<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
			</svg>
			<span>100% Hàng chính hãng nguyên seal</span>
		</div>
		<div class="ktd-trust-item">
			<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
				<path d="M7 11V7a5 5 0 0 1 10 0v4"/>
			</svg>
			<span>Bảo mật thanh toán chuẩn SSL 256-bit</span>
		</div>
		<div class="ktd-trust-item">
			<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/>
			</svg>
			<span>Đổi trả miễn phí 30 ngày nếu lỗi</span>
		</div>
	</div>
	<?php
}
add_action( 'woocommerce_after_cart_totals', 'ktd_cart_trust_badges', 20 );

/**
 * ==========================================================================
 * My Account Customizations (Minimalist Architecture)
 * ==========================================================================
 */

/**
 * Redirect /my-account/ base endpoint to /my-account/orders/ for logged-in users
 */
add_action( 'template_redirect', function() {
	if ( function_exists( 'is_account_page' ) && is_account_page() && is_user_logged_in() && ! is_wc_endpoint_url() ) {
		wp_safe_redirect( wc_get_endpoint_url( 'orders' ) );
		exit;
	}
} );

/**
 * Customize My Account Menu Items (3 Horizontal Tabs)
 */
function ktd_custom_account_menu_items( $items ) {
	return array(
		'orders'       => 'Đơn hàng của tôi',
		'edit-account' => 'Thông tin & Bảo mật',
		'edit-address' => 'Sổ địa chỉ',
	);
}
add_filter( 'woocommerce_account_menu_items', 'ktd_custom_account_menu_items', 99 );

/**
 * Get Minimalist My Account Profile Header HTML
 */
function ktd_get_account_hero_html() {
	ob_start();
	if ( is_user_logged_in() ) {
		$current_user = wp_get_current_user();
		$display_name = $current_user->display_name ? $current_user->display_name : $current_user->user_login;
		?>
		<div class="ktd-account-minimal-header">
			<div class="ktd-profile-user-left">
				<div class="ktd-profile-avatar-wrap">
					<?php echo get_avatar( $current_user->ID, 56, '', esc_attr( $display_name ) ); ?>
				</div>
				<div class="ktd-profile-user-meta">
					<div class="ktd-profile-user-title">
						<span class="ktd-greeting-label">Xin chào,</span>
						<h1 class="ktd-profile-user-name"><?php echo esc_html( $display_name ); ?></h1>
					</div>
					<span class="ktd-profile-user-email"><?php echo esc_html( $current_user->user_email ); ?></span>
				</div>
			</div>
			<div class="ktd-profile-user-right">
				<a href="<?php echo esc_url( wc_logout_url() ); ?>" class="ktd-profile-logout-btn" title="Đăng xuất khỏi tài khoản">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/>
					</svg>
					<span>Đăng xuất</span>
				</a>
			</div>
		</div>
		<?php
	} else {
		?>
		<div class="ktd-account-minimal-header ktd-auth-minimal-header">
			<div class="ktd-auth-header-text">
				<h1 class="ktd-auth-page-title">Đăng Nhập Tài Khoản</h1>
				<p class="ktd-auth-page-subtitle">Đăng nhập tài khoản để theo dõi đơn hàng và quản lý thông tin mua sắm tại KTD-Ecommerce.</p>
			</div>
		</div>
		<?php
	}
	return ob_get_clean();
}

/**
 * Filter the_content to prepend the Minimalist Header on My Account page
 */
add_filter( 'the_content', function( $content ) {
	if ( function_exists( 'is_account_page' ) && is_account_page() && in_the_loop() && is_main_query() ) {
		static $account_hero_rendered = false;
		if ( ! $account_hero_rendered ) {
			$account_hero_rendered = true;
			return ktd_get_account_hero_html() . $content;
		}
	}
	return $content;
}, 2 );

/**
 * ==========================================================================
 * Single Product Layout & Enhancements
 * ==========================================================================
 */

/**
 * Wrap Single Product inside standard centered container
 */
function ktd_single_product_wrapper_start() {
	if ( function_exists( 'is_product' ) && is_product() ) {
		echo '<div class="ktd-page-container ktd-single-product-container">';
	}
}
add_action( 'woocommerce_before_main_content', 'ktd_single_product_wrapper_start', 5 );

function ktd_single_product_wrapper_end() {
	if ( function_exists( 'is_product' ) && is_product() ) {
		echo '</div><!-- .ktd-single-product-container -->';
	}
}
add_action( 'woocommerce_after_main_content', 'ktd_single_product_wrapper_end', 25 );

/**
 * Vietnamese Breadcrumb Defaults
 */
function ktd_custom_breadcrumb_defaults( $defaults ) {
	$defaults['home']      = 'Trang chủ';
	$defaults['delimiter'] = ' <span class="ktd-bc-sep">/</span> ';
	return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'ktd_custom_breadcrumb_defaults' );

/**
 * Vietnamese Single Product Add to Cart Button Text
 */
function ktd_custom_product_single_add_to_cart_text() {
	return 'Thêm Vào Giỏ Hàng';
}
add_filter( 'woocommerce_product_single_add_to_cart_text', 'ktd_custom_product_single_add_to_cart_text' );

/**
 * Vietnamese Product Loop Add to Cart Button Text (Catalog/Shop)
 */
function ktd_custom_product_add_to_cart_text( $text, $product = null ) {
	if ( $product && method_exists( $product, 'is_type' ) && $product->is_type( 'variable' ) ) {
		return 'Xem chi tiết';
	}
	return 'Thêm vào giỏ';
}
add_filter( 'woocommerce_product_add_to_cart_text', 'ktd_custom_product_add_to_cart_text', 10, 2 );

/**
 * Vietnamese Stock Availability Text
 */
function ktd_custom_product_availability( $availability, $product ) {
	if ( ! is_object( $product ) || ! method_exists( $product, 'is_in_stock' ) ) {
		return $availability;
	}

	if ( $product->is_in_stock() ) {
		$qty = method_exists( $product, 'get_stock_quantity' ) ? $product->get_stock_quantity() : null;
		if ( $qty !== null && $qty > 0 ) {
			$availability['availability'] = sprintf( 'Còn hàng (%d sản phẩm sẵn sàng)', $qty );
		} else {
			$availability['availability'] = 'Còn hàng (Sẵn sàng giao)';
		}
	} else {
		$availability['availability'] = 'Tạm hết hàng';
	}
	return $availability;
}
add_filter( 'woocommerce_get_availability', 'ktd_custom_product_availability', 10, 2 );

/**
 * Render Trust Badges Card on Single Product Page
 */
function ktd_render_single_product_trust_badges() {
	?>
	<div class="ktd-single-trust-card">
		<div class="ktd-single-trust-item">
			<div class="ktd-single-trust-icon">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
				</svg>
			</div>
			<div class="ktd-single-trust-text">
				<strong>100% Chính Hãng</strong>
				<span>Nguyên seal, nguồn gốc rõ ràng</span>
			</div>
		</div>

		<div class="ktd-single-trust-item">
			<div class="ktd-single-trust-icon">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<circle cx="12" cy="12" r="10"/>
					<polyline points="12 6 12 12 16 14"/>
				</svg>
			</div>
			<div class="ktd-single-trust-text">
				<strong>Giao Hỏa Tốc</strong>
				<span>Nhận hàng trong 2 giờ</span>
			</div>
		</div>

		<div class="ktd-single-trust-item">
			<div class="ktd-single-trust-icon">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/>
				</svg>
			</div>
			<div class="ktd-single-trust-text">
				<strong>Đổi Mới 30 Ngày</strong>
				<span>Lỗi 1 đổi 1 nhanh chóng</span>
			</div>
		</div>

		<div class="ktd-single-trust-item">
			<div class="ktd-single-trust-icon">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<rect width="20" height="14" x="2" y="5" rx="2"/>
					<line x1="2" x2="22" y1="10" y2="10"/>
				</svg>
			</div>
			<div class="ktd-single-trust-text">
				<strong>Trả Góp 0%</strong>
				<span>Thủ tục duyệt online 5 phút</span>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'woocommerce_single_product_summary', 'ktd_render_single_product_trust_badges', 35 );

/**
 * Vietnamese Product Tabs Customization
 */
function ktd_custom_product_tabs( $tabs ) {
	if ( isset( $tabs['description'] ) ) {
		$tabs['description']['title'] = 'Mô tả chi tiết';
	}
	if ( isset( $tabs['additional_information'] ) ) {
		$tabs['additional_information']['title'] = 'Thông số kỹ thuật';
	}
	if ( isset( $tabs['reviews'] ) ) {
		$count = function_exists( 'get_comments_number' ) ? get_comments_number() : 0;
		$tabs['reviews']['title'] = sprintf( 'Đánh giá & Nhận xét (%d)', $count );
	}
	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'ktd_custom_product_tabs', 98 );

/**
 * Vietnamese Related Products Heading
 */
function ktd_custom_related_products_heading() {
	return 'Sản phẩm tương tự';
}
add_filter( 'woocommerce_product_related_products_heading', 'ktd_custom_related_products_heading', 20 );

/**
 * Helper to retrieve structured product attributes for display
 */
function ktd_get_product_specs( $product = null ) {
	if ( ! is_object( $product ) ) {
		global $product;
		if ( ! is_object( $product ) && function_exists( 'wc_get_product' ) ) {
			$product = wc_get_product( get_the_ID() );
		}
	}
	if ( ! $product || ! method_exists( $product, 'get_attributes' ) ) {
		return array();
	}

	$specs = array();
	foreach ( $product->get_attributes() as $attr ) {
		if ( is_a( $attr, 'WC_Product_Attribute' ) ) {
			$raw_name = $attr->get_name();
			if ( in_array( $raw_name, array( 'pa_dung-luong', 'pa_mau-sac' ), true ) ) {
				continue;
			}
			$name = $attr->is_taxonomy() && function_exists( 'wc_attribute_label' ) ? wc_attribute_label( $raw_name ) : $raw_name;
			$options = array();
			if ( $attr->is_taxonomy() && function_exists( 'wc_get_product_terms' ) ) {
				$options = wc_get_product_terms( $product->get_id(), $attr->get_name(), array( 'fields' => 'names' ) );
			} elseif ( method_exists( $attr, 'get_options' ) ) {
				$options = $attr->get_options();
			}
			$specs[ $name ] = is_array( $options ) ? implode( ', ', $options ) : (string) $options;
		}
	}
	return $specs;
}

/**
 * Modern Swatches (Button Pills) for WooCommerce Variable Products
 * Replaces standard dropdown selects with rich, interactive pills
 */
function ktd_custom_variation_dropdown_swatches( $html, $args ) {
	$options   = isset( $args['options'] ) ? $args['options'] : array();
	$product   = isset( $args['product'] ) ? $args['product'] : null;
	$attribute = isset( $args['attribute'] ) ? $args['attribute'] : '';
	$selected  = isset( $args['selected'] ) ? $args['selected'] : '';

	if ( empty( $options ) || ! $product || ! is_object( $product ) ) {
		return $html;
	}

	// Cache variation prices map for attribute swatches to display corresponding price
	$variation_prices = array();
	if ( method_exists( $product, 'get_available_variations' ) ) {
		$variations = $product->get_available_variations();
		$attr_key   = 'attribute_' . $attribute;
		foreach ( $variations as $var ) {
			$v_opt = isset( $var['attributes'][ $attr_key ] ) ? $var['attributes'][ $attr_key ] : '';
			if ( $v_opt && ! isset( $variation_prices[ $v_opt ] ) ) {
				$variation_prices[ $v_opt ] = isset( $var['display_price'] ) ? $var['display_price'] : 0;
			}
		}
	}

	$swatches_html = '<div class="ktd-swatch-pills-wrap" data-attribute="' . esc_attr( $attribute ) . '">';

	if ( taxonomy_exists( $attribute ) && function_exists( 'wc_get_product_terms' ) ) {
		$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				if ( ! in_array( $term->slug, $options, true ) ) {
					continue;
				}
				$is_selected  = ( sanitize_title( $selected ) === $term->slug );
				$active_class = $is_selected ? ' active' : '';

				$price_markup = '';
				if ( isset( $variation_prices[ $term->slug ] ) && $variation_prices[ $term->slug ] > 0 && function_exists( 'wc_price' ) ) {
					$price_markup = '<span class="ktd-swatch-price">' . wc_price( $variation_prices[ $term->slug ] ) . '</span>';
				}

				$color_dot = '';
				if ( 'pa_mau-sac' === $attribute ) {
					$color_dot = '<span class="ktd-color-dot" data-color="' . esc_attr( $term->slug ) . '"></span>';
				}

				$swatches_html .= sprintf(
					'<button type="button" class="ktd-swatch-pill%s" data-value="%s" aria-pressed="%s">%s<span class="ktd-swatch-text"><span class="ktd-swatch-name">%s</span>%s</span><span class="ktd-swatch-check"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></span></button>',
					$active_class,
					esc_attr( $term->slug ),
					$is_selected ? 'true' : 'false',
					$color_dot,
					esc_html( $term->name ),
					$price_markup
				);
			}
		}
	} else {
		foreach ( $options as $option ) {
			$opt_slug     = sanitize_title( $option );
			$is_selected  = ( sanitize_title( $selected ) === $opt_slug );
			$active_class = $is_selected ? ' active' : '';

			$price_markup = '';
			if ( isset( $variation_prices[ $opt_slug ] ) && $variation_prices[ $opt_slug ] > 0 && function_exists( 'wc_price' ) ) {
				$price_markup = '<span class="ktd-swatch-price">' . wc_price( $variation_prices[ $opt_slug ] ) . '</span>';
			}

			$swatches_html .= sprintf(
				'<button type="button" class="ktd-swatch-pill%s" data-value="%s" aria-pressed="%s"><span class="ktd-swatch-text"><span class="ktd-swatch-name">%s</span>%s</span><span class="ktd-swatch-check"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></span></button>',
				$active_class,
				esc_attr( $opt_slug ),
				$is_selected ? 'true' : 'false',
				esc_html( $option ),
				$price_markup
			);
		}
	}

	$swatches_html .= '</div>';

	return $swatches_html . $html;
}
add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'ktd_custom_variation_dropdown_swatches', 20, 2 );

/**
 * Render HoangHaMobile / ClickBuy 2-Column Product Details Layout
 */
function ktd_render_single_product_details() {
	global $product;
	if ( ! is_object( $product ) && function_exists( 'wc_get_product' ) ) {
		$product = wc_get_product( get_the_ID() );
	}
	if ( ! $product ) {
		return;
	}

	$specs = ktd_get_product_specs( $product );
	$product_name = method_exists( $product, 'get_name' ) ? $product->get_name() : get_the_title();
	?>
	<div class="ktd-product-detail-section" id="ktdProductDetailSection">
		<div class="ktd-product-detail-grid">
			<!-- Cột trái (70%): Bài viết đánh giá chi tiết -->
			<div class="ktd-product-article-col">
				<div class="ktd-article-card">
					<div class="ktd-article-header">
						<span class="ktd-title-badge">Đánh giá chi tiết</span>
						<h2 class="ktd-article-title">Đặc điểm nổi bật của <?php echo esc_html( $product_name ); ?></h2>
					</div>
					<div class="ktd-article-collapsible" id="ktdArticleCollapsible">
						<div class="ktd-article-body" id="ktdArticleBody">
							<?php the_content(); ?>
						</div>
						<div class="ktd-article-fade" id="ktdArticleFade"></div>
					</div>
					<div class="ktd-article-action">
						<button type="button" class="ktd-btn-toggle-article" id="ktdToggleArticleBtn" aria-expanded="false" aria-controls="ktdArticleCollapsible">
							<span class="ktd-btn-text">Đọc tiếp bài viết</span>
							<svg class="ktd-btn-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
								<polyline points="6 9 12 15 18 9"></polyline>
							</svg>
						</button>
					</div>
				</div>
			</div>

			<!-- Cột phải (30%): Bảng Thông số kỹ thuật nhanh -->
			<div class="ktd-product-specs-col">
				<div class="ktd-specs-card">
					<div class="ktd-specs-header">
						<h3 class="ktd-specs-title">Thông số kỹ thuật</h3>
					</div>
					<div class="ktd-specs-table-wrap">
						<table class="ktd-specs-table">
							<tbody>
								<?php if ( ! empty( $specs ) ) : ?>
									<?php 
									$count = 0;
									foreach ( $specs as $key => $val ) : 
										if ( $count >= 7 ) break;
										$count++;
									?>
										<tr>
											<th><?php echo esc_html( $key ); ?></th>
											<td><?php echo esc_html( $val ); ?></td>
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr>
										<th>Thương hiệu</th>
										<td>Chính hãng phân phối</td>
									</tr>
									<tr>
										<th>Bảo hành</th>
										<td>12 tháng chính hãng</td>
									</tr>
									<tr>
										<th>Tình trạng</th>
										<td>Mới 100% nguyên seal</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
					<?php if ( ! empty( $specs ) ) : ?>
						<div class="ktd-specs-footer">
							<button type="button" class="ktd-btn-view-specs" id="ktdOpenSpecsModal">
								<span>Xem cấu hình chi tiết</span>
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
									<polyline points="9 18 15 12 9 6"></polyline>
								</svg>
							</button>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<!-- Khối Đánh giá & Nhận xét của khách hàng (Toàn rộng bên dưới 2 cột) -->
		<div class="ktd-reviews-card" id="ktdReviewsSection">
			<div class="ktd-reviews-header">
				<h3 class="ktd-reviews-title">Đánh giá & Nhận xét từ khách hàng</h3>
			</div>
			<div class="ktd-reviews-content">
				<?php 
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>
			</div>
		</div>
	</div>

	<?php if ( ! empty( $specs ) ) : ?>
		<!-- Modal Cấu hình chi tiết -->
		<div class="ktd-specs-modal" id="ktdSpecsModal" role="dialog" aria-modal="true" aria-hidden="true">
			<div class="ktd-specs-modal-overlay" id="ktdSpecsModalOverlay"></div>
			<div class="ktd-specs-modal-dialog">
				<div class="ktd-specs-modal-header">
					<h3 class="ktd-specs-modal-title">
						Thông số kỹ thuật chi tiết – <?php echo esc_html( $product_name ); ?>
					</h3>
					<button type="button" class="ktd-specs-modal-close" id="ktdCloseSpecsModal" aria-label="Đóng popup thông số">
						<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<line x1="18" y1="6" x2="6" y2="18"></line>
							<line x1="6" y1="6" x2="18" y2="18"></line>
						</svg>
					</button>
				</div>
				<div class="ktd-specs-modal-body">
					<table class="ktd-specs-full-table">
						<tbody>
							<?php foreach ( $specs as $key => $val ) : ?>
								<tr>
									<th><?php echo esc_html( $key ); ?></th>
									<td><?php echo esc_html( $val ); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="ktd-specs-modal-footer">
					<button type="button" class="ktd-btn-modal-dismiss" id="ktdDismissSpecsModal">Đóng</button>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<?php
}
if ( function_exists( 'remove_action' ) ) {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
	add_action( 'woocommerce_after_single_product_summary', 'ktd_render_single_product_details', 10 );
}

/**
 * ==========================================================================
 * Local by Flywheel Live Link Asset Bridge
 * Automatically rewrites internal local URLs to the public Live Link domain
 * ==========================================================================
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

	// Prevent WordPress canonical redirect from bouncing to ktd-ecommerce.local
	add_filter( 'redirect_canonical', '__return_false' );

	$scheme = is_ssl() ? 'https://' : 'http://';

	// Dynamic Site URL & Home URL
	add_filter( 'option_siteurl', function() use ( $live_host, $scheme ) {
		return $scheme . $live_host;
	} );
	add_filter( 'option_home', function() use ( $live_host, $scheme ) {
		return $scheme . $live_host;
	} );

	// Dynamic Upload directory baseurl
	add_filter( 'upload_dir', function( $uploads ) use ( $live_host ) {
		if ( isset( $uploads['baseurl'] ) ) {
			$uploads['baseurl'] = preg_replace( '#https?://[^/]+#', '//' . $live_host, $uploads['baseurl'] );
		}
		if ( isset( $uploads['url'] ) ) {
			$uploads['url'] = preg_replace( '#https?://[^/]+#', '//' . $live_host, $uploads['url'] );
		}
		return $uploads;
	} );

	// Output buffer to rewrite hardcoded local domains in HTML to protocol-relative URLs
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

/**
 * Hiển thị 1 giá duy nhất cho Variable Products trên trang chi tiết sản phẩm
 */
function ktd_single_product_variable_price_html( $price, $product ) {
	if ( function_exists( 'is_product' ) && is_product() && is_object( $product ) && method_exists( $product, 'get_variation_price' ) ) {
		$min_price         = $product->get_variation_price( 'min', true );
		$min_regular_price = method_exists( $product, 'get_variation_regular_price' ) ? $product->get_variation_regular_price( 'min', true ) : null;

		if ( $min_regular_price && $min_regular_price > $min_price && function_exists( 'wc_price' ) ) {
			return '<del aria-hidden="true">' . wc_price( $min_regular_price ) . '</del> <ins>' . wc_price( $min_price ) . '</ins>';
		} elseif ( $min_price && function_exists( 'wc_price' ) ) {
			return wc_price( $min_price );
		}
	}
	return $price;
}
add_filter( 'woocommerce_variable_price_html', 'ktd_single_product_variable_price_html', 10, 2 );

/**
 * Render Sticky Add to Cart Bar on Mobile Single Product
 */
function ktd_render_mobile_sticky_bar() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}
	global $product;
	if ( ! is_object( $product ) && function_exists( 'wc_get_product' ) ) {
		$product = wc_get_product( get_the_ID() );
	}
	if ( ! $product ) {
		return;
	}
	?>
	<div class="ktd-mobile-sticky-bar" id="ktdMobileStickyBar" aria-label="Thanh mua hàng nhanh">
		<div class="ktd-sticky-bar-inner">
			<div class="ktd-sticky-bar-price">
				<span class="ktd-sticky-label">Giá ưu đãi:</span>
				<div class="ktd-sticky-amount"><?php echo $product->get_price_html(); ?></div>
			</div>
			<div class="ktd-sticky-bar-action">
				<button type="button" class="ktd-sticky-buy-btn" id="ktdStickyBuyBtn">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="9" cy="21" r="1"></circle>
						<circle cx="20" cy="21" r="1"></circle>
						<path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
					</svg>
					<span>Thêm Vào Giỏ</span>
				</button>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'woocommerce_after_single_product', 'ktd_render_mobile_sticky_bar', 25 );

/**
 * Tối ưu hóa bảng quản trị sản phẩm WooCommerce (Admin Products List Table)
 * 1. Loại bỏ các cột văn bản dài gây vỡ bố cục và rớt dòng ký tự (SEO Title, Meta Desc, GTIN, Tags).
 * 2. Bảo tồn các cột quan trọng (Ảnh, Tên, SKU, Kho, Giá, Danh mục, Thương hiệu, Ngày).
 * 3. Ẩn 2 cột đánh giá điểm SEO của Yoast (wpseo-score, wpseo-score-readability) để bảng gọn gàng.
 * 4. CSS Admin cố định độ rộng cột Giá (chống rớt dòng đơn vị tiền tệ) và triệt tiêu word-break xếp dọc.
 */
function ktd_optimize_product_admin_columns( $columns ) {
	unset( $columns['wpseo-title'] );
	unset( $columns['wpseo-metadesc'] );
	unset( $columns['wpseo-focuskw'] );
	unset( $columns['wpseo-score'] );
	unset( $columns['wpseo-score-readability'] );
	unset( $columns['global_unique_id'] );
	unset( $columns['product_tag'] );
	return $columns;
}
add_filter( 'manage_edit-product_columns', 'ktd_optimize_product_admin_columns', 99 );

function ktd_product_admin_table_styles() {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || 'edit-product' !== $screen->id ) {
		return;
	}
	?>
	<style type="text/css">
		/* Ngăn chặn triệt để lỗi rớt từng chữ cái xếp dọc */
		.wp-list-table th,
		.wp-list-table td {
			hyphens: none !important;
			word-break: normal !important;
			vertical-align: middle !important;
		}
		/* Cố định độ rộng cột Giá tiền, chống rớt dòng chữ '0 đ' và ngăn tràn sang Danh mục */
		.wp-list-table th.column-price,
		.wp-list-table td.column-price {
			white-space: nowrap !important;
			min-width: 165px !important;
			width: 175px !important;
			padding-right: 15px !important;
		}
		/* Đảm bảo cột Tên sản phẩm luôn rộng rãi, thoáng đãng */
		.wp-list-table th.column-name,
		.wp-list-table td.column-name {
			min-width: 220px !important;
		}
		/* Chuẩn hóa cột SKU và Kho */
		.wp-list-table th.column-sku,
		.wp-list-table td.column-sku {
			white-space: nowrap !important;
			width: 110px !important;
		}
		.wp-list-table th.column-is_in_stock,
		.wp-list-table td.column-is_in_stock {
			white-space: nowrap !important;
			width: 105px !important;
		}
		/* Cho phép cuộn ngang mượt nếu màn hình quản trị quá hẹp */
		.wrap .subsubsub ~ form {
			overflow-x: auto;
		}
	</style>
	<?php
}
add_action( 'admin_head', 'ktd_product_admin_table_styles' );

/**
 * ==============================================================================
 * KTD STORE NATIVE AI CHATBOT (KHÔNG DÙNG IFRAME)
 * Tích hợp Dify REST API bảo mật qua WordPress AJAX
 * ==============================================================================
 */

/**
 * 1. AJAX Backend Handler: Kết nối an toàn đến Dify API (giấu kín API Key, phản hồi tinh tế khi lỗi)
 */
function ktd_ajax_dify_chat() {
	check_ajax_referer( 'ktd_chat_nonce', 'nonce' );

	$fallback_msg    = 'Dạ em chưa có thông tin về vấn đề này, anh/chị vui lòng liên hệ Hotline 1900 8888 để được hỗ trợ ạ.';
	$query           = isset( $_POST['query'] ) ? sanitize_text_field( wp_unslash( $_POST['query'] ) ) : '';
	$conversation_id = isset( $_POST['conversation_id'] ) ? sanitize_text_field( wp_unslash( $_POST['conversation_id'] ) ) : '';

	if ( empty( $query ) ) {
		wp_send_json_success( array(
			'answer'          => $fallback_msg,
			'conversation_id' => $conversation_id,
		) );
	}

	$api_key = 'app-bs8kJzfKCICOgzkN6dOcPt0p';
	$user_ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'guest';
	$user_id = 'ktd_user_' . substr( md5( $user_ip ), 0, 12 );

	$payload = array(
		'inputs'          => (object) array(),
		'query'           => $query,
		'response_mode'   => 'blocking',
		'conversation_id' => ! empty( $conversation_id ) ? $conversation_id : '',
		'user'            => $user_id,
	);

	$response = wp_remote_post( 'https://api.dify.ai/v1/chat-messages', array(
		'headers'   => array(
			'Authorization' => 'Bearer ' . $api_key,
			'Content-Type'  => 'application/json',
			'User-Agent'    => 'WordPress/' . ( function_exists( 'get_bloginfo' ) ? get_bloginfo( 'version' ) : '6.7' ) . '; KTD-Store',
		),
		'body'      => wp_json_encode( $payload ),
		'timeout'   => 45,
		'sslverify' => false,
	) );

	$system_error_msg = 'Dạ hệ thống AI hiện đang xử lý nhiều lượt truy cập hoặc gián đoạn kết nối tạm thời. Anh/chị vui lòng đợi 15-20 giây và nhắn lại giúp em, hoặc liên hệ trực tiếp Hotline 1900 8888 để được hỗ trợ tức thì nhé ạ!';

	if ( is_wp_error( $response ) ) {
		error_log( 'KTD Dify WP_Error: ' . $response->get_error_message() );
		wp_send_json_success( array(
			'answer'          => $system_error_msg,
			'conversation_id' => $conversation_id,
		) );
	}

	$status_code = wp_remote_retrieve_response_code( $response );
	$body        = wp_remote_retrieve_body( $response );
	$data        = json_decode( $body, true );

	if ( 200 !== $status_code || empty( $data['answer'] ) ) {
		error_log( 'KTD Dify HTTP ' . $status_code . ': ' . $body );
		wp_send_json_success( array(
			'answer'          => $system_error_msg,
			'conversation_id' => $conversation_id,
		) );
	}

	$clean_answer = preg_replace( '/<think>[\s\S]*?<\/think>/i', '', $data['answer'] );
	$clean_answer = trim( $clean_answer );
	if ( empty( $clean_answer ) ) {
		$clean_answer = $fallback_msg;
	}

	wp_send_json_success( array(
		'answer'          => $clean_answer,
		'conversation_id' => ! empty( $data['conversation_id'] ) ? $data['conversation_id'] : '',
	) );
}
add_action( 'wp_ajax_ktd_dify_chat', 'ktd_ajax_dify_chat' );
add_action( 'wp_ajax_nopriv_ktd_dify_chat', 'ktd_ajax_dify_chat' );

/**
 * 2. Frontend Widget: Giao diện Native Chatbot phong cách KTD Store
 */
function ktd_render_native_chatbot() {
	if ( is_admin() ) {
		return;
	}

	$ajax_url = admin_url( 'admin-ajax.php' );
	$nonce    = wp_create_nonce( 'ktd_chat_nonce' );
	?>
	<!-- KTD Store Native AI Chatbot (No Iframe) -->
	<div id="ktd-chatbot-root">
		<!-- Floating Launcher Button -->
		<button id="ktd-chat-launcher" type="button" aria-label="Mở tư vấn AI KTD Store" title="Trò chuyện với EV - Trợ lý KTD Store">
			<svg class="ktd-icon-open" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
			</svg>
			<svg class="ktd-icon-close" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
				<line x1="18" y1="6" x2="6" y2="18"></line>
				<line x1="6" y1="6" x2="18" y2="18"></line>
			</svg>
			<span class="ktd-pulse-badge" title="Đang trực tuyến 24/7"></span>
		</button>

		<!-- Native Chat Window -->
		<div id="ktd-chat-window" class="ktd-chat-hidden" role="dialog" aria-modal="true" aria-label="Khung tư vấn KTD Store">
			<!-- Header -->
			<div class="ktd-chat-header">
				<div class="ktd-chat-header-user">
					<div class="ktd-chat-avatar">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<rect x="3" y="11" width="18" height="10" rx="2"></rect>
							<circle cx="12" cy="5" r="2"></circle>
							<path d="M12 7v4"></path>
							<line x1="8" y1="16" x2="8" y2="16"></line>
							<line x1="16" y1="16" x2="16" y2="16"></line>
						</svg>
						<span class="ktd-avatar-status"></span>
					</div>
					<div class="ktd-chat-header-text">
						<h3 class="ktd-chat-title">EV — Trợ lý AI KTD Store</h3>
						<span class="ktd-chat-status">Trực tuyến 24/7 • Sẵn sàng hỗ trợ</span>
					</div>
				</div>
				<div class="ktd-chat-header-actions">
					<button type="button" id="ktd-chat-reset-btn" title="Làm mới cuộc trò chuyện" aria-label="Làm mới">
						<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
							<path d="M21 3v5h-5"></path>
							<path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
							<path d="M8 16H3v5"></path>
						</svg>
					</button>
					<button type="button" id="ktd-chat-close-btn" title="Đóng cửa sổ" aria-label="Đóng">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
							<line x1="18" y1="6" x2="6" y2="18"></line>
							<line x1="6" y1="6" x2="18" y2="18"></line>
						</svg>
					</button>
				</div>
			</div>

			<!-- Messages Body (100% không gian thoáng đãng) -->
			<div id="ktd-chat-body" class="ktd-chat-body">
				<div id="ktd-chat-messages" class="ktd-chat-messages"></div>
				<div id="ktd-chat-typing" class="ktd-chat-typing">
					<span class="ktd-typing-dot"></span>
					<span class="ktd-typing-dot"></span>
					<span class="ktd-typing-dot"></span>
					<span class="ktd-typing-text">EV đang soạn câu trả lời...</span>
				</div>
			</div>

			<!-- Input Form -->
			<form id="ktd-chat-form" class="ktd-chat-form" autocomplete="off">
				<input type="text" id="ktd-chat-input" placeholder="Hỏi EV bất kỳ điều gì..." maxlength="500" required />
				<button type="submit" id="ktd-chat-send" aria-label="Gửi tin nhắn">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
						<line x1="22" y1="2" x2="11" y2="13"></line>
						<polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
					</svg>
				</button>
			</form>
		</div>
	</div>

	<style>
		/* ==========================================================================
		   KTD Native Chatbot Styles (Light Modern Theme)
		   ========================================================================== */
		:root {
			--ktd-chat-primary: #2563eb;
			--ktd-chat-primary-dark: #1d4ed8;
			--ktd-chat-dark: #0f172a;
			--ktd-chat-border: #e2e8f0;
			--ktd-chat-bg: #f8fafc;
		}

		/* 1. Launcher Button */
		#ktd-chat-launcher {
			position: fixed !important;
			bottom: 92px !important;
			right: 28px !important;
			width: 52px !important;
			height: 52px !important;
			border-radius: 50% !important;
			background: linear-gradient(135deg, var(--ktd-chat-primary) 0%, var(--ktd-chat-primary-dark) 100%) !important;
			color: #ffffff !important;
			border: none !important;
			box-shadow: 0 8px 24px rgba(37, 99, 235, 0.38) !important;
			cursor: pointer !important;
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			z-index: 2147483640 !important;
			transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
			padding: 0 !important;
		}

		#ktd-chat-launcher:hover {
			transform: translateY(-3px) scale(1.05) !important;
			box-shadow: 0 12px 30px rgba(37, 99, 235, 0.48) !important;
		}

		#ktd-chat-launcher .ktd-icon-close {
			display: none;
		}

		#ktd-chat-launcher.is-active .ktd-icon-open {
			display: none;
		}

		#ktd-chat-launcher.is-active .ktd-icon-close {
			display: block;
		}

		/* Chấm xanh Pulse Online */
		.ktd-pulse-badge {
			position: absolute;
			top: 2px;
			right: 2px;
			width: 13px;
			height: 13px;
			background-color: #10b981;
			border: 2.5px solid #ffffff;
			border-radius: 50%;
			box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
			animation: ktdPulseGlow 2s infinite cubic-bezier(0.4, 0, 0.6, 1);
			pointer-events: none;
		}

		@keyframes ktdPulseGlow {
			0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
			70% { box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
			100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
		}

		/* 2. Cửa sổ Chatbox Native */
		#ktd-chat-window {
			position: fixed !important;
			bottom: 154px !important;
			right: 28px !important;
			width: 390px !important;
			height: 600px !important;
			max-width: calc(100vw - 32px) !important;
			max-height: calc(100vh - 180px) !important;
			background: #ffffff !important;
			border-radius: 16px !important;
			border: 1px solid rgba(226, 232, 240, 0.9) !important;
			box-shadow: 0 20px 48px -8px rgba(15, 23, 42, 0.18), 0 4px 16px -2px rgba(15, 23, 42, 0.08) !important;
			z-index: 2147483640 !important;
			display: flex !important;
			flex-direction: column !important;
			overflow: hidden !important;
			font-family: var(--ktd-font-body, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif) !important;
			transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1) !important;
			transform-origin: bottom right !important;
		}

		#ktd-chat-window.ktd-chat-hidden {
			opacity: 0 !important;
			visibility: hidden !important;
			pointer-events: none !important;
			transform: translateY(16px) scale(0.95) !important;
		}

		/* Header */
		.ktd-chat-header {
			background: linear-gradient(135deg, var(--ktd-chat-primary) 0%, var(--ktd-chat-primary-dark) 100%) !important;
			color: #ffffff !important;
			padding: 14px 16px !important;
			display: flex !important;
			align-items: center !important;
			justify-content: space-between !important;
			border-bottom: 1px solid rgba(255, 255, 255, 0.12) !important;
		}

		.ktd-chat-header-user {
			display: flex !important;
			align-items: center !important;
			gap: 12px !important;
		}

		.ktd-chat-avatar {
			width: 38px !important;
			height: 38px !important;
			border-radius: 50% !important;
			background: rgba(255, 255, 255, 0.2) !important;
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			position: relative !important;
			color: #ffffff !important;
		}

		.ktd-avatar-status {
			position: absolute !important;
			bottom: 0 !important;
			right: 0 !important;
			width: 10px !important;
			height: 10px !important;
			background: #10b981 !important;
			border: 2px solid #ffffff !important;
			border-radius: 50% !important;
		}

		.ktd-chat-title {
			font-family: var(--ktd-font-heading, inherit) !important;
			font-size: 14.5px !important;
			font-weight: 700 !important;
			color: #ffffff !important;
			margin: 0 !important;
			line-height: 1.2 !important;
		}

		.ktd-chat-status {
			font-size: 11.5px !important;
			color: rgba(255, 255, 255, 0.85) !important;
			display: block !important;
			margin-top: 2px !important;
		}

		.ktd-chat-header-actions {
			display: flex !important;
			align-items: center !important;
			gap: 6px !important;
		}

		.ktd-chat-header-actions button {
			background: rgba(255, 255, 255, 0.12) !important;
			border: none !important;
			color: #ffffff !important;
			width: 32px !important;
			height: 32px !important;
			border-radius: 8px !important;
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			cursor: pointer !important;
			transition: all 0.2s ease !important;
			padding: 0 !important;
		}

		.ktd-chat-header-actions button:hover {
			background: rgba(255, 255, 255, 0.25) !important;
			transform: scale(1.08) !important;
		}

		/* Messages Body */
		.ktd-chat-body {
			flex: 1 !important;
			overflow-y: auto !important;
			padding: 16px !important;
			background: var(--ktd-chat-bg) !important;
			display: flex !important;
			flex-direction: column !important;
			gap: 12px !important;
			scroll-behavior: smooth !important;
		}

		.ktd-chat-messages {
			display: flex !important;
			flex-direction: column !important;
			gap: 12px !important;
		}

		.ktd-msg-item {
			display: flex !important;
			flex-direction: column !important;
			max-width: 86% !important;
			animation: ktdMsgFadeIn 0.25s ease forwards !important;
		}

		@keyframes ktdMsgFadeIn {
			from { opacity: 0; transform: translateY(6px); }
			to { opacity: 1; transform: translateY(0); }
		}

		.ktd-msg-item.ktd-msg-user {
			align-self: flex-end !important;
		}

		.ktd-msg-item.ktd-msg-bot {
			align-self: flex-start !important;
		}

		.ktd-bubble {
			padding: 11px 15px !important;
			font-size: 13.8px !important;
			line-height: 1.55 !important;
			word-break: break-word !important;
		}

		.ktd-msg-user .ktd-bubble {
			background: var(--ktd-chat-primary) !important;
			color: #ffffff !important;
			border-radius: 16px 16px 4px 16px !important;
			box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2) !important;
		}

		.ktd-msg-bot .ktd-bubble {
			background: #ffffff !important;
			color: #1e293b !important;
			border: 1px solid var(--ktd-chat-border) !important;
			border-radius: 16px 16px 16px 4px !important;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03) !important;
		}

		.ktd-msg-bot .ktd-bubble strong {
			color: #0f172a !important;
			font-weight: 700 !important;
		}

		.ktd-msg-bot .ktd-bubble ul,
		.ktd-msg-bot .ktd-bubble ol {
			margin: 6px 0 !important;
			padding-left: 18px !important;
		}

		.ktd-msg-bot .ktd-bubble li {
			margin-bottom: 4px !important;
		}

		.ktd-msg-bot .ktd-bubble a {
			color: var(--ktd-chat-primary) !important;
			font-weight: 600 !important;
			text-decoration: underline !important;
		}

		.ktd-msg-time {
			font-size: 11px !important;
			color: #94a3b8 !important;
			margin-top: 3px !important;
			padding: 0 4px !important;
		}

		.ktd-msg-user .ktd-msg-time {
			text-align: right !important;
		}

		/* Typing Indicator: Mặc định ẩn 100%, chỉ hiện khi có class is-typing */
		.ktd-chat-typing {
			display: none !important;
			align-items: center !important;
			gap: 5px !important;
			background: #ffffff !important;
			border: 1px solid var(--ktd-chat-border) !important;
			border-radius: 16px 16px 16px 4px !important;
			padding: 10px 14px !important;
			align-self: flex-start !important;
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02) !important;
		}

		.ktd-chat-typing.is-typing {
			display: flex !important;
		}

		.ktd-typing-dot {
			width: 6px !important;
			height: 6px !important;
			border-radius: 50% !important;
			background: #94a3b8 !important;
			animation: ktdTyping 1.4s infinite ease-in-out !important;
		}

		.ktd-typing-dot:nth-child(2) { animation-delay: 0.2s !important; }
		.ktd-typing-dot:nth-child(3) { animation-delay: 0.4s !important; }

		@keyframes ktdTyping {
			0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
			30% { transform: translateY(-4px); opacity: 1; }
		}

		.ktd-typing-text {
			font-size: 12px !important;
			color: #64748b !important;
			margin-left: 6px !important;
		}

		/* Form Input */
		.ktd-chat-form {
			display: flex !important;
			align-items: center !important;
			gap: 8px !important;
			padding: 12px 14px !important;
			background: #ffffff !important;
			border-top: 1px solid var(--ktd-chat-border) !important;
		}

		#ktd-chat-input {
			flex: 1 !important;
			height: 42px !important;
			border: 1px solid var(--ktd-chat-border) !important;
			border-radius: 24px !important;
			padding: 0 16px !important;
			font-size: 13.8px !important;
			background: var(--ktd-chat-bg) !important;
			color: #1e293b !important;
			outline: none !important;
			font-family: inherit !important;
			transition: all 0.2s ease !important;
			box-sizing: border-box !important;
		}

		#ktd-chat-input:focus {
			border-color: #cbd5e1 !important;
			background: #ffffff !important;
			box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04) !important;
		}

		#ktd-chat-send {
			width: 42px !important;
			height: 42px !important;
			border-radius: 50% !important;
			background: var(--ktd-chat-primary) !important;
			border: none !important;
			color: #ffffff !important;
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			cursor: pointer !important;
			transition: all 0.2s ease !important;
			padding: 0 !important;
			flex-shrink: 0 !important;
		}

		#ktd-chat-send:hover {
			background: var(--ktd-chat-primary-dark) !important;
			transform: scale(1.06) !important;
		}

		#ktd-chat-send:disabled {
			opacity: 0.5 !important;
			cursor: not-allowed !important;
			transform: none !important;
		}

		/* 3. Mobile Responsive (Bottom Sheet) */
		@media (max-width: 768px) {
			#ktd-chat-launcher {
				bottom: 78px !important;
				right: 20px !important;
				width: 48px !important;
				height: 48px !important;
			}

			#ktd-chat-window {
				bottom: 84px !important;
				right: 16px !important;
				left: 16px !important;
				width: calc(100vw - 32px) !important;
				height: calc(100vh - 120px) !important;
				max-width: none !important;
				max-height: 82vh !important;
				border-radius: 16px !important;
			}
		}
	</style>

	<script>
		(function() {
			const AJAX_URL = <?php echo wp_json_encode( $ajax_url ); ?>;
			const NONCE = <?php echo wp_json_encode( $nonce ); ?>;
			const STORAGE_MSG_KEY = 'ktd_chat_messages_v3';
			const STORAGE_CONV_KEY = 'ktd_chat_conv_id_v3';
			const FALLBACK_MSG = 'Dạ em chưa có thông tin về vấn đề này, anh/chị vui lòng liên hệ Hotline 1900 8888 để được hỗ trợ ạ.';
			const DEFAULT_GREETING = 'Dạ em chào anh/chị! Em là EV - Trợ lý AI KTD Store. Anh/chị cần em hỗ trợ gì hôm nay ạ? 😊';

			const launcher = document.getElementById('ktd-chat-launcher');
			const chatWindow = document.getElementById('ktd-chat-window');
			const closeBtn = document.getElementById('ktd-chat-close-btn');
			const resetBtn = document.getElementById('ktd-chat-reset-btn');
			const chatForm = document.getElementById('ktd-chat-form');
			const chatInput = document.getElementById('ktd-chat-input');
			const chatSend = document.getElementById('ktd-chat-send');
			const messagesEl = document.getElementById('ktd-chat-messages');
			const typingEl = document.getElementById('ktd-chat-typing');

			let conversationId = localStorage.getItem(STORAGE_CONV_KEY) || '';
			let isRequesting = false;
			let safetyTimer = null;

			// Markdown Parser nhẹ an toàn
			function parseMarkdown(text) {
				if (!text) return '';
				let cleanText = text.replace(/<think>[\s\S]*?<\/think>/gi, '').trim();
				let escaped = cleanText
					.replace(/&/g, '&amp;')
					.replace(/</g, '&lt;')
					.replace(/>/g, '&gt;');

				// Bold **text**
				escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
				// Italic *text*
				escaped = escaped.replace(/\*(.*?)\*/g, '<em>$1</em>');

				// Phone links (1900 8888 or 19008888)
				escaped = escaped.replace(/(1900\s?8888)/g, '<a href="tel:19008888">$1</a>');

				// Bullet list lines (- item or * item)
				const lines = escaped.split('\n');
				let inList = false;
				let result = [];

				for (let line of lines) {
					const trimmed = line.trim();
					if (trimmed.startsWith('- ') || trimmed.startsWith('* ')) {
						if (!inList) {
							result.push('<ul>');
							inList = true;
						}
						result.push('<li>' + trimmed.substring(2) + '</li>');
					} else {
						if (inList) {
							result.push('</ul>');
							inList = false;
						}
						if (trimmed.length > 0) {
							result.push('<p>' + trimmed + '</p>');
						}
					}
				}
				if (inList) result.push('</ul>');

				return result.join('');
			}

			function getCurrentTime() {
				const now = new Date();
				return now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
			}

			function appendMessage(role, text, time, save = true) {
				const msgTime = time || getCurrentTime();
				const item = document.createElement('div');
				item.className = 'ktd-msg-item ' + (role === 'user' ? 'ktd-msg-user' : 'ktd-msg-bot');

				const bubble = document.createElement('div');
				bubble.className = 'ktd-bubble';
				if (role === 'bot') {
					bubble.innerHTML = parseMarkdown(text);
				} else {
					bubble.textContent = text;
				}

				const timeEl = document.createElement('div');
				timeEl.className = 'ktd-msg-time';
				timeEl.textContent = msgTime;

				item.appendChild(bubble);
				item.appendChild(timeEl);
				messagesEl.appendChild(item);

				scrollToBottom();

				if (save) {
					const history = getStoredMessages();
					history.push({ role, text, time: msgTime });
					localStorage.setItem(STORAGE_MSG_KEY, JSON.stringify(history));
				}
			}

			function getStoredMessages() {
				try {
					const raw = localStorage.getItem(STORAGE_MSG_KEY);
					return raw ? JSON.parse(raw) : [];
				} catch(e) {
					return [];
				}
			}

			function scrollToBottom() {
				const body = document.getElementById('ktd-chat-body');
				if (body) {
					body.scrollTop = body.scrollHeight;
				}
			}

			function loadHistory() {
				messagesEl.innerHTML = '';
				if (typingEl) typingEl.classList.remove('is-typing');
				const history = getStoredMessages();
				if (history.length === 0) {
					appendMessage('bot', DEFAULT_GREETING, getCurrentTime(), true);
				} else {
					history.forEach(m => appendMessage(m.role, m.text, m.time, false));
				}
				scrollToBottom();
			}

			function toggleChat(forceOpen) {
				const isCurrentlyHidden = chatWindow.classList.contains('ktd-chat-hidden');
				const open = typeof forceOpen === 'boolean' ? forceOpen : isCurrentlyHidden;

				if (open) {
					chatWindow.classList.remove('ktd-chat-hidden');
					launcher.classList.add('is-active');
					setTimeout(() => {
						chatInput.focus();
						scrollToBottom();
					}, 200);
				} else {
					chatWindow.classList.add('ktd-chat-hidden');
					launcher.classList.remove('is-active');
				}
			}

			function finishRequest() {
				if (safetyTimer) {
					clearTimeout(safetyTimer);
					safetyTimer = null;
				}
				isRequesting = false;
				chatSend.disabled = false;
				if (typingEl) typingEl.classList.remove('is-typing');
			}

			function sendMessage(text) {
				const query = (text || chatInput.value || '').trim();
				if (!query || isRequesting) return;

				appendMessage('user', query, getCurrentTime(), true);
				chatInput.value = '';

				isRequesting = true;
				chatSend.disabled = true;
				if (typingEl) typingEl.classList.add('is-typing');
				scrollToBottom();

				// Safety watchdog: tự động hoàn tất sau 35s nếu kết nối mạng bị nghẽn
				safetyTimer = setTimeout(() => {
					if (isRequesting) {
						finishRequest();
						appendMessage('bot', FALLBACK_MSG, getCurrentTime(), true);
					}
				}, 35000);

				const formData = new URLSearchParams();
				formData.append('action', 'ktd_dify_chat');
				formData.append('nonce', NONCE);
				formData.append('query', query);
				if (conversationId) {
					formData.append('conversation_id', conversationId);
				}

				fetch(AJAX_URL, {
					method: 'POST',
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
					body: formData.toString()
				})
				.then(res => res.json())
				.then(data => {
					finishRequest();
					if (data && data.success && data.data && data.data.answer) {
						if (data.data.conversation_id) {
							conversationId = data.data.conversation_id;
							localStorage.setItem(STORAGE_CONV_KEY, conversationId);
						}
						appendMessage('bot', data.data.answer, getCurrentTime(), true);
					} else {
						appendMessage('bot', FALLBACK_MSG, getCurrentTime(), true);
					}
				})
				.catch(() => {
					finishRequest();
					appendMessage('bot', FALLBACK_MSG, getCurrentTime(), true);
				});
			}

			// Sự kiện
			launcher.addEventListener('click', () => toggleChat());
			closeBtn.addEventListener('click', () => toggleChat(false));

			resetBtn.addEventListener('click', () => {
				if (confirm('Bắt đầu cuộc trò chuyện mới với EV?')) {
					localStorage.removeItem(STORAGE_MSG_KEY);
					localStorage.removeItem(STORAGE_CONV_KEY);
					conversationId = '';
					loadHistory();
				}
			});

			chatForm.addEventListener('submit', (e) => {
				e.preventDefault();
				sendMessage();
			});

			document.addEventListener('keydown', (e) => {
				if (e.key === 'Escape' && !chatWindow.classList.contains('ktd-chat-hidden')) {
					toggleChat(false);
				}
			});

			// Khởi tạo
			loadHistory();
		})();

		// Hỗ trợ môi trường tĩnh (chỉ kích hoạt khi host trên github.io tĩnh)
		(function() {
			if (window.location.hostname.indexOf('github.io') !== -1) {
				var nativeRoot = document.getElementById('ktd-chatbot-root');
				if (nativeRoot) {
					nativeRoot.style.display = 'none';
				}
				window.difyChatbotConfig = {
					token: '4oDnckt1hFSIXLuD',
					baseUrl: 'https://udify.app'
				};
				var s = document.createElement('script');
				s.src = 'https://udify.app/embed.min.js';
				s.id = '4oDnckt1hFSIXLuD';
				s.defer = true;
				document.body.appendChild(s);
			}
		})();
	</script>
	<style>
		#dify-chatbot-bubble-button {
			background-color: #1C64F2 !important;
		}
		#dify-chatbot-bubble-window {
			width: 24rem !important;
			height: 40rem !important;
		}
	</style>
	<?php
}
add_action( 'wp_footer', 'ktd_render_native_chatbot', 99 );

