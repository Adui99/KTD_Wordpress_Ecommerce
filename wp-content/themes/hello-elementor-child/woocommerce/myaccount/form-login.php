<?php
/**
 * Custom KTD Store Login & Registration Modal Template
 *
 * @package HelloElementorChild
 * @version 9.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_customer_login_form' );
?>

<div class="ktd-auth-container" id="customer_login">

	<!-- 1. Centered Login Card -->
	<div class="ktd-auth-card ktd-login-card">
		<div class="ktd-auth-card-header">
			<h2 class="ktd-auth-title"><?php esc_html_e( 'Đăng Nhập', 'hello-elementor-child' ); ?></h2>
			<p class="ktd-auth-subtitle"><?php esc_html_e( 'Chào mừng bạn quay trở lại với KTD Store', 'hello-elementor-child' ); ?></p>
		</div>

		<form class="woocommerce-form woocommerce-form-login login" method="post" novalidate>

			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="username"><?php esc_html_e( 'Tên đăng nhập hoặc email', 'hello-elementor-child' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" placeholder="email@example.com" value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" />
			</p>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="password"><?php esc_html_e( 'Mật khẩu', 'hello-elementor-child' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
				<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" placeholder="••••••••" required aria-required="true" />
			</p>

			<?php do_action( 'woocommerce_login_form' ); ?>

			<div class="ktd-form-options-row">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Ghi nhớ đăng nhập', 'hello-elementor-child' ); ?></span>
				</label>
				<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="ktd-lost-password-link"><?php esc_html_e( 'Quên mật khẩu?', 'hello-elementor-child' ); ?></a>
			</div>

			<p class="form-row ktd-submit-row">
				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
				<button type="submit" class="woocommerce-button button woocommerce-form-login__submit ktd-auth-submit-btn" name="login" value="<?php esc_attr_e( 'Đăng nhập', 'hello-elementor-child' ); ?>"><?php esc_html_e( 'ĐĂNG NHẬP', 'hello-elementor-child' ); ?></button>
			</p>

			<?php do_action( 'woocommerce_login_form_end' ); ?>

			<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>
				<div class="ktd-auth-switch-prompt">
					<span><?php esc_html_e( 'Chưa có tài khoản KTD Store?', 'hello-elementor-child' ); ?></span>
					<button type="button" class="ktd-switch-btn" id="ktdOpenRegisterModal" aria-haspopup="dialog" aria-controls="ktdRegisterModal"><?php esc_html_e( 'Đăng ký ngay', 'hello-elementor-child' ); ?></button>
				</div>
			<?php endif; ?>

		</form>
	</div>

	<!-- 2. Registration Modal -->
	<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : 
		$has_reg_error = false;
		if ( function_exists( 'wc_notice_count' ) && wc_notice_count( 'error' ) > 0 && ! empty( $_POST['register'] ) ) {
			$has_reg_error = true;
		}
	?>
	<div class="ktd-auth-modal-backdrop<?php echo $has_reg_error ? ' is-active' : ''; ?>" id="ktdRegisterModal" role="dialog" aria-modal="true" aria-labelledby="ktdRegisterModalTitle" style="<?php echo $has_reg_error ? 'display: flex;' : 'display: none;'; ?>">
		<div class="ktd-auth-modal-dialog">
			<button type="button" class="ktd-auth-modal-close" id="ktdCloseRegisterModal" aria-label="Đóng popup">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
			</button>

			<div class="ktd-auth-card-header">
				<h2 class="ktd-auth-title" id="ktdRegisterModalTitle"><?php esc_html_e( 'Tạo Tài Khoản KTD Store', 'hello-elementor-child' ); ?></h2>
				<p class="ktd-auth-subtitle"><?php esc_html_e( 'Nhận ngay đặc quyền bảo hành chính hãng và ưu đãi thành viên VIP', 'hello-elementor-child' ); ?></p>
			</div>

			<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

				<?php do_action( 'woocommerce_register_form_start' ); ?>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="reg_username"><?php esc_html_e( 'Tên tài khoản', 'hello-elementor-child' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" placeholder="Ví dụ: nguyenvana" value="<?php echo ( ! empty( $_POST['username'] ) && ! empty( $_POST['register'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" />
					</p>
				<?php endif; ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_email"><?php esc_html_e( 'Địa chỉ Email', 'hello-elementor-child' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
					<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" placeholder="email@example.com" value="<?php echo ( ! empty( $_POST['email'] ) && ! empty( $_POST['register'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required aria-required="true" />
				</p>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="reg_password"><?php esc_html_e( 'Mật khẩu khởi tạo', 'hello-elementor-child' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
						<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" placeholder="Tối thiểu 6 ký tự" required aria-required="true" />
					</p>
				<?php else : ?>
					<p class="ktd-auth-notice-text"><?php esc_html_e( 'Liên kết thiết lập mật khẩu sẽ được gửi đến email của bạn.', 'hello-elementor-child' ); ?></p>
				<?php endif; ?>

				<?php do_action( 'woocommerce_register_form' ); ?>

				<p class="woocommerce-privacy-policy-text">
					<?php esc_html_e( 'Thông tin cá nhân của bạn sẽ được bảo mật và sử dụng để hỗ trợ trải nghiệm trên KTD Store theo chính sách bảo mật của chúng tôi.', 'hello-elementor-child' ); ?>
				</p>

				<p class="woocommerce-form-row form-row ktd-submit-row">
					<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
					<button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit ktd-auth-submit-btn" name="register" value="<?php esc_attr_e( 'Đăng ký', 'hello-elementor-child' ); ?>"><?php esc_html_e( 'ĐĂNG KÝ TÀI KHOẢN', 'hello-elementor-child' ); ?></button>
				</p>

				<?php do_action( 'woocommerce_register_form_end' ); ?>

				<div class="ktd-auth-switch-prompt">
					<span><?php esc_html_e( 'Đã có tài khoản KTD Store?', 'hello-elementor-child' ); ?></span>
					<button type="button" class="ktd-switch-btn" id="ktdBackToLoginBtn"><?php esc_html_e( 'Đăng nhập ngay', 'hello-elementor-child' ); ?></button>
				</div>

			</form>
		</div>
	</div>
	<?php endif; ?>

</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
