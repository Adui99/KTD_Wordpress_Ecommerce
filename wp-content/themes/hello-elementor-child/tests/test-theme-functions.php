<?php
/**
 * Automated Unit Test Suite for Hello Elementor Child (PHP Backend)
 * 
 * Run command:
 * & "C:\Users\Administrator\AppData\Roaming\Local\lightning-services\php-8.2.29+0\bin\win32\php.exe" "c:\Users\Administrator\Local Sites\ktd-ecommerce\app\public\wp-content\themes\hello-elementor-child\tests\test-theme-functions.php"
 */

// 1. Simple Test Harness
class SimpleTestRunner {
    private int $passed = 0;
    private int $failed = 0;
    private array $errors = [];
    private float $startTime;

    public function __construct() {
        $this->startTime = microtime(true);
    }

    public function describe(string $suiteName): void {
        echo "\n\033[1;36m▶ Suite: {$suiteName}\033[0m\n";
    }

    public function it(string $testName, callable $callback): void {
        try {
            $callback();
            $this->passed++;
            echo "  \033[32m✔\033[0m {$testName}\n";
        } catch (Throwable $e) {
            $this->failed++;
            $this->errors[] = [
                'name' => $testName,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ];
            echo "  \033[31m✖ {$testName}\033[0m\n";
            echo "    \033[90mError: {$e->getMessage()}\033[0m\n";
        }
    }

    public function assertSame($expected, $actual, string $message = ''): void {
        if ($expected !== $actual) {
            $msg = $message ?: 'Expected ' . var_export($expected, true) . ' but got ' . var_export($actual, true);
            throw new Exception($msg);
        }
    }

    public function assertEquals($expected, $actual, string $message = ''): void {
        if ($expected != $actual) {
            $msg = $message ?: 'Expected ' . var_export($expected, true) . ' == ' . var_export($actual, true);
            throw new Exception($msg);
        }
    }

    public function assertTrue(bool $condition, string $message = 'Condition is not true'): void {
        if (!$condition) {
            throw new Exception($message);
        }
    }

    public function assertFalse(bool $condition, string $message = 'Condition is not false'): void {
        if ($condition) {
            throw new Exception($message);
        }
    }

    public function assertContains($needle, array $haystack, string $message = ''): void {
        if (!in_array($needle, $haystack, true)) {
            $msg = $message ?: "Expected array to contain " . var_export($needle, true);
            throw new Exception($msg);
        }
    }

    public function assertArrayHasKey(string $key, array $array, string $message = ''): void {
        if (!array_key_exists($key, $array)) {
            $msg = $message ?: "Expected array to have key '{$key}'";
            throw new Exception($msg);
        }
    }

    public function report(): int {
        $elapsed = round((microtime(true) - $this->startTime) * 1000, 2);
        echo "\n" . str_repeat('=', 60) . "\n";
        echo "Test Results: \033[32m{$this->passed} passed\033[0m, ";
        if ($this->failed > 0) {
            echo "\033[31m{$this->failed} failed\033[0m";
        } else {
            echo "0 failed";
        }
        echo " ({$elapsed} ms)\n";
        echo str_repeat('=', 60) . "\n";

        return $this->failed === 0 ? 0 : 1;
    }
}

// 2. Mock WordPress & WooCommerce Environment
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/../');
}
if (!defined('HOUR_IN_SECONDS')) {
    define('HOUR_IN_SECONDS', 3600);
}

// Global mocks store
$GLOBALS['mock_transients'] = [];
$GLOBALS['mock_filters'] = [];
$GLOBALS['mock_actions'] = [];

function esc_html($text) {
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}

function esc_attr($text) {
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}

function sanitize_title($title) {
    return strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '-', (string)$title));
}

function taxonomy_exists($tax) {
    return false;
}

function wc_price($price) {
    return number_format((float)$price, 0, ',', '.') . ' ₫';
}

function _n($single, $plural, $number, $domain = 'default') {
    return $number === 1 ? $single : $plural;
}

function wc_get_cart_url() {
    return 'http://ktd-ecommerce.local/cart/';
}

function esc_url($url) {
    return filter_var($url, FILTER_SANITIZE_URL) ?: $url;
}

function home_url($path = '') {
    return 'http://ktd-ecommerce.local' . ($path ? '/' . ltrim($path, '/') : '');
}

function get_transient($key) {
    return $GLOBALS['mock_transients'][$key] ?? false;
}

