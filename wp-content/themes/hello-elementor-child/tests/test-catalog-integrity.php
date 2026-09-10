<?php
/**
 * Layer 2: Database & Catalog Integrity Test Suite
 * 
 * Verifies that the live WooCommerce database and filesystem state meet all requirements:
 * 1. Exactly 25 products published.
 * 2. All 25 products are variable products with 4 variations.
 * 3. All variations have prices matching the September 2026 market schedule.
 * 4. All 25 products have a valid WebP featured image existing physically on disk.
 * 5. All variation thumbnails exist physically on disk.
 * 6. All 25 products have the complete 12 hardware specifications.
 * 7. Default attributes are properly defined (including iPhone 17 Pro Max default = cam-vu-tru).
 * 8. iPhone 17 Pro and Pro Max have multi-angle WebP galleries (>= 7 images).
 */

// Load WordPress Core
require_once dirname(__DIR__, 4) . '/wp-load.php';

class CatalogIntegrityRunner {
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

    public function summary(): int {
        $duration = round((microtime(true) - $this->startTime) * 1000, 2);
        echo "\n============================================================\n";
        echo "Catalog Integrity Results: {$this->passed} passed, {$this->failed} failed ({$duration} ms)\n";
        echo "============================================================\n";
        return $this->failed > 0 ? 1 : 0;
    }
}

$t = new CatalogIntegrityRunner();

$t->describe('Catalog Product Structure & Quantities');

$products = wc_get_products([
    'status' => 'publish',
    'limit'  => -1,
]);

$t->it('should have exactly 25 published products in catalog', function() use ($t, $products) {
    $t->assertSame(25, count($products), 'Expected 25 products, found ' . count($products));
});

$t->it('all 25 products should be variable products', function() use ($t, $products) {
    foreach ($products as $p) {
        $t->assertTrue($p->is_type('variable'), "Product #{$p->get_id()} ({$p->get_name()}) is not a variable product!");
    }
});

$t->it('all 25 products should have at least 4 active variations', function() use ($t, $products) {
    foreach ($products as $p) {
        $children = $p->get_children();
        $t->assertTrue(count($children) >= 4, "Product #{$p->get_id()} ({$p->get_name()}) has only " . count($children) . " variations");
    }
});

$t->describe('September 2026 Market Pricing Integrity');

$expected_market_prices = [
    // Apple
    132 => ['name' => 'iPhone 17 Pro Max', 'min' => 33990000, 'max' => 37990000],
    133 => ['name' => 'iPhone 17 Pro',     'min' => 27490000, 'max' => 30990000],
    134 => ['name' => 'iPhone 17 Slim',    'min' => 22990000, 'max' => 25990000],
    135 => ['name' => 'iPhone 17',         'min' => 21490000, 'max' => 24490000],
    136 => ['name' => 'iPhone 16 Pro Max', 'min' => 28490000, 'max' => 32490000],
    137 => ['name' => 'iPhone 16 Pro',     'min' => 23490000, 'max' => 26490000],
    138 => ['name' => 'iPhone 16 Plus',    'min' => 20990000, 'max' => 23490000],
    139 => ['name' => 'iPhone 16',         'min' => 18490000, 'max' => 21490000],
    140 => ['name' => 'iPhone 15 Pro Max', 'min' => 24490000, 'max' => 27990000],
    141 => ['name' => 'iPhone 15',         'min' => 14990000, 'max' => 17490000],
    // Samsung
    142 => ['name' => 'Samsung Galaxy S26 Ultra', 'min' => 29990000, 'max' => 33990000],
    143 => ['name' => 'Samsung Galaxy S26 Plus',  'min' => 24490000, 'max' => 27990000],
    144 => ['name' => 'Samsung Galaxy S26',       'min' => 18490000, 'max' => 20990000],
    145 => ['name' => 'Samsung Galaxy S25 Ultra', 'min' => 25490000, 'max' => 28990000],
    146 => ['name' => 'Samsung Galaxy S24 Ultra', 'min' => 21990000, 'max' => 24990000],
    147 => ['name' => 'Samsung Galaxy Z Fold6',   'min' => 31990000, 'max' => 35490000],
    148 => ['name' => 'Samsung Galaxy Z Flip6',   'min' => 19990000, 'max' => 22990000],
    149 => ['name' => 'Samsung Galaxy A55 5G',    'min' => 7990000,  'max' => 8990000],
    150 => ['name' => 'Samsung Galaxy A35 5G',    'min' => 6290000,  'max' => 7190000],
    // OPPO
    151 => ['name' => 'OPPO Find X9 Pro',   'min' => 28490000, 'max' => 31990000],
    152 => ['name' => 'OPPO Find X8 Pro',   'min' => 20990000, 'max' => 23990000],
    153 => ['name' => 'OPPO Find X7 Ultra', 'min' => 18990000, 'max' => 21990000],
    154 => ['name' => 'OPPO Find N3 5G',    'min' => 29990000, 'max' => 34990000],
    155 => ['name' => 'OPPO Reno12 Pro 5G', 'min' => 11990000, 'max' => 13990000],
    156 => ['name' => 'OPPO Reno12 5G',     'min' => 9490000,  'max' => 11290000],
];

