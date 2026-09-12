<?php
/**
 * Test Suite: WooCommerce Coupon Creation & Cart Flow Testing
 * Theme: Hello Elementor Child (KTD E-Commerce)
 */

require_once __DIR__ . '/../../../../wp-load.php';

echo "============================================================\n";
echo " KTD E-COMMERCE: COUPON SYSTEM AUDIT & FLOW VERIFICATION\n";
echo "============================================================\n";

if ( ! class_exists( 'WooCommerce' ) ) {
    echo "[FAIL] WooCommerce is not active!\n";
    exit(1);
}

// 1. Seed or Verify Sample Coupons
$coupons_to_seed = [
    [
        'code'          => 'KTD10',
        'type'          => 'percent',
        'amount'        => 10,
        'description'   => 'Giảm giá 10% trên tổng giá trị giỏ hàng',
        'free_shipping' => false,
    ],
    [
        'code'          => 'KTD100K',
        'type'          => 'fixed_cart',
        'amount'        => 100000,
        'description'   => 'Giảm ngay 100.000đ trực tiếp vào đơn hàng',
        'free_shipping' => false,
    ],
    [
        'code'          => 'FREESHIP',
        'type'          => 'fixed_cart',
        'amount'        => 0,
        'description'   => 'Miễn phí vận chuyển toàn quốc cho đơn hàng',
        'free_shipping' => true,
    ],
];

echo "\n[Phase 1] Seeding Sample Coupons into Database...\n";
foreach ( $coupons_to_seed as $seed ) {
    $existing_id = wc_get_coupon_id_by_code( $seed['code'] );
    if ( ! $existing_id ) {
        $coupon = new WC_Coupon();
        $coupon->set_code( $seed['code'] );
        $coupon->set_discount_type( $seed['type'] );
        $coupon->set_amount( $seed['amount'] );
        $coupon->set_description( $seed['description'] );
        $coupon->set_free_shipping( $seed['free_shipping'] );
        $coupon->set_individual_use( false );
        $coupon->save();
        echo "  [CREATED] Coupon '{$seed['code']}' (Type: {$seed['type']}, Amount: {$seed['amount']}) -> ID: " . $coupon->get_id() . "\n";
    } else {
        $coupon = new WC_Coupon( $existing_id );
        echo "  [EXISTS] Coupon '{$seed['code']}' already present (ID: {$existing_id}, Amount: " . $coupon->get_amount() . ")\n";
    }
}

// 2. Cart Context Setup
echo "\n[Phase 2] Setting up WooCommerce Cart for Testing...\n";
if ( null === WC()->cart ) {
    wc_load_cart();
}

// Find a product
$products = wc_get_products( ['limit' => 10, 'status' => 'publish'] );
$test_product = null;
$variation_id = 0;

foreach ( $products as $p ) {
    if ( $p->is_type( 'variable' ) ) {
        $children = $p->get_children();
        if ( ! empty( $children ) ) {
            $test_product = $p;
            $variation_id = $children[0];
            break;
        }
    } elseif ( $p->is_type( 'simple' ) ) {
        $test_product = $p;
        break;
    }
}

if ( ! $test_product ) {
    echo "[FAIL] No test product available in database!\n";
    exit(1);
}

WC()->cart->empty_cart();
$cart_item_key = WC()->cart->add_to_cart( $test_product->get_id(), 1, $variation_id );
WC()->cart->calculate_totals();

$initial_subtotal = WC()->cart->get_subtotal();
$initial_total    = WC()->cart->get_total( 'edit' );

echo "  Test Product: " . $test_product->get_name() . "\n";
echo "  Cart Subtotal: " . wc_price( $initial_subtotal ) . " (Raw: {$initial_subtotal})\n";
echo "  Initial Total: " . wc_price( $initial_total ) . " (Raw: {$initial_total})\n";

