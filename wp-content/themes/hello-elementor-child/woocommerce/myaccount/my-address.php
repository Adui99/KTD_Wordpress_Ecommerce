<?php
/**
 * My Addresses - Custom Minimalist Grid for KTD-Ecommerce
 *
 * @package HelloElementorChild
 * @version 9.3.0
 */

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing'  => 'Địa chỉ thanh toán',
			'shipping' => 'Địa chỉ nhận hàng',
		),
		$customer_id
	);
} else {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing' => 'Địa chỉ thanh toán',
		),
		$customer_id
	);
}
?>

<div class="ktd-addresses-wrapper">
	<div class="ktd-addresses-header">
		<h2 class="ktd-addresses-title">Sổ Địa Chỉ Giao Nhận</h2>
		<p class="ktd-addresses-desc">Các địa chỉ bên dưới sẽ được sử dụng làm thông tin mặc định khi thanh toán và giao hàng.</p>
	</div>

	<div class="ktd-addresses-grid">
		<?php foreach ( $get_addresses as $name => $address_title ) : 
			$address     = wc_get_account_formatted_address( $name );
			$is_billing  = 'billing' === $name;
			$edit_url    = wc_get_endpoint_url( 'edit-address', $name );
		?>
			<div class="ktd-address-card ktd-address-<?php echo esc_attr( $name ); ?>">
				<div class="ktd-address-card-header">
					<div class="ktd-address-type">
						<span class="ktd-address-icon">
							<?php if ( $is_billing ) : ?>
								<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
								</svg>
							<?php else : ?>
								<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/>
								</svg>
							<?php endif; ?>
						</span>
						<h3 class="ktd-address-title-text"><?php echo esc_html( $address_title ); ?></h3>
					</div>
					<div class="ktd-address-action">
						<a href="<?php echo esc_url( $edit_url ); ?>" class="ktd-btn-edit-address">
							<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
							</svg>
							<span><?php echo $address ? 'Chỉnh sửa' : 'Thêm địa chỉ'; ?></span>
						</a>
					</div>
				</div>

				<div class="ktd-address-card-body">
					<?php if ( $address ) : ?>
						<address class="ktd-address-content">
							<?php echo wp_kses_post( $address ); ?>
						</address>
					<?php else : ?>
						<div class="ktd-address-empty">
							<p class="ktd-empty-text">Bạn chưa thiết lập <?php echo esc_html( mb_strtolower( $address_title, 'UTF-8' ) ); ?>.</p>
							<a href="<?php echo esc_url( $edit_url ); ?>" class="ktd-empty-add-btn">
								+ Thêm địa chỉ ngay
							</a>
						</div>
					<?php endif; ?>

					<?php do_action( 'woocommerce_my_account_after_my_address', $name ); ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
