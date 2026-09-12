<?php
/**
 * WooCommerce E-Commerce Customizations
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SVG Icon Helper — tránh duplicate SVG string rải rác trong các trust badge sections.
 * Sử dụng với $name: shield, lock, refresh, clock, card
 *
 * @param string $name  Tên icon.
 * @param int    $size  Kích thước px.
 * @return string HTML SVG.
 */
function ktd_svg_icon( $name, $size = 18 ) {
	$inner = '';
	switch ( $name ) {
		case 'shield':
			$inner = '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>';
			break;
		case 'lock':
			$inner = '<rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>';
			break;
		case 'refresh':
			$inner = '<path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/>';
			break;
		case 'clock':
			$inner = '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>';
			break;
		case 'card':
			$inner = '<rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>';
			break;
	}
	return '<svg xmlns="http://www.w3.org/2000/svg" width="' . (int) $size . '" height="' . (int) $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $inner . '</svg>';
}

/**
 * 1. AJAX Cart Count Fragments
 */
function ktd_cart_count_fragments( $fragments ) {
	if ( function_exists( 'WC' ) && WC()->cart ) {
		$count = WC()->cart->get_cart_contents_count();
		$fragments['span.ktd-cart-count'] = '<span class="ktd-cart-count">' . esc_html( $count ) . '</span>';
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
 * 2. Floating Navigation / Scroll to Top
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
 * 3. Shop Price Range Bounds Helper
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
 * 4. Shop Page Hero Banner & Sidebar Filter
 */
function ktd_shop_layout_start() {
	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) && ! is_product() ) {
		$shop_url    = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
		$current_cat = is_product_category() ? get_queried_object()->slug : '';
		$cat_title   = is_product_category() ? single_term_title( '', false ) : '';
		?>
		<div class="ktd-shop-hero">
			<div class="ktd-page-container">
				<!-- Top-Left Breadcrumbs (Format Hình 1) -->
				<div class="ktd-hero-breadcrumbs-wrapper">
					<nav class="ktd-breadcrumbs" aria-label="Breadcrumb">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ktd-crumb-item">Trang chủ</a>
						<span class="ktd-crumb-sep">/</span>
						<?php if ( is_product_category() && $cat_title ) : ?>
							<a href="<?php echo esc_url( $shop_url ); ?>" class="ktd-crumb-item">Sản phẩm</a>
							<span class="ktd-crumb-sep">/</span>
							<span class="ktd-crumb-item ktd-crumb-current"><?php echo esc_html( $cat_title ); ?></span>
						<?php else : ?>
							<span class="ktd-crumb-item ktd-crumb-current">Sản phẩm</span>
						<?php endif; ?>
					</nav>
				</div>

				<!-- Centered Hero Content -->
				<div class="ktd-shop-hero-content">
					<span class="ktd-section-badge">FLAGSHIP SMARTPHONE • CHÍNH HÃNG 100%</span>
					<h1 class="ktd-shop-hero-title"><?php echo ( is_product_category() && $cat_title ) ? esc_html( $cat_title ) : 'Bộ Sưu Tập Điện Thoại Cao Cấp'; ?></h1>
					<p class="ktd-shop-hero-desc">Khám phá các dòng sản phẩm mới nhất từ Apple, Samsung, OPPO với mức giá ưu đãi và chính sách bảo hành 1 đổi 1 trong 30 ngày.</p>
				</div>
			</div>
		</div>

		<div class="ktd-shop-wrapper ktd-page-container">
			<div class="ktd-mobile-filter-bar">
				<button type="button" class="ktd-mobile-filter-btn" id="ktdFilterToggle">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
					</svg>
					<span>Lọc Sản Phẩm</span>
				</button>
			</div>

			<div class="ktd-shop-layout">
				<aside class="ktd-shop-sidebar" id="ktdShopSidebar">
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

				<div class="ktd-shop-main">
		<?php
	}
}
add_action( 'woocommerce_before_main_content', 'ktd_shop_layout_start', 15 );

// Ẩn tiêu đề Shop trùng lặp và xóa breadcrumb mặc định của WooCommerce trên catalog (đã tích hợp vào Hero)
add_filter( 'woocommerce_show_page_title', function( $show ) {
	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) && ! is_product() ) {
		return false;
	}
	return $show;
} );

