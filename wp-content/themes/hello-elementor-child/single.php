<?php
/**
 * Single Post Template for KTD Store
 * Focus on clean typography, readability, social sharing, and user engagement.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$post_id      = get_the_ID();
	$img_url      = ktd_get_post_image_url( $post_id, 'full' );
	$reading_time = ktd_get_reading_time( $post_id );
	$primary_cat  = ktd_get_primary_category( $post_id );
	$author_id    = get_the_author_meta( 'ID' );
	$author_name  = get_the_author();
	$post_url     = get_permalink();
	$post_title   = get_the_title();

	// Query 3 related posts in the same category (fallback to latest posts)
	$related_args = array(
		'post_type'      => 'post',
		'posts_per_page' => 3,
		'post__not_in'   => array( $post_id ),
		'no_found_rows'  => true,
	);
	if ( $primary_cat ) {
		$related_args['cat'] = $primary_cat->term_id;
	}
	$related_query = new WP_Query( $related_args );
	if ( ! $related_query->have_posts() ) {
		unset( $related_args['cat'] );
		$related_query = new WP_Query( $related_args );
	}
	?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'ktd-single-article-view' ); ?>>
		<div class="ktd-article-container">

			<!-- Breadcrumbs -->
			<div class="ktd-article-breadcrumbs">
				<?php ktd_render_blog_breadcrumbs(); ?>
			</div>

			<!-- Article Header -->
			<header class="ktd-article-header">
				<?php if ( $primary_cat ) : ?>
					<a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>" class="ktd-article-cat-badge">
						<?php echo esc_html( $primary_cat->name ); ?>
					</a>
				<?php endif; ?>

				<h1 class="ktd-article-title"><?php the_title(); ?></h1>

				<div class="ktd-article-meta-bar">
					<div class="ktd-meta-author-wrap">
						<?php echo get_avatar( $author_id, 40, '', $author_name, array( 'class' => 'ktd-author-avatar' ) ); ?>
						<div class="ktd-author-info">
							<span class="ktd-author-label">Tác giả</span>
							<strong class="ktd-author-name"><?php echo esc_html( $author_name ); ?></strong>
						</div>
					</div>

					<div class="ktd-meta-details-wrap">
						<span class="ktd-meta-detail-item">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
								<line x1="16" y1="2" x2="16" y2="6"></line>
								<line x1="8" y1="2" x2="8" y2="6"></line>
								<line x1="3" y1="10" x2="21" y2="10"></line>
							</svg>
							<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'd/m/Y' ) ); ?></time>
						</span>
						<span class="ktd-meta-dot">•</span>
						<span class="ktd-meta-detail-item">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<circle cx="12" cy="12" r="10"></circle>
								<polyline points="12 6 12 12 16 14"></polyline>
							</svg>
							<?php echo esc_html( $reading_time ); ?>
						</span>
						<?php if ( comments_open() ) : ?>
							<span class="ktd-meta-dot">•</span>
							<a href="#comments" class="ktd-meta-detail-item ktd-meta-comments-link">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
								</svg>
								<?php comments_number( '0 phản hồi', '1 phản hồi', '% phản hồi' ); ?>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</header>

			<!-- Featured Image -->
			<?php if ( ! empty( $img_url ) ) : ?>
				<figure class="ktd-article-featured-media">
					<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php the_title_attribute(); ?>" width="1200" height="675" fetchpriority="high">
				</figure>
			<?php endif; ?>

			<!-- Main Article Content -->
			<div class="ktd-article-body entry-content">
				<?php
				the_content();

				wp_link_pages( array(
					'before'      => '<div class="page-links"><span class="page-links-title">Trang:</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
				) );
				?>
			</div>

			<!-- Tags (if any) -->
			<?php
			$post_tags = get_the_tags();
			if ( ! empty( $post_tags ) ) :
				?>
				<div class="ktd-article-tags-wrap">
					<span class="ktd-tags-label">Từ khóa:</span>
					<div class="ktd-tags-list">
						<?php foreach ( $post_tags as $tag ) : ?>
							<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="ktd-tag-pill">
								#<?php echo esc_html( $tag->name ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<!-- Social Share Bar -->
			<div class="ktd-article-share-section">
				<div class="ktd-share-title">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="18" cy="5" r="3"></circle>
						<circle cx="6" cy="12" r="3"></circle>
						<circle cx="18" cy="19" r="3"></circle>
						<line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
						<line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
					</svg>
					<span>Chia sẻ bài viết này:</span>
				</div>
				<div class="ktd-share-buttons">
					<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode( $post_url ); ?>" target="_blank" rel="noopener noreferrer" class="ktd-share-btn ktd-share-fb" title="Chia sẻ lên Facebook">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
							<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
						</svg>
						<span>Facebook</span>
					</a>
					<a href="https://twitter.com/intent/tweet?text=<?php echo rawurlencode( $post_title ); ?>&url=<?php echo rawurlencode( $post_url ); ?>" target="_blank" rel="noopener noreferrer" class="ktd-share-btn ktd-share-tw" title="Chia sẻ lên X (Twitter)">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
							<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
						</svg>
						<span>X / Twitter</span>
					</a>
					<button type="button" class="ktd-share-btn ktd-share-copy" id="ktdCopyLinkBtn" data-url="<?php echo esc_url( $post_url ); ?>" title="Sao chép liên kết">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
							<path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
						</svg>
						<span id="ktdCopyLinkText">Sao chép link</span>
					</button>
				</div>
			</div>

			<!-- Author Bio Box -->
			<div class="ktd-author-card">
				<div class="ktd-author-card-avatar">
					<?php echo get_avatar( $author_id, 70, '', $author_name ); ?>
				</div>
				<div class="ktd-author-card-info">
					<span class="ktd-author-card-badge">Biên tập viên KTD</span>
					<h4 class="ktd-author-card-name"><?php echo esc_html( $author_name ); ?></h4>
					<p class="ktd-author-card-bio">
						<?php
						$bio = get_the_author_meta( 'description', $author_id );
						echo esc_html( $bio ? $bio : 'Chuyên gia phân tích và đánh giá công nghệ tại KTD Store. Luôn mang đến những thông tin chân thực, chính xác và có giá trị nhất cho cộng đồng người yêu công nghệ.' );
						?>
					</p>
				</div>
			</div>

			<!-- Related Posts Section -->
			<?php if ( $related_query->have_posts() ) : ?>
				<section class="ktd-related-posts-section">
					<div class="ktd-related-heading-wrap">
						<span class="ktd-related-badge">Có thể bạn quan tâm</span>
						<h3 class="ktd-related-title">Bài viết liên quan</h3>
					</div>
					<div class="ktd-related-grid">
						<?php
						while ( $related_query->have_posts() ) :
							$related_query->the_post();
							$r_id   = get_the_ID();
							$r_img  = ktd_get_post_image_url( $r_id );
							$r_time = ktd_get_reading_time( $r_id );
							?>
							<article class="ktd-related-card">
								<a href="<?php the_permalink(); ?>" class="ktd-related-thumb" tabindex="-1">
									<img src="<?php echo esc_url( $r_img ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" width="400" height="240">
								</a>
								<div class="ktd-related-body">
									<div class="ktd-related-meta">
										<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'd/m/Y' ) ); ?></time>
										<span class="ktd-meta-dot">•</span>
										<span><?php echo esc_html( $r_time ); ?></span>
									</div>
									<h4 class="ktd-related-post-title">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h4>
								</div>
							</article>
						<?php endwhile; wp_reset_postdata(); ?>
					</div>
				</section>
			<?php endif; ?>

			<!-- Comments Section -->
			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>

		</div>
	</article>

	<!-- Script sao chép liên kết -->
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		var copyBtn = document.getElementById('ktdCopyLinkBtn');
		var copyText = document.getElementById('ktdCopyLinkText');
		if (copyBtn && copyText) {
			copyBtn.addEventListener('click', function() {
				var url = copyBtn.getAttribute('data-url') || window.location.href;
				if (navigator.clipboard) {
					navigator.clipboard.writeText(url).then(function() {
						var original = copyText.textContent;
						copyText.textContent = 'Đã chép!';
						copyBtn.classList.add('is-copied');
						setTimeout(function() {
							copyText.textContent = original;
							copyBtn.classList.remove('is-copied');
						}, 2000);
					}).catch(function() {
						fallbackCopy(url);
					});
				} else {
					fallbackCopy(url);
				}
			});
		}

		function fallbackCopy(url) {
			var input = document.createElement('textarea');
			input.value = url;
			document.body.appendChild(input);
			input.select();
			try {
				document.execCommand('copy');
				var copyText = document.getElementById('ktdCopyLinkText');
				if (copyText) {
					var original = copyText.textContent;
					copyText.textContent = 'Đã chép!';
					setTimeout(function() { copyText.textContent = original; }, 2000);
				}
			} catch (e) {}
			document.body.removeChild(input);
		}
	});
	</script>

	<?php
endwhile;

get_footer();
