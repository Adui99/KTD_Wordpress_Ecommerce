<?php
/**
 * Blog Listing Template for KTD Store (/blog/)
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$categories   = get_categories( array( 'hide_empty' => true ) );
$current_page = max( 1, get_query_var( 'paged' ) );
?>

<!-- Blog Hero Banner (Full width with border-bottom) -->
<section class="ktd-blog-hero">
	<div class="ktd-container">
		<!-- Breadcrumbs -->
		<div class="ktd-blog-breadcrumbs-wrapper">
			<?php ktd_render_blog_breadcrumbs(); ?>
		</div>

		<div class="ktd-blog-hero-content">
			<span class="ktd-blog-hero-badge">KTD Tech Insights</span>
			<h1 class="ktd-blog-hero-title">Tin Tức & Công Nghệ</h1>
			<p class="ktd-blog-hero-desc">
				Khám phá các đánh giá chuyên sâu, cẩm nang chọn mua flagship và những thủ thuật công nghệ hữu ích được chia sẻ bởi đội ngũ chuyên gia KTD Store.
			</p>
		</div>
	</div>
</section>

<!-- Main Blog Content -->
<main id="primary" class="site-main ktd-blog-main">
	<div class="ktd-container">

		<!-- Category Filter Pills -->
		<?php if ( ! empty( $categories ) ) : ?>
			<nav class="ktd-cat-filter-nav" aria-label="Lọc theo chuyên mục">
				<ul class="ktd-cat-filter-list">
					<li>
						<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ); ?>" class="ktd-cat-pill is-active">
							Tất cả bài viết
						</a>
					</li>
					<?php foreach ( $categories as $cat ) : ?>
						<li>
							<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="ktd-cat-pill">
								<?php echo esc_html( $cat->name ); ?>
								<span class="ktd-cat-count"><?php echo esc_html( $cat->count ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</nav>
		<?php endif; ?>

		<!-- Post Listing Grid -->
		<?php if ( have_posts() ) : ?>
			<div class="ktd-blog-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					$post_id      = get_the_ID();
					$img_url      = ktd_get_post_image_url( $post_id );
					$reading_time = ktd_get_reading_time( $post_id );
					$primary_cat  = ktd_get_primary_category( $post_id );
					$post_link    = get_permalink();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'ktd-post-card' ); ?>>
						<!-- Card Thumbnail -->
						<div class="ktd-post-card-thumb">
							<a href="<?php echo esc_url( $post_link ); ?>" tabindex="-1" aria-hidden="true">
								<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" width="600" height="380">
							</a>
							<?php if ( $primary_cat ) : ?>
								<a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>" class="ktd-post-card-cat-badge">
									<?php echo esc_html( $primary_cat->name ); ?>
								</a>
							<?php endif; ?>
						</div>

						<!-- Card Content -->
						<div class="ktd-post-card-body">
							<div class="ktd-post-card-meta">
								<span class="ktd-meta-item">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="ktd-meta-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
										<line x1="16" y1="2" x2="16" y2="6"></line>
										<line x1="8" y1="2" x2="8" y2="6"></line>
										<line x1="3" y1="10" x2="21" y2="10"></line>
									</svg>
									<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'd/m/Y' ) ); ?></time>
								</span>
								<span class="ktd-meta-dot">•</span>
								<span class="ktd-meta-item">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="ktd-meta-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<circle cx="12" cy="12" r="10"></circle>
										<polyline points="12 6 12 12 16 14"></polyline>
									</svg>
									<?php echo esc_html( $reading_time ); ?>
								</span>
							</div>

							<h3 class="ktd-post-card-title">
								<a href="<?php echo esc_url( $post_link ); ?>" title="<?php the_title_attribute(); ?>">
									<?php the_title(); ?>
								</a>
							</h3>

							<div class="ktd-post-card-excerpt">
								<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22, '...' ) ); ?></p>
							</div>

							<div class="ktd-post-card-footer">
								<a href="<?php echo esc_url( $post_link ); ?>" class="ktd-readmore-link">
									<span>Đọc tiếp</span>
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
										<line x1="5" y1="12" x2="19" y2="12"></line>
										<polyline points="12 5 19 12 12 19"></polyline>
									</svg>
								</a>
							</div>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<!-- Pagination -->
			<div class="ktd-blog-pagination">
				<?php
				the_posts_pagination( array(
					'mid_size'           => 2,
					'prev_text'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg> <span>Trang trước</span>',
					'next_text'          => '<span>Trang sau</span> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>',
					'screen_reader_text' => 'Điều hướng phân trang bài viết',
				) );
				?>
			</div>

		<?php else : ?>
			<div class="ktd-blog-empty">
				<div class="ktd-empty-icon">📝</div>
				<h3>Chưa có bài viết nào</h3>
				<p>Các bài viết và tin tức công nghệ mới nhất sẽ sớm được cập nhật tại đây.</p>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ktd-btn ktd-btn-primary">Quay lại Trang Chủ</a>
			</div>
		<?php endif; ?>

	</div>
</main>

<?php
get_footer();
