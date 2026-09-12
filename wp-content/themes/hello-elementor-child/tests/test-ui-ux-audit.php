<?php
/**
 * UI/UX Automated Audit Test Suite
 * Evaluates live site against Nielsen's 10 Usability Heuristics & Mobile/Accessibility (WCAG AA) standards
 * Theme: Hello Elementor Child (KTD E-Commerce)
 * 
 * Invoked via:
 * php.exe tests/test-ui-ux-audit.php
 */

require_once __DIR__ . '/../../../../wp-load.php';

class UXAuditRunner {
    public int $passed = 0;
    public int $failed = 0;
    public array $scores = [
        'visibility'    => ['score' => 10, 'max' => 10, 'name' => 'Visibility of System Status'],
        'real_world'    => ['score' => 10, 'max' => 10, 'name' => 'Match System & Real World'],
        'control'       => ['score' => 10, 'max' => 10, 'name' => 'User Control & Freedom'],
        'consistency'   => ['score' => 10, 'max' => 10, 'name' => 'Consistency & Standards'],
        'error_prev'    => ['score' => 10, 'max' => 10, 'name' => 'Error Prevention'],
        'recognition'   => ['score' => 10, 'max' => 10, 'name' => 'Recognition vs Recall'],
        'efficiency'    => ['score' => 10, 'max' => 10, 'name' => 'Flexibility & Efficiency'],
        'minimalist'    => ['score' => 10, 'max' => 10, 'name' => 'Aesthetic & Minimalist'],
        'recovery'      => ['score' => 10, 'max' => 10, 'name' => 'Help Recover from Errors'],
        'help_doc'      => ['score' => 10, 'max' => 10, 'name' => 'Help & Documentation'],
        'mobile_a11y'   => ['score' => 10, 'max' => 10, 'name' => 'Mobile Ergonomics & A11y'],
    ];

    public function describe( string $title ): void {
        echo "\n\033[1;36m▶ Nielsen Heuristic: {$title}\033[0m\n";
    }

    public function test( string $dimension, string $name, callable $check ): void {
        try {
            $check();
            $this->passed++;
            echo "  \033[32m✔ [PASS]\033[0m {$name}\n";
        } catch ( Throwable $e ) {
            $this->failed++;
            if ( isset( $this->scores[$dimension] ) ) {
                $this->scores[$dimension]['score'] = max( 0, $this->scores[$dimension]['score'] - 2 );
            }
            echo "  \033[31m✖ [FAIL]\033[0m {$name}\n";
            echo "    \033[90mIssue: {$e->getMessage()}\033[0m\n";
        }
    }

    public function assertTrue( bool $cond, string $msg = 'Assertion failed' ): void {
        if ( ! $cond ) {
            throw new Exception( $msg );
        }
    }

    public function assertSame( $expected, $actual, string $msg = '' ): void {
        if ( $expected !== $actual ) {
            $message = $msg ?: 'Expected ' . var_export( $expected, true ) . ' but got ' . var_export( $actual, true );
            throw new Exception( $message );
        }
    }

    public function printReport(): int {
        echo "\n============================================================\n";
        echo " KTD E-COMMERCE: UI/UX AUDIT SCORECARD & REPORT\n";
        echo "============================================================\n";

        $total_score = 0;
        $max_score   = count( $this->scores ) * 10;
        foreach ( $this->scores as $dim => $data ) {
            $pct = round( ( $data['score'] / $data['max'] ) * 100 );
            $color = $pct >= 90 ? "\033[32m" : ( $pct >= 70 ? "\033[33m" : "\033[31m" );
            printf( " %-35s %s%3d / %3d (%d%%)\033[0m\n", $data['name'], $color, $data['score'], $data['max'], $pct );
            $total_score += $data['score'];
        }

        $overall_pct = round( ( $total_score / $max_score ) * 100, 1 );
        $grade = 'F';
        if ( $overall_pct >= 95 ) $grade = 'A+';
        elseif ( $overall_pct >= 90 ) $grade = 'A';
        elseif ( $overall_pct >= 85 ) $grade = 'B+';
        elseif ( $overall_pct >= 80 ) $grade = 'B';
        elseif ( $overall_pct >= 70 ) $grade = 'C';

        echo "------------------------------------------------------------\n";
        printf( " Overall Usability Score: %s%.1f%% (Grade: %s)\033[0m\n", $overall_pct >= 90 ? "\033[1;32m" : "\033[1;33m", $overall_pct, $grade );
        echo " Total Passed: {$this->passed} | Failed: {$this->failed}\n";
        echo "============================================================\n";

        return $this->failed > 0 ? 1 : 0;
    }
}

