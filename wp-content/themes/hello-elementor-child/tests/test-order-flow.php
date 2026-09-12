<?php
/**
 * Test Suite: End-to-End Shopping & Order Creation Journey
 * Theme: Hello Elementor Child (KTD E-Commerce)
 */

require_once __DIR__ . '/../../../../wp-load.php';

// Instantiate runner if not present
if ( ! class_exists( 'SimpleTestRunner' ) ) {
    class SimpleTestRunner {
        public int $passed = 0;
        public int $failed = 0;
        public array $errors = [];

        public function describe( string $suiteName ): void {
            echo "\n\033[1;36m▶ Suite: {$suiteName}\033[0m\n";
        }

        public function it( string $testName, callable $callback ): void {
            try {
                $callback();
                $this->passed++;
                echo "  \033[32m✔\033[0m {$testName}\n";
            } catch ( Throwable $e ) {
                $this->failed++;
                $this->errors[] = [
                    'name'    => $testName,
                    'message' => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'    => $e->getLine()
                ];
                echo "  \033[31m✖ {$testName}\033[0m\n";
                echo "    \033[90mError: {$e->getMessage()}\033[0m\n";
            }
        }

        public function assertSame( $expected, $actual, string $message = '' ): void {
            if ( $expected !== $actual ) {
                $msg = $message ?: 'Expected ' . var_export( $expected, true ) . ' but got ' . var_export( $actual, true );
                throw new Exception( $msg );
            }
        }

        public function assertTrue( bool $condition, string $message = 'Condition is not true' ): void {
            if ( ! $condition ) {
                throw new Exception( $message );
            }
        }
    }
}

$t = new SimpleTestRunner();

echo "============================================================\n";
echo " KTD E-COMMERCE: E2E ORDER FLOW & VIETQR TDD SUITE\n";
echo "============================================================\n";

if ( null === WC()->cart ) {
    wc_load_cart();
}

$t->describe( '1. Variable Product Selection & Cart Initialization' );

$test_product = null;
$variation_id = 0;

$t->it( 'should find a published variable product in catalog', function() use ( $t, &$test_product, &$variation_id ) {
    $products = wc_get_products( array(
        'status' => 'publish',
        'type'   => 'variable',
        'limit'  => 5,
    ) );

    $t->assertTrue( ! empty( $products ), 'At least one published variable product must exist' );
    $test_product = $products[0];
    $children     = $test_product->get_children();
    $t->assertTrue( ! empty( $children ), 'Variable product must have active variations' );
    $variation_id = $children[0];
} );

$t->it( 'should add variable product to cart and compute subtotal correctly', function() use ( $t, &$test_product, &$variation_id ) {
    WC()->cart->empty_cart();
    $cart_item_key = WC()->cart->add_to_cart( $test_product->get_id(), 1, $variation_id );
    WC()->cart->calculate_totals();

    $t->assertTrue( false !== $cart_item_key, 'Cart item key must be returned' );
    $t->assertSame( 1, WC()->cart->get_cart_contents_count(), 'Cart count must be 1' );
    $t->assertTrue( (float) WC()->cart->get_subtotal() > 0, 'Cart subtotal must be greater than 0' );
} );

$t->describe( '2. Discount Application on Cart (KTD10)' );

$t->it( 'should apply coupon KTD10 and deduct 10% from cart total', function() use ( $t ) {
    $subtotal_before = (float) WC()->cart->get_subtotal();
    
    // Ensure KTD10 exists
    if ( ! wc_get_coupon_id_by_code( 'KTD10' ) ) {
        $coupon = new WC_Coupon();
        $coupon->set_code( 'KTD10' );
        $coupon->set_discount_type( 'percent' );
        $coupon->set_amount( 10 );
        $coupon->save();
    }

    $applied = WC()->cart->apply_coupon( 'KTD10' );
    WC()->cart->calculate_totals();

    $t->assertTrue( WC()->cart->has_discount( 'KTD10' ), 'Cart must contain coupon KTD10' );
    $discount_total = (float) WC()->cart->get_discount_total();
    $expected_discount = round( $subtotal_before * 0.10 );
    
    $t->assertTrue( abs( $discount_total - $expected_discount ) <= 1, 'Discount total must equal 10% of subtotal' );
} );

$t->describe( '3. Order Creation & Database Record Integrity' );

$created_order = null;

