<?php
/**
 * My Account Dashboard - Seamless Orders View for Minimalist Architecture
 *
 * @package HelloElementorChild
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( function_exists( 'woocommerce_account_orders' ) ) {
	woocommerce_account_orders( 1 );
}
