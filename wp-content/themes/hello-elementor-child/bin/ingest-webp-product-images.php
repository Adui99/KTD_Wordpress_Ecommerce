<?php
/**
 * Ingest WebP Images from wp-content/uploads/2026/products into WooCommerce
 * - Creates WordPress attachments with clean titles and alt text
 * - Synchronizes S26 Ultra color terms (Trắng Titan & Đen Titan)
 * - Assigns Featured Images, Variation Thumbnails, and Full Gallery Albums
 */

require 'c:/Users/Administrator/Local Sites/ktd-ecommerce/app/public/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

if (!function_exists('wc_get_product')) {
    die("WooCommerce is not active.\n");
}

echo "============================================================\n";
echo "  INGESTING WEBP PRODUCT IMAGES INTO WOOCOMMERCE\n";
echo "============================================================\n\n";

$products_dir = 'c:/Users/Administrator/Local Sites/ktd-ecommerce/app/public/wp-content/uploads/2026/products';
$upload_info = wp_upload_dir();

// 1. Helper to find or create attachment for a file in 2026/products
function ktd_get_or_create_attachment($filename, $products_dir) {
    global $wpdb;
    $file_path = $products_dir . '/' . $filename;
    if (!file_exists($file_path)) {
        echo "  [ERROR] File does not exist: {$file_path}\n";
        return 0;
    }

    $relative_path = '2026/products/' . $filename;

    // Check if attachment already exists by meta or guid
    $existing_id = $wpdb->get_var($wpdb->prepare(
        "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value = %s LIMIT 1",
        $relative_path
    ));

    if ($existing_id) {
        return (int)$existing_id;
    }

    $filetype = wp_check_filetype($filename, null);
    $clean_title = sanitize_text_field(str_replace(array('-', '_'), ' ', preg_replace('/\.[^.]+$/', '', $filename)));
    $clean_title = ucwords($clean_title);

    $attachment = array(
        'guid'           => wp_upload_dir()['baseurl'] . '/2026/products/' . $filename,
        'post_mime_type' => $filetype['type'] ? $filetype['type'] : 'image/webp',
        'post_title'     => $clean_title,
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    $attach_id = wp_insert_attachment($attachment, $file_path);
    if (is_wp_error($attach_id) || !$attach_id) {
        echo "  [ERROR] Failed to insert attachment for: {$filename}\n";
        return 0;
    }

    update_post_meta($attach_id, '_wp_attached_file', $relative_path);
    update_post_meta($attach_id, '_wp_attachment_image_alt', $clean_title);

    $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
    wp_update_attachment_metadata($attach_id, $attach_data);

    echo "  [NEW ATTACHMENT] ID: {$attach_id} -> {$filename}\n";
    return (int)$attach_id;
}

// 2. Pre-index all webp files in products directory
$all_files = scandir($products_dir);
$attachments = array();
echo "[Step 1] Ingesting attachments from {$products_dir}...\n";
foreach ($all_files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'webp') {
        $aid = ktd_get_or_create_attachment($file, $products_dir);
        if ($aid) {
            $attachments[$file] = $aid;
        }
    }
}
echo "Total WebP attachments available: " . count($attachments) . "\n\n";

// 3. Update Samsung Galaxy S26 Ultra (ID 142) colors to Trắng Titan & Đen Titan
echo "[Step 2] Synchronizing Galaxy S26 Ultra (142) color variations...\n";
$tax_storage = 'pa_dung-luong';
$tax_color   = 'pa_mau-sac';

function ktd_ensure_term_quick($name, $taxonomy) {
    $term = get_term_by('name', $name, $taxonomy);
    if (!$term) {
        $result = wp_insert_term($name, $taxonomy);
        if (!is_wp_error($result)) {
            $term = get_term_by('id', $result['term_id'], $taxonomy);
        }
    }
    return $term;
}

