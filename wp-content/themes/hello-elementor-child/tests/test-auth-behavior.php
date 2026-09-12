<?php
/**
 * Test Suite: User Authentication & My Account Behavior Tests
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
echo " KTD E-COMMERCE: AUTH & ACCOUNT BEHAVIOR TDD SUITE\n";
echo "============================================================\n";

$t->describe( 'WooCommerce Authentication Options & Registration Policies' );

$t->it( 'should force-enable registration on My Account page via pre_option filter', function() use ( $t ) {
    $registration_enabled = get_option( 'woocommerce_enable_myaccount_registration' );
    $t->assertSame( 'yes', $registration_enabled, 'woocommerce_enable_myaccount_registration must be "yes"' );
} );

$t->it( 'should allow users to specify their own password during registration', function() use ( $t ) {
    $generate_pw = get_option( 'woocommerce_registration_generate_password' );
    $t->assertSame( 'no', $generate_pw, 'woocommerce_registration_generate_password must be "no"' );
} );

$t->describe( 'My Account Navigation & Ergonomics' );

$t->it( 'should streamline account menu items to exactly 3 essential tabs with Vietnamese labels', function() use ( $t ) {
    $default_items = array(
        'dashboard'       => 'Dashboard',
        'orders'          => 'Orders',
        'downloads'       => 'Downloads',
        'edit-address'    => 'Addresses',
        'payment-methods' => 'Payment methods',
        'edit-account'    => 'Account details',
        'customer-logout' => 'Logout',
    );
    $filtered_items = apply_filters( 'woocommerce_account_menu_items', $default_items );

    $t->assertTrue( is_array( $filtered_items ), 'Filtered items must be array' );
    $t->assertSame( 3, count( $filtered_items ), 'Must only retain exactly 3 tabs' );
    $t->assertTrue( isset( $filtered_items['orders'] ), 'Must contain orders tab' );
    $t->assertTrue( isset( $filtered_items['edit-account'] ), 'Must contain edit-account tab' );
    $t->assertTrue( isset( $filtered_items['edit-address'] ), 'Must contain edit-address tab' );
    $t->assertSame( 'Đơn hàng của tôi', $filtered_items['orders'] );
    $t->assertSame( 'Thông tin & Bảo mật', $filtered_items['edit-account'] );
    $t->assertSame( 'Sổ địa chỉ', $filtered_items['edit-address'] );
    $t->assertTrue( ! isset( $filtered_items['downloads'] ), 'Downloads tab must be removed' );
} );

$t->it( 'should render guest hero header when user is not logged in', function() use ( $t ) {
    // Ensure no user logged in
    wp_set_current_user( 0 );
    $html = ktd_get_account_hero_html();

    $t->assertTrue( strpos( $html, 'ktd-auth-minimal-header' ) !== false, 'Must contain auth minimal header' );
    $t->assertTrue( strpos( $html, 'Tài Khoản KTD Store' ) !== false, 'Must contain page title' );
    $t->assertTrue( strpos( $html, 'ktd-profile-logout-btn' ) === false, 'Must not render logout button for guests' );
} );

$t->it( 'should render personalized greeting and logout button when user is logged in', function() use ( $t ) {
    // Mock user login by picking first administrator/user
    $users = get_users( array( 'number' => 1 ) );
    if ( ! empty( $users ) ) {
        $test_user = $users[0];
        wp_set_current_user( $test_user->ID );

        $html = ktd_get_account_hero_html();

        $t->assertTrue( strpos( $html, 'ktd-account-minimal-header' ) !== false, 'Must contain account minimal header' );
        $t->assertTrue( strpos( $html, 'Xin chào,' ) !== false, 'Must contain greeting prefix' );
        $t->assertTrue( strpos( $html, $test_user->user_email ) !== false, 'Must display user email' );
        $t->assertTrue( strpos( $html, 'ktd-profile-logout-btn' ) !== false, 'Must render logout button for logged-in user' );

        // Reset user
        wp_set_current_user( 0 );
    } else {
        $t->assertTrue( true, 'Skipping mock user verification as no users found' );
    }
} );

echo "\n============================================================\n";
echo "Auth & Account Behavior Results: {$t->passed} passed, {$t->failed} failed\n";
echo "============================================================\n";

if ( $t->failed > 0 ) {
    exit( 1 );
}
exit( 0 );