function set_transient($key, $value, $expiration = 0) {
    $GLOBALS['mock_transients'][$key] = $value;
    return true;
}

function add_action($tag, $callback, $priority = 10, $accepted_args = 1) {
    $GLOBALS['mock_actions'][$tag][] = $callback;
}

function remove_action($tag, $callback, $priority = 10) {
    return true;
}

function add_filter($tag, $callback, $priority = 10, $accepted_args = 1) {
    $GLOBALS['mock_filters'][$tag][] = $callback;
}

function remove_filter($tag, $callback, $priority = 10) {
    return true;
}

function get_stylesheet_directory() {
    return realpath(__DIR__ . '/..');
}

if (!function_exists('is_admin')) {
    function is_admin() {
        return false;
    }
}

if (!function_exists('admin_url')) {
    function admin_url($path = '') {
        return 'http://ktd-ecommerce.local/wp-admin/' . $path;
    }
}

if (!function_exists('wp_create_nonce')) {
    function wp_create_nonce($action = -1) {
        return 'mock_nonce_' . $action;
    }
}

if (!function_exists('wp_json_encode')) {
    function wp_json_encode($data, $options = 0, $depth = 512) {
        return json_encode($data, $options, $depth);
    }
}

// Mock WC() Cart
class MockWCCart {
    private int $count;
    public function __construct(int $count = 0) {
        $this->count = $count;
    }
    public function get_cart_contents_count(): int {
        return $this->count;
    }
}

class MockWC {
    public ?MockWCCart $cart = null;
    public function __construct(?MockWCCart $cart = null) {
        $this->cart = $cart;
    }
}

$GLOBALS['mock_wc_instance'] = null;
function WC() {
    return $GLOBALS['mock_wc_instance'];
}

// Mock $wpdb
class MockWPDB {
    public string $postmeta = 'wp_postmeta';
    public string $posts = 'wp_posts';
    public ?object $mock_row = null;

    public function prepare(string $query, ...$args): string {
        return vsprintf(str_replace('%s', "'%s'", $query), $args);
    }

    public function get_row(string $query): ?object {
        return $this->mock_row;
    }
}
$GLOBALS['wpdb'] = new MockWPDB();

// Mock WC_Product_Attribute
if (!class_exists('WC_Product_Attribute')) {
    class WC_Product_Attribute {
        private string $name;
        private array $options;
        private bool $is_taxonomy;
        public function __construct(string $name = '', array $options = [], bool $is_taxonomy = false) {
            $this->name = $name;
            $this->options = $options;
            $this->is_taxonomy = $is_taxonomy;
        }
        public function get_name(): string { return $this->name; }
        public function get_options(): array { return $this->options; }
        public function is_taxonomy(): bool { return $this->is_taxonomy; }
    }
}

// Mock WC_Product
class MockWCProduct {
    private bool $in_stock;
    private ?int $stock_qty;
    private array $attributes;
    private array $variations;
    private string $type;
    public function __construct(bool $in_stock = true, ?int $stock_qty = null, array $attributes = [], array $variations = [], string $type = 'simple') {
        $this->in_stock = $in_stock;
        $this->stock_qty = $stock_qty;
        $this->attributes = $attributes;
        $this->variations = $variations;
        $this->type = $type;
    }
    public function is_in_stock(): bool {
        return $this->in_stock;
    }
    public function get_stock_quantity(): ?int {
        return $this->stock_qty;
    }
    public function get_attributes(): array {
        return $this->attributes;
    }
    public function get_available_variations(): array {
        return $this->variations;
    }
    public function is_type(string $type): bool {
        return $this->type === $type;
    }
}

if (!function_exists('get_comments_number')) {
    function get_comments_number($post_id = 0) {
        return 3;
    }
}

// 3. Load target functions from functions.php
require_once __DIR__ . '/../functions.php';

// 4. Test Execution
$test = new SimpleTestRunner();

// --- Suite 1: My Account Menu Items ---
$test->describe('ktd_custom_account_menu_items()');

$test->it('should return exactly 3 menu items', function() use ($test) {
    $items = ktd_custom_account_menu_items([
        'dashboard'       => 'Dashboard',
        'orders'          => 'Orders',
        'downloads'       => 'Downloads',
        'edit-address'    => 'Addresses',
        'edit-account'    => 'Account details',
        'customer-logout' => 'Log out'
    ]);
    $test->assertSame(3, count($items));
});

