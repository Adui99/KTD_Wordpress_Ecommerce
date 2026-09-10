<?php
/**
 * Edit account form - Minimalist & Vietnamese for KTD-Ecommerce
 *
 * @package HelloElementorChild
 * @version 11.0.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' );
?>

<div class="ktd-edit-account-wrapper">
	<div class="ktd-account-section-header">
		<h2 class="ktd-account-section-title">Thông Tin & Bảo Mật</h2>
		<p class="ktd-account-section-desc">Cập nhật họ tên, địa chỉ email và quản lý mật khẩu đăng nhập của bạn.</p>
	</div>

	<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

		<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

		<div class="ktd-form-row-group ktd-row-half">
			<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
				<label for="account_first_name">Tên <span class="required" aria-hidden="true">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" aria-required="true" placeholder="Nhập tên của bạn" />
			</p>
			<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
				<label for="account_last_name">Họ <span class="required" aria-hidden="true">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" aria-required="true" placeholder="Nhập họ của bạn" />
			</p>
		</div>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="account_display_name">Tên hiển thị <span class="required" aria-hidden="true">*</span></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" aria-describedby="account_display_name_description" value="<?php echo esc_attr( $user->display_name ); ?>" aria-required="true" />
			<span id="account_display_name_description" class="ktd-field-hint">Tên này sẽ được hiển thị trong mục tài khoản và các đánh giá sản phẩm.</span>
		</p>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="account_email">Địa chỉ Email <span class="required" aria-hidden="true">*</span></label>
			<input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" aria-required="true" />
		</p>

		<?php do_action( 'woocommerce_edit_account_form_fields' ); ?>

		<fieldset class="ktd-password-fieldset">
			<legend class="ktd-fieldset-legend">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
				</svg>
				<span>Đổi Mật Khẩu Tài Khoản</span>
			</legend>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="password_current">Mật khẩu hiện tại (để trống nếu không đổi)</label>
				<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" autocomplete="current-password" placeholder="Nhập mật khẩu hiện tại" />
			</p>
			<div class="ktd-form-row-group ktd-row-half">
				<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
					<label for="password_1">Mật khẩu mới (để trống nếu không đổi)</label>
					<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="new-password" placeholder="Nhập mật khẩu mới" />
				</p>
				<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
					<label for="password_2">Xác nhận mật khẩu mới</label>
					<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="new-password" placeholder="Nhập lại mật khẩu mới" />
				</p>
			</div>
		</fieldset>

		<?php do_action( 'woocommerce_edit_account_form' ); ?>

		<p class="ktd-form-submit-row">
			<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
			<button type="submit" class="woocommerce-Button button ktd-btn-save-account" name="save_account_details" value="Lưu thay đổi">
				<span>Lưu thay đổi</span>
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
			</button>
			<input type="hidden" name="action" value="save_account_details" />
		</p>

		<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
	</form>
</div>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