$s26u_product = new WC_Product_Variable(142);
$s26u_storage_terms = array();
foreach (array('256GB', '512GB') as $st) {
    $t = ktd_ensure_term_quick($st, $tax_storage);
    if ($t) $s26u_storage_terms[] = (int)$t->term_id;
}
$s26u_color_terms = array();
$s26u_color_slugs = array();
foreach (array('Trắng Titan', 'Đen Titan') as $col) {
    $t = ktd_ensure_term_quick($col, $tax_color);
    if ($t) {
        $s26u_color_terms[] = (int)$t->term_id;
        $s26u_color_slugs[$col] = $t->slug;
    }
}
wp_set_object_terms(142, $s26u_storage_terms, $tax_storage);
wp_set_object_terms(142, $s26u_color_terms, $tax_color);

// Delete old S26U variations and recreate cleanly
$old_vars = get_posts(array(
    'post_type' => 'product_variation',
    'post_parent' => 142,
    'posts_per_page' => -1,
    'fields' => 'ids',
    'post_status' => 'any'
));
foreach ($old_vars as $ovid) {
    wp_delete_post($ovid, true);
}

$s26u_attrs = $s26u_product->get_attributes();
$new_s26u_attrs = array();

$a_st = new WC_Product_Attribute();
$a_st->set_id(wc_attribute_taxonomy_id_by_name($tax_storage));
$a_st->set_name($tax_storage);
$a_st->set_options($s26u_storage_terms);
$a_st->set_position(0);
$a_st->set_visible(true);
$a_st->set_variation(true);
$new_s26u_attrs[$tax_storage] = $a_st;

$a_col = new WC_Product_Attribute();
$a_col->set_id(wc_attribute_taxonomy_id_by_name($tax_color));
$a_col->set_name($tax_color);
$a_col->set_options($s26u_color_terms);
$a_col->set_position(1);
$a_col->set_visible(true);
$a_col->set_variation(true);
$new_s26u_attrs[$tax_color] = $a_col;

$pos = 2;
foreach ($s26u_attrs as $k => $obj) {
    if ($k !== $tax_storage && $k !== $tax_color && is_object($obj)) {
        $obj->set_position($pos++);
        $new_s26u_attrs[$k] = $obj;
    }
}
$s26u_product->set_attributes($new_s26u_attrs);
$s26u_product->set_default_attributes(array(
    $tax_storage => '256gb',
    $tax_color   => $s26u_color_slugs['Trắng Titan']
));
$s26u_product->save();

// Create 4 variations for S26U
$s26u_prices = array('256GB' => 29990000, '512GB' => 33990000);
$v_count = 0;
foreach ($s26u_prices as $st_name => $price) {
    $st_slug = sanitize_title($st_name);
    foreach (array('Trắng Titan', 'Đen Titan') as $col_name) {
        $col_slug = $s26u_color_slugs[$col_name];
        $var = new WC_Product_Variation();
        $var->set_parent_id(142);
        $var->set_attributes(array(
            $tax_storage => $st_slug,
            $tax_color   => $col_slug
        ));
        $var->set_regular_price($price);
        $var->set_price($price);
        $sku_col = ($col_name === 'Trắng Titan') ? 'TT' : 'DT';
        $var->set_sku("SS-S26U-{$st_name}-{$sku_col}");
        $var->set_manage_stock(false);
        $var->set_stock_status('instock');
        $var->save();
        $v_count++;
    }
}
WC_Product_Variable::sync(142);
echo "  Galaxy S26 Ultra updated with {$v_count} variations!\n\n";

// 4. Complete Mapping Matrix for 25 Products
echo "[Step 3] Assigning images to 25 products, galleries and variations...\n";

