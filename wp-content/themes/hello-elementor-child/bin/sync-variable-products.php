<?php
/**
 * Migration Script: Convert 25 Simple Products to Variable Products
 * Creates Global Taxonomies (pa_dung-luong, pa_mau-sac)
 * Creates 4 Variations per product with structured SKUs & Tiered Pricing
 */

// Load WordPress environment
$wp_load_path = dirname(dirname(dirname(dirname(__DIR__)))) . '/wp-load.php';
if (!file_exists($wp_load_path)) {
    die("Cannot find wp-load.php at: " . $wp_load_path . "\n");
}
require_once $wp_load_path;

if (!function_exists('wc_get_product')) {
    die("WooCommerce is not active.\n");
}

echo "=== STARTING VARIABLE PRODUCTS MIGRATION ===\n\n";

// 1. Ensure Global Taxonomies exist in WooCommerce
function ktd_ensure_attribute_taxonomy($slug, $name) {
    global $wpdb;
    $existing = wc_get_attribute_taxonomies();
    $found = false;
    foreach ($existing as $tax) {
        if ($tax->attribute_name === $slug) {
            $found = true;
            break;
        }
    }
    if (!$found) {
        $attr_id = wc_create_attribute(array(
            'name'         => $name,
            'slug'         => $slug,
            'type'         => 'select',
            'order_by'     => 'menu_order',
            'has_archives' => false,
        ));
        echo "Created attribute taxonomy: {$slug} ({$name}) [ID: {$attr_id}]\n";
    } else {
        echo "Attribute taxonomy already exists: {$slug} ({$name})\n";
    }

    $taxonomy_name = 'pa_' . $slug;
    if (!taxonomy_exists($taxonomy_name)) {
        register_taxonomy($taxonomy_name, array('product'), array(
            'hierarchical' => false,
            'label'        => $name,
            'query_var'    => true,
            'rewrite'      => array('slug' => $slug),
        ));
    }
    return $taxonomy_name;
}

$tax_storage = ktd_ensure_attribute_taxonomy('dung-luong', 'Dung lượng');
$tax_color   = ktd_ensure_attribute_taxonomy('mau-sac', 'Màu sắc');

// Helper to ensure term exists and return term object
function ktd_ensure_term($name, $taxonomy) {
    $term = get_term_by('name', $name, $taxonomy);
    if (!$term) {
        $result = wp_insert_term($name, $taxonomy);
        if (is_wp_error($result)) {
            echo "Error creating term {$name} in {$taxonomy}: " . $result->get_error_message() . "\n";
            return null;
        }
        $term = get_term_by('id', $result['term_id'], $taxonomy);
    }
    return $term;
}

