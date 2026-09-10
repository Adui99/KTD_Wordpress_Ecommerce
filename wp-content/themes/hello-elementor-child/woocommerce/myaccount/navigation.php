<?php
/**
 * My Account Horizontal Navigation - Minimalist for KTD-Ecommerce
 *
 * @package HelloElementorChild
 * @version 9.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="woocommerce-MyAccount-navigation ktd-minimal-nav" aria-label="<?php esc_html_e( 'Account pages', 'woocommerce' ); ?>">
	<ul class="ktd-minimal-tab-list">
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : 
			$is_active = wc_is_current_account_menu_item( $endpoint );
		?>
			<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?> <?php echo $is_active ? 'is-active' : ''; ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" <?php echo $is_active ? 'aria-current="page"' : ''; ?>>
					<span class="ktd-tab-icon">
						<?php
						switch ( $endpoint ) {
							case 'orders':
								?>
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>
								</svg>
								<?php
								break;
							case 'edit-account':
								?>
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/>
								</svg>
								<?php
								break;
							case 'edit-address':
								?>
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/>
								</svg>
								<?php
								break;
							default:
								?>
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg>
								<?php
								break;
						}
						?>
					</span>
					<span class="ktd-tab-label"><?php echo esc_html( $label ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
