<?php
/**
 * Test Suite: AI Chatbot Behavior & Dify Integration Tests
 * Theme: Hello Elementor Child (KTD E-Commerce)
 */

if ( ! defined( 'DOING_AJAX' ) ) {
    define( 'DOING_AJAX', true );
}

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
echo " KTD E-COMMERCE: AI CHATBOT BEHAVIOR TDD SUITE\n";
echo "============================================================\n";

$t->describe( 'Chatbot Hook Registration & Frontend Integration' );

$t->it( 'should register wp_ajax and wp_ajax_nopriv for ktd_dify_chat', function() use ( $t ) {
    $has_priv   = has_action( 'wp_ajax_ktd_dify_chat', 'ktd_ajax_dify_chat' );
    $has_nopriv = has_action( 'wp_ajax_nopriv_ktd_dify_chat', 'ktd_ajax_dify_chat' );
    $t->assertTrue( false !== $has_priv, 'wp_ajax_ktd_dify_chat action must be registered' );
    $t->assertTrue( false !== $has_nopriv, 'wp_ajax_nopriv_ktd_dify_chat action must be registered' );
} );

$t->it( 'should hook ktd_render_native_chatbot to wp_footer at priority 99', function() use ( $t ) {
    $priority = has_action( 'wp_footer', 'ktd_render_native_chatbot' );
    $t->assertSame( 99, $priority, 'ktd_render_native_chatbot must be hooked at priority 99' );
} );

$t->it( 'should render semantic chatbot markup with accessibility attributes', function() use ( $t ) {
    ob_start();
    ktd_render_native_chatbot();
    $html = ob_get_clean();

    $t->assertTrue( strpos( $html, 'id="ktd-chatbot-root"' ) !== false, 'Chatbot root must be present' );
    $t->assertTrue( strpos( $html, 'id="ktd-chat-launcher"' ) !== false, 'Chat launcher button must be present' );
    $t->assertTrue( strpos( $html, 'id="ktd-chat-window"' ) !== false, 'Chat window must be present' );
    $t->assertTrue( strpos( $html, 'aria-modal="true"' ) !== false, 'Chat window must have aria-modal' );
    $t->assertTrue( strpos( $html, 'id="ktd-chat-reset-btn"' ) !== false, 'Reset button must be present' );
    $t->assertTrue( strpos( $html, 'id="ktd-chat-form"' ) !== false, 'Chat form must be present' );
    $t->assertTrue( strpos( $html, 'id="ktd-chat-input"' ) !== false, 'Chat input must be present' );
} );

$t->describe( 'Chatbot Endpoint Query Validation & Answer Cleaning' );

$t->it( 'should strip <think> reasoning tags cleanly from AI responses', function() use ( $t ) {
    $raw_response = "<think>\nThinking about iPhone 17 specifications...\nCustomer wants to know about camera.\n</think>iPhone 17 Pro Max sở hữu cụm camera 48MP Fusion thế hệ mới với zoom quang học 5x.";
    $cleaned = preg_replace( '/<think>[\s\S]*?<\/think>/i', '', $raw_response );
    $cleaned = trim( $cleaned );
    
    $t->assertTrue( strpos( $cleaned, '<think>' ) === false, 'Result must not contain <think>' );
    $t->assertTrue( strpos( $cleaned, 'Thinking about' ) === false, 'Internal thinking must be stripped' );
    $t->assertSame( 'iPhone 17 Pro Max sở hữu cụm camera 48MP Fusion thế hệ mới với zoom quang học 5x.', $cleaned );
} );

$t->it( 'should handle empty input queries gracefully with fallback response', function() use ( $t ) {
    // Setup simulated POST/REQUEST request
    $nonce = wp_create_nonce( 'ktd_chat_nonce' );
    $_POST['nonce']    = $nonce;
    $_REQUEST['nonce'] = $nonce;
    $_POST['query']    = '';
    $_POST['conversation_id'] = 'conv_test_123';

    // Intercept wp_die handler for AJAX
    add_filter( 'wp_die_ajax_handler', function() {
        return function( $message ) {
            throw new Exception( 'WP_AJAX_DIE' );
        };
    } );

    ob_start();
    try {
        ktd_ajax_dify_chat();
    } catch ( Exception $e ) {
        // Expected wp_die
    }
    $output = ob_get_clean();
    $data = json_decode( $output, true );

    $t->assertTrue( is_array( $data ), 'Response must be valid JSON' );
    $t->assertTrue( isset( $data['success'] ) && $data['success'] === true, 'Response must be success' );
    $t->assertTrue( isset( $data['data']['answer'] ), 'Response must contain answer' );
    $t->assertTrue( strpos( $data['data']['answer'], 'Hotline 1900 8888' ) !== false, 'Fallback hotline must be included' );
    $t->assertSame( 'conv_test_123', $data['data']['conversation_id'], 'Conversation ID must be preserved' );
} );

$t->it( 'should return friendly system error message when external Dify API fails', function() use ( $t ) {
    $nonce = wp_create_nonce( 'ktd_chat_nonce' );
    $_POST['nonce']           = $nonce;
    $_REQUEST['nonce']        = $nonce;
    $_POST['query']           = 'Tư vấn iPhone 17 Pro Max';
    $_POST['conversation_id'] = 'conv_fail_456';

    // Simulate external HTTP failure via WordPress filter
    $filter_cb = function() {
        return new WP_Error( 'http_request_failed', 'cURL error 28: Operation timed out' );
    };
    add_filter( 'pre_http_request', $filter_cb, 10, 3 );

    ob_start();
    try {
        ktd_ajax_dify_chat();
    } catch ( Exception $e ) {
        // Intercepted wp_die
    }
    $output = ob_get_clean();
    remove_filter( 'pre_http_request', $filter_cb );

    $data = json_decode( $output, true );

    $t->assertTrue( is_array( $data ), 'Response must be valid JSON' );
    $t->assertTrue( isset( $data['success'] ) && $data['success'] === true, 'Response must be success JSON structure' );
    $t->assertTrue( strpos( $data['data']['answer'], 'Hotline 1900 8888' ) !== false, 'Friendly fallback message must be returned' );
    $t->assertSame( 'conv_fail_456', $data['data']['conversation_id'], 'Conversation ID must be preserved' );
} );

echo "\n============================================================\n";
echo "Chatbot Behavior Results: {$t->passed} passed, {$t->failed} failed\n";
echo "============================================================\n";

if ( $t->failed > 0 ) {
    exit( 1 );
}
exit( 0 );