$test->it('should have correct tab keys in orders, edit-account, edit-address order', function() use ($test) {
    $items = ktd_custom_account_menu_items([]);
    $expected_keys = ['orders', 'edit-account', 'edit-address'];
    $test->assertSame($expected_keys, array_keys($items));
});

$test->it('should have Vietnamese labels for all 3 tabs', function() use ($test) {
    $items = ktd_custom_account_menu_items([]);
    $test->assertSame('Đơn hàng của tôi', $items['orders']);
    $test->assertSame('Thông tin & Bảo mật', $items['edit-account']);
    $test->assertSame('Sổ địa chỉ', $items['edit-address']);
});

$test->it('should strictly exclude legacy dashboard, downloads and logout tabs from menu', function() use ($test) {
    $items = ktd_custom_account_menu_items([
        'dashboard' => 'Trang tổng quan',
        'downloads' => 'Tải về'
    ]);
    $test->assertTrue(!array_key_exists('dashboard', $items), 'dashboard should be removed');
    $test->assertTrue(!array_key_exists('downloads', $items), 'downloads should be removed');
    $test->assertTrue(!array_key_exists('customer-logout', $items), 'customer-logout should be handled separately');
});


// --- Suite 2: WooCommerce Cart Fragments ---
$test->describe('ktd_cart_count_fragments()');

$test->it('should update fragment with correct count when cart has items', function() use ($test) {
    $GLOBALS['mock_wc_instance'] = new MockWC(new MockWCCart(5));
    $fragments = ktd_cart_count_fragments([]);
    $test->assertArrayHasKey('span.ktd-cart-count', $fragments);
    $test->assertSame('<span class="ktd-cart-count">5</span>', $fragments['span.ktd-cart-count']);
});

$test->it('should handle empty cart (count = 0)', function() use ($test) {
    $GLOBALS['mock_wc_instance'] = new MockWC(new MockWCCart(0));
    $fragments = ktd_cart_count_fragments(['other-fragment' => '<div></div>']);
    $test->assertSame('<span class="ktd-cart-count">0</span>', $fragments['span.ktd-cart-count']);
    $test->assertArrayHasKey('other-fragment', $fragments);
});

$test->it('should preserve incoming fragments unmodified if cart is unavailable', function() use ($test) {
    $GLOBALS['mock_wc_instance'] = new MockWC(null);
    $fragments = ktd_cart_count_fragments(['test' => 'sample']);
    $test->assertSame(['test' => 'sample'], $fragments);
});


// --- Suite 3: Price Bounds Logic & Rounding ---
$test->describe('ktd_get_shop_price_bounds()');

$test->it('should return cached bounds if transient exists', function() use ($test) {
    $cached = ['min' => 1000000, 'max' => 20000000];
    set_transient('ktd_price_bounds', $cached, HOUR_IN_SECONDS);
    
    $result = ktd_get_shop_price_bounds();
    $test->assertSame($cached, $result);
    // Clear transient for next tests
    $GLOBALS['mock_transients'] = [];
});

$test->it('should floor min price and ceil max price to 500,000 steps', function() use ($test) {
    global $wpdb;
    $wpdb->mock_row = (object)[
        'min_p' => '1250000',  // Should floor to 1,000,000
        'max_p' => '34200000'  // Should ceil to 34,500,000
    ];
    $result = ktd_get_shop_price_bounds();
    $test->assertSame(1000000, $result['min']);
    $test->assertSame(34500000, $result['max']);
    $GLOBALS['mock_transients'] = [];
});

$test->it('should fallback to 0 and 50,000,000 if min >= max (single price in catalog)', function() use ($test) {
    global $wpdb;
    $wpdb->mock_row = (object)[
        'min_p' => '15000000',
        'max_p' => '15000000'
    ];
    $result = ktd_get_shop_price_bounds();
    $test->assertSame(0, $result['min']);
    $test->assertSame(50000000, $result['max']);
    $GLOBALS['mock_transients'] = [];
});

$test->it('should handle null prices gracefully with standard defaults', function() use ($test) {
    global $wpdb;
    $wpdb->mock_row = (object)[
        'min_p' => null,
        'max_p' => null
    ];
    $result = ktd_get_shop_price_bounds();
    $test->assertSame(0, $result['min']);
    $test->assertSame(50000000, $result['max']);
    $GLOBALS['mock_transients'] = [];
});