// 2. Matrix Definition for 25 Products
$products_matrix = array(
    // Apple
    132 => array(
        'name'        => 'iPhone 17 Pro Max',
        'sku_prefix'  => 'IP17PM',
        'storage'     => array(
            '256GB' => 33990000,
            '512GB' => 37990000,
        ),
        'colors'      => array('Cam Vũ Trụ', 'Xanh'),
        'default'     => array('storage' => '256GB', 'color' => 'Cam Vũ Trụ'),
    ),
    133 => array(
        'name'        => 'iPhone 17 Pro',
        'sku_prefix'  => 'IP17P',
        'storage'     => array(
            '128GB' => 27490000,
            '256GB' => 30990000,
        ),
        'colors'      => array('Xanh', 'Cam Vũ Trụ'),
        'default'     => array('storage' => '128GB', 'color' => 'Xanh'),
    ),
    134 => array(
        'name'        => 'iPhone 17 Slim',
        'sku_prefix'  => 'IP17S',
        'storage'     => array(
            '128GB' => 22990000,
            '256GB' => 25990000,
        ),
        'colors'      => array('Bạc Ánh Trăng', 'Đen Không Gian'),
        'default'     => array('storage' => '128GB', 'color' => 'Bạc Ánh Trăng'),
    ),
    135 => array(
        'name'        => 'iPhone 17',
        'sku_prefix'  => 'IP17',
        'storage'     => array(
            '128GB' => 21490000,
            '256GB' => 24490000,
        ),
        'colors'      => array('Xanh Lưu Ly', 'Đen'),
        'default'     => array('storage' => '128GB', 'color' => 'Xanh Lưu Ly'),
    ),
    136 => array(
        'name'        => 'iPhone 16 Pro Max',
        'sku_prefix'  => 'IP16PM',
        'storage'     => array(
            '256GB' => 28490000,
            '512GB' => 32490000,
        ),
        'colors'      => array('Titan Sa Mạc', 'Titan Tự Nhiên'),
        'default'     => array('storage' => '256GB', 'color' => 'Titan Sa Mạc'),
    ),
    137 => array(
        'name'        => 'iPhone 16 Pro',
        'sku_prefix'  => 'IP16P',
        'storage'     => array(
            '128GB' => 23490000,
            '256GB' => 26490000,
        ),
        'colors'      => array('Titan Tự Nhiên', 'Titan Đen'),
        'default'     => array('storage' => '128GB', 'color' => 'Titan Tự Nhiên'),
    ),
    138 => array(
        'name'        => 'iPhone 16 Plus',
        'sku_prefix'  => 'IP16PL',
        'storage'     => array(
            '128GB' => 20990000,
            '256GB' => 23490000,
        ),
        'colors'      => array('Hồng Pastel', 'Xanh Mòng Két'),
        'default'     => array('storage' => '128GB', 'color' => 'Hồng Pastel'),
    ),
    139 => array(
        'name'        => 'iPhone 16',
        'sku_prefix'  => 'IP16',
        'storage'     => array(
            '128GB' => 18490000,
            '256GB' => 21490000,
        ),
        'colors'      => array('Trắng', 'Đen'),
        'default'     => array('storage' => '128GB', 'color' => 'Trắng'),
    ),
    140 => array(
        'name'        => 'iPhone 15 Pro Max',
        'sku_prefix'  => 'IP15PM',
        'storage'     => array(
            '256GB' => 24490000,
            '512GB' => 27990000,
        ),
        'colors'      => array('Titan Tự Nhiên', 'Titan Xanh'),
        'default'     => array('storage' => '256GB', 'color' => 'Titan Tự Nhiên'),
    ),
    141 => array(
        'name'        => 'iPhone 15',
        'sku_prefix'  => 'IP15',
        'storage'     => array(
            '128GB' => 14990000,
            '256GB' => 17490000,
        ),
        'colors'      => array('Xanh Lá', 'Đen'),
        'default'     => array('storage' => '128GB', 'color' => 'Xanh Lá'),
    ),

    // Samsung
    142 => array(
        'name'        => 'Samsung Galaxy S26 Ultra',
        'sku_prefix'  => 'SS-S26U',
        'storage'     => array(
            '256GB' => 29990000,
            '512GB' => 33990000,
        ),
        'colors'      => array('Trắng Titan', 'Đen Titan'),
        'default'     => array('storage' => '256GB', 'color' => 'Trắng Titan'),
    ),
    143 => array(
        'name'        => 'Samsung Galaxy S26 Plus',
        'sku_prefix'  => 'SS-S26P',
        'storage'     => array(
            '256GB' => 24490000,
            '512GB' => 27990000,
        ),
        'colors'      => array('Bạc Ánh Trăng', 'Đen Cẩm Thạch'),
        'default'     => array('storage' => '256GB', 'color' => 'Bạc Ánh Trăng'),
    ),
    144 => array(
        'name'        => 'Samsung Galaxy S26',
        'sku_prefix'  => 'SS-S26',
        'storage'     => array(
            '128GB' => 18490000,
            '256GB' => 20990000,
        ),
        'colors'      => array('Tím Khói', 'Vàng Hổ Phách'),
        'default'     => array('storage' => '128GB', 'color' => 'Tím Khói'),
    ),
    145 => array(
        'name'        => 'Samsung Galaxy S25 Ultra',
        'sku_prefix'  => 'SS-S25U',
        'storage'     => array(
            '256GB' => 25490000,
            '512GB' => 28990000,
        ),
        'colors'      => array('Xám Titan', 'Đen Titan'),
        'default'     => array('storage' => '256GB', 'color' => 'Xám Titan'),
    ),
    146 => array(
        'name'        => 'Samsung Galaxy S24 Ultra',
        'sku_prefix'  => 'SS-S24U',
        'storage'     => array(
            '256GB' => 21990000,
            '512GB' => 24990000,
        ),
        'colors'      => array('Xám Titan', 'Vàng Titan'),
        'default'     => array('storage' => '256GB', 'color' => 'Xám Titan'),
    ),
    147 => array(
        'name'        => 'Samsung Galaxy Z Fold6',
        'sku_prefix'  => 'SS-ZFOLD6',
        'storage'     => array(
            '256GB' => 31990000,
            '512GB' => 35490000,
        ),
        'colors'      => array('Xám Metal', 'Xanh Navy'),
        'default'     => array('storage' => '256GB', 'color' => 'Xám Metal'),
    ),
    148 => array(
        'name'        => 'Samsung Galaxy Z Flip6',
        'sku_prefix'  => 'SS-ZFLIP6',
        'storage'     => array(
            '256GB' => 19990000,
            '512GB' => 22990000,
        ),
        'colors'      => array('Xanh Mint', 'Vàng Mơ'),
        'default'     => array('storage' => '256GB', 'color' => 'Xanh Mint'),
    ),
    149 => array(
        'name'        => 'Samsung Galaxy A55 5G',
        'sku_prefix'  => 'SS-A55',
        'storage'     => array(
            '128GB' => 7990000,
            '256GB' => 8990000,
        ),
        'colors'      => array('Xanh Iceblue', 'Tím Lilac'),
        'default'     => array('storage' => '128GB', 'color' => 'Xanh Iceblue'),
    ),
    150 => array(
        'name'        => 'Samsung Galaxy A35 5G',
        'sku_prefix'  => 'SS-A35',
        'storage'     => array(
            '128GB' => 6290000,
            '256GB' => 7190000,
        ),
        'colors'      => array('Xanh Navy', 'Vàng Lemon'),
        'default'     => array('storage' => '128GB', 'color' => 'Xanh Navy'),
    ),

    // OPPO
    151 => array(
        'name'        => 'OPPO Find X9 Pro',
        'sku_prefix'  => 'OP-X9P',
        'storage'     => array(
            '256GB' => 28490000,
            '512GB' => 31990000,
        ),
        'colors'      => array('Đen Titan', 'Xanh Sa Mạc'),
        'default'     => array('storage' => '256GB', 'color' => 'Đen Titan'),
    ),
    152 => array(
        'name'        => 'OPPO Find X8 Pro',
        'sku_prefix'  => 'OP-X8P',
        'storage'     => array(
            '256GB' => 20990000,
            '512GB' => 23990000,
        ),
        'colors'      => array('Trắng Ngọc Trai', 'Đen Vũ Trụ'),
        'default'     => array('storage' => '256GB', 'color' => 'Trắng Ngọc Trai'),
    ),
    153 => array(
        'name'        => 'OPPO Find X7 Ultra',
        'sku_prefix'  => 'OP-X7U',
        'storage'     => array(
            '256GB' => 18990000,
            '512GB' => 21990000,
        ),
        'colors'      => array('Xanh Đại Dương', 'Nâu Da Bò'),
        'default'     => array('storage' => '256GB', 'color' => 'Xanh Đại Dương'),
    ),
    154 => array(
        'name'        => 'OPPO Find N3 5G',
        'sku_prefix'  => 'OP-FN3',
        'storage'     => array(
            '512GB' => 29990000,
            '1TB'   => 34990000,
        ),
        'colors'      => array('Vàng Hoàng Kim', 'Đen Cổ Điển'),
        'default'     => array('storage' => '512GB', 'color' => 'Vàng Hoàng Kim'),
    ),
    155 => array(
        'name'        => 'OPPO Reno12 Pro 5G',
        'sku_prefix'  => 'OP-RN12P',
        'storage'     => array(
            '256GB' => 11990000,
            '512GB' => 13990000,
        ),
        'colors'      => array('Nâu Tinh Vân', 'Bạc Không Gian'),
        'default'     => array('storage' => '256GB', 'color' => 'Nâu Tinh Vân'),
    ),
    156 => array(
        'name'        => 'OPPO Reno12 5G',
        'sku_prefix'  => 'OP-RN12',
        'storage'     => array(
            '256GB' => 9490000,
            '512GB' => 11290000,
        ),
        'colors'      => array('Bạc Vũ Trụ', 'Hồng Hoàng Hôn'),
        'default'     => array('storage' => '256GB', 'color' => 'Bạc Vũ Trụ'),
    ),
);