$products_mapping = array(
    // 132: iPhone 17 Pro Max
    132 => array(
        'featured' => 'iphone-17-pro-max-deep-blue-pdp-image-position-1-deep-blue-color-vn-vi.webp',
        'colors' => array(
            'Xanh'        => 'iphone-17-pro-max-deep-blue-pdp-image-position-1-deep-blue-color-vn-vi.webp',
            'Cam Vũ Trụ' => 'iphone-17-pro-max-cosmic-orange-pdp-image-position-1-cosmic-orange-color-vn-vi.webp',
        ),
        'gallery' => array(
            'iphone-17-pro-max-cosmic-orange-pdp-image-position-1-cosmic-orange-color-vn-vi.webp',
            'iphone-17-pro-max-deep-blue-pdp-image-position-2-deep-blue-color-vn-vi.webp',
            'iphone-17-pro-max-deep-blue-pdp-image-position-3-design-vn-vi.webp',
            'iphone-17-pro-max-deep-blue-pdp-image-position-4-design-detail-vn-vi.webp',
            'iphone-17-pro-max-cosmic-orange-pdp-image-position-2-cosmic-orange-color-vn-vi.webp',
            'iphone-17-pro-max-cosmic-orange-pdp-image-position-3-design-vn-vi.webp',
            'iphone-17-pro-max-cosmic-orange-pdp-image-position-4-design-detail-vn-vi.webp',
        ),
    ),
    // 133: iPhone 17 Pro
    133 => array(
        'featured' => 'iphone-17-pro-deep-blue-pdp-image-position-1-deep-blue-color-vn-vi.webp',
        'colors' => array(
            'Xanh'        => 'iphone-17-pro-deep-blue-pdp-image-position-1-deep-blue-color-vn-vi.webp',
            'Cam Vũ Trụ' => 'iphone-17-pro-cosmic-orange-pdp-image-position-1-cosmic-orange-color-vn-vi.webp',
        ),
        'gallery' => array(
            'iphone-17-pro-cosmic-orange-pdp-image-position-1-cosmic-orange-color-vn-vi.webp',
            'iphone-17-pro-deep-blue-pdp-image-position-2-deep-blue-color-vn-vi.webp',
            'iphone-17-pro-deep-blue-pdp-image-position-3-design-vn-vi.webp',
            'iphone-17-pro-deep-blue-pdp-image-position-4-design-detail-vn-vi.webp',
            'iphone-17-pro-cosmic-orange-pdp-image-position-2-cosmic-orange-color-vn-vi.webp',
            'iphone-17-pro-cosmic-orange-pdp-image-position-3-design-vn-vi.webp',
            'iphone-17-pro-cosmic-orange-pdp-image-position-4-design-detail-vn-vi.webp',
        ),
    ),
    // 134: iPhone 17 Slim
    134 => array(
        'featured' => 'iphone-17-pro-silver-pdp-image-position-1-silver-color-vn-vi.webp',
        'colors' => array(
            'Bạc Ánh Trăng' => 'iphone-17-pro-silver-pdp-image-position-1-silver-color-vn-vi.webp',
            'Đen Không Gian' => 'iphone-17-black-pdp-image-position-1-black-color-vn-vi.webp',
        ),
        'gallery' => array(
            'iphone-17-black-pdp-image-position-1-black-color-vn-vi.webp',
        ),
    ),
    // 135: iPhone 17
    135 => array(
        'featured' => 'iphone-17-mist-blue-pdp-image-position-1-mist-blue-color-vn-vi.webp',
        'colors' => array(
            'Xanh Lưu Ly' => 'iphone-17-mist-blue-pdp-image-position-1-mist-blue-color-vn-vi.webp',
            'Đen'         => 'iphone-17-black-pdp-image-position-1-black-color-vn-vi.webp',
        ),
        'gallery' => array(
            'iphone-17-black-pdp-image-position-1-black-color-vn-vi.webp',
        ),
    ),
    // 136: iPhone 16 Pro Max
    136 => array(
        'featured' => 'iphone-16-pro-max-sa-mac-1.webp',
        'colors' => array(
            'Titan Sa Mạc'  => 'iphone-16-pro-max-sa-mac-1.webp',
            'Titan Tự Nhiên' => 'iphone-16-pro-max-tu-nhien-1.webp',
        ),
        'gallery' => array(
            'iphone-16-pro-max-tu-nhien-1.webp',
        ),
    ),
    // 137: iPhone 16 Pro
    137 => array(
        'featured' => 'iphone-16-pro-tu-nhien-1.webp',
        'colors' => array(
            'Titan Tự Nhiên' => 'iphone-16-pro-tu-nhien-1.webp',
            'Titan Đen'      => 'iphone-16-pro-den-1.webp',
        ),
        'gallery' => array(
            'iphone-16-pro-den-1.webp',
        ),
    ),
    // 138: iPhone 16 Plus
    138 => array(
        'featured' => 'iphone-16-plus-hong_2.webp',
        'colors' => array(
            'Hồng Pastel'    => 'iphone-16-plus-hong_2.webp',
            'Xanh Mòng Két' => 'iphone-16-plus-xanh-mong-ket.webp',
        ),
        'gallery' => array(
            'iphone-16-plus-xanh-mong-ket.webp',
            'iphone-16-plus-trang.webp',
            'iphone-16-plus-den.webp',
        ),
    ),
    // 139: iPhone 16
    139 => array(
        'featured' => 'iphone-16-plus-trang.webp',
        'colors' => array(
            'Trắng' => 'iphone-16-plus-trang.webp',
            'Đen'   => 'iphone-16-plus-den.webp',
        ),
        'gallery' => array(
            'iphone-16-plus-den.webp',
        ),
    ),
    // 140: iPhone 15 Pro Max
    140 => array(
        'featured' => 'iphone15-pro-max-titan-nau.webp',
        'colors' => array(
            'Titan Tự Nhiên' => 'iphone15-pro-max-titan-nau.webp',
            'Titan Xanh'     => 'iphone15-pro-max-titan-xanh.webp',
        ),
        'gallery' => array(
            'iphone15-pro-max-titan-xanh.webp',
        ),
    ),
    // 141: iPhone 15
    141 => array(
        'featured' => 'iphone-15-128gb-xanh-la.webp',
        'colors' => array(
            'Xanh Lá' => 'iphone-15-128gb-xanh-la.webp',
            'Đen'     => 'iphone-15-128-gbden.webp',
        ),
        'gallery' => array(
            'iphone-15-128-gbden.webp',
        ),
    ),

    // 142: Samsung Galaxy S26 Ultra
    142 => array(
        'featured' => 'samsung-galaxy-s26-ultra-trang.webp',
        'colors' => array(
            'Trắng Titan' => 'samsung-galaxy-s26-ultra-trang.webp',
            'Đen Titan'   => 'samsung-galaxy-s26-ultra-den.webp',
        ),
        'gallery' => array(
            'samsung-galaxy-s26-ultra-den.webp',
        ),
    ),
    // 143: Samsung Galaxy S26 Plus
    143 => array(
        'featured' => 'samsung-galaxy-s26-plus-trang.webp',
        'colors' => array(
            'Bạc Ánh Trăng' => 'samsung-galaxy-s26-plus-trang.webp',
            'Đen Cẩm Thạch' => 'samsung-galaxy-s26-plus-den.webp',
        ),
        'gallery' => array(
            'samsung-galaxy-s26-plus-den.webp',
        ),
    ),
    // 144: Samsung Galaxy S26
    144 => array(
        'featured' => 'samsung-galaxy-s26-tim.webp',
        'colors' => array(
            'Tím Khói'      => 'samsung-galaxy-s26-tim.webp',
            'Vàng Hổ Phách' => 'samsung-galaxy-s26-tim.webp',
        ),
        'gallery' => array(),
    ),
    // 145: Samsung Galaxy S25 Ultra
    145 => array(
        'featured' => 'dien-thoai-samsung-galaxy-s25-ultra-xam.webp',
        'colors' => array(
            'Xám Titan' => 'dien-thoai-samsung-galaxy-s25-ultra-xam.webp',
            'Đen Titan' => 'dien-thoai-samsung-galaxy-s25-utra-den.webp',
        ),
        'gallery' => array(
            'dien-thoai-samsung-galaxy-s25-utra-den.webp',
        ),
    ),
    // 146: Samsung Galaxy S24 Ultra
    146 => array(
        'featured' => 'samsung-galaxy-s24-ultra-512gb-xam.webp',
        'colors' => array(
            'Xám Titan'  => 'samsung-galaxy-s24-ultra-512gb-xam.webp',
            'Vàng Titan' => 'samsung-galaxy-s24-ultra-512gb-vang.webp',
        ),
        'gallery' => array(
            'samsung-galaxy-s24-ultra-512gb-vang.webp',
        ),
    ),
    // 147: Samsung Galaxy Z Fold6
    147 => array(
        'featured' => 'samsung-galaxy-z-fold-6-xam.webp',
        'colors' => array(
            'Xám Metal' => 'samsung-galaxy-z-fold-6-xam.webp',
            'Xanh Navy' => 'samsung-galaxy-z-fold-6-xanh.webp',
        ),
        'gallery' => array(
            'samsung-galaxy-z-fold-6-xanh.webp',
        ),
    ),
    // 148: Samsung Galaxy Z Flip6
    148 => array(
        'featured' => 'zflip6-5g-xanhmint.webp',
        'colors' => array(
            'Xanh Mint' => 'zflip6-5g-xanhmint.webp',
            'Vàng Mơ'   => 'zflip6-5g-vang.webp',
        ),
        'gallery' => array(
            'zflip6-5g-vang.webp',
        ),
    ),
    // 149: Samsung Galaxy A55 5G
    149 => array(
        'featured' => 'ss-a55-5g-xanh.webp',
        'colors' => array(
            'Xanh Iceblue' => 'ss-a55-5g-xanh.webp',
            'Tím Lilac'    => 'ss-a55-5g-tim.webp',
        ),
        'gallery' => array(
            'ss-a55-5g-tim.webp',
        ),
    ),
    // 150: Samsung Galaxy A35 5G
    150 => array(
        'featured' => 'samsung-galaxy-a35-8gb-128gb-cu-dep.webp',
        'colors' => array(
            'Xanh Navy'   => 'samsung-galaxy-a35-8gb-128gb-cu-dep.webp',
            'Vàng Lemon'  => 'samsung-galaxy-a35-8gb-128gb-cu-dep.webp',
        ),
        'gallery' => array(),
    ),

    // 151: OPPO Find X9 Pro
    151 => array(
        'featured' => 'oppo_find_x9_pro_16gb_512gb_2.webp',
        'colors' => array(
            'Đen Titan'   => 'oppo_find_x9_pro_16gb_512gb_2.webp',
            'Xanh Sa Mạc' => 'oppo_find_x9_pro_16gb_512gb-1_2.webp',
        ),
        'gallery' => array(
            'oppo_find_x9_pro_16gb_512gb-1_2.webp',
        ),
    ),
    // 152: OPPO Find X8 Pro
    152 => array(
        'featured' => 'dien-thoai-oppo-find-x8-pro_2__3.webp',
        'colors' => array(
            'Trắng Ngọc Trai' => 'dien-thoai-oppo-find-x8-pro_2__3.webp',
            'Đen Vũ Trụ'      => 'dien-thoai-oppo-find-x8-pro_3.webp',
        ),
        'gallery' => array(
            'dien-thoai-oppo-find-x8-pro_3.webp',
        ),
    ),
    // 153: OPPO Find X7 Ultra
    153 => array(
        'featured' => 'oppo-find-x7-ultra.webp',
        'colors' => array(
            'Xanh Đại Dương' => 'oppo-find-x7-ultra.webp',
            'Nâu Da Bò'      => 'oppo-find-x7-ultra.webp',
        ),
        'gallery' => array(),
    ),
    // 154: OPPO Find N3 5G
    154 => array(
        'featured' => 'oppo-find-n3-vang.webp',
        'colors' => array(
            'Vàng Hoàng Kim' => 'oppo-find-n3-vang.webp',
            'Đen Cổ Điển'    => 'oppo-find-n3-den.webp',
        ),
        'gallery' => array(
            'oppo-find-n3-den.webp',
        ),
    ),
    // 155: OPPO Reno12 Pro 5G
    155 => array(
        'featured' => 'dien-thoai-oppo-reno12-pro-5g_9__3_3_1.webp',
        'colors' => array(
            'Nâu Tinh Vân'   => 'dien-thoai-oppo-reno12-pro-5g_9__3_3_1.webp',
            'Bạc Không Gian' => 'dien-thoai-oppo-reno12-pro-5g_9__3_3_1.webp',
        ),
        'gallery' => array(),
    ),
    // 156: OPPO Reno12 5G
    156 => array(
        'featured' => 'dien-thoai-oppo-reno12-5g_9_.webp',
        'colors' => array(
            'Bạc Vũ Trụ'    => 'dien-thoai-oppo-reno12-5g_9_.webp',
            'Hồng Hoàng Hôn' => 'dien-thoai-oppo-reno12-5g_9_.webp',
        ),
        'gallery' => array(),
    ),
);