add_action( 'wp', function() {
	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) && ! is_product() ) {
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
	}
} );

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
 * Clean HTML helper to prevent WordPress wpautop from injecting stray <p> and <br> tags
 * Dùng single preg_replace với mảng pattern — hiệu quả hơn 2 lần gọi lồng nhau
 */
function ktd_clean_html( $html ) {
	return trim( preg_replace( array( '/[\r\n\t]+/', '/>\s+</' ), array( ' ', '><' ), (string) $html ) );
}

/**
 * Unified 3-Step Horizontal Stepper (Giỏ Hàng -> Thanh Toán -> Hoàn Tất)
 * Hiển thị nằm ngay DƯỚI tiêu đề trang với đường line ngang kết nối liên tục
 */
function ktd_render_order_funnel_stepper( $current_step = 1 ) {
	$step1_class = ( $current_step > 1 ) ? 'is-done' : ( ( $current_step === 1 ) ? 'is-active' : '' );
	$step2_class = ( $current_step > 2 ) ? 'is-done' : ( ( $current_step === 2 ) ? 'is-active' : '' );
	$step3_class = ( $current_step === 3 ) ? 'is-active' : '';

	$line1_class = ( $current_step >= 2 ) ? 'is-done' : '';
	$line2_class = ( $current_step >= 3 ) ? 'is-done' : '';

	ob_start();
	?>
	<div class="ktd-stepper-horizontal" aria-label="Tiến trình đặt hàng">
		<div class="ktd-step-item <?php echo esc_attr( $step1_class ); ?>">
			<div class="ktd-step-bubble">
				<?php if ( $current_step > 1 ) : ?>
					<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
				<?php else : ?>
					<span class="ktd-step-num">1</span>
				<?php endif; ?>
			</div>
			<span class="ktd-step-label">Giỏ Hàng</span>
		</div>

		<div class="ktd-step-line <?php echo esc_attr( $line1_class ); ?>"></div>

		<div class="ktd-step-item <?php echo esc_attr( $step2_class ); ?>">
			<div class="ktd-step-bubble">
				<?php if ( $current_step > 2 ) : ?>
					<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
				<?php else : ?>
					<span class="ktd-step-num">2</span>
				<?php endif; ?>
			</div>
			<span class="ktd-step-label">Thanh Toán</span>
		</div>

		<div class="ktd-step-line <?php echo esc_attr( $line2_class ); ?>"></div>

		<div class="ktd-step-item <?php echo esc_attr( $step3_class ); ?>">
			<div class="ktd-step-bubble">
				<span class="ktd-step-num">3</span>
			</div>
			<span class="ktd-step-label">Hoàn Tất</span>
		</div>
	</div>
	<?php
	return ktd_clean_html( ob_get_clean() );
}

/**
 * 5. Cart Page Hero Banner & Stepper Dưới Tiêu Đề
 */
function ktd_get_cart_hero_html() {
	$home_url   = esc_url( home_url( '/' ) );
	$cart_count = function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	$is_empty   = ( $cart_count === 0 );
	ob_start();
	?>
	<div class="ktd-cart-hero<?php echo $is_empty ? ' is-empty-hero' : ''; ?>">
		<div class="ktd-cart-hero-inner">
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

			<?php if ( ! $is_empty ) : ?>
				<?php echo ktd_render_order_funnel_stepper( 1 ); ?>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return ktd_clean_html( ob_get_clean() );
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
}, 20 );

remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );

/**
 * Append empty cart modifier to body_class for viewport vertical centering
 */
add_filter( 'body_class', function( $classes ) {
	if ( function_exists( 'is_cart' ) && is_cart() && function_exists( 'WC' ) && WC()->cart && WC()->cart->is_empty() ) {
		$classes[] = 'ktd-cart-is-empty';
	}
	return $classes;
} );

/**
 * Disable Shipping Calculator on Cart Page (Tránh form phức tạp, hỏi ZIP code)
 */
add_filter( 'woocommerce_shipping_calculator_enable_city', '__return_false' );
add_filter( 'woocommerce_shipping_calculator_enable_postcode', '__return_false' );
add_filter( 'pre_option_woocommerce_enable_shipping_calc', '__return_zero' );