// Helper to generate unique SKU parts
function ktd_generate_sku_part($str) {
    $str = remove_accents($str);
    $words = preg_split('/[\s\-_]+/', trim($str));
    if (count($words) > 1) {
        $acronym = '';
        foreach ($words as $w) {
            $cleaned = preg_replace('/[^A-Za-z0-9]/', '', $w);
            if (!empty($cleaned)) {
                $acronym .= strtoupper($cleaned[0]);
            }
        }
        if (strlen($acronym) >= 2) {
            return $acronym;
        }
    }
    return strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $str), 0, 5));
}

// 3. Process Each Product
$count_success = 0;

foreach ($products_matrix as $product_id => $config) {
    echo "------------------------------------------------------------\n";
    echo "Processing Product ID: {$product_id} — {$config['name']}...\n";

    // Clean up old variations if any exist before proceeding
    $old_children = get_posts(array(
        'post_type'      => 'product_variation',
        'post_parent'    => $product_id,
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'post_status'    => 'any',
    ));
    if (!empty($old_children)) {
        foreach ($old_children as $oid) {
            wp_delete_post($oid, true);
        }
        echo "  Cleaned up " . count($old_children) . " previous variations.\n";
    }

    // A. Update Parent Product Name & Slug
    $post_data = array(
        'ID'         => $product_id,
        'post_title' => $config['name'],
        'post_name'  => sanitize_title($config['name']),
    );
    wp_update_post($post_data);

    // B. Set product type to 'variable'
    wp_set_object_terms($product_id, 'variable', 'product_type');
    clean_post_cache($product_id);

    // Instantiate explicitly as WC_Product_Variable
    $product = new WC_Product_Variable($product_id);
    $product->set_name($config['name']);

    // C. Register & Assign Terms for Storage and Color
    $storage_terms = array();
    $storage_slugs = array();
    foreach (array_keys($config['storage']) as $st) {
        $term = ktd_ensure_term($st, $tax_storage);
        if ($term) {
            $storage_terms[] = (int)$term->term_id;
            $storage_slugs[$st] = $term->slug;
        }
    }
    wp_set_object_terms($product_id, $storage_terms, $tax_storage);

    $color_terms = array();
    $color_slugs = array();
    foreach ($config['colors'] as $col) {
        $term = ktd_ensure_term($col, $tax_color);
        if ($term) {
            $color_terms[] = (int)$term->term_id;
            $color_slugs[$col] = $term->slug;
        }
    }
    wp_set_object_terms($product_id, $color_terms, $tax_color);

    // D. Preserve existing product attributes and add variation attributes
    $existing_attributes = $product->get_attributes();
    $new_attributes = array();

    // Storage Attribute
    $attr_storage = new WC_Product_Attribute();
    $attr_storage->set_id(wc_attribute_taxonomy_id_by_name('pa_dung-luong'));
    $attr_storage->set_name($tax_storage);
    $attr_storage->set_options($storage_terms);
    $attr_storage->set_position(0);
    $attr_storage->set_visible(true);
    $attr_storage->set_variation(true);
    $new_attributes[$tax_storage] = $attr_storage;

    // Color Attribute
    $attr_color = new WC_Product_Attribute();
    $attr_color->set_id(wc_attribute_taxonomy_id_by_name('pa_mau-sac'));
    $attr_color->set_name($tax_color);
    $attr_color->set_options($color_terms);
    $attr_color->set_position(1);
    $attr_color->set_visible(true);
    $attr_color->set_variation(true);
    $new_attributes[$tax_color] = $attr_color;

    // Keep any existing non-variation specs attributes (e.g. CPU, RAM, Camera...)
    $pos = 2;
    foreach ($existing_attributes as $key => $attr_obj) {
        if ($key !== $tax_storage && $key !== $tax_color) {
            if (is_object($attr_obj)) {
                $attr_obj->set_position($pos++);
                $new_attributes[$key] = $attr_obj;
            }
        }
    }

    $product->set_attributes($new_attributes);

    // E. Set Default Variation
    $default_storage_slug = isset($storage_slugs[$config['default']['storage']]) ? $storage_slugs[$config['default']['storage']] : '';
    $default_color_slug   = isset($color_slugs[$config['default']['color']]) ? $color_slugs[$config['default']['color']] : '';
    $product->set_default_attributes(array(
        $tax_storage => $default_storage_slug,
        $tax_color   => $default_color_slug,
    ));

    $product->save();

    // G. Create 4 Variations (Storage x Color)
    $variation_count = 0;
    foreach ($config['storage'] as $storage_name => $price) {
        $st_slug = $storage_slugs[$storage_name];
        foreach ($config['colors'] as $color_name) {
            $col_slug = $color_slugs[$color_name];

            $variation = new WC_Product_Variation();
            $variation->set_parent_id($product_id);
            $variation->set_attributes(array(
                $tax_storage => $st_slug,
                $tax_color   => $col_slug,
            ));

            $variation->set_regular_price($price);
            $variation->set_price($price);

            // Structured SKU
            $sku_storage = preg_replace('/[^A-Za-z0-9]/', '', $storage_name);
            $sku_color   = ktd_generate_sku_part($color_name);
            $sku = sprintf('%s-%s-%s', $config['sku_prefix'], $sku_storage, $sku_color);
            try {
                $existing_id = wc_get_product_id_by_sku($sku);
                if ($existing_id && $existing_id !== $variation->get_id()) {
                    $sku .= '-' . ($variation_count + 1);
                }
                $variation->set_sku($sku);
            } catch (Exception $e) {
                $variation->set_sku($sku . '-' . wp_generate_password(3, false));
            }

            $variation->set_manage_stock(false);
            $variation->set_stock_status('instock');

            // If parent has a thumbnail, we can keep or leave empty to inherit parent
            $variation->save();
            $variation_count++;
        }
    }

    // H. Sync parent product variable prices and status
    WC_Product_Variable::sync($product_id);
    wc_delete_product_transients($product_id);

    echo "  Successfully converted to Variable Product with {$variation_count} variations!\n";
    $count_success++;
}

// Clear overall price bounds transient so shop filter recalculates accurately
delete_transient('ktd_price_bounds');

echo "\n============================================================\n";
echo "MIGRATION COMPLETED: {$count_success}/25 products converted successfully!\n";
echo "============================================================\n";
