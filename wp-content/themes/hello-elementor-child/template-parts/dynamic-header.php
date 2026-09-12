<?php
/**
 * Custom Header for Hello Elementor Child
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$site_name   = get_bloginfo( 'name' );
$home_url    = home_url( '/' );
$shop_url    = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$cart_url    = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );
$account_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' );

$cart_count = 0;
if ( function_exists( 'WC' ) && WC()->cart ) {
	$cart_count = WC()->cart->get_cart_contents_count();
}

$blog_url    = get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' );

// Current page detection for active class
$is_home     = is_front_page();
$is_shop     = function_exists( 'is_shop' ) && is_shop();
$is_blog     = is_home() || is_category() || is_tag() || is_singular( 'post' ) || ( is_archive() && ! ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) );
$is_about    = is_page( 'about' );
$is_contact  = is_page( 'contact-us' ) || is_page( 'contact' );
?>

<header id="site-header" class="site-header header-full-width ktd-custom-header">
	<div class="ktd-header-container">
		<!-- Site Branding / Logo -->
		<div class="ktd-header-brand">
			<?php if ( has_custom_logo() ) : ?>
				<div class="ktd-site-logo"><?php the_custom_logo(); ?></div>
			<?php else : ?>
				<a href="<?php echo esc_url( $home_url ); ?>" class="ktd-brand-link" title="KTD STORE">
					KTD STORE
				</a>
			<?php endif; ?>
		</div>

		<!-- Desktop Navigation Menu -->
		<nav class="ktd-header-nav" aria-label="Main Navigation">
			<ul class="ktd-nav-list">
				<li class="ktd-nav-item">
					<a href="<?php echo esc_url( $home_url ); ?>" class="ktd-nav-link <?php echo $is_home ? 'active' : ''; ?>">
						Home
					</a>
				</li>
				<li class="ktd-nav-item">
					<a href="<?php echo esc_url( $shop_url ); ?>" class="ktd-nav-link <?php echo $is_shop ? 'active' : ''; ?>">
						Products
					</a>
				</li>
				<li class="ktd-nav-item">
					<a href="<?php echo esc_url( $blog_url ); ?>" class="ktd-nav-link <?php echo $is_blog ? 'active' : ''; ?>">
						Blog
					</a>
				</li>
				<li class="ktd-nav-item">
					<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="ktd-nav-link <?php echo $is_about ? 'active' : ''; ?>">
						About
					</a>
				</li>
				<li class="ktd-nav-item">
					<a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" class="ktd-nav-link <?php echo $is_contact ? 'active' : ''; ?>">
						Contact
					</a>
				</li>
			</ul>
		</nav>

		<!-- Right Actions: Cart, User & Mobile Toggle -->
		<div class="ktd-header-actions">
			<!-- Cart Icon Button -->
			<a href="<?php echo esc_url( $cart_url ); ?>" class="ktd-action-btn ktd-cart-btn" title="Giỏ hàng KTD Store" aria-label="<?php echo esc_attr( sprintf( _n( 'Giỏ hàng (%d sản phẩm)', 'Giỏ hàng (%d sản phẩm)', $cart_count, 'hello-elementor-child' ), $cart_count ) ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
				</svg>
				<span class="ktd-cart-badge">
					<span class="ktd-cart-count"><?php echo esc_html( $cart_count ); ?></span>
				</span>
			</a>

			<!-- User / Account Button -->
			<?php if ( is_user_logged_in() ) : 
				$current_user = wp_get_current_user();
				$name_parts   = explode( ' ', trim( $current_user->display_name ?: $current_user->user_login ) );
				$short_name   = end( $name_parts );
			?>
				<a href="<?php echo esc_url( $account_url ); ?>" class="ktd-action-btn ktd-user-btn is-logged-in" title="Tài khoản: <?php echo esc_attr( $current_user->display_name ); ?>" aria-label="Tài khoản">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
						<path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
					</svg>
					<span class="ktd-user-greeting">Xin chào, <strong><?php echo esc_html( $short_name ); ?></strong></span>
				</a>
			<?php else : ?>
				<a href="<?php echo esc_url( $account_url ); ?>" class="ktd-action-btn ktd-user-btn" title="Tài khoản KTD Store" aria-label="Tài khoản">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
						<path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
					</svg>
				</a>
			<?php endif; ?>

			<!-- Mobile Hamburger Toggle -->
			<button type="button" class="ktd-mobile-toggle" id="ktdMobileToggle" aria-label="Toggle navigation" aria-expanded="false">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
				</svg>
			</button>
		</div>
	</div>

	<!-- Mobile Menu Drawer -->
	<div class="ktd-mobile-drawer" id="ktdMobileDrawer">
		<ul class="ktd-mobile-nav-list">
			<li><a href="<?php echo esc_url( $home_url ); ?>" class="ktd-mobile-nav-link <?php echo $is_home ? 'active' : ''; ?>">Home</a></li>
			<li><a href="<?php echo esc_url( $shop_url ); ?>" class="ktd-mobile-nav-link <?php echo $is_shop ? 'active' : ''; ?>">Products</a></li>
			<li><a href="<?php echo esc_url( $blog_url ); ?>" class="ktd-mobile-nav-link <?php echo $is_blog ? 'active' : ''; ?>">Blog</a></li>
			<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="ktd-mobile-nav-link <?php echo $is_about ? 'active' : ''; ?>">About</a></li>
			<li><a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" class="ktd-mobile-nav-link <?php echo $is_contact ? 'active' : ''; ?>">Contact</a></li>
		</ul>
	</div>
</header>
