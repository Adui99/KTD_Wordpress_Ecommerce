<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Phone_Store_Taxonomy_Registrar {

	public function __construct() {
		add_action( 'init', array( $this, 'register_taxonomies' ) );
	}

	public function register_taxonomies() {
		// 1. Taxonomy: Thương hiệu (phone_brand: iPhone/Apple, Samsung, OPPO...)
		$labels_brand = array(
			'name'              => 'Thương hiệu',
			'singular_name'     => 'Thương hiệu',
			'search_items'      => 'Tìm kiếm thương hiệu',
			'all_items'         => 'Tất cả thương hiệu',
			'parent_item'       => 'Thương hiệu cha',
			'parent_item_colon' => 'Thương hiệu cha:',
			'edit_item'         => 'Chỉnh sửa thương hiệu',
			'update_item'       => 'Cập nhật thương hiệu',
			'add_new_item'      => 'Thêm thương hiệu mới',
			'new_item_name'     => 'Tên thương hiệu mới',
			'menu_name'         => 'Thương hiệu',
		);

		$args_brand = array(
			'hierarchical'      => true, // Phân cấp danh mục (như Category)
			'labels'            => $labels_brand,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'thuong-hieu' ),
			'show_in_rest'      => true,
		);

		register_taxonomy( 'phone_brand', array( 'phone_product' ), $args_brand );

		// 2. Taxonomy: Dòng sản phẩm (phone_series: iPhone 15 Series, Galaxy S24 Series, Reno Series...)
		$labels_series = array(
			'name'              => 'Dòng sản phẩm',
			'singular_name'     => 'Dòng sản phẩm',
			'search_items'      => 'Tìm kiếm dòng sản phẩm',
			'all_items'         => 'Tất cả dòng sản phẩm',
			'edit_item'         => 'Chỉnh sửa dòng sản phẩm',
			'update_item'       => 'Cập nhật dòng sản phẩm',
			'add_new_item'      => 'Thêm dòng sản phẩm mới',
			'new_item_name'     => 'Tên dòng sản phẩm mới',
			'menu_name'         => 'Dòng sản phẩm',
		);

		$args_series = array(
			'hierarchical'      => false, // Thẻ phân loại (như Tag)
			'labels'            => $labels_series,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'dong-san-pham' ),
			'show_in_rest'      => true,
		);

		register_taxonomy( 'phone_series', array( 'phone_product' ), $args_series );
	}
}