function ktd_cart_trust_badges() {
	?>
	<div class="ktd-cart-trust-badges">
		<div class="ktd-trust-item">
			<?php echo ktd_svg_icon( 'shield', 18 ); ?>
			<span>100% Hàng chính hãng nguyên seal</span>
		</div>
		<div class="ktd-trust-item">
			<?php echo ktd_svg_icon( 'lock', 18 ); ?>
			<span>Bảo mật thanh toán chuẩn SSL 256-bit</span>
		</div>
		<div class="ktd-trust-item">
			<?php echo ktd_svg_icon( 'refresh', 18 ); ?>
			<span>Đổi trả miễn phí 30 ngày nếu lỗi</span>
		</div>
	</div>
	<?php
}
add_action( 'woocommerce_after_cart_totals', 'ktd_cart_trust_badges', 20 );

/**
 * 5.2.1 VietQR Napas247 Dynamic Data Helper
 */
function ktd_get_vietqr_data( $order_id, $amount = 0 ) {
	$bank_id      = 'VCB'; // Vietcombank
	$bank_name    = 'Vietcombank (Ngân hàng TMCP Ngoại Thương VN)';
	$account_no   = '999988886666';
	$account_name = 'KTD STORE';
	$amount       = (int) round( $amount );
	$memo         = 'KTD ' . $order_id;
	$encoded_memo = rawurlencode( $memo );
	$encoded_name = rawurlencode( $account_name );
	$qr_image_url = "https://img.vietqr.io/image/{$bank_id}-{$account_no}-compact2.png?amount={$amount}&addInfo={$encoded_memo}&accountName={$encoded_name}";

	return array(
		'bank_id'      => $bank_id,
		'bank_name'    => $bank_name,
		'account_no'   => $account_no,
		'account_name' => $account_name,
		'amount'       => $amount,
		'memo'         => $memo,
		'qr_image_url' => $qr_image_url,
	);
}

/**
 * 5.2.2 Checkout Hero & Stepper Dưới Tiêu Đề
 */
function ktd_get_checkout_hero_html() {
	$home_url = function_exists( 'home_url' ) ? home_url( '/' ) : '/';
	$cart_url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '/cart/';
	ob_start();
	?>
	<div class="ktd-checkout-hero">
		<div class="ktd-checkout-hero-inner">
			<nav class="ktd-checkout-breadcrumbs" aria-label="Breadcrumb">
				<a href="<?php echo esc_url( $home_url ); ?>">Trang chủ</a>
				<span class="ktd-bc-sep">/</span>
				<a href="<?php echo esc_url( $cart_url ); ?>">Giỏ hàng</a>
				<span class="ktd-bc-sep">/</span>
				<span class="ktd-bc-current">Thanh toán</span>
			</nav>
			<h1 class="ktd-checkout-title">
				Thanh Toán Đơn Hàng
			</h1>

			<?php echo ktd_render_order_funnel_stepper( 2 ); ?>
		</div>
	</div>
	<?php
	return ktd_clean_html( ob_get_clean() );
}

/**
 * 5.2.3 Order Received (Thank You) Hero & Stepper Dưới Tiêu Đề (Step 3: Hoàn Tất)
 */
function ktd_get_order_received_hero_html() {
	$home_url = function_exists( 'home_url' ) ? home_url( '/' ) : '/';
	ob_start();
	?>
	<div class="ktd-checkout-hero ktd-order-received-hero">
		<div class="ktd-checkout-hero-inner">
			<nav class="ktd-checkout-breadcrumbs" aria-label="Breadcrumb">
				<a href="<?php echo esc_url( $home_url ); ?>">Trang chủ</a>
				<span class="ktd-bc-sep">/</span>
				<span class="ktd-bc-current">Hoàn tất đơn hàng</span>
			</nav>
			<h1 class="ktd-checkout-title">
				Đặt Hàng Thành Công
			</h1>

			<?php echo ktd_render_order_funnel_stepper( 3 ); ?>
		</div>
	</div>
	<?php
	return ktd_clean_html( ob_get_clean() );
}