$success_count = 0;

foreach ($products_mapping as $product_id => $cfg) {
    $product = wc_get_product($product_id);
    if (!$product) {
        echo "[ERROR] Product ID {$product_id} not found!\n";
        continue;
    }

    $name = $product->get_name();
    echo "Processing Product ID {$product_id}: {$name}...\n";

    // A. Set Featured Image
    $feat_file = $cfg['featured'];
    $feat_id = isset($attachments[$feat_file]) ? $attachments[$feat_file] : 0;
    if ($feat_id) {
        $product->set_image_id($feat_id);
        echo "  - Featured image set: [{$feat_id}] {$feat_file}\n";
    }

    // B. Set Product Gallery
    $gallery_ids = array();
    foreach ($cfg['gallery'] as $gfile) {
        if (isset($attachments[$gfile])) {
            $gallery_ids[] = $attachments[$gfile];
        }
    }
    $product->set_gallery_image_ids($gallery_ids);
    echo "  - Gallery images set: " . count($gallery_ids) . " items\n";

    $product->save();

    // C. Set Variation Images
    if ($product->is_type('variable')) {
        $children = $product->get_children();
        $updated_var_count = 0;
        foreach ($children as $var_id) {
            $variation = wc_get_product($var_id);
            $var_color = $variation->get_attribute($tax_color);

            // Find matching color in our mapping
            $matched_file = null;
            foreach ($cfg['colors'] as $color_name => $file) {
                if (mb_strtolower(trim($color_name)) === mb_strtolower(trim($var_color))) {
                    $matched_file = $file;
                    break;
                }
            }

            // Fallback to first color in mapping if not matched exactly
            if (!$matched_file && !empty($cfg['colors'])) {
                $matched_file = reset($cfg['colors']);
            }

            if ($matched_file && isset($attachments[$matched_file])) {
                $var_attach_id = $attachments[$matched_file];
                $variation->set_image_id($var_attach_id);
                $variation->save();
                update_post_meta($var_id, '_thumbnail_id', $var_attach_id);
                $updated_var_count++;
            }
        }
        echo "  - Updated {$updated_var_count} variation images according to color\n";
    }

    WC_Product_Variable::sync($product_id);
    wc_delete_product_transients($product_id);
    $success_count++;
    echo "\n";
}

delete_transient('ktd_price_bounds');
wp_cache_flush();

echo "============================================================\n";
echo "SUCCESSFULLY SYNCHRONIZED {$success_count}/25 PRODUCTS!\n";
echo "============================================================\n";