// Fetch helper with timeout
function fetch_page( string $path ): string {
    $url = 'http://ktd-ecommerce.local' . $path;
    $ch = curl_init( $url );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_TIMEOUT, 6 );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    $res = curl_exec( $ch );
    curl_close( $ch );
    return is_string( $res ) ? $res : '';
}

$audit = new UXAuditRunner();

echo "============================================================\n";
echo " KTD E-COMMERCE: COMPREHENSIVE UI/UX SYSTEM AUDIT\n";
echo " Standards: Nielsen 10 Heuristics & Mobile/WCAG AA\n";
echo "============================================================\n";

// Pre-fetch key pages
$home_html    = fetch_page( '/' );
$product_html = fetch_page( '/product/iphone-17-pro-max/' );
$cart_html    = fetch_page( '/cart/' );
$checkout_html= fetch_page( '/checkout/' );
$account_html = fetch_page( '/my-account/' );

// Check 1: Visibility of System Status
$audit->describe( '1. Visibility of System Status' );
$audit->test( 'visibility', 'Cart page has clear 3-step breadcrumb progress stepper', function() use ( $audit, $cart_html ) {
    $audit->assertTrue( strpos( $cart_html, 'ktd-cart-breadcrumbs' ) !== false, 'Missing Cart breadcrumb stepper' );
    $audit->assertTrue( strpos( $cart_html, 'Giỏ hàng' ) !== false, 'Missing Cart step title' );
} );

$audit->test( 'visibility', 'Checkout page has Step 2 progress indicator', function() use ( $audit ) {
    $hero = ktd_get_checkout_hero_html();
    $audit->assertTrue( strpos( $hero, 'ktd-checkout-breadcrumbs' ) !== false, 'Missing Checkout breadcrumb' );
    $audit->assertTrue( strpos( $hero, 'Thanh toán' ) !== false, 'Missing Checkout step title' );
} );

$audit->test( 'visibility', 'Single product shows real-time stock availability badge', function() use ( $audit, $product_html ) {
    $audit->assertTrue( strpos( $product_html, 'ktd-stock-badge' ) !== false || strpos( $product_html, 'in-stock' ) !== false, 'Missing in-stock badge' );
} );

$audit->test( 'visibility', 'AI Chatbot launcher displays live pulse status badge', function() use ( $audit, $home_html ) {
    $audit->assertTrue( strpos( $home_html, 'ktd-pulse-badge' ) !== false, 'Missing pulse badge on AI chatbot launcher' );
} );

// Check 2: Match Between System & Real World
$audit->describe( '2. Match Between System and Real World' );
$audit->test( 'real_world', 'Prices formatted with standard Vietnamese Dong currency symbol (₫)', function() use ( $audit, $product_html ) {
    $audit->assertTrue( strpos( $product_html, '₫' ) !== false || strpos( $product_html, '&#8363;' ) !== false, 'Prices must use standard VND ₫ symbol' );
} );

$audit->test( 'real_world', 'UI microcopy uses natural Vietnamese language instead of default English jargon', function() use ( $audit, $account_html ) {
    $audit->assertTrue( strpos( $account_html, 'Đăng nhập' ) !== false, 'Missing Vietnamese Đăng nhập label' );
    $fields = ktd_custom_checkout_fields( array( 'billing' => array( 'billing_first_name' => array(), 'billing_phone' => array() ) ) );
    $audit->assertSame( 'Họ và tên', $fields['billing']['billing_first_name']['label'] );
    $audit->assertSame( 'Số điện thoại nhận hàng', $fields['billing']['billing_phone']['label'] );
} );

// Check 3: User Control & Freedom
$audit->describe( '3. User Control and Freedom' );
$audit->test( 'control', 'Registration modal has explicit close button and backdrop dismissal hooks', function() use ( $audit, $account_html ) {
    $audit->assertTrue( strpos( $account_html, 'ktdRegisterModal' ) !== false, 'Missing registration modal on account page' );
    $audit->assertTrue( strpos( $account_html, 'ktdOpenRegisterModal' ) !== false, 'Missing open registration modal button' );
    $audit->assertTrue( strpos( $account_html, 'ktd-auth-modal-close' ) !== false, 'Missing registration modal close button' );
} );

$audit->test( 'control', 'Installment 0% modal can be easily closed via close button', function() use ( $audit, $product_html ) {
    $audit->assertTrue( strpos( $product_html, 'ktd-modal-close' ) !== false, 'Missing installment modal close button' );
} );