add_filter( 'the_content', function( $content ) {
	if ( function_exists( 'is_order_received_page' ) && is_order_received_page() && in_the_loop() && is_main_query() ) {
		static $thankyou_hero_rendered = false;
		if ( ! $thankyou_hero_rendered ) {
			$thankyou_hero_rendered = true;
			return ktd_get_order_received_hero_html() . $content;
		}
	} elseif ( function_exists( 'is_checkout' ) && is_checkout() && in_the_loop() && is_main_query() ) {
		static $checkout_rendered = false;
		if ( ! $checkout_rendered ) {
			$checkout_rendered = true;
			return ktd_get_checkout_hero_html() . $content;
		}
	}
	return $content;
}, 20 );

// Localize Privacy Policy Text
add_filter( 'woocommerce_get_privacy_policy_text', function( $text, $type = '' ) {
	return 'Thông tin cá nhân của bạn sẽ được bảo mật tuyệt đối và sử dụng để xử lý đơn hàng theo chính sách của KTD Store.';
}, 20, 2 );

// Localize Order Details and Address Labels on Thank You Page
add_filter( 'gettext', function( $translated_text, $text, $domain ) {
	if ( 'woocommerce' === $domain ) {
		switch ( $text ) {
			case 'Order details':
				return 'Chi Tiết Đơn Hàng';
			case 'Product':
				return 'Sản phẩm';
			case 'Total':
				return 'Tổng tiền';
			case 'Subtotal:':
				return 'Tạm tính:';
			case 'Discount:':
				return 'Giảm giá coupon:';
			case 'Shipping:':
				return 'Phí giao hàng:';
			case 'Payment method:':
				return 'Phương thức thanh toán:';
			case 'Billing address':
				return 'Địa Chỉ & Thông Tin Nhận Hàng';
			case 'Shipping address':
				return 'Địa chỉ giao hàng';
		}
	}
	return $translated_text;
}, 20, 3 );

// Disable WooCommerce guest email verification — cho phép hiển thị trang Order Received
// ngay sau khi đặt hàng (không yêu cầu nhập email xác minh).
// Bảo mật: trang Order Received chỉ hiển thị khi có order_key hợp lệ trong URL;
// WooCommerce tự động kiểm tra key — Order ID enumeration không thực tế
// vì order_key là random hash, không phải ID tuần tự.
add_filter( 'woocommerce_order_email_verification_required', '__return_false', 99 );

// Suppress default Hello Elementor entry title on Cart, Checkout and Order Received
add_filter( 'hello_elementor_page_title', function( $show ) {
	if ( ( function_exists( 'is_order_received_page' ) && is_order_received_page() ) || ( function_exists( 'is_checkout' ) && is_checkout() ) || ( function_exists( 'is_cart' ) && is_cart() ) ) {
		return false;
	}
	return $show;
} );

/**
 * 5.3 Checkout Fields Friction Reduction (Chuẩn TMĐT Việt Nam)
 */
