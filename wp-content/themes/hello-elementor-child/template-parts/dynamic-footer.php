<?php
/**
 * Custom 4-Column E-Commerce Footer for Hello Elementor Child
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$site_name = get_bloginfo( 'name' );
$home_url  = home_url( '/' );
$shop_url  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
?>

<footer id="site-footer" class="site-footer footer-full-width ktd-custom-footer">
	<div class="ktd-footer-container">
		<div class="ktd-footer-grid">
			<!-- Column 1: Brand & Introduction -->
			<div class="ktd-footer-col ktd-footer-brand-col">
				<h3 class="ktd-footer-brand-title"><?php echo esc_html( $site_name ? $site_name : 'KTD Store' ); ?></h3>
				<p class="ktd-footer-desc">
					Khám phá thế giới công nghệ đỉnh cao cùng những siêu phẩm flagship hàng đầu từ Apple, Samsung, OPPO. Đỉnh cao thiết kế, hiệu năng vượt trội - Make your best experience.
				</p>
			</div>

			<!-- Column 2: Quick Links -->
			<div class="ktd-footer-col">
				<h4 class="ktd-footer-title">Khám Phá</h4>
				<ul class="ktd-footer-links">
					<li><a href="<?php echo esc_url( $home_url ); ?>">Trang chủ (Home)</a></li>
					<li><a href="<?php echo esc_url( $shop_url ); ?>">Sản phẩm (Products)</a></li>
					<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">Về chúng tôi (About)</a></li>
					<li><a href="<?php echo esc_url( $shop_url ); ?>">Bộ sưu tập (Gallery)</a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">Liên hệ (Contact)</a></li>
				</ul>
			</div>

			<!-- Column 3: Customer Care & Policies -->
			<div class="ktd-footer-col">
				<h4 class="ktd-footer-title">Chính Sách & Hỗ Trợ</h4>
				<ul class="ktd-footer-links">
					<li><a href="<?php echo esc_url( home_url( '/about/#commitments' ) ); ?>">Bảo hành chính hãng 12 tháng</a></li>
					<li><a href="<?php echo esc_url( home_url( '/about/#commitments' ) ); ?>">Đổi trả miễn phí 30 ngày</a></li>
					<li><a href="<?php echo esc_url( home_url( '/about/#commitments' ) ); ?>">Giao hàng hỏa tốc toàn quốc</a></li>
					<li><a href="<?php echo esc_url( home_url( '/about/#commitments' ) ); ?>">Hướng dẫn trả góp 0%</a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">Chính sách bảo mật thông tin</a></li>
				</ul>
			</div>

			<!-- Column 4: Contact Info & Socials -->
			<div class="ktd-footer-col">
				<h4 class="ktd-footer-title">Thông Tin Liên Hệ</h4>
				<ul class="ktd-footer-contact-info">
					<li class="ktd-footer-contact-item">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
							<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
						</svg>
						<span>Hồ Chí Minh, Việt Nam</span>
					</li>
					<li class="ktd-footer-contact-item">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
							<path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
						</svg>
						<span>Hotline: 1900 8888 (8:00 - 21:30)</span>
					</li>
					<li class="ktd-footer-contact-item">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
							<path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
						</svg>
						<span>support@ktd-store.com</span>
					</li>
				</ul>

				<!-- Social Icons -->
				<div class="ktd-footer-socials">
					<a href="#" class="ktd-social-icon" aria-label="Facebook" title="Facebook">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
							<path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95C18.05 21.45 22 17.19 22 12z"/>
						</svg>
					</a>
					<a href="#" class="ktd-social-icon" aria-label="Instagram" title="Instagram">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
							<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
						</svg>
					</a>
					<a href="#" class="ktd-social-icon" aria-label="YouTube" title="YouTube">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
							<path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
						</svg>
					</a>
				</div>
			</div>
		</div>
	</div>

	<!-- Bottom Copyright -->
	<div class="ktd-footer-bottom">
		<p>© <?php echo esc_html( date( 'Y' ) ); ?> <?php echo esc_html( $site_name ? $site_name : 'KTD-Ecommerce' ); ?>. All rights reserved. Make your best experience.</p>
	</div>
</footer>
