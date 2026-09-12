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
			'📱 Thông Số Kỹ Thuật, Giá Bán & Tối Ưu Marketing / SEO',
			array( $this, 'render_product_metabox' ),
			'phone_product',
			'normal',
			'high'
		);
	}

	public function render_product_metabox( $post ) {
		wp_nonce_field( 'phone_save_product_meta', 'phone_product_nonce' );

		// 1. Giá & Kho
		$price        = get_post_meta( $post->ID, '_phone_price', true );
		$sale_price   = get_post_meta( $post->ID, '_phone_sale_price', true );
		$stock_status = get_post_meta( $post->ID, '_phone_stock_status', true );
		if ( empty( $stock_status ) ) {
			$stock_status = 'instock';
		}

		// 2. Cấu hình
		$storage = get_post_meta( $post->ID, '_phone_storage', true );
		$ram     = get_post_meta( $post->ID, '_phone_ram', true );
		$chipset = get_post_meta( $post->ID, '_phone_chipset', true );
		$screen  = get_post_meta( $post->ID, '_phone_screen', true );
		$battery = get_post_meta( $post->ID, '_phone_battery', true );

		// 3. Hình thức & Tình trạng (kế thừa từ lab2)
		$color     = get_post_meta( $post->ID, '_phone_color', true );
		$condition = get_post_meta( $post->ID, '_phone_condition', true );
		if ( empty( $condition ) ) {
			$condition = 'new_seal';
		}

		// 4. Marketing & Local SEO (kế thừa từ lab2)
		$cta_text     = get_post_meta( $post->ID, '_phone_cta_text', true );
		$geo_location = get_post_meta( $post->ID, '_phone_geo_location', true );
		?>
		<div class="phone-meta-container">
			
			<!-- SECTION 1: GIÁ BÁN & TÌNH TRẠNG KHO -->
			<div class="phone-meta-section">
				<h4 class="phone-section-title"><span class="dashicons dashicons-money-alt"></span> 1. Giá Bán & Tồn Kho</h4>
				<div class="phone-meta-grid">
					<div class="phone-meta-field">
						<label for="phone_price">Giá gốc niêm yết (VNĐ):</label>
						<input type="number" id="phone_price" name="phone_price" value="<?php echo esc_attr( $price ); ?>" placeholder="Ví dụ: 34990000">
					</div>

					<div class="phone-meta-field">
						<label for="phone_sale_price">Giá khuyến mãi (VNĐ):</label>
						<input type="number" id="phone_sale_price" name="phone_sale_price" value="<?php echo esc_attr( $sale_price ); ?>" placeholder="Ví dụ: 31490000">
					</div>

					<div class="phone-meta-field phone-full-width">
						<label for="phone_stock_status">Tình trạng kho hàng:</label>
						<select id="phone_stock_status" name="phone_stock_status">
							<option value="instock" <?php selected( $stock_status, 'instock' ); ?>>Còn hàng (In Stock)</option>
							<option value="outofstock" <?php selected( $stock_status, 'outofstock' ); ?>>Tạm hết hàng (Out of Stock)</option>
							<option value="preorder" <?php selected( $stock_status, 'preorder' ); ?>>Đặt hàng trước (Pre-order)</option>
						</select>
					</div>
				</div>
			</div>

			<!-- SECTION 2: THÔNG SỐ CẤU HÌNH KỸ THUẬT -->
			<div class="phone-meta-section">
				<h4 class="phone-section-title"><span class="dashicons dashicons-admin-generic"></span> 2. Thông Số Kỹ Thuật Phần Cứng</h4>
				<div class="phone-meta-grid">
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
							<option value="8GB" <?php selected( $ram, '8GB' ); ?>>8 GB RAM</option>
							<option value="12GB" <?php selected( $ram, '12GB' ); ?>>12 GB RAM</option>
							<option value="16GB" <?php selected( $ram, '16GB' ); ?>>16 GB RAM</option>
							<option value="24GB" <?php selected( $ram, '24GB' ); ?>>24 GB RAM</option>
						</select>
					</div>

					<div class="phone-meta-field">
						<label for="phone_chipset">Chipset / CPU:</label>
						<input type="text" id="phone_chipset" name="phone_chipset" value="<?php echo esc_attr( $chipset ); ?>" placeholder="VD: Apple A17 Pro / Snapdragon 8 Gen 3">
					</div>

					<div class="phone-meta-field">
						<label for="phone_screen">Màn hình hiển thị:</label>
						<input type="text" id="phone_screen" name="phone_screen" value="<?php echo esc_attr( $screen ); ?>" placeholder="VD: 6.7 inch Super Retina XDR OLED 120Hz">
					</div>

					<div class="phone-meta-field phone-full-width">
						<label for="phone_battery">Dung lượng Pin & Sạc:</label>
						<input type="text" id="phone_battery" name="phone_battery" value="<?php echo esc_attr( $battery ); ?>" placeholder="VD: 4,422 mAh, Sạc nhanh PD 20W">
					</div>
				</div>
			</div>

			<!-- SECTION 3: HÌNH THỨC & TÌNH TRẠNG MÁY -->
			<div class="phone-meta-section">
				<h4 class="phone-section-title"><span class="dashicons dashicons-smartphone"></span> 3. Hình Thức & Tình Trạng Máy</h4>
				<div class="phone-meta-grid">
					<div class="phone-meta-field">
						<label for="phone_color">Màu sắc / Chất liệu cao cấp:</label>
						<input type="text" id="phone_color" name="phone_color" value="<?php echo esc_attr( $color ); ?>" placeholder="VD: Titan Tự Nhiên, Titan Sa Mạc, Xanh Titan">
					</div>

					<div class="phone-meta-field">
						<label for="phone_condition">Tình trạng máy / Gói bảo hành:</label>
						<select id="phone_condition" name="phone_condition">
							<option value="Mới 100% Nguyên Seal - Chính Hãng VN/A" <?php selected( $condition, 'Mới 100% Nguyên Seal - Chính Hãng VN/A' ); ?>>Mới 100% Nguyên Seal - Chính Hãng VN/A</option>
							<option value="Likenew 99% Fullbox VIP" <?php selected( $condition, 'Likenew 99% Fullbox VIP' ); ?>>Likenew 99% Fullbox VIP</option>
							<option value="Phiên Bản Độc Bản / Limited Edition" <?php selected( $condition, 'Phiên Bản Độc Bản / Limited Edition' ); ?>>Phiên Bản Độc Bản / Limited Edition</option>
						</select>
					</div>
				</div>
			</div>

			<!-- SECTION 4: MARKETING CTA & LOCAL SEO -->
			<div class="phone-meta-section">
				<h4 class="phone-section-title"><span class="dashicons dashicons-megaphone"></span> 4. Call To Action & Local SEO (GEO Target)</h4>
				<div class="phone-meta-grid">
					<div class="phone-meta-field phone-full-width">
						<label for="phone_cta_text">Nội dung nút CTA nổi bật:</label>
						<input type="text" id="phone_cta_text" name="phone_cta_text" value="<?php echo esc_attr( $cta_text ); ?>" placeholder="VD: 🔥 MUA NGAY - TẶNG GÓI BẢO HÀNH VIP 24 THÁNG & GIAO 2H">
					</div>

					<div class="phone-meta-field phone-full-width">
						<label for="phone_geo_location">Khu vực Giao Hàng & Local SEO (GEO Target):</label>
						<input type="text" id="phone_geo_location" name="phone_geo_location" value="<?php echo esc_attr( $geo_location ); ?>" placeholder="VD: TP.HCM, Hà Nội, Đà Nẵng - Giao Hỏa Tốc trong 2 Giờ">
						<p class="description">Dữ liệu này sẽ tự động được đưa vào cấu trúc <code>Schema.org/Product</code> (areaServed) giúp tối ưu tìm kiếm theo vị trí địa lý.</p>
					</div>
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
			// Giá & kho
			'phone_price'        => '_phone_price',
			'phone_sale_price'   => '_phone_sale_price',
			'phone_stock_status' => '_phone_stock_status',
			// Cấu hình
			'phone_storage'      => '_phone_storage',
			'phone_ram'          => '_phone_ram',
			'phone_chipset'      => '_phone_chipset',
			'phone_screen'       => '_phone_screen',
			'phone_battery'      => '_phone_battery',
			// Hình thức & Tình trạng
			'phone_color'        => '_phone_color',
			'phone_condition'    => '_phone_condition',
			// Marketing & SEO
			'phone_cta_text'     => '_phone_cta_text',
			'phone_geo_location' => '_phone_geo_location',
		);

		foreach ( $fields as $input_key => $meta_key ) {
			if ( isset( $_POST[ $input_key ] ) ) {
				$val = sanitize_text_field( wp_unslash( $_POST[ $input_key ] ) );
				update_post_meta( $post_id, $meta_key, $val );
			}
		}
	}
}