function ktd_custom_checkout_fields( $fields ) {
	if ( isset( $fields['billing'] ) ) {
		unset( $fields['billing']['billing_company'] );
		unset( $fields['billing']['billing_postcode'] );
		unset( $fields['billing']['billing_country'] );
		unset( $fields['billing']['billing_address_2'] );
		unset( $fields['billing']['billing_last_name'] );
		unset( $fields['billing']['billing_state'] );

		if ( isset( $fields['billing']['billing_first_name'] ) ) {
			$fields['billing']['billing_first_name']['label'] = 'Họ và tên';
			$fields['billing']['billing_first_name']['placeholder'] = 'Ví dụ: Nguyễn Văn A';
			$fields['billing']['billing_first_name']['required'] = true;
			$fields['billing']['billing_first_name']['class'] = array( 'form-row-wide' );
		}
		if ( isset( $fields['billing']['billing_phone'] ) ) {
			$fields['billing']['billing_phone']['label'] = 'Số điện thoại nhận hàng';
			$fields['billing']['billing_phone']['placeholder'] = 'Ví dụ: 0912345678';
			$fields['billing']['billing_phone']['required'] = true;
			$fields['billing']['billing_phone']['class'] = array( 'form-row-wide' );
		}
		if ( isset( $fields['billing']['billing_email'] ) ) {
			$fields['billing']['billing_email']['label'] = 'Địa chỉ Email (Nhận thông tin đơn)';
			$fields['billing']['billing_email']['placeholder'] = 'email@example.com';
			$fields['billing']['billing_email']['required'] = true;
			$fields['billing']['billing_email']['class'] = array( 'form-row-wide' );
		}
		if ( isset( $fields['billing']['billing_city'] ) ) {
			$fields['billing']['billing_city']['label'] = 'Tỉnh / Thành phố';
			$fields['billing']['billing_city']['placeholder'] = 'Ví dụ: Hà Nội, TP. Hồ Chí Minh';
			$fields['billing']['billing_city']['required'] = true;
			$fields['billing']['billing_city']['class'] = array( 'form-row-wide' );
		}
		if ( isset( $fields['billing']['billing_address_1'] ) ) {
			$fields['billing']['billing_address_1']['label'] = 'Địa chỉ giao hàng cụ thể';
			$fields['billing']['billing_address_1']['placeholder'] = 'Số nhà, tên đường, phường/xã, quận/huyện';
			$fields['billing']['billing_address_1']['required'] = true;
			$fields['billing']['billing_address_1']['class'] = array( 'form-row-wide' );
		}
	}

	if ( isset( $fields['shipping'] ) ) {
		unset( $fields['shipping']['shipping_company'] );
		unset( $fields['shipping']['shipping_postcode'] );
		unset( $fields['shipping']['shipping_country'] );
		unset( $fields['shipping']['shipping_address_2'] );
		unset( $fields['shipping']['shipping_last_name'] );
		unset( $fields['shipping']['shipping_state'] );
	}

	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'ktd_custom_checkout_fields', 99 );

// Simplify shipping address requirement
add_filter( 'woocommerce_cart_needs_shipping_address', '__return_false' );

// Localize Place Order button to Vietnamese
add_filter( 'woocommerce_order_button_text', function() {
	return 'ĐẶT HÀNG NGAY';
} );

/**
 * 5.3 Checkout Trust Badges under Place Order Button
 */
function ktd_checkout_trust_badges() {
	?>
	<div class="ktd-checkout-trust-badges">
		<div class="ktd-checkout-trust-item">
			<?php echo ktd_svg_icon( 'lock', 16 ); ?>
			<span>Bảo mật thanh toán chuẩn SSL 256-bit</span>
		</div>
		<div class="ktd-checkout-trust-item">
			<?php echo ktd_svg_icon( 'clock', 16 ); ?>
			<span>Giao hàng hỏa tốc nội thành 1-2h</span>
		</div>
		<div class="ktd-checkout-trust-item">
			<?php echo ktd_svg_icon( 'shield', 16 ); ?>
			<span>Bảo hành chính hãng 1 đổi 1 trong 30 ngày</span>
		</div>
	</div>
	<?php
}
add_action( 'woocommerce_review_order_after_submit', 'ktd_checkout_trust_badges', 10 );

/**
 * 6. Single Product Wrapper & Customizations
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

function ktd_custom_breadcrumb_defaults( $defaults ) {
	$defaults['home']      = 'Trang chủ';
	$defaults['delimiter'] = ' <span class="ktd-bc-sep">/</span> ';
	return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'ktd_custom_breadcrumb_defaults' );

function ktd_custom_product_single_add_to_cart_text() {
	return 'Thêm Vào Giỏ Hàng';
}
add_filter( 'woocommerce_product_single_add_to_cart_text', 'ktd_custom_product_single_add_to_cart_text' );

function ktd_custom_product_add_to_cart_text( $text, $product = null ) {
	if ( $product && method_exists( $product, 'is_type' ) && $product->is_type( 'variable' ) ) {
		return 'Xem chi tiết';
	}
	return 'Thêm vào giỏ';
}
add_filter( 'woocommerce_product_add_to_cart_text', 'ktd_custom_product_add_to_cart_text', 10, 2 );

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

function ktd_render_single_product_trust_badges() {
	?>
	<div class="ktd-single-trust-card">
		<div class="ktd-single-trust-item">
			<div class="ktd-single-trust-icon"><?php echo ktd_svg_icon( 'shield', 20 ); ?></div>
			<div class="ktd-single-trust-text">
				<strong>100% Chính Hãng</strong>
				<span>Nguyên seal, nguồn gốc rõ ràng</span>
			</div>
		</div>
		<div class="ktd-single-trust-item">
			<div class="ktd-single-trust-icon"><?php echo ktd_svg_icon( 'clock', 20 ); ?></div>
			<div class="ktd-single-trust-text">
				<strong>Giao Hỏa Tốc</strong>
				<span>Nhận hàng trong 2 giờ</span>
			</div>
		</div>
		<div class="ktd-single-trust-item">
			<div class="ktd-single-trust-icon"><?php echo ktd_svg_icon( 'refresh', 20 ); ?></div>
			<div class="ktd-single-trust-text">
				<strong>Đổi Mới 30 Ngày</strong>
				<span>Lỗi 1 đổi 1 nhanh chóng</span>
			</div>
		</div>
		<div class="ktd-single-trust-item">
			<div class="ktd-single-trust-icon"><?php echo ktd_svg_icon( 'card', 20 ); ?></div>
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
 * Single Product Dual CTA: Nút Trả Góp 0%
 */
