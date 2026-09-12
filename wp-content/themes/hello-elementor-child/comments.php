<?php
/**
 * Custom Comments Template for KTD Store
 * Modern, clean layout with avatar, threading, and account badge.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}

// Do not render comments on WooCommerce transactional and account pages
if ( ( function_exists( 'is_account_page' ) && is_account_page() ) || ( function_exists( 'is_checkout' ) && is_checkout() ) || ( function_exists( 'is_cart' ) && is_cart() ) ) {
	return;
}

$comments_count = get_comments_number();
?>

<section id="comments" class="ktd-comments-area" aria-label="Bình luận bài viết">

	<div class="ktd-comments-header">
		<h3 class="ktd-comments-title">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
			</svg>
			<span>Bình luận & Thảo luận</span>
			<span class="ktd-comments-count-pill"><?php echo esc_html( $comments_count ); ?></span>
		</h3>
		<p class="ktd-comments-desc">Chia sẻ quan điểm hoặc đặt câu hỏi cùng chuyên gia và cộng đồng KTD Store.</p>
	</div>

	<?php if ( have_comments() ) : ?>
		<ol class="ktd-comment-list">
			<?php
			wp_list_comments( array(
				'style'       => 'ol',
				'short_ping'  => true,
				'avatar_size' => 48,
				'callback'    => 'ktd_custom_comment_row',
			) );
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav class="ktd-comment-navigation" aria-label="Điều hướng bình luận">
				<div class="nav-previous"><?php previous_comments_link( '&larr; Bình luận trước' ); ?></div>
				<div class="nav-next"><?php next_comments_link( 'Bình luận tiếp theo &rarr;' ); ?></div>
			</nav>
		<?php endif; ?>

	<?php elseif ( ! comments_open() ) : ?>
		<p class="ktd-no-comments">Mục bình luận cho bài viết này hiện đã đóng.</p>
	<?php endif; ?>

	<!-- Comment Form -->
	<?php if ( comments_open() ) : ?>
		<div class="ktd-comment-form-wrap">
			<?php
			$commenter = wp_get_current_commenter();
			$req       = get_option( 'require_name_email' );
			$aria_req  = ( $req ? " aria-required='true'" : '' );

			$fields = array(
				'author' => '<div class="ktd-form-row ktd-form-row-author">
					<label for="author" class="ktd-form-label">Họ và tên ' . ( $req ? '<span class="required">*</span>' : '' ) . '</label>
					<input id="author" name="author" type="text" class="ktd-form-input" value="' . esc_attr( $commenter['comment_author'] ) . '" placeholder="Nguyễn Văn A" size="30"' . $aria_req . ' />
				</div>',
				'email'  => '<div class="ktd-form-row ktd-form-row-email">
					<label for="email" class="ktd-form-label">Địa chỉ Email ' . ( $req ? '<span class="required">*</span>' : '' ) . '</label>
					<input id="email" name="email" type="email" class="ktd-form-input" value="' . esc_attr( $commenter['comment_author_email'] ) . '" placeholder="name@example.com" size="30"' . $aria_req . ' />
				</div>',
			);

			comment_form( array(
				'fields'               => $fields,
				'comment_field'        => '<div class="ktd-form-row ktd-form-row-comment">
					<label for="comment" class="ktd-form-label">Nội dung bình luận <span class="required">*</span></label>
					<textarea id="comment" name="comment" class="ktd-form-textarea" cols="45" rows="4" placeholder="Nhập câu hỏi hoặc cảm nhận của bạn về bài viết..." aria-required="true"></textarea>
				</div>',
				'title_reply'          => 'Để lại bình luận của bạn',
				'title_reply_to'       => 'Trả lời bình luận của %s',
				'cancel_reply_link'    => 'Hủy trả lời',
				'title_reply_before'   => '<h4 id="reply-title" class="ktd-reply-title">',
				'title_reply_after'    => '</h4>',
				'label_submit'         => 'Gửi bình luận',
				'submit_button'        => '<button type="submit" name="%1$s" id="%2$s" class="ktd-submit-comment-btn">%4$s</button>',
				'submit_field'         => '<div class="ktd-form-submit-row">%1$s %2$s</div>',
				'logged_in_as'         => '<p class="ktd-logged-in-as">' . sprintf(
					'Đang đăng nhập với tư cách <a href="%1$s" class="ktd-user-link"><strong>%2$s</strong></a> (Khách hàng KTD Store). <a href="%3$s" class="ktd-logout-link" title="Đăng xuất khỏi tài khoản này">Đăng xuất?</a>',
					get_edit_user_link(),
					wp_get_current_user()->display_name,
					wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )
				) . '</p>',
				'comment_notes_before' => '',
				'comment_notes_after'  => '',
			) );
			?>
		</div>
	<?php endif; ?>

</section>