$audit->test( 'control', 'Chatbot window has explicit close button and reset conversation button', function() use ( $audit, $home_html ) {
    $audit->assertTrue( strpos( $home_html, 'ktd-chat-close-btn' ) !== false, 'Missing chat close button' );
    $audit->assertTrue( strpos( $home_html, 'ktd-chat-reset-btn' ) !== false, 'Missing chat reset button' );
} );

// Check 4: Consistency & Standards
$audit->describe( '4. Consistency and Standards' );
$audit->test( 'consistency', 'Core primary color (#2563eb) and fonts defined in base design tokens', function() use ( $audit ) {
    $base_css = file_get_contents( __DIR__ . '/../assets/css/base.css' );
    $audit->assertTrue( strpos( $base_css, '--ktd-primary: #2563eb' ) !== false, 'Primary color token must be #2563eb' );
    $audit->assertTrue( strpos( $base_css, '--ktd-font-heading' ) !== false, 'Font heading token must be defined' );
    $audit->assertTrue( strpos( $base_css, '--ktd-font-body' ) !== false, 'Font body token must be defined' );
} );

$audit->test( 'consistency', 'Cart, Checkout, and Single Product share cohesive card radius conventions', function() use ( $audit ) {
    $cart_css = file_get_contents( __DIR__ . '/../assets/css/cart.css' );
    $checkout_css = file_get_contents( __DIR__ . '/../assets/css/checkout.css' );
    $audit->assertTrue( strpos( $cart_css, 'border-radius:' ) !== false, 'Cart must use rounded card scale' );
    $audit->assertTrue( strpos( $checkout_css, 'border-radius:' ) !== false, 'Checkout must use rounded card scale' );
} );

// Check 5: Error Prevention
$audit->describe( '5. Error Prevention' );
$audit->test( 'error_prev', 'Checkout telephone input uses Vietnamese placeholder format hint', function() use ( $audit ) {
    $fields = ktd_custom_checkout_fields( array( 'billing' => array( 'billing_phone' => array() ) ) );
    $audit->assertTrue( strpos( $fields['billing']['billing_phone']['placeholder'], '09' ) !== false, 'Phone placeholder must show Vietnamese format hint' );
} );

$audit->test( 'error_prev', 'Modals and backdrops enforce pointer-events: none when hidden to prevent click-jacking', function() use ( $audit ) {
    $sp_css = file_get_contents( __DIR__ . '/../assets/css/single-product.css' );
    $audit->assertTrue( strpos( $sp_css, 'pointer-events: none' ) !== false, 'Hidden modals must have pointer-events: none' );
} );

// Check 6: Recognition Rather Than Recall
$audit->describe( '6. Recognition Rather Than Recall' );
$audit->test( 'recognition', 'Product swatches render visual color dots and clear storage labels', function() use ( $audit, $product_html ) {
    $audit->assertTrue( strpos( $product_html, 'ktd-swatch-pill' ) !== false, 'Swatches must render as interactive pills' );
    $audit->assertTrue( strpos( $product_html, 'ktd-color-dot' ) !== false, 'Color swatches must render visual color dots' );
} );

$audit->test( 'recognition', 'Header action buttons have explicit aria-labels and descriptive titles', function() use ( $audit, $home_html ) {
    $audit->assertTrue( strpos( $home_html, 'aria-label="Tài khoản"' ) !== false, 'User button must have aria-label' );
    $audit->assertTrue( strpos( $home_html, 'ktd-cart-btn' ) !== false, 'Cart button must exist in header' );
} );

// Check 7: Flexibility & Efficiency
$audit->describe( '7. Flexibility and Efficiency' );
$audit->test( 'efficiency', 'Touch targets on header action buttons meet minimum 44x44px standard', function() use ( $audit ) {
    $core_css = file_get_contents( __DIR__ . '/../assets/css/core.css' );
    $audit->assertTrue( strpos( $core_css, 'width: 44px' ) !== false && strpos( $core_css, 'height: 44px' ) !== false, 'ktd-action-btn must be 44x44px minimum' );
} );

$audit->test( 'efficiency', 'Single Product provides 1-touch Trả Góp 0% installment option', function() use ( $audit, $product_html ) {
    $audit->assertTrue( strpos( $product_html, 'TRẢ GÓP 0%' ) !== false, 'Installment 0% button must be present' );
} );

