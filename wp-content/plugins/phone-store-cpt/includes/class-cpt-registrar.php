<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Phone_Store_CPT_Registrar {

	public function __construct() {
		add_action( 'init', array( $this, 'register_post_types' ) );
	}

	public function register_post_types() {
		// 1. CPT: Điện thoại (phone_product)
		$labels_product = array(
			'name'               => 'Điện thoại',
			'singular_name'      => 'Điện thoại',
			'menu_name'          => 'Điện Thoại',
			'name_admin_bar'     => 'Điện thoại',
			'add_new'            => 'Thêm điện thoại mới',
			'add_new_item'       => 'Thêm mẫu điện thoại mới',
			'new_item'           => 'Điện thoại mới',
			'edit_item'          => 'Chỉnh sửa điện thoại',
			'view_item'          => 'Xem điện thoại',
			'all_items'          => 'Tất cả điện thoại',
			'search_items'       => 'Tìm kiếm điện thoại',
			'not_found'          => 'Không tìm thấy điện thoại nào.',
			'not_found_in_trash' => 'Không có điện thoại nào trong thùng rác.',
		);

		$args_product = array(
			'labels'             => $labels_product,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'dien-thoai' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 5,
			'menu_icon'          => 'dashicons-smartphone',
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
			'show_in_rest'       => true, // Kích hoạt Gutenberg block editor & REST API
		);

		register_post_type( 'phone_product', $args_product );

		// 2. CPT: Đơn hàng / Yêu cầu tư vấn (phone_order)
		$labels_order = array(
			'name'               => 'Đơn hàng & Tư vấn',
			'singular_name'      => 'Đơn hàng',
			'menu_name'          => 'Đơn Hàng / Tư Vấn',
			'name_admin_bar'     => 'Đơn hàng mới',
			'add_new'            => 'Tạo đơn mới',
			'add_new_item'       => 'Tạo đơn hàng / Yêu cầu mới',
			'new_item'           => 'Đơn hàng mới',
			'edit_item'          => 'Chi tiết đơn hàng',
			'view_item'          => 'Xem đơn hàng',
			'all_items'          => 'Tất cả đơn hàng',
			'search_items'       => 'Tìm kiếm đơn hàng',
			'not_found'          => 'Không tìm thấy đơn hàng nào.',
			'not_found_in_trash' => 'Không có đơn hàng nào trong thùng rác.',
		);

		$args_order = array(
			'labels'             => $labels_order,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => false,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 6,
			'menu_icon'          => 'dashicons-cart',
			'supports'           => array( 'title', 'revisions' ),
			'show_in_rest'       => false,
		);

		register_post_type( 'phone_order', $args_order );
	}
}
