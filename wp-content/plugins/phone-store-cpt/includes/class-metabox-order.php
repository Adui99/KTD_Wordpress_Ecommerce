<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Phone_Store_Metabox_Order {

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_order_metaboxes' ) );
		add_action( 'save_post_phone_order', array( $this, 'save_order_metaboxes' ) );
	}

	public function add_order_metaboxes() {
		add_meta_box(
			'phone_order_details',
			'Thông Tin Khách Hàng & Đơn Đặt Hàng',
			array( $this, 'render_order_metabox' ),
			'phone_order',
			'normal',
			'high'
		);
	}

	public function render_order_metabox( $post ) {
		wp_nonce_field( 'phone_save_order_meta', 'phone_order_nonce' );

		$customer_name  = get_post_meta( $post->ID, '_order_customer_name', true );
		$customer_phone = get_post_meta( $post->ID, '_order_customer_phone', true );
		$customer_email = get_post_meta( $post->ID, '_order_customer_email', true );
		$product_name   = get_post_meta( $post->ID, '_order_product_name', true );
		$order_status   = get_post_meta( $post->ID, '_order_status', true );

		if ( empty( $order_status ) ) {
			$order_status = 'pending';
		}
		?>
		<div class="phone-order-meta-wrapper">
			<style>
				.phone-order-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
				.phone-order-field { display: flex; flex-direction: column; margin-bottom: 10px; }
				.phone-order-field label { font-weight: 600; margin-bottom: 5px; color: #1d2327; }
				.phone-order-field input, .phone-order-field select { padding: 6px 10px; border-radius: 4px; border: 1px solid #8c8f94; }
			</style>

			<div class="phone-order-grid">
				<div class="phone-order-field">
					<label for="order_customer_name">Tên khách hàng:</label>
					<input type="text" id="order_customer_name" name="order_customer_name" value="<?php echo esc_attr( $customer_name ); ?>" placeholder="Ví dụ: Nguyễn Văn A">
				</div>

				<div class="phone-order-field">
					<label for="order_customer_phone">Số điện thoại liên hệ:</label>
					<input type="text" id="order_customer_phone" name="order_customer_phone" value="<?php echo esc_attr( $customer_phone ); ?>" placeholder="Ví dụ: 0912345678">
				</div>

				<div class="phone-order-field">
					<label for="order_customer_email">Email khách hàng:</label>
					<input type="email" id="order_customer_email" name="order_customer_email" value="<?php echo esc_attr( $customer_email ); ?>" placeholder="Ví dụ: nguyenvana@gmail.com">
				</div>

				<div class="phone-order-field">
					<label for="order_product_name">Sản phẩm quan tâm / Chọn mua:</label>
					<input type="text" id="order_product_name" name="order_product_name" value="<?php echo esc_attr( $product_name ); ?>" placeholder="Ví dụ: iPhone 15 Pro Max 256GB">
				</div>

				<div class="phone-order-field" style="grid-column: span 2;">
					<label for="order_status">Trạng thái xử lý đơn hàng:</label>
					<select id="order_status" name="order_status">
						<option value="pending" <?php selected( $order_status, 'pending' ); ?>>Mới tiếp nhận (Pending)</option>
						<option value="contacted" <?php selected( $order_status, 'contacted' ); ?>>Đã tư vấn / Liên hệ (Contacted)</option>
						<option value="completed" <?php selected( $order_status, 'completed' ); ?>>Hoàn thành (Completed)</option>
						<option value="cancelled" <?php selected( $order_status, 'cancelled' ); ?>>Hủy đơn (Cancelled)</option>
					</select>
				</div>
			</div>
		</div>
		<?php
	}

	public function save_order_metaboxes( $post_id ) {
		if ( ! isset( $_POST['phone_order_nonce'] ) || ! wp_verify_nonce( $_POST['phone_order_nonce'], 'phone_save_order_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$fields = array(
			'order_customer_name'  => '_order_customer_name',
			'order_customer_phone' => '_order_customer_phone',
			'order_customer_email' => '_order_customer_email',
			'order_product_name'   => '_order_product_name',
			'order_status'         => '_order_status',
		);

		foreach ( $fields as $input_key => $meta_key ) {
			if ( isset( $_POST[ $input_key ] ) ) {
				$val = sanitize_text_field( $_POST[ $input_key ] );
				update_post_meta( $post_id, $meta_key, $val );
			}
		}
	}
}
