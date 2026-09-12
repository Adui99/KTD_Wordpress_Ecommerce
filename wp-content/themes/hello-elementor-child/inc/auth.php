<?php
/**
 * User Authentication & My Account Management
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Redirect /my-account/ base endpoint to /my-account/orders/ for logged-in users
 */
add_action( 'template_redirect', function() {
	if ( function_exists( 'is_account_page' ) && is_account_page() && is_user_logged_in() && ! is_wc_endpoint_url() ) {
		wp_safe_redirect( wc_get_endpoint_url( 'orders' ) );
		exit;
	}
} );

/**
 * 2. Customize My Account Menu Items (3 Horizontal Tabs)
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
 * 3. Minimalist My Account Profile Header HTML
 */
if ( ! function_exists( 'ktd_get_account_hero_html' ) ) :
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
				<h1 class="ktd-auth-page-title">Tài Khoản KTD Store</h1>
				<p class="ktd-auth-page-subtitle">Đăng nhập hoặc đăng ký tài khoản mới để theo dõi đơn hàng và nhận các đặc quyền bảo hành chính hãng.</p>
			</div>
		</div>
		<?php
	}
	return ob_get_clean();
}
endif; // function_exists ktd_get_account_hero_html

/**
 * 4. Filter the_content to prepend the Minimalist Header on My Account page
 */
// Priority 10 (default) là an toàn — tránh xung đột với Elementor/WooCommerce
// chạy filter the_content ở priority 2 có thể gây render sai thứ tự HTML.
add_filter( 'the_content', function( $content ) {
	if ( function_exists( 'is_account_page' ) && is_account_page() && in_the_loop() && is_main_query() ) {
		static $account_hero_rendered = false;
		if ( ! $account_hero_rendered ) {
			$account_hero_rendered = true;
			return ktd_get_account_hero_html() . $content;
		}
	}
	return $content;
}, 10 );

/**
 * 5. Enable Registration & Custom Password on My Account
 *
 * INTENTIONAL OVERRIDE: Các filter pre_option_* bên dưới ghi đè setting trong
 * WooCommerce Admin > Accounts & Privacy một cách CÓ CHỦ Ý.
 * Lý do: Thiết kế UX yêu cầu luôn cho phép đăng ký và mật khẩu tự chọn,
 * bất kể admin có vô tình tắt setting đó không.
 * Nếu muốn thay đổi hành vi này, hãy xóa 2 filter bên dưới và cấu hình
 * trực tiếp trong WooCommerce Admin.
 */
add_filter( 'pre_option_woocommerce_enable_myaccount_registration', function() {
	return 'yes'; // Luôn bật đăng ký — xem comment section 5 ở trên
} );

add_filter( 'pre_option_woocommerce_registration_generate_password', function() {
	return 'no'; // Luôn yêu cầu người dùng tự đặt mật khẩu
} );