// --- Suite 4: Single Product Customizations & Localization ---
$test->describe('Single Product Customizations');

$test->it('should customize breadcrumbs with Vietnamese Home label and separator', function() use ($test) {
    $defaults = [
        'home' => 'Home',
        'delimiter' => ' / '
    ];
    $custom = ktd_custom_breadcrumb_defaults($defaults);
    $test->assertSame('Trang chủ', $custom['home']);
    $test->assertSame(' <span class="ktd-bc-sep">/</span> ', $custom['delimiter']);
});

$test->it('should return Vietnamese Add to Cart button label', function() use ($test) {
    $btn_text = ktd_custom_product_single_add_to_cart_text();
    $test->assertSame('Thêm Vào Giỏ Hàng', $btn_text);
});

$test->it('should return Xem chi tiết for variable products in shop loop', function() use ($test) {
    $variable_product = new MockWCProduct(true, 10, [], [], 'variable');
    $btn_text = ktd_custom_product_add_to_cart_text('Select options', $variable_product);
    $test->assertSame('Xem chi tiết', $btn_text);
});

$test->it('should return Thêm vào giỏ for simple products in shop loop', function() use ($test) {
    $simple_product = new MockWCProduct(true, 10, [], [], 'simple');
    $btn_text = ktd_custom_product_add_to_cart_text('Add to cart', $simple_product);
    $test->assertSame('Thêm vào giỏ', $btn_text);
});

$test->it('should format in-stock status with exact quantity when available', function() use ($test) {
    $product = new MockWCProduct(true, 42);
    $availability = ktd_custom_product_availability(['availability' => '42 in stock'], $product);
    $test->assertSame('Còn hàng (42 sản phẩm sẵn sàng)', $availability['availability']);
});

$test->it('should format in-stock status without quantity as ready to deliver', function() use ($test) {
    $product = new MockWCProduct(true, null);
    $availability = ktd_custom_product_availability(['availability' => 'In stock'], $product);
    $test->assertSame('Còn hàng (Sẵn sàng giao)', $availability['availability']);
});

$test->it('should format out-of-stock status gracefully', function() use ($test) {
    $product = new MockWCProduct(false, 0);
    $availability = ktd_custom_product_availability(['availability' => 'Out of stock'], $product);
    $test->assertSame('Tạm hết hàng', $availability['availability']);
});

$test->it('should localize product tabs into clear Vietnamese titles', function() use ($test) {
    $tabs = [
        'description'            => ['title' => 'Description'],
        'additional_information' => ['title' => 'Additional information'],
        'reviews'                => ['title' => 'Reviews (0)']
    ];
    $localized = ktd_custom_product_tabs($tabs);
    $test->assertSame('Mô tả chi tiết', $localized['description']['title']);
    $test->assertSame('Thông số kỹ thuật', $localized['additional_information']['title']);
    $test->assertSame('Đánh giá & Nhận xét (3)', $localized['reviews']['title']);
});

$test->it('should return Vietnamese heading for related products', function() use ($test) {
    $heading = ktd_custom_related_products_heading();
    $test->assertSame('Sản phẩm tương tự', $heading);
});

// --- Suite 5: Product Specifications Extraction (ktd_get_product_specs) ---
$test->describe('ktd_get_product_specs()');

$test->it('should return empty array when product is null or invalid', function() use ($test) {
    $specs = ktd_get_product_specs(null);
    $test->assertSame([], $specs);
});

$test->it('should extract structured key-value specs from product attributes', function() use ($test) {
    $attr1 = new WC_Product_Attribute('Màn hình', ['6.9 inch OLED 120Hz']);
    $attr2 = new WC_Product_Attribute('Chipset', ['Apple A19 Pro 2nm']);
    $product = new MockWCProduct(true, 10, [$attr1, $attr2]);
    $specs = ktd_get_product_specs($product);
    $test->assertSame('6.9 inch OLED 120Hz', $specs['Màn hình']);
    $test->assertSame('Apple A19 Pro 2nm', $specs['Chipset']);
});

