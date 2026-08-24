<?php
/**
 * Plugin Name: Phone Store Custom Post Types & Meta Boxes
 * Plugin URI:  https://github.com/example/phone-store-cpt
 * Description: Quản lý kiến trúc dữ liệu (Data Architecture) cho Website Bán Điện Thoại Cao Cấp (iPhone, Samsung, OPPO). Bao gồm CPT Điện thoại, Đơn hàng, Taxonomies Thương hiệu, Dòng sản phẩm và Custom Meta Boxes.
 * Version:     1.0.0
 * Author:      KTD Phone Store Team
 * Text Domain: phone-store-cpt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'PHONE_STORE_CPT_PATH', plugin_dir_path( __FILE__ ) );
define( 'PHONE_STORE_CPT_URL', plugin_dir_url( __FILE__ ) );

// Nạp các file xử lý chính
require_once PHONE_STORE_CPT_PATH . 'includes/class-cpt-registrar.php';
require_once PHONE_STORE_CPT_PATH . 'includes/class-taxonomy-registrar.php';
require_once PHONE_STORE_CPT_PATH . 'includes/class-metabox-product.php';
require_once PHONE_STORE_CPT_PATH . 'includes/class-metabox-order.php';

/**
 * Khởi tạo Plugin
 */
class Phone_Store_CPT_Plugin {
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		
		// Đăng ký CPT & Taxonomy
		new Phone_Store_CPT_Registrar();
		new Phone_Store_Taxonomy_Registrar();
		
		// Đăng ký Custom Meta Boxes
		new Phone_Store_Metabox_Product();
		new Phone_Store_Metabox_Order();
	}

	public function enqueue_admin_assets( $hook ) {
		wp_enqueue_style(
			'phone-store-admin-css',
			PHONE_STORE_CPT_URL . 'assets/admin-style.css',
			array(),
			'1.0.0'
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
