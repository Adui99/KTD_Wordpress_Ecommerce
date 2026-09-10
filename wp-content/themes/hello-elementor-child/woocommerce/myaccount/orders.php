<?php
/**
 * Orders - Minimalist Cards for KTD-Ecommerce
 *
 * @package HelloElementorChild
 * @version 9.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<?php if ( $has_orders ) : ?>

	<div class="ktd-orders-container">
		<div class="ktd-orders-header">
			<h2 class="ktd-orders-title">Lịch Sử Đơn Hàng</h2>
			<span class="ktd-orders-count">Tổng cộng <?php echo esc_html( count( $customer_orders->orders ) ); ?> đơn hàng</span>
		</div>

		<div class="ktd-orders-list">
			<?php
			foreach ( $customer_orders->orders as $customer_order ) {
				$order = wc_get_order( $customer_order );
				if ( ! $order ) {
					continue;
				}
				$order_id    = $order->get_id();
				$status      = $order->get_status();
				$status_name = wc_get_order_status_name( $status );
				$order_url   = $order->get_view_order_url();
				$item_count  = $order->get_item_count() - $order->get_item_count_refunded();
				$items       = $order->get_items();
				?>
				<div class="ktd-order-card ktd-status-<?php echo esc_attr( $status ); ?>">
					<!-- Top Row: ID, Date, Status -->
					<div class="ktd-order-card-header">
						<div class="ktd-order-meta-left">
							<span class="ktd-order-number">
								Đơn hàng <a href="<?php echo esc_url( $order_url ); ?>">#<?php echo esc_html( $order->get_order_number() ); ?></a>
							</span>
							<span class="ktd-order-date">
								<?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?>
							</span>
						</div>
						<div class="ktd-order-meta-right">
							<span class="ktd-status-badge ktd-badge-<?php echo esc_attr( $status ); ?>">
								<?php echo esc_html( $status_name ); ?>
							</span>
						</div>
					</div>

					<!-- Middle Row: Order Items Preview -->
					<div class="ktd-order-items-preview">
						<?php 
						$shown = 0;
						foreach ( $items as $item_id => $item ) :
							if ( $shown >= 3 ) break;
							$product = $item->get_product();
							$image_url = $product ? wp_get_attachment_image_url( $product->get_image_id(), 'thumbnail' ) : wc_placeholder_img_src();
						?>
							<div class="ktd-order-item-row">
								<div class="ktd-order-item-thumb">
									<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $item->get_name() ); ?>" loading="lazy" />
								</div>
								<div class="ktd-order-item-info">
									<span class="ktd-order-item-name"><?php echo esc_html( $item->get_name() ); ?></span>
									<span class="ktd-order-item-qty">Số lượng: <?php echo esc_html( $item->get_quantity() ); ?></span>
								</div>
								<div class="ktd-order-item-total">
									<?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?>
								</div>
							</div>
						<?php 
							$shown++;
						endforeach; 

						if ( count( $items ) > 3 ) :
						?>
							<div class="ktd-order-more-items">
								+ và <?php echo esc_html( count( $items ) - 3 ); ?> sản phẩm khác...
							</div>
						<?php endif; ?>
					</div>

					<!-- Bottom Row: Total & Action CTA -->
					<div class="ktd-order-card-footer">
						<div class="ktd-order-total-wrap">
							<span class="ktd-total-label">Tổng thanh toán (<?php echo esc_html( $item_count ); ?> sản phẩm):</span>
							<span class="ktd-total-amount"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></span>
						</div>
						<div class="ktd-order-actions-wrap">
							<a href="<?php echo esc_url( $order_url ); ?>" class="ktd-btn-view-order">
								<span>Chi tiết đơn hàng</span>
								<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
							</a>
						</div>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>

	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination ktd-orders-pagination">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'woocommerce' ); ?></a>
			<?php endif; ?>

			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php else : ?>

	<div class="ktd-empty-orders-card">
		<div class="ktd-empty-icon">
			<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
				<path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>
			</svg>
		</div>
		<h3 class="ktd-empty-title">Bạn chưa có đơn hàng nào</h3>
		<p class="ktd-empty-desc">Khi bạn đặt mua sản phẩm, chi tiết và trạng thái vận chuyển sẽ hiển thị ngay tại đây.</p>
		<a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="ktd-btn-explore-shop">
			<span>Khám phá sản phẩm ngay</span>
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
		</a>
	</div>

<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