$t->it( 'should create real WooCommerce order with BACS payment and custom billing details', function() use ( $t, &$created_order ) {
    $order = wc_create_order();
    $t->assertTrue( $order instanceof WC_Order, 'Created object must be instance of WC_Order' );
    $t->assertTrue( $order->get_id() > 0, 'Order ID must be positive integer' );

    // Transfer cart items
    foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
        $item_id = $order->add_product(
            $values['data'],
            $values['quantity'],
            array(
                'variation' => $values['variation'],
                'subtotal'  => $values['line_subtotal'],
                'total'     => $values['line_total'],
            )
        );
        $t->assertTrue( $item_id > 0, 'Line item must be added successfully' );
    }

    // Transfer coupons
    foreach ( WC()->cart->get_applied_coupons() as $coupon_code ) {
        $order->apply_coupon( $coupon_code );
    }

    // Transfer shipping if present in cart
    if ( WC()->cart->get_shipping_total() > 0 ) {
        $shipping_item = new WC_Order_Item_Shipping();
        $shipping_item->set_method_title( 'Giao hàng tiêu chuẩn' );
        $shipping_item->set_method_id( 'flat_rate' );
        $shipping_item->set_total( WC()->cart->get_shipping_total() );
        $order->add_item( $shipping_item );
    }

    // Set Vietnamese billing info
    $order->set_billing_first_name( 'Nguyễn' );
    $order->set_billing_last_name( 'Văn A' );
    $order->set_billing_phone( '0988888888' );
    $order->set_billing_email( 'test-checkout@ktdstore.vn' );
    $order->set_billing_address_1( '72 Lê Thánh Tôn, Bến Nghé' );
    $order->set_billing_city( 'Hồ Chí Minh' );
    $order->set_billing_country( 'VN' );
    $order->set_payment_method( 'bacs' );
    $order->set_payment_method_title( 'Chuyển khoản VietQR Napas247' );

    $order->calculate_totals();
    $order->set_status( 'pending', 'Khởi tạo đơn hàng kiểm thử TDD KTD-Ecommerce' );
    $order->save();

    $cart_total  = (float) WC()->cart->get_total( 'edit' );
    $order_total = (float) $order->get_total();

    $t->assertTrue( abs( $cart_total - $order_total ) < 1.0, "Order total ({$order_total}) must match cart total ({$cart_total})" );

    $created_order = $order;
} );

$t->it( 'should retrieve order from database and verify persistence', function() use ( $t, &$created_order ) {
    $db_order = wc_get_order( $created_order->get_id() );
    $t->assertTrue( false !== $db_order, 'Order must be retrievable from database' );
    $t->assertSame( 'Nguyễn', $db_order->get_billing_first_name(), 'First name must persist' );
    $t->assertSame( '0988888888', $db_order->get_billing_phone(), 'Phone must persist' );
    $coupon_codes = array_map( 'strtolower', $db_order->get_coupon_codes() );
    $t->assertTrue( in_array( 'ktd10', $coupon_codes, true ), 'Order must retain KTD10 coupon' );
} );

$t->describe( '4. Dynamic VietQR Napas247 Payload Verification' );

$t->it( 'should generate correct VietQR payload for created order', function() use ( $t, &$created_order ) {
    $order_id = $created_order->get_id();
    $amount   = (float) $created_order->get_total();

    $qr = ktd_get_vietqr_data( $order_id, $amount );

    $t->assertSame( 'VCB', $qr['bank_id'], 'Bank ID must be VCB' );
    $t->assertSame( '999988886666', $qr['account_no'], 'Account number must be 999988886666' );
    $t->assertSame( 'KTD STORE', $qr['account_name'], 'Beneficiary name must be KTD STORE' );
    $t->assertSame( (int) round( $amount ), $qr['amount'], 'Amount must match rounded order total' );
    $t->assertSame( "KTD {$order_id}", $qr['memo'], 'Transfer memo must be KTD {order_id}' );
    $t->assertTrue( strpos( $qr['qr_image_url'], 'https://img.vietqr.io/image/VCB-999988886666-compact2.png' ) === 0, 'Image URL must use VietQR compact2 format' );
    $t->assertTrue( strpos( $qr['qr_image_url'], "amount=" . (int) round( $amount ) ) !== false, 'URL must encode exact amount' );
    $t->assertTrue( strpos( $qr['qr_image_url'], "addInfo=KTD%20{$order_id}" ) !== false, 'URL must encode transfer memo' );
} );

$t->describe( '5. Teardown & Environment Cleanup' );

$t->it( 'should permanently delete test order and clean up cart without leaving orphan records', function() use ( $t, &$created_order ) {
    if ( $created_order instanceof WC_Order ) {
        $order_id = $created_order->get_id();
        $created_order->delete( true );
        $deleted_check = wc_get_order( $order_id );
        $t->assertTrue( false === $deleted_check, 'Order must be permanently deleted from database' );
    }

    WC()->cart->empty_cart();
    $t->assertSame( 0, WC()->cart->get_cart_contents_count(), 'Cart must be emptied' );
} );

echo "\n============================================================\n";
echo "E2E Order Flow Results: {$t->passed} passed, {$t->failed} failed\n";
echo "============================================================\n";

if ( $t->failed > 0 ) {
    exit( 1 );
}
exit( 0 );
