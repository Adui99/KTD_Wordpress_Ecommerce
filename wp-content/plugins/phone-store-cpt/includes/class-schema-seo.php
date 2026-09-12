<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Phone_Store_Schema_SEO {

	public function __construct() {
		add_action( 'wp_head', array( $this, 'inject_product_json_ld' ) );
	}

	/**
	 * Tự động chèn JSON-LD Schema.org/Product vào header khi xem chi tiết điện thoại
	 */
	public function inject_product_json_ld() {
		if ( ! is_singular( 'phone_product' ) ) {
			return;
		}

		global $post;
		if ( ! $post ) {
			return;
		}

		$post_id      = $post->ID;
		$price        = get_post_meta( $post_id, '_phone_price', true );
		$sale_price   = get_post_meta( $post_id, '_phone_sale_price', true );
		$condition    = get_post_meta( $post_id, '_phone_condition', true );
		$geo_location = get_post_meta( $post_id, '_phone_geo_location', true );
		$stock_status = get_post_meta( $post_id, '_phone_stock_status', true );

		// Lấy tên thương hiệu từ taxonomy phone_brand
		$brands = get_the_terms( $post_id, 'phone_brand' );
		$brand_name = 'Phone Store';
		if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) {
			$brand_name = $brands[0]->name;
		}

		// Tính toán giá hiển thị (ưu tiên giá khuyến mãi nếu có)
		$raw_price = ! empty( $sale_price ) ? $sale_price : $price;
		$final_price = preg_replace( '/[^0-9]/', '', (string) $raw_price );
		if ( empty( $final_price ) ) {
			$final_price = '0';
		}

		// Ánh xạ tình trạng kho hàng sang Schema Availability
		$availability = 'https://schema.org/InStock';
		if ( 'outofstock' === $stock_status ) {
			$availability = 'https://schema.org/OutOfStock';
		} elseif ( 'preorder' === $stock_status ) {
			$availability = 'https://schema.org/PreOrder';
		}

		// Ánh xạ tình trạng máy sang Schema ItemCondition
		$item_condition = 'https://schema.org/NewCondition';
		if ( ! empty( $condition ) && false !== stripos( $condition, '99%' ) ) {
			$item_condition = 'https://schema.org/UsedCondition';
		}

		// Lấy ảnh đại diện bài viết
		$image_url = get_the_post_thumbnail_url( $post_id, 'full' );
		if ( ! $image_url ) {
			$image_url = '';
		}

		$schema = array(
			'@context'    => 'https://schema.org/',
			'@type'       => 'Product',
			'name'        => get_the_title( $post_id ),
			'image'       => $image_url,
			'description' => wp_strip_all_tags( get_the_excerpt( $post_id ) ),
			'brand'       => array(
				'@type' => 'Brand',
				'name'  => $brand_name,
			),
			'offers'      => array(
				'@type'         => 'Offer',
				'priceCurrency' => 'VND',
				'price'         => $final_price,
				'availability'  => $availability,
				'itemCondition' => $item_condition,
				'areaServed'    => ! empty( $geo_location ) ? $geo_location : 'Vietnam',
				'url'           => get_permalink( $post_id ),
			),
		);

		echo "\n<!-- Phone Store SEO & GEO Schema JSON-LD -->\n";
		echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . "</script>\n<!-- /Phone Store SEO & GEO Schema JSON-LD -->\n\n";
	}
}
