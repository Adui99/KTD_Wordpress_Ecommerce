<?php
/**
 * Thankyou Page - Custom KTD Store Order Received & VietQR Template
 *
 * @package HelloElementorChild
 * @version 8.1.0
 * @var WC_Order|false $order
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order ktd-thankyou-container">

	<?php if ( $order ) : ?>

		<?php do_action( 'woocommerce_before_thankyou', $order->get_id() ); ?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<div class="ktd-thankyou-notice ktd-notice-failed">
				<div class="ktd-notice-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
				</div>
				<div class="ktd-notice-content">
					<h2>Giao dịch chưa hoàn tất</h2>
					<p>Rất tiếc, đơn hàng của bạn chưa thể xử lý. Vui lòng thử thanh toán lại hoặc chọn phương thức khác.</p>
					<div class="ktd-notice-actions">
						<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay ktd-btn-primary">Thanh toán lại</a>
					</div>
				</div>
			</div>

		<?php else : ?>

			<!-- 1. Success Notification Card -->
			<div class="ktd-thankyou-notice ktd-notice-success">
				<div class="ktd-notice-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
				</div>
				<div class="ktd-notice-content">
					<h2 class="ktd-thankyou-title">Cảm ơn bạn! Đơn hàng đã được tiếp nhận</h2>
					<p class="ktd-thankyou-subtitle">Mã đơn hàng: <strong>#<?php echo esc_html( $order->get_order_number() ); ?></strong> • Chi tiết xác nhận đã được gửi đến email <strong><?php echo esc_html( $order->get_billing_email() ); ?></strong></p>
				</div>
			</div>

			<!-- 2. Order Overview Badges Grid -->
			<div class="ktd-order-overview-grid">
				<div class="ktd-overview-badge">
					<span class="ktd-badge-label">Mã đơn hàng</span>
					<strong class="ktd-badge-value">#<?php echo esc_html( $order->get_order_number() ); ?></strong>
				</div>
				<div class="ktd-overview-badge">
					<span class="ktd-badge-label">Ngày đặt hàng</span>
					<strong class="ktd-badge-value"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></strong>
				</div>
				<div class="ktd-overview-badge">
					<span class="ktd-badge-label">Tổng thanh toán</span>
					<strong class="ktd-badge-value ktd-price-highlight"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong>
				</div>
				<div class="ktd-overview-badge">
					<span class="ktd-badge-label">Phương thức thanh toán</span>
					<strong class="ktd-badge-value"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
				</div>
			</div>

			<!-- 3. Dynamic VietQR Card (If BACS Payment) -->
			<?php if ( 'bacs' === $order->get_payment_method() && function_exists( 'ktd_get_vietqr_data' ) ) : 
				$qr_data = ktd_get_vietqr_data( $order->get_order_number(), (float) $order->get_total() );
			?>
				<div class="ktd-vietqr-card" id="ktdVietQrCard">
					<div class="ktd-vietqr-header">
						<div class="ktd-vietqr-header-left">
							<span class="ktd-vietqr-pill">THANH TOÁN TỰ ĐỘNG NAPAS247</span>
							<h3 class="ktd-vietqr-title">Quét Mã VietQR Để Hoàn Tất Thanh Toán</h3>
							<p class="ktd-vietqr-desc">Mở ứng dụng ngân hàng (Vietcombank, MB, Techcombank, Momo, v.v.) và quét mã bên dưới. Số tiền và nội dung sẽ được điền tự động chính xác 100%.</p>
						</div>
					</div>

					<div class="ktd-vietqr-body">
						<!-- Left: QR Code Image -->
						<div class="ktd-vietqr-image-box">
							<div class="ktd-vietqr-frame">
								<img src="<?php echo esc_url( $qr_data['qr_image_url'] ); ?>" alt="Mã VietQR thanh toán đơn hàng #<?php echo esc_attr( $order->get_order_number() ); ?>" class="ktd-vietqr-img" width="280" height="280" />
							</div>
							<span class="ktd-vietqr-hint">
								<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
								Quét bằng camera hoặc app Banking bất kỳ
							</span>
						</div>

						<!-- Right: Bank Details with 1-Click Copy Buttons -->
						<div class="ktd-vietqr-details-box">
							<div class="ktd-qr-info-row">
								<div class="ktd-qr-info-label">Ngân hàng thụ hưởng</div>
								<div class="ktd-qr-info-val">
									<strong><?php echo esc_html( $qr_data['bank_name'] ); ?></strong>
								</div>
							</div>

							<div class="ktd-qr-info-row">
								<div class="ktd-qr-info-label">Chủ tài khoản</div>
								<div class="ktd-qr-info-val">
									<strong><?php echo esc_html( $qr_data['account_name'] ); ?></strong>
								</div>
							</div>

							<div class="ktd-qr-info-row">
								<div class="ktd-qr-info-label">Số tài khoản</div>
								<div class="ktd-qr-info-val ktd-with-copy">
									<strong class="ktd-copyable-text"><?php echo esc_html( $qr_data['account_no'] ); ?></strong>
									<button type="button" class="ktd-copy-btn" data-copy="<?php echo esc_attr( $qr_data['account_no'] ); ?>" title="Sao chép số tài khoản">
										<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
										<span>Sao chép</span>
									</button>
								</div>
							</div>

							<div class="ktd-qr-info-row">
								<div class="ktd-qr-info-label">Số tiền cần chuyển</div>
								<div class="ktd-qr-info-val ktd-with-copy">
									<strong class="ktd-copyable-text ktd-price-red"><?php echo esc_html( number_format( $qr_data['amount'], 0, ',', '.' ) . ' ₫' ); ?></strong>
									<button type="button" class="ktd-copy-btn" data-copy="<?php echo esc_attr( $qr_data['amount'] ); ?>" title="Sao chép số tiền">
										<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
										<span>Sao chép</span>
									</button>
								</div>
							</div>

							<div class="ktd-qr-info-row">
								<div class="ktd-qr-info-label">Nội dung chuyển khoản</div>
								<div class="ktd-qr-info-val ktd-with-copy">
									<strong class="ktd-copyable-text ktd-code-highlight"><?php echo esc_html( $qr_data['memo'] ); ?></strong>
									<button type="button" class="ktd-copy-btn" data-copy="<?php echo esc_attr( $qr_data['memo'] ); ?>" title="Sao chép nội dung">
										<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
										<span>Sao chép</span>
									</button>
								</div>
							</div>

							<div class="ktd-vietqr-security-note">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
								<span>Vui lòng giữ nguyên nội dung chuyển khoản để đơn hàng được duyệt tự động nhanh nhất.</span>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<!-- 4. Standard Order Details (Products & Address) -->
			<div class="ktd-thankyou-details-card">
				<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
			</div>

			<!-- 5. Navigation Actions -->
			<div class="ktd-thankyou-actions">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ktd-btn-secondary">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
					<span>Tiếp tục mua sắm</span>
				</a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_endpoint_url( 'orders', '', wc_get_page_permalink( 'myaccount' ) ) ); ?>" class="ktd-btn-primary">
						<span>Xem đơn hàng của tôi</span>
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
					</a>
				<?php endif; ?>
			</div>

		<?php endif; ?>

	<?php else : ?>

		<div class="ktd-thankyou-notice ktd-notice-success">
			<h2>Cảm ơn bạn. Đơn hàng của bạn đã được nhận.</h2>
		</div>

	<?php endif; ?>

</div>
