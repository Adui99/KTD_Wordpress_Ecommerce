<?php
/**
 * Layer 3: Routes & Frontend Enqueue E2E Health Test Suite
 * 
 * Verifies live HTTP behavior on http://ktd-ecommerce.local:
 * 1. HTTP 200 OK on 8 key routes (Home, Shop, Single Product, Cart, Checkout, My Account, About, Contact).
 * 2. Response latency is fast (< 2500ms).
 * 3. Conditional Stylesheet Enqueue accuracy (no style bleeding).
 * 4. Key UI elements present in the rendered HTML.
 */

class RouteE2ERunner {
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

    public function assertTrue(bool $condition, string $message = 'Condition is not true'): void {
        if (!$condition) {
            throw new Exception($message);
        }
    }

    public function summary(): int {
        $duration = round((microtime(true) - $this->startTime) * 1000, 2);
        echo "\n============================================================\n";
        echo "Route E2E Results: {$this->passed} passed, {$this->failed} failed ({$duration} ms)\n";
        echo "============================================================\n";
        return $this->failed > 0 ? 1 : 0;
    }
}

// Fetch helper using cURL
function fetch_route($path) {
    $url = 'http://ktd-ecommerce.local' . $path;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    curl_setopt($ch, CURLOPT_USERAGENT, 'KTD-Test-Runner/1.0');
    
    $start = microtime(true);
    $body = curl_exec($ch);
    $duration = (microtime(true) - $start) * 1000;
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr = curl_error($ch);
    curl_close($ch);

    return [
        'url'      => $url,
        'httpCode' => $httpCode,
        'body'     => $body,
        'duration' => $duration,
        'error'    => $curlErr,
    ];
}

$t = new RouteE2ERunner();

$routes = [
    '/'                              => 'Homepage',
    '/shop/'                         => 'Shop Catalog',
    '/product/iphone-17-pro-max/'    => 'Single Product (iPhone 17 Pro Max)',
    '/cart/'                         => 'Cart Page',
    '/checkout/'                     => 'Checkout Page',
    '/my-account/'                   => 'My Account Page',
    '/about/'                        => 'About Us Page',
    '/contact-us/'                   => 'Contact Us Page',
];

$responses = [];

$t->describe('HTTP 200 OK & Latency across 8 Core Routes');

foreach ($routes as $path => $label) {
    $t->it("Route {$path} ({$label}) should respond with HTTP 200 within 2500ms", function() use ($t, $path, $label, &$responses) {
        $res = fetch_route($path);
        $responses[$path] = $res;

        $t->assertTrue(empty($res['error']), "cURL error on {$path}: {$res['error']}");
        $t->assertSame(200, $res['httpCode'], "Route {$path} expected HTTP 200, got {$res['httpCode']}");
        $t->assertTrue($res['duration'] < 2500, "Route {$path} took too long: round({$res['duration']}) ms");
    });
}

$t->describe('Conditional Stylesheet Enqueue Accuracy');

$t->it('all routes must load base.css and layout.css', function() use ($t, $responses) {
    foreach ($responses as $path => $res) {
        $t->assertTrue(strpos($res['body'], 'base.css') !== false, "Route {$path} missing base.css");
        $t->assertTrue(strpos($res['body'], 'layout.css') !== false, "Route {$path} missing layout.css");
    }
});

$t->it('Homepage (/) should load home.css and not leak shop, single-product, or cart styles', function() use ($t, $responses) {
    $body = $responses['/']['body'];
    $t->assertTrue(strpos($body, 'home.css') !== false, "Homepage missing home.css");
    $t->assertTrue(strpos($body, 'shop.css') === false, "Homepage should not load shop.css");
    $t->assertTrue(strpos($body, 'single-product.css') === false, "Homepage should not load single-product.css");
    $t->assertTrue(strpos($body, 'cart.css') === false, "Homepage should not load cart.css");
});

$t->it('Shop (/shop/) should load shop.css and not leak home or single-product styles', function() use ($t, $responses) {
    $body = $responses['/shop/']['body'];
    $t->assertTrue(strpos($body, 'shop.css') !== false, "Shop missing shop.css");
    $t->assertTrue(strpos($body, 'home.css') === false, "Shop should not load home.css");
    $t->assertTrue(strpos($body, 'single-product.css') === false, "Shop should not load single-product.css");
});

$t->it('Single Product should load single-product.css and not leak home or shop styles', function() use ($t, $responses) {
    $body = $responses['/product/iphone-17-pro-max/']['body'];
    $t->assertTrue(strpos($body, 'single-product.css') !== false, "Product page missing single-product.css");
    $t->assertTrue(strpos($body, 'home.css') === false, "Product page should not load home.css");
    $t->assertTrue(strpos($body, 'shop.css') === false, "Product page should not load shop.css");
});

$t->it('Cart page (/cart/) should load cart.css', function() use ($t, $responses) {
    $body = $responses['/cart/']['body'];
    $t->assertTrue(strpos($body, 'cart.css') !== false, "Cart page missing cart.css");
});

$t->it('About page (/about/) should load page-about.css', function() use ($t, $responses) {
    $body = $responses['/about/']['body'];
    $t->assertTrue(strpos($body, 'page-about.css') !== false, "About page missing page-about.css");
});

$t->it('Contact page (/contact-us/) should load page-contact.css', function() use ($t, $responses) {
    $body = $responses['/contact-us/']['body'];
    $t->assertTrue(strpos($body, 'page-contact.css') !== false, "Contact page missing page-contact.css");
});

$t->describe('Key UI Components & UX Patterns');

$t->it('iPhone 17 Pro Max single product page should render Cosmic Orange swatch pill with color dot', function() use ($t, $responses) {
    $body = $responses['/product/iphone-17-pro-max/']['body'];
    $t->assertTrue(strpos($body, 'data-color="cam-vu-tru"') !== false, "Missing cam-vu-tru color dot swatch");
    $t->assertTrue(strpos($body, 'data-color="xanh"') !== false, "Missing xanh color dot swatch");
    $t->assertTrue(strpos($body, 'data-value="cam-vu-tru"') !== false, "Missing cam-vu-tru button option");
});

$t->it('iPhone 17 Pro Max should render Related Products section', function() use ($t, $responses) {
    $body = $responses['/product/iphone-17-pro-max/']['body'];
    $t->assertTrue(strpos($body, 'related products') !== false, "Missing related products section");
    $t->assertTrue(strpos($body, 'Sản phẩm tương tự') !== false, "Missing localized heading 'Sản phẩm tương tự'");
});

$t->it('Cart empty state should render clean modern card', function() use ($t, $responses) {
    $body = $responses['/cart/']['body'];
    $t->assertTrue(strpos($body, 'ktd-empty-cart-card') !== false, "Missing ktd-empty-cart-card on empty cart");
});

// Output Summary
exit($t->summary());