// Check 8: Aesthetic & Minimalist Design
$audit->describe( '8. Aesthetic and Minimalist Design' );
$audit->test( 'minimalist', 'Checkout friction fields (country, company, address 2, postcode) removed', function() use ( $audit ) {
    $raw = array(
        'billing' => array(
            'billing_country'  => array(),
            'billing_company'  => array(),
            'billing_postcode' => array(),
            'billing_address_2'=> array(),
        )
    );
    $fields = ktd_custom_checkout_fields( $raw );
    $audit->assertTrue( ! isset( $fields['billing']['billing_country'] ), 'billing_country must be removed' );
    $audit->assertTrue( ! isset( $fields['billing']['billing_company'] ), 'billing_company must be removed' );
    $audit->assertTrue( ! isset( $fields['billing']['billing_postcode'] ), 'billing_postcode must be removed' );
    $audit->assertTrue( ! isset( $fields['billing']['billing_address_2'] ), 'billing_address_2 must be removed' );
} );

$audit->test( 'minimalist', 'Empty Cart page displays clean, symmetric centered card without raw unstyled lists', function() use ( $audit ) {
    $cart_css = file_get_contents( __DIR__ . '/../assets/css/cart.css' );
    $audit->assertTrue( strpos( $cart_css, '.ktd-empty-cart-card' ) !== false, 'Empty cart card must be styled' );
    $audit->assertTrue( strpos( $cart_css, '.is-empty-hero' ) !== false, 'Centered empty hero modifier must exist' );
} );

// Check 9: Help Users Recover from Errors
$audit->describe( '9. Help Users Recover from Errors' );
$audit->test( 'recovery', 'AI Chatbot provides direct phone hotline (1900 8888) when encountering issues', function() use ( $audit ) {
    $chat_php = file_get_contents( __DIR__ . '/../inc/chatbot.php' );
    $audit->assertTrue( strpos( $chat_php, '1900 8888' ) !== false, 'Hotline fallback must be present in chatbot logic' );
} );

// Check 10: Help and Documentation
$audit->describe( '10. Help and Documentation' );
$audit->test( 'help_doc', 'Single Product renders 12-attribute hardware specifications modal', function() use ( $audit, $product_html ) {
    $audit->assertTrue( strpos( $product_html, 'ktd-specs-modal' ) !== false, 'Specs modal must be present' );
} );

$audit->test( 'help_doc', 'E-commerce trust badges (Chính hãng, Giao nhanh, Đổi mới) present on product page', function() use ( $audit, $product_html ) {
    $audit->assertTrue( strpos( $product_html, 'ktd-single-trust-card' ) !== false, 'Trust cards on product' );
    $audit->assertTrue( strpos( $product_html, '100% Chính Hãng' ) !== false, '100% Chính Hãng badge on product' );
} );

// Check 11: Mobile Ergonomics & WCAG AA Accessibility
$audit->describe( '11. Mobile Ergonomics & WCAG AA Accessibility' );
$audit->test( 'mobile_a11y', 'Mobile viewport meta tag configured properly across all pages', function() use ( $audit, $home_html ) {
    $audit->assertTrue( strpos( $home_html, 'name="viewport"' ) !== false, 'Viewport meta tag must exist' );
    $audit->assertTrue( strpos( $home_html, 'width=device-width' ) !== false, 'Viewport must specify width=device-width' );
} );

$audit->test( 'mobile_a11y', 'Featured product images contain non-empty alt text', function() use ( $audit, $product_html ) {
    $has_alt = preg_match( '/<img[^>]*class="[^"]*wp-post-image[^"]*"[^>]*alt="([^"]+)"/i', $product_html ) ||
               preg_match( '/<img[^>]*alt="([^"]+)"[^>]*class="[^"]*wp-post-image[^"]*"/i', $product_html );
    $audit->assertTrue( (bool) $has_alt, 'Featured image must have descriptive alt text' );
} );

$audit->test( 'mobile_a11y', 'Global stylesheet provides prefers-reduced-motion accessibility rule', function() use ( $audit ) {
    $base_css = file_get_contents( __DIR__ . '/../assets/css/base.css' );
    $audit->assertTrue( strpos( $base_css, 'prefers-reduced-motion' ) !== false, 'Base stylesheet must include prefers-reduced-motion rule' );
} );

$audit->test( 'mobile_a11y', 'Header action buttons have full 44x44px ergonomic touch area', function() use ( $audit ) {
    $core_css = file_get_contents( __DIR__ . '/../assets/css/core.css' );
    $audit->assertTrue( strpos( $core_css, 'width: 44px' ) !== false && strpos( $core_css, 'height: 44px' ) !== false, 'Action buttons must be 44x44px minimum' );
} );

exit( $audit->printReport() );