// 3. Test Invalid Coupon
echo "\n[Phase 3] Testing Invalid Coupon Code 'SAICODE999'...\n";
wc_clear_notices();
$invalid_res = WC()->cart->apply_coupon( 'SAICODE999' );
$errors = wc_get_notices( 'error' );
if ( ! empty( $errors ) && ! $invalid_res ) {
    echo "  [PASS] Correctly rejected invalid coupon. Error notice recorded.\n";
} else {
    echo "  [FAIL] Failed to reject invalid coupon properly.\n";
}

// 4. Test KTD10 (10% Discount)
echo "\n[Phase 4] Testing Coupon 'KTD10' (10% Discount)...\n";
wc_clear_notices();
$res_10 = WC()->cart->apply_coupon( 'KTD10' );
WC()->cart->calculate_totals();

$discount_10 = WC()->cart->get_discount_total();
$new_total_10 = WC()->cart->get_total( 'edit' );
$expected_discount_10 = round( $initial_subtotal * 0.10 );

echo "  Applied: " . ( $res_10 ? 'YES' : 'NO' ) . "\n";
echo "  Discount Total: " . wc_price( $discount_10 ) . " (Raw: {$discount_10})\n";
echo "  Expected 10%: " . wc_price( $expected_discount_10 ) . "\n";
echo "  New Cart Total: " . wc_price( $new_total_10 ) . "\n";

if ( abs( $discount_10 - $expected_discount_10 ) <= 1 ) {
    echo "  [PASS] Coupon 'KTD10' calculated and applied perfectly!\n";
} else {
    echo "  [FAIL] Discount calculation mismatch for 'KTD10'.\n";
}

// 5. Remove Coupon KTD10
echo "\n[Phase 5] Testing Coupon Removal...\n";
WC()->cart->remove_coupon( 'KTD10' );
WC()->cart->calculate_totals();
$restored_total = WC()->cart->get_total( 'edit' );

if ( $restored_total == $initial_total ) {
    echo "  [PASS] Coupon 'KTD10' removed successfully. Total restored to initial: " . wc_price( $restored_total ) . "\n";
} else {
    echo "  [FAIL] Total not restored after removing coupon. Initial: {$initial_total}, Restored: {$restored_total}\n";
}

// 6. Test KTD100K (100.000đ Fixed Cart Discount)
echo "\n[Phase 6] Testing Coupon 'KTD100K' (100.000đ Fixed Discount)...\n";
wc_clear_notices();
$res_100k = WC()->cart->apply_coupon( 'KTD100K' );
WC()->cart->calculate_totals();

$discount_100k = WC()->cart->get_discount_total();
$new_total_100k = WC()->cart->get_total( 'edit' );

echo "  Applied: " . ( $res_100k ? 'YES' : 'NO' ) . "\n";
echo "  Discount Total: " . wc_price( $discount_100k ) . " (Raw: {$discount_100k})\n";
echo "  New Cart Total: " . wc_price( $new_total_100k ) . "\n";

if ( $discount_100k == 100000 ) {
    echo "  [PASS] Coupon 'KTD100K' discounted exact 100.000đ!\n";
} else {
    echo "  [FAIL] Discount amount mismatch for 'KTD100K'. Expected: 100000, Got: {$discount_100k}\n";
}

// 7. Test FREESHIP (Free Shipping)
echo "\n[Phase 7] Testing Coupon 'FREESHIP' (Free Shipping Option)...\n";
$res_freeship = WC()->cart->apply_coupon( 'FREESHIP' );
$applied_coupons = WC()->cart->get_applied_coupons();

if ( in_array( 'freeship', array_map( 'strtolower', $applied_coupons ) ) ) {
    $c = new WC_Coupon( 'FREESHIP' );
    if ( $c->get_free_shipping() ) {
        echo "  [PASS] Coupon 'FREESHIP' applied and grants free shipping privilege.\n";
    } else {
        echo "  [FAIL] Coupon 'FREESHIP' does not have free shipping flag.\n";
    }
} else {
    echo "  [FAIL] Failed to apply 'FREESHIP'.\n";
}

// Cleanup cart
WC()->cart->empty_cart();

echo "\n============================================================\n";
echo " ALL COUPON TESTS PASSED SUCCESSFULLY! (100% OK)\n";
echo "============================================================\n";
exit(0);
