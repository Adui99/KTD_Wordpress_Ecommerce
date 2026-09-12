<?php
/**
 * Setup Blog Page, Categories & Sample Articles for KTD Store
 */

require_once __DIR__ . '/../../../../wp-load.php';

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo "=== 1. SETUP BLOG PAGE & READING SETTINGS ===\n";

// 1. Kiểm tra trang Blog
$blog_page = get_page_by_path( 'blog' );
if ( ! $blog_page ) {
	$blog_id = wp_insert_post( array(
		'post_title'     => 'Tin Tức & Công Nghệ',
		'post_name'      => 'blog',
		'post_status'    => 'publish',
		'post_type'      => 'page',
		'comment_status' => 'closed',
		'ping_status'    => 'closed',
	) );
	echo "  + Created new Blog page with ID: {$blog_id}\n";
} else {
	$blog_id = $blog_page->ID;
	echo "  + Existing Blog page found with ID: {$blog_id}\n";
}

// Gán làm page_for_posts
update_option( 'show_on_front', 'page' );
update_option( 'page_for_posts', $blog_id );
echo "  + Set 'page_for_posts' to page ID: {$blog_id}\n";

echo "\n=== 2. SETUP CATEGORIES ===\n";
$categories = array(
	'Tin Công Nghệ'     => 'tin-cong-nghe',
	'Đánh Giá - Review' => 'danh-gia-review',
	'Mẹo & Thủ Thuật'   => 'meo-thu-thuat',
	'Tư Vấn Chọn Mua'   => 'tu-van-chon-mua',
);

$cat_ids = array();
foreach ( $categories as $cat_name => $cat_slug ) {
	$term = get_term_by( 'slug', $cat_slug, 'category' );
	if ( ! $term ) {
		$inserted = wp_insert_term( $cat_name, 'category', array( 'slug' => $cat_slug ) );
		if ( ! is_wp_error( $inserted ) ) {
			$cat_ids[ $cat_slug ] = $inserted['term_id'];
			echo "  + Created category: '{$cat_name}' (ID: {$inserted['term_id']})\n";
		}
	} else {
		$cat_ids[ $cat_slug ] = $term->term_id;
		echo "  + Category '{$cat_name}' already exists (ID: {$term->term_id})\n";
	}
}

echo "\n=== 3. SEEDING SAMPLE ARTICLES ===\n";

