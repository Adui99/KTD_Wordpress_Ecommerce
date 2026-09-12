<?php
/**
 * Plugin Name: Phone Store Master - CPT, Meta Boxes & SEO Schema
 * Plugin URI:  https://github.com/vlu/ktd-lab9
 * Description: Plugin quản trị toàn diện hệ thống Bán Điện Thoại Cao Cấp (iPhone, Samsung, OPPO). Tích hợp CPT Điện thoại & Đơn hàng, Taxonomies Thương hiệu/Dòng sản phẩm, Custom Meta Boxes phân nhóm và tự động sinh SEO & GEO Structured Data (JSON-LD).
 * Version:     2.0.0
 * Author:      KTD Enterprise Team
 * Text Domain: phone-store-cpt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'PHONE_STORE_CPT_VERSION', '2.0.0' );
define( 'PHONE_STORE_CPT_PATH', plugin_dir_path( __FILE__ ) );
define( 'PHONE_STORE_CPT_URL', plugin_dir_url( __FILE__ ) );

// Nạp các file xử lý chính
require_once PHONE_STORE_CPT_PATH . 'includes/class-cpt-registrar.php';
require_once PHONE_STORE_CPT_PATH . 'includes/class-taxonomy-registrar.php';
require_once PHONE_STORE_CPT_PATH . 'includes/class-metabox-product.php';
require_once PHONE_STORE_CPT_PATH . 'includes/class-metabox-order.php';
require_once PHONE_STORE_CPT_PATH . 'includes/class-schema-seo.php';

/**
 * Khởi tạo Plugin
 */
class Phone_Store_CPT_Plugin {
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		
		// 1. Đăng ký CPT & Taxonomies
		new Phone_Store_CPT_Registrar();
		new Phone_Store_Taxonomy_Registrar();
		
		// 2. Đăng ký Custom Meta Boxes (Sản phẩm & Đơn hàng)
		new Phone_Store_Metabox_Product();
		new Phone_Store_Metabox_Order();

		// 3. Đăng ký Tối ưu hóa SEO / JSON-LD Schema
		new Phone_Store_Schema_SEO();
	}

	public function enqueue_admin_assets( $hook ) {
		wp_enqueue_style(
			'phone-store-admin-css',
			PHONE_STORE_CPT_URL . 'assets/admin-style.css',
			array(),
			PHONE_STORE_CPT_VERSION
		);
	}
}

// Chạy plugin
new Phone_Store_CPT_Plugin();

// Flush rewrite rules khi kích hoạt plugin
register_activation_hook( __FILE__, function() {
	new Phone_Store_CPT_Registrar();
	new Phone_Store_Taxonomy_Registrar();
	flush_rewrite_rules();
} );