$test->it('should filter out pa_dung-luong and pa_mau-sac variation attributes from hardware specs', function() use ($test) {
    $attr1 = new WC_Product_Attribute('pa_dung-luong', ['256GB', '512GB']);
    $attr2 = new WC_Product_Attribute('pa_mau-sac', ['Titan Sa Mạc', 'Titan Tự Nhiên']);
    $attr3 = new WC_Product_Attribute('RAM', ['12 GB']);
    $product = new MockWCProduct(true, 10, [$attr1, $attr2, $attr3]);
    $specs = ktd_get_product_specs($product);
    $test->assertFalse(isset($specs['pa_dung-luong']), 'pa_dung-luong should be excluded');
    $test->assertFalse(isset($specs['pa_mau-sac']), 'pa_mau-sac should be excluded');
    $test->assertSame('12 GB', $specs['RAM']);
});

$test->it('should extract all 12 full hardware specifications correctly', function() use ($test) {
    $keys = [
        'Màn hình', 'Hệ điều hành', 'Chip xử lý (CPU)', 'Bộ nhớ trong (ROM)',
        'RAM', 'Camera sau', 'Camera trước', 'Pin & Sạc',
        'Chất liệu', 'Cổng kết nối', 'Kháng nước & bụi', 'Trọng lượng & Kích thước'
    ];
    $attrs = [];
    foreach ($keys as $k) {
        $attrs[] = new WC_Product_Attribute($k, ["Value for $k"]);
    }
    $product = new MockWCProduct(true, 10, $attrs);
    $specs = ktd_get_product_specs($product);
    $test->assertSame(12, count($specs));
    $test->assertSame('Value for Màn hình', $specs['Màn hình']);
    $test->assertSame('Value for Pin & Sạc', $specs['Pin & Sạc']);
});

// --- Suite 6: Swatches Dropdown Filter (ktd_custom_variation_dropdown_swatches) ---
$test->describe('ktd_custom_variation_dropdown_swatches()');

$test->it('should return unchanged HTML if options or product is empty', function() use ($test) {
    $raw_html = '<select name="test"><option value="1">1</option></select>';
    $res1 = ktd_custom_variation_dropdown_swatches($raw_html, ['options' => [], 'product' => null]);
    $test->assertSame($raw_html, $res1);
});

$test->it('should render custom swatch pills for options and preserve select HTML', function() use ($test) {
    $raw_html = '<select id="storage" name="attribute_storage"><option value="256gb">256GB</option><option value="512gb">512GB</option></select>';
    $product = new MockWCProduct(true, 10, []);
    $args = [
        'options'   => ['256gb', '512gb'],
        'product'   => $product,
        'attribute' => 'storage',
        'selected'  => '256gb',
        'name'      => 'attribute_storage',
        'id'        => 'storage',
    ];
    $output = ktd_custom_variation_dropdown_swatches($raw_html, $args);
    $test->assertTrue(strpos($output, 'class="ktd-swatch-pills-wrap"') !== false, 'Contains swatches wrapper');
    $test->assertTrue(strpos($output, 'data-value="256gb"') !== false, 'Contains 256GB pill');
    $test->assertTrue(strpos($output, 'active') !== false, 'Selected pill has active class');
    $test->assertTrue(strpos($output, '<select id="storage"') !== false, 'Preserves original select element');
});

$test->it('should display variation price markup when available for an attribute', function() use ($test) {
    $raw_html = '<select id="pa_mau-sac" name="attribute_pa_mau-sac"><option value="titan-sa-mac">Titan Sa Mạc</option></select>';
    $mock_variations = [
        [
            'attributes'    => ['attribute_pa_mau-sac' => 'titan-sa-mac'],
            'display_price' => 31190000,
        ],
    ];
    $product = new MockWCProduct(true, 10, [], $mock_variations);
    $args = [
        'options'   => ['titan-sa-mac'],
        'product'   => $product,
        'attribute' => 'pa_mau-sac',
        'selected'  => 'titan-sa-mac',
        'name'      => 'attribute_pa_mau-sac',
        'id'        => 'pa_mau-sac',
    ];
    $output = ktd_custom_variation_dropdown_swatches($raw_html, $args);
    $test->assertTrue(strpos($output, 'ktd-swatch-price') !== false, 'Renders swatch price container');
    $test->assertTrue(strpos($output, '31.190.000') !== false, 'Formats price with Vietnamese thousands separators');
});

$test->describe('ktd_optimize_product_admin_columns()');

