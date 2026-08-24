<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Phone_Store_Metabox_Product {

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_product_metaboxes' ) );
		add_action( 'save_post_phone_product', array( $this, 'save_product_metaboxes' ) );
	}

	public function add_product_metaboxes() {
		add_meta_box(
			'phone_product_details',
			'Thông Số Kỹ Thuật & Giá Bán Điện Thoại',
			array( $this, 'render_product_metabox' ),
			'phone_product',
			'normal',
			'high'
		);
	}

	public function render_product_metabox( $post ) {
		wp_nonce_field( 'phone_save_product_meta', 'phone_product_nonce' );

		$price        = get_post_meta( $post->ID, '_phone_price', true );
		$sale_price   = get_post_meta( $post->ID, '_phone_sale_price', true );
		$storage      = get_post_meta( $post->ID, '_phone_storage', true );
		$ram          = get_post_meta( $post->ID, '_phone_ram', true );
		$chipset      = get_post_meta( $post->ID, '_phone_chipset', true );
		$screen       = get_post_meta( $post->ID, '_phone_screen', true );
		$battery      = get_post_meta( $post->ID, '_phone_battery', true );
		$stock_status = get_post_meta( $post->ID, '_phone_stock_status', true );
		if ( empty( $stock_status ) ) {
			$stock_status = 'instock';
		}
		?>
		<div class="phone-meta-wrapper">
			<style>
				.phone-meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
				.phone-meta-field { display: flex; flex-direction: column; margin-bottom: 10px; }
				.phone-meta-field label { font-weight: 600; margin-bottom: 5px; color: #1d2327; }
				.phone-meta-field input, .phone-meta-field select { padding: 6px 10px; border-radius: 4px; border: 1fr solid #8c8f94; }
			</style>
			
			<div class="phone-meta-grid">
				<div class="phone-meta-field">
					<label for="phone_price">Giá niêm yết (VNĐ):</label>
					<input type="number" id="phone_price" name="phone_price" value="<?php echo esc_attr( $price ); ?>" placeholder="Ví dụ: 29990000">
				</div>

				<div class="phone-meta-field">
					<label for="phone_sale_price">Giá khuyến mãi (VNĐ):</label>
					<input type="number" id="phone_sale_price" name="phone_sale_price" value="<?php echo esc_attr( $sale_price ); ?>" placeholder="Ví dụ: 27490000">
				</div>

				<div class="phone-meta-field">
					<label for="phone_storage">Dung lượng bộ nhớ (Storage):</label>
					<select id="phone_storage" name="phone_storage">
						<option value="128GB" <?php selected( $storage, '128GB' ); ?>>128 GB</option>
						<option value="256GB" <?php selected( $storage, '256GB' ); ?>>256 GB</option>
						<option value="512GB" <?php selected( $storage, '512GB' ); ?>>512 GB</option>
						<option value="1TB" <?php selected( $storage, '1TB' ); ?>>1 TB</option>
					</select>
				</div>

				<div class="phone-meta-field">
					<label for="phone_ram">Dung lượng RAM:</label>
					<select id="phone_ram" name="phone_ram">
						<option value="8GB" <?php selected( $ram, '8GB' ); ?>>8 GB</option>
						<option value="12GB" <?php selected( $ram, '12GB' ); ?>>12 GB</option>
						<option value="16GB" <?php selected( $ram, '16GB' ); ?>>16 GB</option>
						<option value="24GB" <?php selected( $ram, '24GB' ); ?>>24 GB</option>
					</select>
				</div>

				<div class="phone-meta-field">
					<label for="phone_chipset">Chip vi xử lý (CPU/Chipset):</label>
					<input type="text" id="phone_chipset" name="phone_chipset" value="<?php echo esc_attr( $chipset ); ?>" placeholder="Ví dụ: Apple A17 Pro / Snapdragon 8 Gen 3">
				</div>

				<div class="phone-meta-field">
					<label for="phone_screen">Thông số Màn hình:</label>
					<input type="text" id="phone_screen" name="phone_screen" value="<?php echo esc_attr( $screen ); ?>" placeholder="Ví dụ: 6.7 inch Super Retina XDR OLED 120Hz">
				</div>

				<div class="phone-meta-field">
					<label for="phone_battery">Dung lượng Pin:</label>
					<input type="text" id="phone_battery" name="phone_battery" value="<?php echo esc_attr( $battery ); ?>" placeholder="Ví dụ: 4422 mAh, Sạc nhanh 20W">
				</div>

				<div class="phone-meta-field">
					<label for="phone_stock_status">Tình trạng kho hàng:</label>
					<select id="phone_stock_status" name="phone_stock_status">
						<option value="instock" <?php selected( $stock_status, 'instock' ); ?>>Còn hàng (In Stock)</option>
						<option value="outofstock" <?php selected( $stock_status, 'outofstock' ); ?>>Hết hàng (Out of Stock)</option>
						<option value="preorder" <?php selected( $stock_status, 'preorder' ); ?>>Đặt hàng trước (Pre-order)</option>
					</select>
				</div>
			</div>
		</div>
		<?php
	}

	public function save_product_metaboxes( $post_id ) {
		if ( ! isset( $_POST['phone_product_nonce'] ) || ! wp_verify_nonce( $_POST['phone_product_nonce'], 'phone_save_product_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$fields = array(
			'phone_price'        => '_phone_price',
			'phone_sale_price'   => '_phone_sale_price',
			'phone_storage'      => '_phone_storage',
			'phone_ram'          => '_phone_ram',
			'phone_chipset'      => '_phone_chipset',
			'phone_screen'       => '_phone_screen',
			'phone_battery'      => '_phone_battery',
			'phone_stock_status' => '_phone_stock_status',
		);

		foreach ( $fields as $input_key => $meta_key ) {
			if ( isset( $_POST[ $input_key ] ) ) {
				$val = sanitize_text_field( $_POST[ $input_key ] );
				update_post_meta( $post_id, $meta_key, $val );
			}
		}
	}
}
