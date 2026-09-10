<?php
/**
 * Empty cart page template for Hello Elementor Child
 *
 * @package HelloElementorChild
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );

// Brand taxonomy terms
$apple_link   = get_term_link( 'apple', 'product_cat' );
$samsung_link = get_term_link( 'samsung', 'product_cat' );
$oppo_link    = get_term_link( 'oppo', 'product_cat' );

$apple_url   = ! is_wp_error( $apple_link ) ? $apple_link : $shop_url;
$samsung_url = ! is_wp_error( $samsung_link ) ? $samsung_link : $shop_url;
$oppo_url    = ! is_wp_error( $oppo_link ) ? $oppo_link : $shop_url;
?>

<div class="ktd-empty-cart-wrapper">
	<div class="ktd-empty-cart-card">
		<!-- Icon Circle -->
		<div class="ktd-empty-cart-icon-box">
			<div class="ktd-empty-cart-icon-circle">
				<svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
					<circle cx="8" cy="21" r="1"/>
					<circle cx="19" cy="21" r="1"/>
					<path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
					<!-- Soft minus/empty indicator in cart -->
					<line x1="10" y1="9" x2="16" y2="9" stroke-width="2.2" stroke="#94a3b8"/>
				</svg>
			</div>
			<div class="ktd-empty-cart-sparkle"></div>
		</div>

		<!-- Title & Subtitle -->
		<h2 class="ktd-empty-cart-title">Giỏ Hàng Của Bạn Đang Trống</h2>
		<p class="ktd-empty-cart-desc">
			Hiện tại bạn chưa chọn sản phẩm nào. Hãy khám phá bộ sưu tập smartphone flagship chính hãng hàng đầu tại KTD Store với nhiều ưu đãi hấp dẫn ngay hôm nay!
		</p>

		<!-- Primary CTA -->
		<div class="ktd-empty-cart-actions">
			<a href="<?php echo esc_url( $shop_url ); ?>" class="ktd-empty-cart-cta-btn">
				<span>Khám Phá Sản Phẩm Ngay</span>
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
					<line x1="5" y1="12" x2="19" y2="12"/>
					<polyline points="12 5 19 12 12 19"/>
				</svg>
			</a>
		</div>

		<!-- Quick Brand Shortcuts -->
		<div class="ktd-empty-cart-quick-brands">
			<span class="ktd-quick-brands-label">Danh mục nổi bật:</span>
			<div class="ktd-quick-brands-pills">
				<a href="<?php echo esc_url( $apple_url ); ?>" class="ktd-brand-pill">
					<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 6.85c.66-.8 1.11-1.92.99-3.04-1 .04-2.16.66-2.84 1.45-.6.69-1.12 1.83-.98 2.93 1.12.09 2.18-.55 2.83-1.34z"/></svg>
					<span>Apple iPhone</span>
				</a>
				<a href="<?php echo esc_url( $samsung_url ); ?>" class="ktd-brand-pill">
					<span>Samsung Galaxy</span>
				</a>
				<a href="<?php echo esc_url( $oppo_url ); ?>" class="ktd-brand-pill">
					<span>OPPO Flagship</span>
				</a>
			</div>
		</div>

		<!-- Trust Perks Bar -->
		<div class="ktd-empty-cart-trust-bar">
			<div class="ktd-empty-trust-item">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
				<span>100% Chính Hãng Nguyên Seal</span>
			</div>
			<div class="ktd-empty-trust-item">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
				<span>Bảo Hành 1 Đổi 1 Trong 30 Ngày</span>
			</div>
			<div class="ktd-empty-trust-item">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
				<span>Giao Hàng Hỏa Tốc Toàn Quốc</span>
			</div>
			<div class="ktd-empty-trust-item">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
				<span>Hỗ Trợ Trả Góp 0% Lãi Suất</span>
			</div>
		</div>
	</div>
</div>