$t->it('should match exact September 2026 market min and max prices across all 25 products', function() use ($t, $expected_market_prices) {
    foreach ($expected_market_prices as $pid => $exp) {
        $prod = wc_get_product($pid);
        $t->assertTrue(!empty($prod), "Product #{$pid} ({$exp['name']}) not found in database");
        $min = (float)$prod->get_variation_price('min');
        $max = (float)$prod->get_variation_price('max');
        $t->assertEquals($exp['min'], $min, "Product #{$pid} min price mismatch: expected {$exp['min']}, got {$min}");
        $t->assertEquals($exp['max'], $max, "Product #{$pid} max price mismatch: expected {$exp['max']}, got {$max}");
    }
});

$t->describe('Media Attachments & Physical WebP File Integrity');

$t->it('all 25 products must have valid featured images in WebP format existing on disk', function() use ($t, $products) {
    foreach ($products as $p) {
        $thumb_id = $p->get_image_id();
        $t->assertTrue($thumb_id > 0, "Product #{$p->get_id()} ({$p->get_name()}) has no featured thumbnail!");
        $file_path = get_attached_file($thumb_id);
        $t->assertTrue(!empty($file_path) && file_exists($file_path), "Product #{$p->get_id()} thumbnail file does not exist at: {$file_path}");
        $t->assertTrue(preg_match('/\.webp$/i', $file_path) === 1, "Product #{$p->get_id()} thumbnail is not WebP format: {$file_path}");
    }
});

$t->it('all product variations must have valid assigned thumbnails existing on disk', function() use ($t, $products) {
    $variation_check_count = 0;
    foreach ($products as $p) {
        $children = $p->get_children();
        foreach ($children as $cid) {
            $v = wc_get_product($cid);
            if ($v) {
                $v_thumb = $v->get_image_id();
                $t->assertTrue($v_thumb > 0, "Variation #{$cid} of Product #{$p->get_id()} has no thumbnail assigned!");
                $fpath = get_attached_file($v_thumb);
                $t->assertTrue(!empty($fpath) && file_exists($fpath), "Variation #{$cid} thumbnail missing on disk: {$fpath}");
                $variation_check_count++;
            }
        }
    }
    $t->assertTrue($variation_check_count >= 100, "Expected at least 100 variations verified, found {$variation_check_count}");
});

$t->it('iPhone 17 Pro and 17 Pro Max must have multi-angle WebP galleries (>= 7 images)', function() use ($t) {
    foreach ([132, 133] as $flagship_id) {
        $prod = wc_get_product($flagship_id);
        $gallery = $prod->get_gallery_image_ids();
        $t->assertTrue(count($gallery) >= 7, "Product #{$flagship_id} gallery has fewer than 7 images: " . count($gallery));
        foreach ($gallery as $gid) {
            $f = get_attached_file($gid);
            $t->assertTrue(file_exists($f), "Gallery image ID {$gid} missing on disk: {$f}");
        }
    }
});

$t->describe('Hardware Specifications & Default Attributes');

$t->it('all 25 products must have all 12 standardized hardware specification attributes via ktd_get_product_specs()', function() use ($t, $products) {
    foreach ($products as $p) {
        $specs = ktd_get_product_specs($p);
        $t->assertSame(12, count($specs), "Product #{$p->get_id()} ({$p->get_name()}) expected 12 specs, found " . count($specs));
        $t->assertTrue(!empty($specs['Màn hình']), "Product #{$p->get_id()} missing 'Màn hình'");
        $t->assertTrue(!empty($specs['Pin & Sạc']), "Product #{$p->get_id()} missing 'Pin & Sạc'");
        $t->assertTrue(!empty($specs['Kháng nước & bụi']), "Product #{$p->get_id()} missing 'Kháng nước & bụi'");
    }
});

$t->it('all 25 products must have default attributes configured', function() use ($t, $products) {
    foreach ($products as $p) {
        $defaults = $p->get_default_attributes();
        $t->assertTrue(!empty($defaults['pa_dung-luong']), "Product #{$p->get_id()} has no default storage attribute");
        $t->assertTrue(!empty($defaults['pa_mau-sac']), "Product #{$p->get_id()} has no default color attribute");
    }
});

$t->it('iPhone 17 Pro Max (132) default color must be cam-vu-tru and thumbnail must be Cosmic Orange', function() use ($t) {
    $p132 = wc_get_product(132);
    $defaults = $p132->get_default_attributes();
    $t->assertSame('cam-vu-tru', $defaults['pa_mau-sac'], 'iPhone 17 Pro Max default color must be cam-vu-tru');
    $t->assertSame('256gb', $defaults['pa_dung-luong'], 'iPhone 17 Pro Max default storage must be 256gb');
    
    $thumb_url = wp_get_attachment_url($p132->get_image_id());
    $t->assertTrue(strpos($thumb_url, 'cosmic-orange') !== false, "iPhone 17 Pro Max hero image should be Cosmic Orange, got: {$thumb_url}");
});

$t->it('iPhone 17 Pro (133) default color must be xanh and storage 128gb', function() use ($t) {
    $p133 = wc_get_product(133);
    $defaults = $p133->get_default_attributes();
    $t->assertSame('xanh', $defaults['pa_mau-sac'], 'iPhone 17 Pro default color must be xanh');
    $t->assertSame('128gb', $defaults['pa_dung-luong'], 'iPhone 17 Pro default storage must be 128gb');
});

// Output Summary
exit($t->summary());