$test->it('should filter out redundant text columns causing table layout break', function() use ($test) {
    $incoming_cols = [
        'cb'                       => '<input type="checkbox" />',
        'thumb'                    => 'Image',
        'name'                     => 'Name',
        'sku'                      => 'SKU',
        'global_unique_id'         => 'GTIN, UPC, EAN, or ISBN',
        'is_in_stock'              => 'Stock',
        'price'                    => 'Price',
        'product_cat'              => 'Categories',
        'product_tag'              => 'Tags',
        'taxonomy-product_brand'   => 'Brands',
        'wpseo-score'              => 'SEO Score',
        'wpseo-score-readability'  => 'Readability',
        'wpseo-title'              => 'SEO Title',
        'wpseo-metadesc'           => 'Meta Description',
        'wpseo-focuskw'            => 'Keyphrase',
        'date'                     => 'Date',
    ];

    $clean_cols = ktd_optimize_product_admin_columns($incoming_cols);

    $test->assertTrue(!isset($clean_cols['wpseo-title']), 'Removes wpseo-title column');
    $test->assertTrue(!isset($clean_cols['wpseo-metadesc']), 'Removes wpseo-metadesc column');
    $test->assertTrue(!isset($clean_cols['wpseo-focuskw']), 'Removes wpseo-focuskw column');
    $test->assertTrue(!isset($clean_cols['global_unique_id']), 'Removes global_unique_id (GTIN) column');
    $test->assertTrue(!isset($clean_cols['product_tag']), 'Removes product_tag column');
    $test->assertTrue(!isset($clean_cols['wpseo-score']), 'Removes Yoast SEO score icon column');
    $test->assertTrue(!isset($clean_cols['wpseo-score-readability']), 'Removes Yoast readability icon column');

    $test->assertTrue(isset($clean_cols['name']), 'Preserves product name column');
    $test->assertTrue(isset($clean_cols['price']), 'Preserves price column');
    $test->assertTrue(isset($clean_cols['sku']), 'Preserves sku column');
    $test->assertTrue(isset($clean_cols['is_in_stock']), 'Preserves stock column');
    $test->assertTrue(isset($clean_cols['taxonomy-product_brand']), 'Preserves brands column');
});

$test->describe('Native AI Chatbot (No Iframe)');

$test->it('should register AJAX endpoints and footer hook', function() use ($test) {
    $test->assertTrue(function_exists('ktd_ajax_dify_chat'), 'ktd_ajax_dify_chat function exists');
    $test->assertTrue(function_exists('ktd_render_native_chatbot'), 'ktd_render_native_chatbot function exists');
    $test->assertContains('ktd_ajax_dify_chat', $GLOBALS['mock_actions']['wp_ajax_ktd_dify_chat'] ?? [], 'Registers logged-in AJAX endpoint');
    $test->assertContains('ktd_ajax_dify_chat', $GLOBALS['mock_actions']['wp_ajax_nopriv_ktd_dify_chat'] ?? [], 'Registers guest AJAX endpoint');
    $test->assertContains('ktd_render_native_chatbot', $GLOBALS['mock_actions']['wp_footer'] ?? [], 'Registers footer widget hook');
});

$test->it('should render custom Native Chatbot HTML without dify iframe', function() use ($test) {
    ob_start();
    ktd_render_native_chatbot();
    $html = ob_get_clean();

    $test->assertTrue(strpos($html, 'id="ktd-chat-launcher"') !== false, 'Renders custom floating launcher button');
    $test->assertTrue(strpos($html, 'id="ktd-chat-window"') !== false, 'Renders custom chat window');
    $test->assertTrue(strpos($html, 'EV — Trợ lý AI KTD Store') !== false, 'Renders EV branding');
    $test->assertTrue(strpos($html, 'placeholder="Hỏi EV bất kỳ điều gì..."') !== false, 'Renders concise elegant placeholder');
    $test->assertTrue(strpos($html, 'border-color: #cbd5e1 !important;') !== false, 'Uses soft gray focus border');
    $test->assertTrue(strpos($html, 'rgba(37, 99, 235, 0.12)') === false, 'Strictly eliminates harsh blue focus ring');
    $test->assertTrue(strpos($html, 'ktd-quick-chip') === false, 'Strictly eliminates quick chips to maximize message space');
    $test->assertTrue(strpos($html, 'udify.app/embed.min.js') === false, 'Strictly eliminates Dify embed iframe script');
    $test->assertTrue(strpos($html, 'POWERED BY Dify') === false, 'Strictly eliminates Dify watermark');
});

// Final Exit Code
exit($test->report());