$sample_posts = array(
	array(
		'title'    => 'Đánh giá iPhone 15 Pro Max sau 6 tháng: Khung Titan và Camera 5x có đáng tiền?',
		'slug'     => 'danh-gia-iphone-15-pro-max-sau-6-thang',
		'cat'      => 'danh-gia-review',
		'image'    => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?auto=format&fit=crop&w=1200&q=80',
		'excerpt'  => 'Sau nửa năm sử dụng thực tế từ công việc đến quay chụp hàng ngày, iPhone 15 Pro Max với trọng lượng nhẹ hơn từ khung Titan và cổng Type-C đã thay đổi hoàn toàn trải nghiệm người dùng.',
		'content'  => '<!-- wp:paragraph -->
<p>Kể từ khi chính thức ra mắt, <strong>iPhone 15 Pro Max</strong> luôn là cái tên thu hút sự chú ý lớn nhất trong phân khúc flagship cao cấp. Sau 6 tháng trải nghiệm thực tế với vai trò là máy chính, dưới đây là những chia sẻ chi tiết và khách quan nhất từ đội ngũ KTD Store.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>1. Khung vỏ Titan tự nhiên: Thay đổi nhỏ nhưng giá trị lớn</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Điều đầu tiên bạn sẽ cảm nhận được khi cầm iPhone 15 Pro Max trên tay là máy <em>nhẹ hơn đáng kể</em> so với phiên bản tiền nhiệm 14 Pro Max (khoảng 221g so với 240g). Các cạnh viền cũng được bo cong nhẹ nhàng, giúp việc cầm nắm lâu không còn bị cấn tay như thế hệ viền thép trước đây.</p>
<!-- /wp:paragraph -->

<!-- wp:quote -->
<blockquote class="wp-block-quote"><p>“Việc giảm gần 20g trọng lượng kết hợp cùng viền bo cong công thái học giúp trải nghiệm cầm sử dụng bằng một tay thoải mái hơn rõ rệt.”</p></blockquote>
<!-- /wp:quote -->

<!-- wp:heading {"level":2} -->
<h2>2. Sức mạnh chip A17 Pro và Cổng sạc USB-C</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Việc Apple chuyển sang cổng USB-C tốc độ cao chuẩn 3.0 là một bước ngoặt lớn. Giờ đây bạn có thể dùng chung một sợi cáp cho cả MacBook, iPad và iPhone, đồng thời hỗ trợ xuất màn hình rời 4K và truyền file video ProRes trực tiếp ra ổ cứng SSD gắn ngoài siêu tốc.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>3. Ống kính tiềm vọng Tetraprism Zoom 5x</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Ống kính tiêu cự 120mm quang học cho khả năng chụp chân dung đường phố và chụp vật thể ở xa với độ chi tiết cực kỳ sắc nét. Khả năng chống rung dịch chuyển cảm biến 3D giúp các khung hình zoom 5x không bị nhòe mờ ngay cả trong điều kiện ánh sáng phức tạp.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Kết luận: Ai nên nâng cấp?</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Nếu bạn đang sử dụng từ đời iPhone 12 Pro Max hoặc 13 Pro Max trở xuống, iPhone 15 Pro Max chắc chắn là bước nhảy vọt toàn diện về hiệu năng, camera và cổng kết nối. Khách hàng quan tâm có thể ghé ngay showroom <strong>KTD Store</strong> để trải nghiệm máy và nhận ưu đãi bảo hành 12 tháng chính hãng!</p>
<!-- /wp:paragraph -->',
	),
	array(
		'title'    => 'Samsung Galaxy S24 Ultra và Kỷ nguyên Galaxy AI: Trợ lý đắc lực cho công việc',
		'slug'     => 'samsung-galaxy-s24-ultra-va-ky-nguyen-galaxy-ai',
		'cat'      => 'tin-cong-nghe',
		'image'    => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?auto=format&fit=crop&w=1200&q=80',
		'excerpt'  => 'Tính năng Khoanh vùng tìm kiếm (Circle to Search), dịch thuật cuộc gọi trực tiếp hai chiều và màn hình chống lóa đỉnh cao giúp Galaxy S24 Ultra định hình lại chuẩn mực smartphone thông minh.',
		'content'  => '<!-- wp:paragraph -->
<p>Với sự ra mắt của Galaxy S24 Ultra, Samsung không chỉ đơn thuần nâng cấp phần cứng mà còn chính thức mở ra kỷ nguyên của điện thoại tích hợp trí tuệ nhân tạo thế hệ mới: <strong>Galaxy AI</strong>.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Khoanh tròn để tìm kiếm (Circle to Search) cùng Google</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Chỉ cần giữ phím Home và dùng bút S-Pen hoặc ngón tay khoanh tròn bất kỳ vật thể nào trên màn hình, thông tin chi tiết về sản phẩm, địa điểm hay nội dung đó sẽ lập tức xuất hiện mà không cần rời khỏi ứng dụng đang mở.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Màn hình phẳng với kính Gorilla Armor chống phản chiếu</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Lớp kính mới trên Galaxy S24 Ultra giảm tới 75% độ phản xạ ánh sáng, giúp bạn xem nội dung ngoài trời nắng gắt một cách rõ ràng và dịu mắt chưa từng có trên bất kỳ dòng smartphone nào trước đây.</p>
<!-- /wp:paragraph -->',
	),
	array(
		'title'    => '5 Mẹo tối ưu hóa thời lượng Pin trên Smartphone đơn giản mà hiệu quả',
		'slug'     => '5-meo-toi-uu-hoa-thoi-luong-pin-tren-smartphone',
		'cat'      => 'meo-thu-thuat',
		'image'    => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=1200&q=80',
		'excerpt'  => 'Thời lượng pin luôn là nỗi lo lắng hàng đầu của người dùng smartphone. Bỏ túi ngay 5 thói quen thiết lập giúp máy hoạt động bền bỉ suốt ngày dài mà không cần sạc dự phòng.',
		'content'  => '<!-- wp:paragraph -->
<p>Pin điện thoại tụt nhanh sau một thời gian sử dụng là vấn đề phổ biến. Tuy nhiên, áp dụng đúng các mẹo thiết lập dưới đây sẽ giúp kéo dài đáng kể thời gian sử dụng trong ngày cũng như tuổi thọ viên pin của bạn.</p>
<!-- /wp:paragraph -->

<!-- wp:list {"ordered":true} -->
<ol>
<li><strong>Bật chế độ Sạc bảo vệ (Giới hạn 80%):</strong> Giúp giảm hao mòn hóa học của tế bào pin Lithium-ion khi cắm sạc qua đêm.</li>
<li><strong>Kiểm soát ứng dụng chạy ngầm & Vị trí (Location Services):</strong> Tắt quyền truy cập vị trí "Luôn luôn" đối với các app không cần thiết.</li>
<li><strong>Tận dụng chế độ Dark Mode trên màn hình OLED:</strong> Màn hình OLED sẽ tắt hẳn các điểm ảnh màu đen, tiết kiệm từ 15-20% năng lượng pin.</li>
<li><strong>Tắt tính năng tìm kiếm Wi-Fi và Bluetooth ngầm khi không dùng.</strong></li>
<li><strong>Sử dụng củ sạc và cáp sạc chính hãng chuẩn công suất khuyến nghị.</strong></li>
</ol>
<!-- /wp:list -->',
	),
	array(
		'title'    => 'Nên chọn iPhone 15 Pro hay Samsung Galaxy S24: Kèo đấu flagship 2024',
		'slug'     => 'so-sanh-iphone-15-pro-va-samsung-galaxy-s24',
		'cat'      => 'tu-van-chon-mua',
		'image'    => 'https://images.unsplash.com/photo-1565849904461-04a58ad377e0?auto=format&fit=crop&w=1200&q=80',
		'excerpt'  => 'Cả hai đều là những chiếc flagship kích thước vừa vặn nhất thị trường hiện nay. Vậy hệ sinh thái iOS mượt mà hay sự linh hoạt và tính năng AI của One UI mới là chân ái của bạn?',
		'content'  => '<!-- wp:paragraph -->
<p>Trong phân khúc smartphone nhỏ gọn cao cấp, cuộc đối đầu giữa <strong>iPhone 15 Pro</strong> và <strong>Galaxy S24</strong> luôn làm người dùng phải đắn đo suy nghĩ.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>So sánh tổng quan cấu hình & tính năng</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Nếu bạn đã quen dùng Apple Watch, MacBook hay iPad, iPhone 15 Pro với tính đồng bộ tuyệt vời của iOS là sự lựa chọn không cần bàn cãi. Ngược lại, nếu bạn yêu thích khả năng tùy biến sâu, hỗ trợ ghi âm cuộc gọi native và các tính năng Galaxy AI thông minh, Galaxy S24 sẽ đem lại sự tự do và tiện ích vượt trội.</p>
<!-- /wp:paragraph -->',
	),
);

// Tạo bài viết nếu chưa có
foreach ( $sample_posts as $p ) {
	$existing = get_page_by_path( $p['slug'], OBJECT, 'post' );
	if ( ! $existing ) {
		$post_id = wp_insert_post( array(
			'post_title'     => $p['title'],
			'post_name'      => $p['slug'],
			'post_content'   => $p['content'],
			'post_excerpt'   => $p['excerpt'],
			'post_status'    => 'publish',
			'post_type'      => 'post',
			'comment_status' => 'open',
			'post_category'  => isset( $cat_ids[ $p['cat'] ] ) ? array( $cat_ids[ $p['cat'] ] ) : array(),
		) );

		if ( ! is_wp_error( $post_id ) ) {
			// Lưu ảnh đại diện dự phòng qua post meta nếu chưa có media attachment
			update_post_meta( $post_id, '_ktd_featured_img_url', $p['image'] );
			echo "  + Inserted post: '{$p['title']}' (ID: {$post_id})\n";
		}
	} else {
		update_post_meta( $existing->ID, '_ktd_featured_img_url', $p['image'] );
		echo "  + Post '{$p['title']}' already exists (ID: {$existing->ID})\n";
	}
}

echo "\n=== BLOG SETUP COMPLETE! ===\n";
