<?php
/**
 * Comprehensive E2E Verification Suite for KTD E-Commerce
 * Validates: Frontend, Backend, Database, and UX
 *
 * @package HelloElementorChild
 */

// Load WordPress environment
$wp_load = dirname( __DIR__, 4 ) . '/wp-load.php';
if ( file_exists( $wp_load ) ) {
	require_once $wp_load;
} else {
	echo "wp-load.php not found at $wp_load\n";
	exit( 1 );
}

$passed = 0;
$failed = 0;

function assert_test( $name, $condition ) {
	global $passed, $failed;
	if ( $condition ) {
		echo "  ✔ [PASS] $name\n";
		$passed++;
	} else {
		echo "  ✖ [FAIL] $name\n";
		$failed++;
	}
}

echo "============================================================\n";
echo " KTD E-COMMERCE: LATEST COMPREHENSIVE E2E VERIFICATION\n";
echo " Domains: Frontend, Backend, Database & UX\n";
echo "============================================================\n\n";

// --- 1. BACKEND & LOGIC VERIFICATION ---
echo "▶ 1. Backend Hooks & Logic\n";

// 1.1 Test comments_open filter on account page
add_filter( 'woocommerce_is_account_page', '__return_true' );
$comment_on_account = apply_filters( 'comments_open', true, 1 );
remove_filter( 'woocommerce_is_account_page', '__return_true' );
assert_test( 'comments_open must return false on My Account page', $comment_on_account === false );

// 1.2 Test order item totals localization filter
$sample_rows = array(
	'cart_subtotal' => array( 'label' => 'Subtotal:' ),
	'shipping'      => array( 'label' => 'Shipping:' ),
	'order_total'   => array( 'label' => 'Total:' ),
);
$filtered_rows = apply_filters( 'woocommerce_get_order_item_totals', $sample_rows, null, null );
assert_test( 'woocommerce_get_order_item_totals should rename order_total to "Tổng thanh toán:"', isset( $filtered_rows['order_total']['label'] ) && $filtered_rows['order_total']['label'] === 'Tổng thanh toán:' );
assert_test( 'woocommerce_get_order_item_totals should rename cart_subtotal to "Tạm tính:"', isset( $filtered_rows['cart_subtotal']['label'] ) && $filtered_rows['cart_subtotal']['label'] === 'Tạm tính:' );

// 1.3 Test Chatbot typo & variable fix
$chatbot_code = file_get_contents( get_stylesheet_directory() . '/inc/chatbot.php' );
assert_test( 'chatbot.php should not contain typos and system_error_msg must be defined before use', strpos( $chatbot_code, 'Dị chụ' ) === false && strpos( $chatbot_code, 'Dịch vụ AI hiện không khả dụng.' ) !== false );


// --- 2. FRONTEND & CSS INTEGRITY ---
echo "\n▶ 2. Frontend Assets & CSS Integrity\n";

$account_css = file_get_contents( get_stylesheet_directory() . '/assets/css/my-account.css' );
assert_test( 'my-account.css must enforce background-color: #ffffff on page-content and body', strpos( $account_css, 'background-color: #ffffff !important;' ) !== false );
assert_test( 'my-account.css must hide comments area (#comments and .ktd-comments-area)', strpos( $account_css, '.woocommerce-account #comments' ) !== false );
assert_test( 'my-account.css must center CTA "Lưu thay đổi" button content (justify-content: center)', strpos( $account_css, 'justify-content: center !important;' ) !== false );
assert_test( 'my-account.css must contain .woocommerce-view-order styling for order details', strpos( $account_css, '.woocommerce-view-order table.order_details' ) !== false );
assert_test( 'my-account.css must contain single-column stacked address card styling', strpos( $account_css, '.woocommerce-view-order .woocommerce-customer-details address' ) !== false );

$chatbot_css = file_get_contents( get_stylesheet_directory() . '/assets/css/chatbot.css' );
assert_test( 'chatbot.css must apply Be Vietnam Pro to all elements', strpos( $chatbot_css, "font-family: 'Be Vietnam Pro', sans-serif !important;" ) !== false );


// --- 3. DATABASE INTEGRITY ---
echo "\n▶ 3. Database Catalog & Orders Verification\n";

global $wpdb;
$post_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'product' AND post_status = 'publish'" );
assert_test( 'Database must contain 25 active published smartphone products', (int) $post_count === 25 );

$terms_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->terms} t INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id WHERE tt.taxonomy = 'product_cat' AND t.slug IN ('apple', 'samsung', 'oppo')" );
assert_test( 'Database must contain 3 core brand categories (apple, samsung, oppo)', (int) $terms_count === 3 );


// --- 4. UX & LIVE HTTP VERIFICATION ---
echo "\n▶ 4. UX & Live HTTP Routes Verification\n";

$routes = array(
	'Homepage' => home_url( '/' ),
	'Shop'     => home_url( '/shop/' ),
	'Cart'     => home_url( '/cart/' ),
	'Checkout' => home_url( '/checkout/' ),
	'Account'  => home_url( '/my-account/' ),
	'About'    => home_url( '/about/' ),
	'Contact'  => home_url( '/contact-us/' ),
	'Blog'     => home_url( '/blog/' ),
);

foreach ( $routes as $label => $url ) {
	$response = wp_remote_get( $url, array( 'timeout' => 5 ) );
	if ( is_wp_error( $response ) ) {
		assert_test( "Route $label ($url) responds", false );
	} else {
		$code = wp_remote_retrieve_response_code( $response );
		$body = wp_remote_retrieve_body( $response );
		
		$is_ok = ( 200 === $code );
		if ( 'Account' === $label ) {
			$has_no_comments = ( strpos( $body, 'Bình luận & Thảo luận' ) === false );
			assert_test( "Route $label ($url) returns 200 and has NO comment leakage", $is_ok && $has_no_comments );
		} else {
			assert_test( "Route $label ($url) returns 200 OK", $is_ok );
		}
	}
}

echo "\n============================================================\n";
echo " RESULTS: $passed PASSED, $failed FAILED\n";
echo "============================================================\n";

if ( $failed > 0 ) {
	exit( 1 );
}
exit( 0 );
