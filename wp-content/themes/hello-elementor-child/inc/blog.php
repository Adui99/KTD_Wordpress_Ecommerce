<?php
/**
 * Blog Helper Functions & Features for KTD Store
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Lấy URL ảnh đại diện của bài viết (hỗ trợ featured image & custom fallback meta)
 */
function ktd_get_post_image_url( $post_id = null, $size = 'large' ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	if ( has_post_thumbnail( $post_id ) ) {
		$img_url = get_the_post_thumbnail_url( $post_id, $size );
		if ( $img_url ) {
			return $img_url;
		}
	}

	$meta_img = get_post_meta( $post_id, '_ktd_featured_img_url', true );
	if ( ! empty( $meta_img ) ) {
		return esc_url( $meta_img );
	}

	// Fallback mặc định
	return 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=1200&q=80';
}

/**
 * Tính thời gian ước tính đọc bài viết (tính theo trung bình 200 từ/phút)
 */
function ktd_get_reading_time( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$content    = get_post_field( 'post_content', $post_id );
	$clean_text = wp_strip_all_tags( $content );
	$word_count = count( preg_split( '/\s+/u', trim( $clean_text ), -1, PREG_SPLIT_NO_EMPTY ) );
	$minutes    = max( 1, (int) ceil( $word_count / 200 ) );

	return sprintf( _n( '%d phút đọc', '%d phút đọc', $minutes, 'hello-elementor-child' ), $minutes );
}

/**
 * Lấy danh mục chính của bài viết
 */
function ktd_get_primary_category( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$categories = get_the_category( $post_id );
	if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
		return $categories[0];
	}

	return null;
}

/**
 * Hiển thị Breadcrumbs chuẩn cho bài viết & blog
 */
function ktd_render_blog_breadcrumbs() {
	$home_url = home_url( '/' );
	$blog_url = get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' );

	echo '<nav class="ktd-breadcrumbs" aria-label="Breadcrumb">';
	echo '<a href="' . esc_url( $home_url ) . '" class="ktd-crumb-item">Trang chủ</a>';
	echo '<span class="ktd-crumb-sep">/</span>';

	if ( is_home() ) {
		echo '<span class="ktd-crumb-item ktd-crumb-current">Tin Tức & Blog</span>';
	} elseif ( is_category() ) {
		echo '<a href="' . esc_url( $blog_url ) . '" class="ktd-crumb-item">Blog</a>';
		echo '<span class="ktd-crumb-sep">/</span>';
		echo '<span class="ktd-crumb-item ktd-crumb-current">' . esc_html( single_cat_title( '', false ) ) . '</span>';
	} elseif ( is_singular( 'post' ) ) {
		echo '<a href="' . esc_url( $blog_url ) . '" class="ktd-crumb-item">Blog</a>';
		echo '<span class="ktd-crumb-sep">/</span>';
		$cat = ktd_get_primary_category();
		if ( $cat ) {
			echo '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '" class="ktd-crumb-item">' . esc_html( $cat->name ) . '</a>';
			echo '<span class="ktd-crumb-sep">/</span>';
		}
		echo '<span class="ktd-crumb-item ktd-crumb-current" title="' . esc_attr( get_the_title() ) . '">' . esc_html( wp_trim_words( get_the_title(), 7 ) ) . '</span>';
	}
	echo '</nav>';
}

/**
 * Custom Comment Callback Render
 */
function ktd_custom_comment_row( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	$is_author          = ( (int) $comment->user_id === (int) get_the_author_meta( 'ID' ) );
	?>
	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'ktd-comment-item' ); ?>>
		<article class="ktd-comment-body">
			<div class="ktd-comment-avatar-wrap">
				<?php echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'class' => 'ktd-comment-avatar' ) ); ?>
			</div>
			<div class="ktd-comment-content-wrap">
				<header class="ktd-comment-meta">
					<div class="ktd-comment-author-name">
						<strong><?php echo get_comment_author_link(); ?></strong>
						<?php if ( $is_author ) : ?>
							<span class="ktd-comment-badge-author">Tác giả</span>
						<?php elseif ( $comment->user_id ) : ?>
							<span class="ktd-comment-badge-customer">Khách hàng</span>
						<?php endif; ?>
					</div>
					<time class="ktd-comment-time" datetime="<?php comment_time( 'c' ); ?>">
						<?php printf( '%s lúc %s', get_comment_date( 'd/m/Y' ), get_comment_time( 'H:i' ) ); ?>
					</time>
				</header>

				<?php if ( '0' === $comment->comment_approved ) : ?>
					<p class="ktd-comment-awaiting-moderation"><em>Bình luận của bạn đang chờ quản trị viên duyệt trước khi hiển thị.</em></p>
				<?php endif; ?>

				<div class="ktd-comment-text">
					<?php comment_text(); ?>
				</div>

				<div class="ktd-comment-actions">
					<?php
					comment_reply_link( array_merge( $args, array(
						'depth'     => $depth,
						'max_depth' => $args['max_depth'],
						'reply_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 17 4 12 9 7"></polyline><path d="M20 18v-2a4 4 0 0 0-4-4H4"></path></svg> <span>Trả lời</span>',
					) ) );
					?>
				</div>
			</div>
		</article>
	<?php
}