function ktd_render_single_product_dual_cta() {
	?>
	<div class="ktd-dual-cta-group">
		<button type="button" class="ktd-installment-cta-btn" id="ktdInstallmentBtn" title="Xem chính sách mua trả góp 0%">
			<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
			</svg>
			<div class="ktd-cta-btn-text">
				<strong>TRẢ GÓP 0%</strong>
				<span>Xét duyệt online 5 phút</span>
			</div>
		</button>
	</div>
	<?php
}
add_action( 'woocommerce_after_add_to_cart_button', 'ktd_render_single_product_dual_cta', 20 );

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

function ktd_custom_related_products_heading() {
	return 'Sản phẩm tương tự';
}
add_filter( 'woocommerce_product_related_products_heading', 'ktd_custom_related_products_heading', 20 );

/**
 * 7. Product Specifications Extraction Helper
 */
function ktd_get_product_specs( $passed_product = null ) {
	// Fix: tránh shadow variable bằng cách dùng tên tham số khác và không dùng global
	$product = is_object( $passed_product ) ? $passed_product : null;
	if ( ! $product && function_exists( 'wc_get_product' ) ) {
		$product = wc_get_product( get_the_ID() );
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
 * 8. Interactive Swatches for Variable Products
 */
function ktd_custom_variation_dropdown_swatches( $html, $args ) {
	$options   = isset( $args['options'] ) ? $args['options'] : array();
	$product   = isset( $args['product'] ) ? $args['product'] : null;
	$attribute = isset( $args['attribute'] ) ? $args['attribute'] : '';
	$selected  = isset( $args['selected'] ) ? $args['selected'] : '';

	if ( empty( $options ) || ! $product || ! is_object( $product ) ) {
		return $html;
	}

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
				// Phát hiện attribute màu sắc dựa trên tên attribute — không hard-code slug cụ thể
				$attr_lower = strtolower( (string) $attribute );
				if ( str_contains( $attr_lower, 'mau' ) || str_contains( $attr_lower, 'color' ) || str_contains( $attr_lower, 'colour' ) ) {
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
 * 9. Two-Column Product Details Layout & Specs Modal
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

		<!-- Khối Đánh giá & Nhận xét của khách hàng -->
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
 * 10. Single Product Variable Price Format (Hiển thị 1 giá đơn)
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
 * 11. Mobile Sticky Add to Cart Bar
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
 * Single Product: Modal Hướng Dẫn Mua Trả Góp 0% Lãi Suất & Tích Hợp AI
 */
function ktd_render_installment_modal() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}
	?>
	<div class="ktd-modal-backdrop" id="ktdInstallmentModal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="ktdInstallmentModalTitle">
		<div class="ktd-modal-dialog">
			<div class="ktd-modal-header">
				<div class="ktd-modal-title-wrap">
					<span class="ktd-modal-badge">Ưu Đãi Độc Quyền</span>
					<h3 class="ktd-modal-title" id="ktdInstallmentModalTitle">Chính Sách Mua Trả Góp 0% Lãi Suất</h3>
				</div>
				<button type="button" class="ktd-modal-close-btn" id="ktdModalCloseBtn" aria-label="Đóng cửa sổ">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
					</svg>
				</button>
			</div>
			<div class="ktd-modal-body">
				<div class="ktd-installment-tabs">
					<div class="ktd-inst-card">
						<div class="ktd-inst-card-header">
							<span class="ktd-inst-tag">Phương Thức 1</span>
							<h4 class="ktd-inst-card-title">Trả Góp 0% Qua Thẻ Tín Dụng</h4>
						</div>
						<ul class="ktd-inst-list">
							<li><strong>Lãi suất 0%:</strong> Áp dụng kỳ hạn linh hoạt 3, 6, 9, 12 tháng.</li>
							<li><strong>Hỗ trợ 25 ngân hàng:</strong> Vietcombank, Techcombank, MB, VPBank, ACB, BIDV...</li>
							<li><strong>Thủ tục 100% online:</strong> Không cần chứng minh thu nhập, duyệt tự động nhanh chóng.</li>
						</ul>
					</div>
					<div class="ktd-inst-card">
						<div class="ktd-inst-card-header">
							<span class="ktd-inst-tag">Phương Thức 2</span>
							<h4 class="ktd-inst-card-title">Trả Góp Qua CCCD Gắn Chip</h4>
						</div>
						<ul class="ktd-inst-list">
							<li><strong>Hồ sơ tinh gọn:</strong> Chỉ cần CCCD gắn chip chính chủ (từ đủ 18 tuổi).</li>
							<li><strong>Xét duyệt 5 phút:</strong> Hợp tác cùng Home Credit, FE Credit, HD SAISON tại cửa hàng.</li>
							<li><strong>Trả trước linh hoạt:</strong> Chỉ từ 0% đến 30% giá trị máy, nhận máy ngay sau duyệt.</li>
						</ul>
					</div>
				</div>

				<div class="ktd-modal-ai-box">
					<div class="ktd-ai-box-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/>
						</svg>
					</div>
					<div class="ktd-ai-box-content">
						<strong>Cần tính toán số tiền góp mỗi tháng?</strong>
						<p>Trợ lý AI EV sẽ giải đáp ngay bảng tạm tính kỳ hạn và hỗ trợ chuẩn bị hồ sơ nhanh nhất.</p>
					</div>
					<button type="button" class="ktd-modal-ai-trigger" id="ktdAskAiInstallmentBtn">
						<span>Hỏi AI Ngay</span>
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
						</svg>
					</button>
				</div>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'woocommerce_after_single_product', 'ktd_render_installment_modal', 30 );

/**
 * 12. Admin Product List Table Columns Optimization
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
		.wp-list-table th,
		.wp-list-table td {
			hyphens: none !important;
			word-break: normal !important;
			vertical-align: middle !important;
		}
		.wp-list-table th.column-price,
		.wp-list-table td.column-price {
			white-space: nowrap !important;
			min-width: 165px !important;
			width: 175px !important;
			padding-right: 15px !important;
		}
		.wp-list-table th.column-name,
		.wp-list-table td.column-name {
			min-width: 220px !important;
		}
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
		.wrap .subsubsub ~ form {
			overflow-x: auto;
		}
	</style>
	<?php
}
add_action( 'admin_head', 'ktd_product_admin_table_styles' );

/**
 * Việt hóa và chuẩn hóa các nhãn tổng kết đơn hàng trong View Order & Checkout
 */
add_filter( 'woocommerce_get_order_item_totals', function( $total_rows, $order, $tax_display ) {
	if ( isset( $total_rows['order_total'] ) ) {
		$total_rows['order_total']['label'] = 'Tổng thanh toán:';
	}
	if ( isset( $total_rows['cart_subtotal'] ) ) {
		$total_rows['cart_subtotal']['label'] = 'Tạm tính:';
	}
	if ( isset( $total_rows['shipping'] ) ) {
		$total_rows['shipping']['label'] = 'Phí giao hàng:';
	}
	if ( isset( $total_rows['payment_method'] ) ) {
		$total_rows['payment_method']['label'] = 'Phương thức thanh toán:';
	}
	return $total_rows;
}, 10, 3 );

/**
 * Đóng bình luận trên toàn bộ các trang tài khoản người dùng
 */
add_filter( 'comments_open', function( $open, $post_id ) {
	if ( function_exists( 'is_account_page' ) && is_account_page() ) {
		return false;
	}
	return $open;
}, 10, 2 );
