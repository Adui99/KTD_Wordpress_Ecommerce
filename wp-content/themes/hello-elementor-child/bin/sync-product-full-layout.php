<?php
/**
 * CLI Sync Script: Populate Full Layout (12 Hardware Specs & Rich Review Articles)
 * Theme: Hello Elementor Child (KTD E-Commerce)
 * Target: All 24 WooCommerce products to match iPhone 17 Pro Max full layout
 */

if ( php_sapi_name() !== 'cli' ) {
    die( "Access denied: CLI only.\n" );
}

require_once dirname( __DIR__, 4 ) . '/wp-load.php';

if ( ! function_exists( 'wc_get_product' ) ) {
    die( "WooCommerce is not active.\n" );
}

echo "============================================================\n";
echo " Starting Full Layout Synchronization for 24 Products\n";
echo "============================================================\n\n";

$products_data = [
    // -------------------------------------------------------------------------
    // 1. iPhone 17 Pro (ID 133)
    // -------------------------------------------------------------------------
    133 => [
        'name' => 'iPhone 17 Pro',
        'specs' => [
            'man-hinh' => ['Màn hình', '6.3 inch, Super Retina XDR OLED, 120Hz ProMotion, 3.500 nits, Dynamic Island'],
            'he-dieu-hanh' => ['Hệ điều hành', 'iOS 19 (Tích hợp Apple Intelligence tiếng Việt)'],
            'chip-xu-ly-cpu' => ['Chip xử lý (CPU)', 'Apple A19 Pro 6 nhân (Tiến trình 2nm tiên tiến)'],
            'bo-nho-trong-rom' => ['Bộ nhớ trong (ROM)', '256 GB NVMe siêu tốc'],
            'ram' => ['RAM', '12 GB Unified Memory'],
            'camera-sau' => ['Camera sau', 'Chính 48MP (OIS) + Siêu rộng 48MP + Tele 48MP (Zoom quang 5x Tetraprism)'],
            'camera-truoc' => ['Camera trước', '24MP TrueDepth, Autofocus, Retina Flash'],
            'pin-sac' => ['Pin & Sạc', '3.650 mAh, Sạc nhanh 45W, Sạc không dây MagSafe 25W'],
            'chat-lieu' => ['Chất liệu', 'Khung viền Titanium cấp 5, Kính Ceramic Shield thế hệ mới'],
            'cong-ket-noi' => ['Cổng kết nối', 'USB-C chuẩn USB 3 (Tốc độ 10Gbps), DisplayPort'],
            'khang-nuoc-bui' => ['Kháng nước & bụi', 'IP68 (Độ sâu 6 mét trong thời gian tối đa 30 phút)'],
            'trong-luong-kich-thuoc' => ['Trọng lượng & Kích thước', '199 g | 149.6 x 71.5 x 8.25 mm'],
        ],
        'content' => <<<HTML
<div class="ktd-article-intro">
    <p class="ktd-lead-text">
        <strong>iPhone 17 Pro</strong> định hình lại chuẩn mực của dòng smartphone nhỏ gọn cao cấp. Kết hợp hoàn hảo giữa kích thước 6.3 inch lý tưởng, sức mạnh vô song của chipset <strong>Apple A19 Pro 2nm</strong> và hệ thống 3 camera 48MP Tetraprism, đây là sự lựa chọn tối thượng cho những ai yêu thích sức mạnh flagship trong một thân máy linh hoạt.
    </p>
</div>

<h2>1. Thiết Kế Titanium Cấp 5 Gọn Gàng – Cầm Nắm Thoải Mái Cả Ngày Dài</h2>
<p>
    iPhone 17 Pro sở hữu khung viền chế tác từ <strong>Titanium cấp 5</strong> siêu bền và nhẹ, bề mặt xử lý phun cát mịn chống bám vân tay hiệu quả. Các góc cạnh được bo cong mềm mại hơn, giúp thao tác cầm một tay cực kỳ chắc chắn mà không gây cấn hay mỏi dù sử dụng liên tục trong nhiều giờ.
</p>

<h2>2. Màn Hình Super Retina XDR 6.3 Inch Siêu Sáng 3.500 Nits</h2>
<p>
    Màn hình <strong>Super Retina XDR OLED 6.3 inch</strong> được tối ưu viền siêu mỏng, nâng độ sáng tối đa lên mức <strong>3.500 nits</strong>, mang lại khả năng hiển thị xuất sắc ngay cả dưới ánh nắng gắt trực tiếp. Tần số quét <strong>ProMotion 120Hz</strong> tự động điều chỉnh mượt mà từ 1Hz đến 120Hz giúp tiết kiệm pin tối đa khi đọc sách hoặc xem ảnh tĩnh.
</p>

<div class="ktd-highlight-box">
    <strong>Điểm đột phá ProMotion:</strong> Công nghệ ProMotion 120Hz thế hệ mới phản hồi xúc giác tức thì, giảm độ trễ chạm xuống dưới 5ms, tối ưu tuyệt hảo cho cả thao tác lướt web hàng ngày và thi đấu gaming chuyên nghiệp.
</div>

<h2>3. Hiệu Năng Đỉnh Cao Với Chip Apple A19 Pro (Tiến Trình 2nm)</h2>
<p>
    Sở hữu con chip <strong>Apple A19 Pro</strong> được gia công trên tiến trình 2nm tiên tiến nhất thế giới, iPhone 17 Pro xử lý nhẹ nhàng mọi tác vụ phức tạp từ chỉnh sửa video ProRes 4K, dựng hình 3D cho đến chơi các tựa game đồ họa Ray Tracing đỉnh cao với tốc độ ổn định 60-120fps mà vẫn duy trì nhiệt độ mát mẻ.
</p>

<h2>4. Bộ Ba Camera 48MP Toàn Diện & Camera Control Thông Minh</h2>
<p>
    Lần đầu tiên trên bản Pro tiêu chuẩn, cảm biến Telephoto được nâng cấp lên <strong>48MP</strong> cùng thiết kế lăng kính Tetraprism zoom quang 5x sắc nét. Phím bấm cảm ứng lực <strong>Camera Control</strong> giúp bạn dễ dàng vuốt chỉnh tiêu cự, khóa sáng và bấm chụp tức thời như trên máy ảnh chuyên nghiệp.
</p>

<h2>5. Thời Lượng Pin Ấn Tượng & Sạc Nhanh 45W Tiện Lợi</h2>
<p>
    Viên pin dung lượng cải tiến cùng khả năng tối ưu hóa điện năng của chip A19 Pro mang lại thời gian xem video liên tục lên đến 27 tiếng. Cổng USB-C tốc độ cao chuẩn USB 3 (10Gbps) hỗ trợ sạc nhanh 50% pin chỉ trong 25 phút.
</p>

<div class="ktd-article-conclusion">
    <h3>Có Nên Chọn Mua iPhone 17 Pro Tại KTD Store?</h3>
    <p>
        Nếu bạn mong muốn sở hữu đầy đủ tính năng cao cấp nhất của Apple trong một kích thước vừa vặn túi áo cùng chế độ hậu mãi chuẩn mực, trả góp 0% và bảo hành uy tín tại <strong>KTD Store</strong>, iPhone 17 Pro chính là lựa chọn không thể bỏ lỡ.
    </p>
</div>
HTML
    ],

    // -------------------------------------------------------------------------
    // 2. iPhone 17 Slim (ID 134)
    // -------------------------------------------------------------------------
    134 => [
        'name' => 'iPhone 17 Slim',
        'specs' => [
            'man-hinh' => ['Màn hình', '6.6 inch, OLED Super Retina XDR, 120Hz ProMotion, 3.000 nits, Dynamic Island'],
            'he-dieu-hanh' => ['Hệ điều hành', 'iOS 19 (Tích hợp Apple Intelligence tiếng Việt)'],
            'chip-xu-ly-cpu' => ['Chip xử lý (CPU)', 'Apple A19 Bionic 6 nhân (Tiến trình 3nm thế hệ 2)'],
            'bo-nho-trong-rom' => ['Bộ nhớ trong (ROM)', '256 GB NVMe siêu tốc'],
            'ram' => ['RAM', '8 GB Unified Memory'],
            'camera-sau' => ['Camera sau', 'Đơn 48MP Fusion cao cấp, Sensor-Shift OIS, Zoom 2x quang học'],
            'camera-truoc' => ['Camera trước', '24MP TrueDepth với Center Stage'],
            'pin-sac' => ['Pin & Sạc', '3.300 mAh, Sạc nhanh 30W, Sạc không dây MagSafe 20W'],
            'chat-lieu' => ['Chất liệu', 'Khung nhôm siêu mỏng nguyên khối 5.6mm, Mặt lưng kính siêu cứng'],
            'cong-ket-noi' => ['Cổng kết nối', 'USB-C chuẩn USB 2'],
            'khang-nuoc-bui' => ['Kháng nước & bụi', 'IP68 (Độ sâu 6 mét trong thời gian tối đa 30 phút)'],
            'trong-luong-kich-thuoc' => ['Trọng lượng & Kích thước', '155 g | 157.2 x 75.3 x 5.6 mm'],
        ],
        'content' => <<<HTML
<div class="ktd-article-intro">
    <p class="ktd-lead-text">
        <strong>iPhone 17 Slim</strong> là kiệt tác công nghệ mỏng nhẹ bậc nhất từ Apple. Với độ mỏng đáng kinh ngạc chỉ <strong>5.6mm</strong> và trọng lượng nhẹ 155g, chiếc điện thoại này mang lại cảm giác thanh thoát chưa từng có mà vẫn giữ trọn hiệu năng mạnh mẽ từ chip <strong>Apple A19</strong> và màn hình ProMotion 120Hz sắc sảo.
    </p>
</div>

<h2>1. Thiết Kế Siêu Mỏng 5.6mm – Kỳ Tích Kỹ Thuật Cơ Khí Chính Xác</h2>
<p>
    Từng đường nét trên iPhone 17 Slim được chế tác tỉ mỉ từ khung nhôm nguyên khối siêu bền. Bằng cách thu nhỏ bảng mạch và tái cấu trúc hệ thống linh kiện bên trong, Apple đã tạo nên một chiếc smartphone mỏng nhất lịch sử dòng iPhone, mở ra tương lai thiết kế tối giản và sang trọng.
</p>

<h2>2. Màn Hình 6.6 Inch ProMotion 120Hz Viền Vô Cực</h2>
<p>
    Dù thân máy cực mỏng, iPhone 17 Slim vẫn được trang bị màn hình <strong>OLED 6.6 inch</strong> chuẩn điện ảnh. Công nghệ <strong>ProMotion 120Hz</strong> mượt mà cùng độ sáng tối đa 3.000 nits giúp mọi thước phim và cử chỉ cuộn trang đều trở nên sống động, rõ nét.
</p>

<div class="ktd-highlight-box">
    <strong>Điểm nhấn trọng lượng:</strong> Chỉ nặng 155g, iPhone 17 Slim nhẹ hơn đáng kể so với các dòng máy cùng kích thước, cho bạn trải nghiệm cầm nắm thanh thoát như một tác phẩm nghệ thuật.
</div>

<h2>3. Chipset Apple A19 – Sức Mạnh Vượt Trội Cho Tương Lai</h2>
<p>
    Trang bị bộ xử lý <strong>Apple A19 Bionic</strong> thế hệ mới, máy tối ưu hóa nhiệt độ và hiệu suất vượt trội trên thân máy mỏng. Khả năng xử lý trí tuệ nhân tạo Apple Intelligence trên thiết bị giúp bảo mật tuyệt đối dữ liệu người dùng.
</p>

<h2>4. Camera Đơn 48MP Đa Năng – Tinh Gọn Mà Chất Lượng</h2>
<p>
    Thay vì nhiều ống kính phức tạp, iPhone 17 Slim tập trung vào một cảm biến <strong>48MP Fusion</strong> thượng hạng. Ống kính tích hợp zoom cảm biến 2x quang học cho chất lượng ảnh chụp chân dung và phong cảnh chi tiết xuất sắc trong mọi điều kiện ánh sáng.
</p>

<div class="ktd-article-conclusion">
    <h3>Ai Nên Sở Hữu iPhone 17 Slim Tại KTD Store?</h3>
    <p>
        Dành cho những người dẫn đầu xu hướng thời trang và đam mê sự thanh mảnh hiện đại, iPhone 17 Slim đang được phân phối chính hãng tại <strong>KTD Store</strong> với chính sách giá ưu đãi và quà tặng hấp dẫn.
    </p>
</div>
HTML
    ],

    // -------------------------------------------------------------------------
    // 3. iPhone 17 (ID 135)
    // -------------------------------------------------------------------------
    135 => [
        'name' => 'iPhone 17',
        'specs' => [
            'man-hinh' => ['Màn hình', '6.3 inch, Super Retina XDR OLED, 120Hz ProMotion, 3.000 nits, Dynamic Island'],
            'he-dieu-hanh' => ['Hệ điều hành', 'iOS 19 (Tích hợp Apple Intelligence tiếng Việt)'],
            'chip-xu-ly-cpu' => ['Chip xử lý (CPU)', 'Apple A19 Bionic 6 nhân (Tiến trình 3nm thế hệ 2)'],
            'bo-nho-trong-rom' => ['Bộ nhớ trong (ROM)', '128 GB NVMe siêu tốc'],
            'ram' => ['RAM', '8 GB Unified Memory'],
            'camera-sau' => ['Camera sau', 'Kép 48MP Fusion (OIS) + Siêu rộng 12MP (Góc chụp 120 độ)'],
            'camera-truoc' => ['Camera trước', '24MP TrueDepth, Autofocus, Retina Flash'],
            'pin-sac' => ['Pin & Sạc', '3.560 mAh, Sạc nhanh 30W, Sạc không dây MagSafe 25W'],
            'chat-lieu' => ['Chất liệu', 'Khung nhôm tái chế 100%, Kính pha màu Ceramic Shield'],
            'cong-ket-noi' => ['Cổng kết nối', 'USB-C chuẩn USB 2'],
            'khang-nuoc-bui' => ['Kháng nước & bụi', 'IP68 (Độ sâu 6 mét trong thời gian tối đa 30 phút)'],
            'trong-luong-kich-thuoc' => ['Trọng lượng & Kích thước', '172 g | 147.6 x 71.6 x 7.8 mm'],
        ],
        'content' => <<<HTML
<div class="ktd-article-intro">
    <p class="ktd-lead-text">
        <strong>iPhone 17</strong> là bước tiến lớn của dòng iPhone tiêu chuẩn khi lần đầu tiên được trang bị màn hình <strong>120Hz ProMotion</strong> mượt mà cùng chip xử lý <strong>Apple A19 Bionic</strong> mạnh mẽ. Chiếc máy mang lại trải nghiệm toàn diện từ làm việc đến giải trí với mức giá vô cùng hợp lý.
    </p>
</div>

<h2>1. Thiết Kế Màu Sắc Năng Động – Mặt Lưng Kính Pha Màu Tinh Tế</h2>
<p>
    iPhone 17 mang đến bảng màu trẻ trung, hiện đại với khung nhôm hàng không vũ trụ bo cong êm ái. Mặt lưng kính pha màu mờ chống bám vân tay và mồ hôi, kết hợp mặt kính Ceramic Shield thế hệ mới tăng gấp đôi khả năng chống nứt vỡ khi va chạm.
</p>

<h2>2. Nâng Cấp Đột Phá: Màn Hình 120Hz ProMotion Đã Xuất Hiện</h2>
<p>
    Người dùng dòng iPhone tiêu chuẩn nay đã có thể tận hưởng công nghệ <strong>ProMotion 120Hz</strong> siêu mượt. Mọi thao tác cuộn lướt web, chuyển đổi đa nhiệm hay chơi game đều diễn ra mượt mà không độ trễ, đồng thời tự động giảm tần số quét khi đọc tin để tiết kiệm pin.
</p>

<div class="ktd-highlight-box">
    <strong>Điểm sáng trải nghiệm:</strong> Màn hình Super Retina XDR 6.3 inch đạt độ sáng tối đa 3.000 nits, giúp bạn xem bản đồ, đọc tin tức ngoài trời nắng gắt hoàn toàn rõ ràng.
</div>

<h2>3. Chip A19 Bionic & Apple Intelligence Tiện Ích</h2>
<p>
    Vi xử lý <strong>A19 Bionic</strong> cùng 8GB RAM mở ra khả năng chạy mượt mà các mô hình ngôn ngữ lớn của Apple Intelligence, tự động sắp xếp thông báo quan trọng, viết lại văn bản và tách nền vật thể trong ảnh nhanh chóng.
</p>

<h2>4. Camera Kép 48MP Nâng Tầm Nhiếp Ảnh</h2>
<p>
    Cảm biến chính <strong>48MP Fusion</strong> với chống rung OIS cảm biến cho phép chụp ảnh độ phân giải siêu nét 24MP và 48MP, kèm tính năng zoom chất lượng quang học 2x mà không cần ống kính tele chuyên biệt.
</p>

<div class="ktd-article-conclusion">
    <h3>Mua iPhone 17 Chính Hãng Giá Tốt Tại KTD Store</h3>
    <p>
        Khám phá ngay iPhone 17 tại <strong>KTD Store</strong> với chế độ bảo hành 1 đổi 1, miễn phí giao hàng toàn quốc và hỗ trợ thu cũ đổi mới lên đời tiết kiệm nhất.
    </p>
</div>
HTML
    ],

    // -------------------------------------------------------------------------
    // 4. iPhone 16 Pro Max (ID 136)
    // -------------------------------------------------------------------------
    136 => [
        'name' => 'iPhone 16 Pro Max',
        'specs' => [
            'man-hinh' => ['Màn hình', '6.9 inch, Super Retina XDR OLED, 120Hz ProMotion, 2.000 nits, Dynamic Island'],
            'he-dieu-hanh' => ['Hệ điều hành', 'iOS 18 (Nâng cấp iOS 19, Apple Intelligence)'],
            'chip-xu-ly-cpu' => ['Chip xử lý (CPU)', 'Apple A18 Pro 6 nhân (Tiến trình 3nm thế hệ 2)'],
            'bo-nho-trong-rom' => ['Bộ nhớ trong (ROM)', '256 GB NVMe siêu tốc'],
            'ram' => ['RAM', '8 GB Unified Memory'],
            'camera-sau' => ['Camera sau', 'Chính 48MP Fusion + Siêu rộng 48MP + Tele 12MP (Zoom quang 5x Tetraprism)'],
            'camera-truoc' => ['Camera trước', '12MP TrueDepth, Autofocus, Photonic Engine'],
            'pin-sac' => ['Pin & Sạc', '4.685 mAh, Sạc nhanh 30W, Sạc MagSafe 25W'],
            'chat-lieu' => ['Chất liệu', 'Khung viền Titanium cấp 5, Mặt lưng kính nhám cao cấp'],
            'cong-ket-noi' => ['Cổng kết nối', 'USB-C chuẩn USB 3 (Tốc độ 10Gbps), DisplayPort'],
            'khang-nuoc-bui' => ['Kháng nước & bụi', 'IP68 (Độ sâu 6 mét trong thời gian tối đa 30 phút)'],
            'trong-luong-kich-thuoc' => ['Trọng lượng & Kích thước', '227 g | 163 x 77.6 x 8.25 mm'],
        ],
        'content' => <<<HTML
<div class="ktd-article-intro">
    <p class="ktd-lead-text">
        <strong>iPhone 16 Pro Max 256GB</strong> là mẫu flagship hội tụ tinh hoa công nghệ hàng đầu của Apple. Nổi bật với màn hình khổng lồ <strong>6.9 inch</strong> viền siêu mỏng, phím bấm <strong>Camera Control</strong> hoàn toàn mới và chip <strong>A18 Pro</strong> tối ưu cho Apple Intelligence, đây là cỗ máy hoàn hảo cho người dùng chuyên nghiệp.
    </p>
</div>

<h2>1. Màn Hình Lớn Nhất 6.9 Inch Trong Thiết Kế Titanium Đẳng Cấp</h2>
<p>
    Nhờ ứng dụng công nghệ viền màn hình siêu mỏng Border Reduction Structure (BRS), kích thước màn hình được mở rộng lên <strong>6.9 inch</strong> mà kích thước tổng thể thân máy gần như không đổi. Khung viền Titanium cấp 5 mang lại sự sang trọng và độ bền vượt thời gian.
</p>

<h2>2. Phím Điều Khiển Camera Control Đột Phá</h2>
<p>
    Nút <strong>Camera Control</strong> tích hợp cảm biến xúc giác Taptic Engine cho phép bạn mở ứng dụng máy ảnh tức thì, trượt ngón tay để zoom, bấm nhẹ để khóa nét và bấm sâu để ghi lại khoảnh khắc mà không cần chạm vào màn hình.
</p>

<div class="ktd-highlight-box">
    <strong>Hệ thống tản nhiệt nâng cấp:</strong> Cấu trúc khung nhôm tái chế dẫn nhiệt bên trong kết hợp mặt lưng kính được tối ưu hóa giúp iPhone 16 Pro Max duy trì hiệu năng chơi game lâu hơn 20% so với thế hệ trước.
</div>

<h2>3. Bộ Vi Xử Lý A18 Pro & Sức Mạnh Trí Tuệ Nhân Tạo</h2>
<p>
    Sản xuất trên tiến trình 3nm thế hệ thứ hai, <strong>A18 Pro</strong> mang đến 16 nhân Neural Engine chuyên dụng, giúp các tác vụ Apple Intelligence như Siri thông minh, tóm tắt nội dung và tạo hình ảnh Genmoji diễn ra nhanh như chớp.
</p>

<h2>4. Bộ 3 Camera Pro: Siêu Rộng 48MP & Zoom 5x Tetraprism</h2>
<p>
    Camera góc siêu rộng được nâng cấp lên độ phân giải <strong>48MP</strong>, mang lại những bức ảnh macro cực kỳ sắc nét. Ống kính tiềm vọng Tetraprism hỗ trợ zoom quang học 5x sắc nét, lý tưởng cho chụp ảnh chân dung từ xa và quay video 4K 120fps Dolby Vision.
</p>

<div class="ktd-article-conclusion">
    <h3>Đặt Mua iPhone 16 Pro Max Tại KTD Store</h3>
    <p>
        Sở hữu ngay siêu phẩm iPhone 16 Pro Max chính hãng VN/A tại <strong>KTD Store</strong> để nhận đặc quyền bảo hành 12 tháng, hỗ trợ trả góp 0% lãi suất và giao hàng hỏa tốc trong 2 giờ.
    </p>
</div>
HTML
    ],

    // -------------------------------------------------------------------------
    // 5. iPhone 16 Pro (ID 137)
    // -------------------------------------------------------------------------
    137 => [
        'name' => 'iPhone 16 Pro',
        'specs' => [
            'man-hinh' => ['Màn hình', '6.3 inch, Super Retina XDR OLED, 120Hz ProMotion, 2.000 nits, Dynamic Island'],
            'he-dieu-hanh' => ['Hệ điều hành', 'iOS 18 (Nâng cấp iOS 19, Apple Intelligence)'],
            'chip-xu-ly-cpu' => ['Chip xử lý (CPU)', 'Apple A18 Pro 6 nhân (Tiến trình 3nm thế hệ 2)'],
            'bo-nho-trong-rom' => ['Bộ nhớ trong (ROM)', '128 GB NVMe siêu tốc'],
            'ram' => ['RAM', '8 GB Unified Memory'],
            'camera-sau' => ['Camera sau', 'Chính 48MP Fusion + Siêu rộng 48MP + Tele 12MP (Zoom quang 5x Tetraprism)'],
            'camera-truoc' => ['Camera trước', '12MP TrueDepth, Autofocus, Photonic Engine'],
            'pin-sac' => ['Pin & Sạc', '3.582 mAh, Sạc nhanh 30W, Sạc MagSafe 25W'],
            'chat-lieu' => ['Chất liệu', 'Khung viền Titanium cấp 5, Kính Ceramic Shield thế hệ mới'],
            'cong-ket-noi' => ['Cổng kết nối', 'USB-C chuẩn USB 3 (Tốc độ 10Gbps), DisplayPort'],
            'khang-nuoc-bui' => ['Kháng nước & bụi', 'IP68 (Độ sâu 6 mét trong thời gian tối đa 30 phút)'],
            'trong-luong-kich-thuoc' => ['Trọng lượng & Kích thước', '199 g | 149.6 x 71.5 x 8.25 mm'],
        ],
        'content' => <<<HTML
<div class="ktd-article-intro">
    <p class="ktd-lead-text">
        <strong>iPhone 16 Pro 128GB</strong> mang trọn vẹn những công nghệ đỉnh cao nhất của dòng Pro Max vào một kích thước 6.3 inch thanh lịch. Trang bị chip <strong>Apple A18 Pro</strong>, nút Camera Control thế hệ mới và cụm camera tele 5x Tetraprism, đây là bạn đồng hành hoàn hảo của người sáng tạo nội dung.
    </p>
</div>

<h2>1. Nâng Cấp Màn Hình 6.3 Inch Viền Cực Mỏng</h2>
<p>
    Không gian hiển thị tăng từ 6.1 inch lên <strong>6.3 inch</strong> sắc nét nhờ viền màn hình mỏng bậc nhất trên smartphone hiện nay. Tấm nền OLED Super Retina XDR 120Hz ProMotion đem đến trải nghiệm hình ảnh tuyệt mỹ, rực rỡ và chân thực trong từng khung hình.
</p>

<h2>2. Phím Bấm Camera Control – Điều Khiển Quang Học Trong Tầm Tay</h2>
<p>
    Nút điều khiển <strong>Camera Control</strong> cho phép bạn kích hoạt chế độ chụp ảnh, đổi phong cách nhiếp ảnh photographic styles và tinh chỉnh tiêu cự bằng thao tác trượt cảm ứng nhẹ nhàng, chuyên nghiệp như trên máy ảnh compact.
</p>

<div class="ktd-highlight-box">
    <strong>Camera Telephoto 5x cao cấp:</strong> Lần đầu tiên bản Pro tiêu chuẩn được tích hợp ống kính tiềm vọng Tetraprism zoom quang 5x tiêu cự 120mm sắc sảo, bắt trọn từng chi tiết từ cự ly xa.
</div>

<h2>3. Hiệu Năng Vô Song Cùng A18 Pro & Apple Intelligence</h2>
<p>
    Con chip A18 Pro đưa hiệu năng xử lý đồ họa lên tầm cao mới, cho phép chơi mượt mà các tựa game console ngay trên điện thoại và tăng cường khả năng dịch thuật, biên tập hình ảnh tức thì của Apple Intelligence.
</p>

<div class="ktd-article-conclusion">
    <h3>Mua iPhone 16 Pro Tại KTD Store</h3>
    <p>
        Trải nghiệm trực tiếp và sở hữu ngay iPhone 16 Pro chính hãng tại showroom <strong>KTD Store</strong> với ưu đãi giảm giá thẻ ngân hàng và hỗ trợ kỹ thuật tận tâm 24/7.
    </p>
</div>
HTML
    ],

    // -------------------------------------------------------------------------
    // 6. iPhone 16 Plus (ID 138)
    // -------------------------------------------------------------------------
    138 => [
        'name' => 'iPhone 16 Plus',
        'specs' => [
            'man-hinh' => ['Màn hình', '6.7 inch, Super Retina XDR OLED, 60Hz, 2.000 nits, Dynamic Island'],
            'he-dieu-hanh' => ['Hệ điều hành', 'iOS 18 (Hỗ trợ Apple Intelligence)'],
            'chip-xu-ly-cpu' => ['Chip xử lý (CPU)', 'Apple A18 6 nhân (Tiến trình 3nm thế hệ 2)'],
            'bo-nho-trong-rom' => ['Bộ nhớ trong (ROM)', '128 GB NVMe siêu tốc'],
            'ram' => ['RAM', '8 GB Unified Memory'],
            'camera-sau' => ['Camera sau', 'Kép 48MP Fusion + 12MP Siêu rộng (Chụp ảnh & quay video không gian)'],
            'camera-truoc' => ['Camera trước', '12MP TrueDepth, Autofocus, Photonic Engine'],
            'pin-sac' => ['Pin & Sạc', '4.674 mAh, Sạc nhanh 25W, Sạc MagSafe 25W'],
            'chat-lieu' => ['Chất liệu', 'Khung nhôm hàng không vũ trụ, Mặt lưng kính pha màu'],
            'cong-ket-noi' => ['Cổng kết nối', 'USB-C chuẩn USB 2'],
            'khang-nuoc-bui' => ['Kháng nước & bụi', 'IP68 (Độ sâu 6 mét trong thời gian tối đa 30 phút)'],
            'trong-luong-kich-thuoc' => ['Trọng lượng & Kích thước', '199 g | 160.9 x 77.8 x 7.8 mm'],
        ],
        'content' => <<<HTML
<div class="ktd-article-intro">
    <p class="ktd-lead-text">
        <strong>iPhone 16 Plus 128GB</strong> là chiếc smartphone lý tưởng cho người dùng yêu thích màn hình lớn <strong>6.7 inch</strong> cùng thời lượng pin siêu bền bỉ kéo dài suốt 2 ngày. Với vi xử lý <strong>Apple A18</strong>, nút Action Button và nút Camera Control tiện dụng, đây là mẫu máy toàn diện bậc nhất phân khúc.
    </p>
</div>

<h2>1. Màn Hình Rộng 6.7 Inch & Dynamic Island Tiện Dụng</h2>
<p>
    Không gian hiển thị rộng rãi <strong>6.7 inch</strong> trên tấm nền Super Retina XDR OLED mang lại trải nghiệm xem phim, lướt mạng xã hội và chơi game cực kỳ đã mắt. Đảo thích ứng Dynamic Island cập nhật trực tiếp chuyến bay, tỷ số bóng đá và phát nhạc một cách trực quan.
</p>

<h2>2. Pin Siêu Trâu Dẫn Đầu Bảng Xếp Hạng</h2>
<p>
    iPhone 16 Plus sở hữu viên pin dung tích lớn cho thời gian phát video lên đến 27 tiếng liên tục. Người dùng văn phòng và du lịch có thể yên tâm sử dụng từ sáng sớm đến tận đêm khuya mà không lo hết pin giữa chừng.
</p>

<div class="ktd-highlight-box">
    <strong>Quay Video Không Gian (Spatial Video):</strong> Cụm camera đặt dọc hoàn toàn mới cho phép quay video không gian 3D tương thích hoàn hảo với kính Apple Vision Pro.
</div>

<h2>3. Chip A18 Nhanh Hơn 30% & RAM 8GB Sẵn Sàng Cho AI</h2>
<p>
    Trang bị chip A18 tiến trình 3nm thế hệ 2, iPhone 16 Plus chạy mượt mà các tác vụ AI thông minh, tự động tóm tắt email và dọn dẹp ảnh thừa chỉ với một cú chạm.
</p>

<div class="ktd-article-conclusion">
    <h3>Ưu Đãi Khi Mua iPhone 16 Plus Tại KTD Store</h3>
    <p>
        Mua ngay iPhone 16 Plus tại <strong>KTD Store</strong> để nhận gói bảo hành mở rộng, tặng dán cường lực cao cấp và giảm thêm 500.000đ khi thanh toán qua chuyển khoản.
    </p>
</div>
HTML
    ],

    // -------------------------------------------------------------------------
    // 7. iPhone 16 (ID 139)
    // -------------------------------------------------------------------------
    139 => [
        'name' => 'iPhone 16',
        'specs' => [
            'man-hinh' => ['Màn hình', '6.1 inch, Super Retina XDR OLED, 2.000 nits, Dynamic Island'],
            'he-dieu-hanh' => ['Hệ điều hành', 'iOS 18 (Hỗ trợ Apple Intelligence)'],
            'chip-xu-ly-cpu' => ['Chip xử lý (CPU)', 'Apple A18 6 nhân (Tiến trình 3nm thế hệ 2)'],
            'bo-nho-trong-rom' => ['Bộ nhớ trong (ROM)', '128 GB NVMe siêu tốc'],
            'ram' => ['RAM', '8 GB Unified Memory'],
            'camera-sau' => ['Camera sau', 'Kép 48MP Fusion + 12MP Siêu rộng, Macro photography'],
            'camera-truoc' => ['Camera trước', '12MP TrueDepth, Autofocus'],
            'pin-sac' => ['Pin & Sạc', '3.561 mAh, Sạc nhanh 25W, Sạc MagSafe 25W'],
            'chat-lieu' => ['Chất liệu', 'Khung nhôm hàng không vũ trụ, Kính Ceramic Shield thế hệ mới'],
            'cong-ket-noi' => ['Cổng kết nối', 'USB-C chuẩn USB 2'],
            'khang-nuoc-bui' => ['Kháng nước & bụi', 'IP68 (Độ sâu 6 mét trong thời gian tối đa 30 phút)'],
            'trong-luong-kich-thuoc' => ['Trọng lượng & Kích thước', '170 g | 147.6 x 71.6 x 7.8 mm'],
        ],
        'content' => <<<HTML
<div class="ktd-article-intro">
    <p class="ktd-lead-text">
        <strong>iPhone 16 128GB</strong> ghi điểm mạnh mẽ nhờ cụm camera đặt dọc độc đáo, nút bấm <strong>Action Button</strong> đa năng và phím cảm ứng <strong>Camera Control</strong> thời thượng. Được vận hành bởi chip <strong>Apple A18</strong> tiến trình 3nm, đây là chiếc iPhone tiêu chuẩn đáng sở hữu nhất năm.
    </p>
</div>

<h2>1. Thiết Kế Trẻ Trung – Cụm Camera Đặt Dọc Cá Tính</h2>
<p>
    Thiết kế camera xếp dọc mang hơi thở hiện đại, kết hợp các tông màu pastel rực rỡ như Hồng, Xanh ngọc, Xanh lưu ly, Đen và Trắng. Mặt lưng kính pha màu cùng khung nhôm tái chế tạo cảm giác cầm nắm đầm tay, chắc chắn.
</p>

<h2>2. Bổ Sung Nút Action & Phím Bấm Camera Control</h2>
<p>
    Nút gạt rung truyền thống đã được thay thế bằng <strong>Action Button</strong> linh hoạt, cho phép người dùng tùy biến mở đèn pin, ghi âm, dịch thuật nhanh chỉ với một lần nhấn. Phím Camera Control đem lại trải nghiệm chụp ảnh nhanh chóng và chuẩn xác.
</p>

<div class="ktd-highlight-box">
    <strong>Chụp Macro Cận Cảnh:</strong> Lần đầu tiên camera siêu rộng trên dòng iPhone thường hỗ trợ lấy nét tự động macro, chụp rõ từng cánh hoa và giọt sương mai.
</div>

<h2>3. Sức Mạnh Vượt Bậc Của Chip Apple A18</h2>
<p>
    Với vi xử lý A18 mạnh hơn 30% và tiết kiệm pin hơn 30% so với thế hệ trước, iPhone 16 xử lý mượt mà mọi ứng dụng học tập, văn phòng và giải trí đỉnh cao.
</p>

<div class="ktd-article-conclusion">
    <h3>Mua Ngay iPhone 16 Tại KTD Store</h3>
    <p>
        Đặt mua iPhone 16 chính hãng tại <strong>KTD Store</strong> để nhận mức giá cạnh tranh nhất thị trường cùng chính sách bảo hành 1 đổi 1 chu đáo.
    </p>
</div>
HTML
    ],

    // -------------------------------------------------------------------------
    // 8. iPhone 15 Pro Max (ID 140)
    // -------------------------------------------------------------------------
    140 => [
        'name' => 'iPhone 15 Pro Max',
        'specs' => [
            'man-hinh' => ['Màn hình', '6.7 inch, Super Retina XDR OLED, 120Hz ProMotion, 2.000 nits, Dynamic Island'],
            'he-dieu-hanh' => ['Hệ điều hành', 'iOS 18 (Hỗ trợ Apple Intelligence)'],
            'chip-xu-ly-cpu' => ['Chip xử lý (CPU)', 'Apple A17 Pro 6 nhân (Tiến trình 3nm tiên phong)'],
            'bo-nho-trong-rom' => ['Bộ nhớ trong (ROM)', '256 GB NVMe siêu tốc'],
            'ram' => ['RAM', '8 GB Unified Memory'],
            'camera-sau' => ['Camera sau', 'Chính 48MP (OIS) + Siêu rộng 12MP + Tele tiềm vọng 12MP (Zoom quang 5x)'],
            'camera-truoc' => ['Camera trước', '12MP TrueDepth, Autofocus'],
            'pin-sac' => ['Pin & Sạc', '4.422 mAh, Sạc nhanh 27W, Sạc không dây MagSafe 15W'],
            'chat-lieu' => ['Chất liệu', 'Khung viền Titanium cấp 5, Mặt lưng kính nhám cao cấp'],
            'cong-ket-noi' => ['Cổng kết nối', 'USB-C chuẩn USB 3 (Tốc độ 10Gbps), DisplayPort'],
            'khang-nuoc-bui' => ['Kháng nước & bụi', 'IP68 (Độ sâu 6 mét trong thời gian tối đa 30 phút)'],
            'trong-luong-kich-thuoc' => ['Trọng lượng & Kích thước', '221 g | 159.9 x 76.7 x 8.25 mm'],
        ],
        'content' => <<<HTML
<div class="ktd-article-intro">
    <p class="ktd-lead-text">
        <strong>iPhone 15 Pro Max 256GB</strong> là biểu tượng chuyển giao công nghệ mang tính cách mạng của Apple với khung viền <strong>Titanium cấp 5</strong> siêu nhẹ, cổng kết nối <strong>USB-C 3.0</strong> tốc độ cao và ống kính zoom quang học 5x sắc nét. Đây vẫn là một trong những chiếc flagship đáng tiền và được săn đón hàng đầu.
    </p>
</div>

<h2>1. Khung Viền Titanium Nhẹ Nhàng & Bền Bỉ</h2>
<p>
    Lần đầu tiên chất liệu Titanium hàng không vũ trụ được ứng dụng trên iPhone, giúp giảm trọng lượng máy tới 19g so với thế hệ trước. Cạnh máy được vê cong nhẹ nhàng tạo cảm giác cầm nắm êm ái, đầm tay.
</p>

<h2>2. Hiệu Năng Đột Phá Với Chip Apple A17 Pro 3nm</h2>
<p>
    Vi xử lý <strong>A17 Pro</strong> tiên phong tiến trình 3nm sở hữu GPU 6 nhân hỗ trợ phần cứng Ray Tracing, cho phép tái hiện ánh sáng và đổ bóng chân thực trong các tựa game đồ họa phức tạp nhất.
</p>

<div class="ktd-highlight-box">
    <strong>Cổng USB-C 10Gbps:</strong> Cổng USB-C chuẩn USB 3 cho phép truyền các file video ProRes dung lượng lớn trực tiếp ra ổ cứng gắn ngoài với tốc độ siêu tốc.
</div>

<h2>3. Camera Telephoto Tiềm Vọng Zoom 5x Sắc Nét</h2>
<p>
    Cảm biến tiềm vọng lăng kính tetraprism đem đến tiêu cự 120mm zoom quang 5x không suy giảm chất lượng, giúp bạn bắt trọn các chủ thể biểu diễn ca nhạc, thể thao từ khoảng cách xa.
</p>

<div class="ktd-article-conclusion">
    <h3>Có Nên Mua iPhone 15 Pro Max Hiện Tại?</h3>
    <p>
        Với mức giá đã hạ nhiệt cực tốt và cấu hình vẫn ở đỉnh cao, iPhone 15 Pro Max tại <strong>KTD Store</strong> là lựa chọn thông minh cho khách hàng muốn sở hữu flagship Titanium với chi phí tối ưu.
    </p>
</div>
HTML
    ],

    // -------------------------------------------------------------------------
    // 9. iPhone 15 (ID 141)
    // -------------------------------------------------------------------------
    141 => [
        'name' => 'iPhone 15',
        'specs' => [
            'man-hinh' => ['Màn hình', '6.1 inch, Super Retina XDR OLED, Dynamic Island, 2.000 nits'],
            'he-dieu-hanh' => ['Hệ điều hành', 'iOS 18'],
            'chip-xu-ly-cpu' => ['Chip xử lý (CPU)', 'Apple A16 Bionic 6 nhân (Tiến trình 4nm)'],
            'bo-nho-trong-rom' => ['Bộ nhớ trong (ROM)', '128 GB NVMe siêu tốc'],
            'ram' => ['RAM', '6 GB Unified Memory'],
            'camera-sau' => ['Camera sau', 'Chính 48MP (OIS) + Siêu rộng 12MP, Zoom 2x chất lượng quang học'],
            'camera-truoc' => ['Camera trước', '12MP TrueDepth, Autofocus'],
            'pin-sac' => ['Pin & Sạc', '3.349 mAh, Sạc nhanh 20W, MagSafe 15W'],
            'chat-lieu' => ['Chất liệu', 'Khung nhôm hàng không vũ trụ, Mặt lưng kính pha màu'],
            'cong-ket-noi' => ['Cổng kết nối', 'USB-C chuẩn USB 2'],
            'khang-nuoc-bui' => ['Kháng nước & bụi', 'IP68 (Độ sâu 6 mét trong thời gian tối đa 30 phút)'],
            'trong-luong-kich-thuoc' => ['Trọng lượng & Kích thước', '171 g | 147.6 x 71.6 x 7.8 mm'],
        ],
        'content' => <<<HTML
<div class="ktd-article-intro">
    <p class="ktd-lead-text">
        <strong>iPhone 15 128GB</strong> đem lại bước lột xác toàn diện cho phân khúc tiêu chuẩn với cụm khuyết đảo động <strong>Dynamic Island</strong>, camera chính <strong>48MP</strong> sắc nét và cổng sạc <strong>USB-C</strong> phổ dụng. Thiết kế kính pha màu mềm mại giúp máy luôn nổi bật ở mọi góc nhìn.
    </p>
</div>

<h2>1. Dynamic Island Thông Minh & Màn Hình Siêu Sáng 2.000 Nits</h2>
<p>
    Dynamic Island mang đến cách tương tác trực quan với các thông báo và hoạt động trực tiếp. Màn hình OLED hiển thị rực rỡ với độ sáng cực đại 2.000 nits, giúp bạn sử dụng thoải mái ngay dưới ánh nắng mặt trời chói chang.
</p>

<h2>2. Camera Nâng Cấp Lên 48MP Sắc Nét</h2>
<p>
    Camera chính 48MP nâng cấp độ phân giải gấp 4 lần thế hệ trước, tự động nhận diện chân dung để bạn tùy chỉnh điểm lấy nét và độ xóa phông ngay sau khi chụp.
</p>

<div class="ktd-highlight-box">
    <strong>Tiện ích USB-C:</strong> Sử dụng chung một sợi cáp sạc USB-C duy nhất cho cả iPhone, iPad, MacBook và AirPods mà không cần mang theo nhiều dây sạc cồng kềnh.
</div>

<h2>3. Chip A16 Bionic Hoạt Động Bền Bỉ</h2>
<p>
    Chip A16 Bionic 6 nhân xử lý các tác vụ đồ họa và game thịnh hành cực kỳ trơn tru, đồng thời quản lý năng lượng hiệu quả cho thời lượng pin trọn vẹn cả ngày.
</p>

<div class="ktd-article-conclusion">
    <h3>Sở Hữu iPhone 15 Tại KTD Store</h3>
    <p>
        Mua ngay iPhone 15 chính hãng với mức giá cạnh tranh nhất tại <strong>KTD Store</strong>, hỗ trợ trả góp 0% duyệt hồ sơ nhanh chóng trong 15 phút.
    </p>
</div>
HTML
    ],

    // -------------------------------------------------------------------------
    // 10. Samsung Galaxy S26 Ultra (ID 142)
    // -------------------------------------------------------------------------
    142 => [
        'name' => 'Samsung Galaxy S26 Ultra',
        'specs' => [
            'man-hinh' => ['Màn hình', '6.8 inch, Dynamic AMOLED 2X, 1-120Hz, 3.600 nits, Kính Gorilla Armor 2'],
            'he-dieu-hanh' => ['Hệ điều hành', 'Android 16 (One UI 8.0, Galaxy AI thế hệ 3)'],
            'chip-xu-ly-cpu' => ['Chip xử lý (CPU)', 'Snapdragon 8 Elite Gen 2 for Galaxy (3nm GAA)'],
            'bo-nho-trong-rom' => ['Bộ nhớ trong (ROM)', '512 GB UFS 4.1 siêu tốc'],
            'ram' => ['RAM', '16 GB LPDDR5X'],
            'camera-sau' => ['Camera sau', 'Chính 200MP (OIS) + Siêu rộng 50MP + Tele 50MP (5x) + Tele 10MP (3x)'],
            'camera-truoc' => ['Camera trước', '40MP Dual Pixel AF'],
            'pin-sac' => ['Pin & Sạc', '5.200 mAh, Sạc nhanh siêu tốc 65W, Sạc không dây 25W'],
            'chat-lieu' => ['Chất liệu', 'Khung viền Titanium cấp 5, Kính cường lực chống phản xạ'],
            'cong-ket-noi' => ['Cổng kết nối', 'USB-C 3.2 Gen 2, Tích hợp bút S-Pen quyền năng'],
            'khang-nuoc-bui' => ['Kháng nước & bụi', 'IP68 (Độ sâu 1.5 mét trong thời gian tối đa 30 phút)'],
            'trong-luong-kich-thuoc' => ['Trọng lượng & Kích thước', '228 g | 162.3 x 79.0 x 8.4 mm'],
        ],
        'content' => <<<HTML
<div class="ktd-article-intro">
    <p class="ktd-lead-text">
        <strong>Samsung Galaxy S26 Ultra 512GB</strong> là đỉnh cao công nghệ smartphone Android toàn cầu. Sở hữu sức mạnh vượt trội từ vi xử lý <strong>Snapdragon 8 Elite Gen 2 for Galaxy</strong>, hệ thống camera 200MP chụp đêm siêu thực, bút <strong>S-Pen</strong> tích hợp và bộ công cụ <strong>Galaxy AI thế hệ mới</strong>, đây là người trợ lý đắc lực cho công việc và giải trí không giới hạn.
    </p>
</div>

<h2>1. Thiết Kế Titanium Cứng Cáp & Bút S-Pen Huyền Thoại</h2>
<p>
    Galaxy S26 Ultra tiếp tục giữ vững ngôn ngữ thiết kế mạnh mẽ với khung viền Titanium sang trọng. Cây bút S-Pen tích hợp bên trong thân máy cho phép bạn ghi chú nhanh trên màn hình khóa, chuyển chữ viết tay thành văn bản và điều khiển chụp ảnh từ xa qua cử chỉ Air Actions.
</p>

<h2>2. Màn Hình Dynamic AMOLED 2X 3.600 Nits Chống Chói Tối Đa</h2>
<p>
    Màn hình 6.8 inch Quad HD+ với tần số quét biến thiên 1-120Hz mang đến hình ảnh rực rỡ và sắc nét. Lớp kính cường lực Corning Gorilla Armor thế hệ mới giảm phản xạ ánh sáng tới 75%, giúp nội dung hiển thị trong trẻo dưới nắng gắt.
</p>

<div class="ktd-highlight-box">
    <strong>Đỉnh Cao Galaxy AI:</strong> Tính năng Khoanh vùng tìm kiếm (Circle to Search), dịch cuộc gọi hai chiều theo thời gian thực và tự động tóm tắt cuộc họp bằng tiếng Việt giúp năng suất công việc tăng gấp bội.
</div>

<h2>3. Hiệu Năng Snapdragon 8 Elite Gen 2 & Tản Nhiệt Buồng Hơi Khổng Lồ</h2>
<p>
    Được chế tạo trên tiến trình 3nm tiên tiến với buồng hơi tản nhiệt lớn gấp 1.9 lần, S26 Ultra mang lại khả năng xử lý đồ họa mượt mà, sẵn sàng đương đầu với mọi tựa game đồ họa nặng nhất hiện nay.
</p>

<h2>4. Camera Mắt Thần Bóng Đêm 200MP & Zoom Không Gian 100x</h2>
<p>
    Cảm biến chính 200MP kết hợp công nghệ xử lý ảnh AI ProVisual bắt trọn từng chi tiết sắc nét trong bóng tối. Hệ thống camera tele kép 3x và 5x quang học cung cấp khả năng zoom rõ ràng đến 100x cực kỳ ổn định.
</p>

<div class="ktd-article-conclusion">
    <h3>Mua Samsung Galaxy S26 Ultra Chính Hãng Tại KTD Store</h3>
    <p>
        Trải nghiệm trực tiếp Galaxy S26 Ultra tại <strong>KTD Store</strong> để nhận ngay gói bảo hiểm Samsung Care+, hỗ trợ thu cũ đổi mới trợ giá cao nhất thị trường và trả góp 0% lãi suất.
    </p>
</div>
HTML
    ],

    // -------------------------------------------------------------------------
    // 11. Samsung Galaxy S26 Plus (ID 143)
    // -------------------------------------------------------------------------
    143 => [
        'name' => 'Samsung Galaxy S26 Plus',
        'specs' => [
            'man-hinh' => ['Màn hình', '6.7 inch, Dynamic AMOLED 2X, QHD+, 1-120Hz, 3.200 nits, Vision Booster'],
            'he-dieu-hanh' => ['Hệ điều hành', 'Android 16 (One UI 8.0, Galaxy AI)'],
            'chip-xu-ly-cpu' => ['Chip xử lý (CPU)', 'Snapdragon 8 Elite for Galaxy (3nm)'],
            'bo-nho-trong-rom' => ['Bộ nhớ trong (ROM)', '256 GB UFS 4.0 siêu tốc'],
            'ram' => ['RAM', '12 GB LPDDR5X'],
            'camera-sau' => ['Camera sau', 'Chính 50MP (OIS) + Siêu rộng 50MP + Tele 10MP (Zoom quang 3x)'],
            'camera-truoc' => ['Camera trước', '12MP Dual Pixel AF'],
            'pin-sac' => ['Pin & Sạc', '4.900 mAh, Sạc nhanh siêu tốc 45W, Không dây 15W'],
            'chat-lieu' => ['Chất liệu', 'Khung nhôm Armor Aluminum 2, Kính Gorilla Glass Victus 2'],
            'cong-ket-noi' => ['Cổng kết nối', 'USB-C 3.2, DisplayPort'],
            'khang-nuoc-bui' => ['Kháng nước & bụi', 'IP68 (Độ sâu 1.5 mét trong thời gian tối đa 30 phút)'],
            'trong-luong-kich-thuoc' => ['Trọng lượng & Kích thước', '196 g | 158.5 x 75.9 x 7.7 mm'],
        ],
        'content' => <<<HTML
<div class="ktd-article-intro">
    <p class="ktd-lead-text">
        <strong>Samsung Galaxy S26 Plus 256GB</strong> mang đến sự cân bằng hoàn hảo giữa màn hình lớn <strong>6.7 inch Quad HD+</strong>, thời lượng pin bền bỉ 4.900 mAh và tốc độ sạc nhanh 45W. Vận hành bởi chip <strong>Snapdragon 8 Elite</strong>, đây là chiếc flagship mạnh mẽ, vừa vặn cho mọi nhu cầu.
    </p>
</div>

<h2>1. Màn Hình QHD+ Siêu Nét Cùng Thiết Kế Bo Cong Tinh Tế</h2>
<p>
    Màn hình Dynamic AMOLED 2X 6.7 inch với độ phân giải Quad HD+ hiển thị sắc nét từng điểm ảnh. Khung viền nhôm Armor Aluminum gia cố độ bền, bề mặt hoàn thiện nhám mờ chống trầy xước và bám vân tay tối đa.
</p>

<h2>2. Trải Nghiệm Trí Tuệ Nhân Tạo Galaxy AI Toàn Diện</h2>
<p>
    Tận hưởng đầy đủ sức mạnh của Galaxy AI: tính năng dịch trực tiếp cuộc gọi, trợ lý ghi âm thông minh tự động gỡ băng ghi âm và chia người nói bằng tiếng Việt, giúp tối ưu hóa công việc hàng ngày.
</p>

<div class="ktd-highlight-box">
    <strong>Sạc Nhanh Siêu Tốc 45W:</strong> Nạp lại 65% dung lượng pin chỉ sau 30 phút cắm sạc, sẵn sàng cho mọi hành trình mà không cần chờ đợi.
</div>

<h2>3. Hệ Thống 3 Camera 50MP Đa Năng</h2>
<p>
    Camera chính 50MP cùng chống rung quang học OIS ghi lại khung hình sắc nét, camera góc siêu rộng nâng cấp lên 50MP bắt trọn phong cảnh hùng vĩ với màu sắc rực rỡ chuẩn Samsung.
</p>

<div class="ktd-article-conclusion">
    <h3>Mua Galaxy S26 Plus Tại KTD Store</h3>
    <p>
        Mua ngay Galaxy S26 Plus chính hãng tại <strong>KTD Store</strong> để nhận mức giá ưu đãi hấp dẫn kèm dịch vụ giao hàng tận nơi siêu tốc.
    </p>
</div>
HTML
    ],

    // -------------------------------------------------------------------------
    // 12. Samsung Galaxy S26 (ID 144)
    // -------------------------------------------------------------------------
    144 => [
        'name' => 'Samsung Galaxy S26',
        'specs' => [
            'man-hinh' => ['Màn hình', '6.2 inch, Dynamic AMOLED 2X, FHD+, 1-120Hz, 3.000 nits'],
            'he-dieu-hanh' => ['Hệ điều hành', 'Android 16 (One UI 8.0, Galaxy AI)'],
            'chip-xu-ly-cpu' => ['Chip xử lý (CPU)', 'Snapdragon 8 Elite for Galaxy (3nm)'],
            'bo-nho-trong-rom' => ['Bộ nhớ trong (ROM)', '256 GB UFS 4.0 siêu tốc'],
            'ram' => ['RAM', '12 GB LPDDR5X'],
            'camera-sau' => ['Camera sau', 'Chính 50MP (OIS) + Siêu rộng 12MP + Tele 10MP (Zoom quang 3x)'],
            'camera-truoc' => ['Camera trước', '12MP Dual Pixel AF'],
            'pin-sac' => ['Pin & Sạc', '4.000 mAh, Sạc nhanh 25W, Sạc không dây 15W'],
            'chat-lieu' => ['Chất liệu', 'Armor Aluminum 2, Kính cường lực Victus 2'],
            'cong-ket-noi' => ['Cổng kết nối', 'USB-C 3.2'],
            'khang-nuoc-bui' => ['Kháng nước & bụi', 'IP68 (Độ sâu 1.5 mét trong thời gian tối đa 30 phút)'],
            'trong-luong-kich-thuoc' => ['Trọng lượng & Kích thước', '167 g | 147.0 x 70.6 x 7.6 mm'],
        ],
        'content' => <<<HTML
<div class="ktd-article-intro">
    <p class="ktd-lead-text">
        <strong>Samsung Galaxy S26 256GB</strong> là biểu mẫu chuẩn mực của một chiếc smartphone flagship nhỏ gọn cao cấp. Với trọng lượng chỉ 167g, màn hình 6.2 inch sắc nét 120Hz và sức mạnh từ chip <strong>Snapdragon 8 Elite</strong>, máy mang lại sự linh hoạt tuyệt đối cho người dùng năng động.
    </p>
</div>

<h2>1. Thiết Kế Nhỏ Gọn – Cầm Nắm Vừa Vặn Lòng Bàn Tay</h2>
<p>
    Galaxy S26 có kích thước cực kỳ thon gọn, dễ dàng bỏ túi quần hoặc túi xách nhỏ mà không tạo cảm giác cồng kềnh. Khung viền phẳng nguyên khối kết hợp mặt lưng kính mờ tinh tế tạo nên vẻ đẹp sang trọng và hiện đại.
</p>

<h2>2. Màn Hình Dynamic AMOLED 2X Sống Động</h2>
<p>
    Tấm nền AMOLED 6.2 inch với độ sáng cực đại 3.000 nits giúp hình ảnh luôn hiển thị rực rỡ ngoài trời nắng. Tần số quét tương thích 1-120Hz đảm bảo mọi thao tác lướt mạng xã hội đều cực kỳ trơn tru.
</p>

<div class="ktd-highlight-box">
    <strong>Trọng Lượng 167g Siêu Nhẹ:</strong> Chiếc flagship cao cấp nhẹ bậc nhất phân khúc, giảm thiểu hoàn toàn tình trạng mỏi ngón tay khi sử dụng một tay trong thời gian dài.
</div>

<h2>3. Bộ 3 Camera Chụp Ảnh Đỉnh Cao & Galaxy AI Tiện Lợi</h2>
<p>
    Cụm 3 camera sau đáp ứng đầy đủ góc chụp từ góc rộng 50MP, siêu rộng 12MP đến tele 3x quang học. Các tính năng Galaxy AI cho phép bạn xóa vật thể thừa và tạo chân dung nghệ thuật tức thì.
</p>

<div class="ktd-article-conclusion">
    <h3>Mua Ngay Galaxy S26 Tại KTD Store</h3>
    <p>
        Sở hữu Galaxy S26 chính hãng với quà tặng hấp dẫn và chế độ bảo hành 12 tháng tại hệ thống cửa hàng <strong>KTD Store</strong>.
    </p>
</div>
HTML
    ],

    // -------------------------------------------------------------------------
    // 13. Samsung Galaxy S25 Ultra (ID 145)
    // -------------------------------------------------------------------------
    145 => [
        'name' => 'Samsung Galaxy S25 Ultra',
        'specs' => [
            'man-hinh' => ['Màn hình', '6.8 inch, Dynamic AMOLED 2X, 1-120Hz, 2.600 nits, Kính Gorilla Armor'],
            'he-dieu-hanh' => ['Hệ điều hành', 'Android 15 (One UI 7.0, Galaxy AI)'],
            'chip-xu-ly-cpu' => ['Chip xử lý (CPU)', 'Snapdragon 8 Elite 8 nhân (Tiến trình 3nm)'],
            'bo-nho-trong-rom' => ['Bộ nhớ trong (ROM)', '256 GB UFS 4.0 siêu tốc'],
            'ram' => ['RAM', '12 GB LPDDR5X'],
            'camera-sau' => ['Camera sau', 'Chính 200MP + Siêu rộng 50MP + Tele tiềm vọng 50MP (5x) + Tele 10MP (3x)'],
            'camera-truoc' => ['Camera trước', '12MP Dual Pixel AF'],
            'pin-sac' => ['Pin & Sạc', '5.000 mAh, Sạc nhanh 45W, Không dây 15W'],
            'chat-lieu' => ['Chất liệu', 'Khung Titanium cấp 5, Kính Gorilla Armor chống chói 75%'],
            'cong-ket-noi' => ['Cổng kết nối', 'USB-C 3.2 Gen 1, Tích hợp bút S-Pen'],
            'khang-nuoc-bui' => ['Kháng nước & bụi', 'IP68 (Độ sâu 1.5 mét trong thời gian tối đa 30 phút)'],
            'trong-luong-kich-thuoc' => ['Trọng lượng & Kích thước', '219 g | 162.8 x 77.6 x 8.2 mm'],
        ],
        'content' => <<<HTML
<div class="ktd-article-intro">
    <p class="ktd-lead-text">
        <strong>Samsung Galaxy S25 Ultra 256GB</strong> đem lại chuẩn mực thiết kế mới với các góc cạnh được bo tròn nhẹ nhàng, khắc phục hoàn toàn sự cấn tay của các thế hệ trước. Sức mạnh vi xử lý <strong>Snapdragon 8 Elite</strong> cùng hệ thống camera tiềm vọng 50MP 5x khẳng định đẳng cấp dẫn đầu.
    </p>
</div>

<h2>1. Thiết Kế Mới Bo Góc Tinh Tế & Viền Siêu Mỏng</h2>
<p>
    Galaxy S25 Ultra tối ưu hóa công thái học với khung viền Titanium bo nhẹ 4 góc, mang lại cảm giác cầm êm ái hơn hẳn. Kính Gorilla Armor chống lóa vượt trội bảo vệ mắt và giữ cho màn hình luôn trong trẻo.
</p>

<h2>2. Camera Tiềm Vọng 50MP Đột Phá Quang Học</h2>
<p>
    Nâng cấp camera tele 5x lên cảm biến độ phân giải cao <strong>50MP</strong>, kết hợp công nghệ ghép điểm ảnh giúp các bức ảnh zoom ở cự ly xa vẫn đạt độ chi tiết kinh ngạc ngay cả trong điều kiện thiếu sáng.
</p>

<div class="ktd-highlight-box">
    <strong>Bút S-Pen Viết Vẽ Tự Nhiên:</strong> Độ trễ cực thấp chỉ 2.8ms cho trải nghiệm viết vẽ thật như trên giấy, hỗ trợ chuyển chữ ký số và chỉnh sửa tài liệu chuyên nghiệp.
</div>

<h2>3. Trí Tuệ Nhân Tạo Galaxy AI Tích Hợp Sâu</h2>
<p>
    Giao diện One UI 7.0 trên nền tảng Galaxy AI giúp tự động hóa lịch trình công tác, tóm tắt bài viết website và chỉnh sửa ảnh chụp với độ chính xác cao.
</p>

<div class="ktd-article-conclusion">
    <h3>Đặt Mua Galaxy S25 Ultra Tại KTD Store</h3>
    <p>
        Mua Galaxy S25 Ultra chính hãng giá tốt tại <strong>KTD Store</strong>, hỗ trợ trả góp 0%, tặng bao da chính hãng và bảo hành uy tín toàn quốc.
    </p>
</div>
HTML
    ],

    // -------------------------------------------------------------------------
    // 14. Samsung Galaxy S24 Ultra (ID 146)
    // -------------------------------------------------------------------------
    146 => [
        'name' => 'Samsung Galaxy S24 Ultra',
        'specs' => [
            'man-hinh' => ['Màn hình', '6.8 inch, Dynamic AMOLED 2X, 1-120Hz phẳng, 2.600 nits'],
            'he-dieu-hanh' => ['Hệ điều hành', 'Android 14 (Nâng cấp One UI 6.1.1 / Galaxy AI)'],
            'chip-xu-ly-cpu' => ['Chip xử lý (CPU)', 'Snapdragon 8 Gen 3 for Galaxy (4nm)'],
            'bo-nho-trong-rom' => ['Bộ nhớ trong (ROM)', '256 GB UFS 4.0 siêu tốc'],
            'ram' => ['RAM', '12 GB LPDDR5X'],
            'camera-sau' => ['Camera sau', '200MP chính + 50MP tiềm vọng (5x) + 12MP siêu rộng + 10MP (3x)'],
            'camera-truoc' => ['Camera trước', '12MP Dual Pixel AF'],
            'pin-sac' => ['Pin & Sạc', '5.000 mAh, Sạc nhanh 45W, Không dây 15W'],
            'chat-lieu' => ['Chất liệu', 'Khung viền Titanium, Kính Corning Gorilla Armor'],
            'cong-ket-noi' => ['Cổng kết nối', 'USB-C 3.2 Gen 1, Kèm bút S-Pen'],
            'khang-nuoc-bui' => ['Kháng nước & bụi', 'IP68 (Độ sâu 1.5 mét trong thời gian tối đa 30 phút)'],
            'trong-luong-kich-thuoc' => ['Trọng lượng & Kích thước', '232 g | 162.3 x 79.0 x 8.6 mm'],
        ],
        'content' => <<<HTML
<div class="ktd-article-intro">
    <p class="ktd-lead-text">
        <strong>Samsung Galaxy S24 Ultra 256GB</strong> là mẫu điện thoại tiên phong mở ra kỷ nguyên <strong>Galaxy AI</strong> trên smartphone. Khung Titanium phẳng mạnh mẽ, kính chống lóa <strong>Corning Gorilla Armor</strong> cùng bút <strong>S-Pen</strong> biến chiếc máy thành công cụ làm việc và giải trí vô song.
    </p>
</div>

<h2>1. Màn Hình Phẳng Hiện Đại & Kính Chống Phản Quang Đột Phá</h2>
<p>
    Thiết kế màn hình phẳng hoàn toàn giúp thao tác viết vẽ bằng bút S-Pen chạm sát mép viền dễ dàng. Mặt kính Gorilla Armor giảm tới 75% hiện tượng lóa sáng, hiển thị rõ nét từng văn bản ngay dưới ánh mặt trời rực rỡ.
</p>

<h2>2. Kỷ Nguyên AI Quyền Năng</h2>
<p>
    Khởi xướng các tính năng AI kinh điển: Circle to Search với Google, Trợ lý Chat thông minh tự động đổi văn phong lịch sự, và Trợ lý Chỉnh ảnh AI cho phép di chuyển hoặc xóa vật thể tự nhiên như thật.
</p>

<div class="ktd-highlight-box">
    <strong>Khung Viền Titanium Cao Cấp:</strong> Tăng cường khả năng chống trầy xước và chịu lực va đập, bảo vệ chiếc máy an toàn trong mọi hoàn cảnh.
</div>

<h2>3. Camera 200MP & Ống Kính Tiềm Vọng 50MP 5x</h2>
<p>
    Hệ thống 4 camera sau đẳng cấp hỗ trợ zoom quang học từ 2x, 3x, 5x đến 10x cực kỳ sắc nét, đi kèm chế độ chụp đêm Nightography chuyên nghiệp.
</p>

<div class="ktd-article-conclusion">
    <h3>Mua Galaxy S24 Ultra Giá Tốt Nhất Tại KTD Store</h3>
    <p>
        Sở hữu Galaxy S24 Ultra chính hãng với mức giá cực kỳ ưu đãi tại <strong>KTD Store</strong> cùng chế độ bảo hành 12 tháng an tâm tuyệt đối.
    </p>
</div>
HTML
    ],

    // -------------------------------------------------------------------------
    // 15. Samsung Galaxy Z Fold6 (ID 147)
    // -------------------------------------------------------------------------
    147 => [
        'name' => 'Samsung Galaxy Z Fold6',
        'specs' => [
            'man-hinh' => ['Màn hình', 'Chính 7.6 inch gập (1-120Hz) + Phụ 6.3 inch (1-120Hz, 2.600 nits)'],
            'he-dieu-hanh' => ['Hệ điều hành', 'Android 14 (One UI 6.1.1 tối ưu đa nhiệm gập)'],
            'chip-xu-ly-cpu' => ['Chip xử lý (CPU)', 'Snapdragon 8 Gen 3 for Galaxy (4nm)'],
            'bo-nho-trong-rom' => ['Bộ nhớ trong (ROM)', '256 GB UFS 4.0 siêu tốc'],
            'ram' => ['RAM', '12 GB LPDDR5X'],
            'camera-sau' => ['Camera sau', 'Chính 50MP (OIS) + Siêu rộng 12MP + Tele 10MP (Zoom quang 3x)'],
            'camera-truoc' => ['Camera trước', 'Ngoài 10MP + Ẩn dưới màn hình trong 4MP UDC'],
            'pin-sac' => ['Pin & Sạc', '4.400 mAh, Sạc nhanh 25W, Không dây 15W'],
            'chat-lieu' => ['Chất liệu', 'Bản lề FlexHinge nhôm Armor, Kính Gorilla Glass Victus 2'],
            'cong-ket-noi' => ['Cổng kết nối', 'USB-C 3.2, Hỗ trợ S-Pen Fold Edition'],
            'khang-nuoc-bui' => ['Kháng nước & bụi', 'IP48 (Kháng hạt vật thể trên 1mm và ngâm nước)'],
            'trong-luong-kich-thuoc' => ['Trọng lượng & Kích thước', '239 g | Mở: 153.5 x 132.6 x 5.6 mm / Gập: 153.5 x 68.1 x 12.1 mm'],
        ],
        'content' => <<<HTML
<div class="ktd-article-intro">
    <p class="ktd-lead-text">
        <strong>Samsung Galaxy Z Fold6 256GB</strong> tái định nghĩa trải nghiệm điện thoại gập với thiết kế vuông vức siêu mỏng nhẹ chỉ <strong>239g</strong> và độ mỏng 5.6mm khi mở. Màn hình ngoài rộng rãi <strong>6.3 inch</strong> kết hợp màn hình trong <strong>7.6 inch</strong> như máy tính bảng, đem đến hiệu suất đa nhiệm vô đối.
    </p>
</div>

<h2>1. Thiết Kế Bản Lề FlexHinge Mỏng Nhẹ Kỷ Lục</h2>
<p>
    Z Fold6 có trọng lượng nhẹ hơn đáng kể so với thế hệ trước, cạnh viền vuông vức nam tính. Bản lề rãnh kép FlexHinge cải tiến giúp hai nửa màn hình gập khít hoàn toàn và nếp gấp ở giữa trở nên mờ nhạt hơn bao giờ hết.
</p>

<h2>2. Đa Nhiệm Màn Hình Lớn Cùng Galaxy AI Chuyên Biệt</h2>
<p>
    Không gian 7.6 inch cho phép bạn mở đồng thời 3 ứng dụng mượt mà. Tính năng AI Phác thảo thông minh (Sketch to Image) tự động biến các nét vẽ phác thô của bạn thành bức tranh nghệ thuật 3D sống động chỉ trong vài giây.
</p>

<div class="ktd-highlight-box">
    <strong>Chuẩn Kháng Bụi & Nước IP48:</strong> Lần đầu tiên điện thoại gập Samsung đạt chuẩn kháng hạt bụi và chống nước bền bỉ, an tâm sử dụng hàng ngày.
</div>

<h2>3. Hiệu Năng Snapdragon 8 Gen 3 for Galaxy</h2>
<p>
    Trang bị buồng hơi tản nhiệt lớn hơn 1.6 lần, Z Fold6 vận hành trơn tru các ứng dụng văn phòng nặng, xuất video và chiến các tựa game đồ họa khủng trên màn hình lớn.
</p>

<div class="ktd-article-conclusion">
    <h3>Trải Nghiệm Galaxy Z Fold6 Tại KTD Store</h3>
    <p>
        Ghé thăm <strong>KTD Store</strong> để trải nghiệm siêu phẩm màn hình gập Galaxy Z Fold6 chính hãng với chính sách trả góp 0% và bảo hành rơi vỡ cao cấp.
    </p>
</div>
HTML
    ],

    // -------------------------------------------------------------------------
    // 16. Samsung Galaxy Z Flip6 (ID 148)
    // -------------------------------------------------------------------------
    148 => [
        'name' => 'Samsung Galaxy Z Flip6',
        'specs' => [
            'man-hinh' => ['Màn hình', 'Chính 6.7 inch gập FHD+ (120Hz) + FlexWindow 3.4 inch Super AMOLED'],
            'he-dieu-hanh' => ['Hệ điều hành', 'Android 14 (One UI 6.1.1, Galaxy AI đa góc FlexCam)'],
            'chip-xu-ly-cpu' => ['Chip xử lý (CPU)', 'Snapdragon 8 Gen 3 for Galaxy (4nm)'],
            'bo-nho-trong-rom' => ['Bộ nhớ trong (ROM)', '256 GB UFS 4.0 siêu tốc'],
            'ram' => ['RAM', '12 GB LPDDR5X (Nâng cấp vượt bậc)'],
            'camera-sau' => ['Camera sau', 'Kép 50MP chính (OIS) + 12MP siêu rộng'],
            'camera-truoc' => ['Camera trước', '10MP sắc nét'],
            'pin-sac' => ['Pin & Sạc', '4.000 mAh (Tản nhiệt buồng hơi Vapor Chamber), Sạc nhanh 25W'],
            'chat-lieu' => ['Chất liệu', 'Khung nhôm Armor Aluminum, Bản lề FlexHinge thu gọn'],
            'cong-ket-noi' => ['Cổng kết nối', 'USB-C 3.2'],
            'khang-nuoc-bui' => ['Kháng nước & bụi', 'IP48'],
            'trong-luong-kich-thuoc' => ['Trọng lượng & Kích thước', '187 g | Gập: 85.1 x 71.9 x 14.9 mm'],
        ],
        'content' => <<<HTML
<div class="ktd-article-intro">
    <p class="ktd-lead-text">
        <strong>Samsung Galaxy Z Flip6 256GB</strong> là phụ kiện công nghệ thời thượng gập gọn như hộp phấn trang điểm. Lần đầu tiên được nâng cấp camera chính lên <strong>50MP</strong>, RAM <strong>12GB</strong> chuẩn flagship và buồng hơi tản nhiệt <strong>Vapor Chamber</strong>, đây là chiếc điện thoại gập vỏ sò mạnh mẽ và quyến rũ nhất.
    </p>
</div>

<h2>1. Thiết Kế Gập Nhỏ Gọn Đầy Mê Hoặc</h2>
<p>
    Z Flip6 thu hút mọi ánh nhìn với các tông màu thanh lịch. Khi gập lại, máy nằm lọt thỏm trong túi áo. Màn hình ngoài FlexWindow 3.4 inch cho phép trả lời tin nhắn nhanh bằng gợi ý AI thông minh mà không cần mở máy.
</p>

<h2>2. Nâng Cấp Lớn: Camera 50MP & Chế Độ FlexCam Rảnh Tay</h2>
<p>
    Camera chính 50MP kết hợp tính năng Auto Zoom tự động nhận diện chủ thể và căn chỉnh khung hình khi bạn đặt máy trên bàn để tự quay video TikTok hay chụp ảnh nhóm rảnh tay.
</p>

<div class="ktd-highlight-box">
    <strong>Buồng Hơi Tản Nhiệt Lần Đầu Xuất Hiện:</strong> Giúp Z Flip6 duy trì nhiệt độ mát mẻ và pin 4.000 mAh bền bỉ hơn 20% so với thế hệ tiền nhiệm.
</div>

<h2>3. RAM 12GB & Snapdragon 8 Gen 3 Bứt Phá</h2>
<p>
    Nâng cấp RAM lên 12GB giúp máy chạy đa nhiệm mượt mà, sẵn sàng đáp ứng mọi tính năng Galaxy AI tiên tiến nhất hiện nay.
</p>

<div class="ktd-article-conclusion">
    <h3>Sở Hữu Galaxy Z Flip6 Tại KTD Store</h3>
    <p>
        Mua Galaxy Z Flip6 chính hãng tại <strong>KTD Store</strong> để nhận ngay ưu đãi tặng củ sạc nhanh và hỗ trợ thu cũ lên đời trợ giá tốt nhất.
    </p>
</div>
HTML
    ],

    // -------------------------------------------------------------------------
    // 17. Samsung Galaxy A55 5G (ID 149)
    // -------------------------------------------------------------------------
    149 => [
        'name' => 'Samsung Galaxy A55 5G',
        'specs' => [
            'man-hinh' => ['Màn hình', '6.6 inch, Super AMOLED, FHD+, 120Hz, 1.000 nits, Vision Booster'],
            'he-dieu-hanh' => ['Hệ điều hành', 'Android 14 (One UI 6.1, Samsung Knox Vault)'],
            'chip-xu-ly-cpu' => ['Chip xử lý (CPU)', 'Exynos 1480 8 nhân (Đồ họa AMD Xclipse 530)'],
            'bo-nho-trong-rom' => ['Bộ nhớ trong (ROM)', '128 GB (Hỗ trợ thẻ nhớ MicroSD 1TB)'],
            'ram' => ['RAM', '8 GB (Hỗ trợ RAM Plus)'],
            'camera-sau' => ['Camera sau', 'Chính 50MP (OIS) + Siêu rộng 12MP + Macro 5MP'],
            'camera-truoc' => ['Camera trước', '32MP chụp chân dung sắc nét'],
            'pin-sac' => ['Pin & Sạc', '5.000 mAh, Sạc nhanh 25W'],
            'chat-lieu' => ['Chất liệu', 'Khung kim loại viền phẳng phay xước, 2 mặt kính Gorilla Glass Victus+'],
            'cong-ket-noi' => ['Cổng kết nối', 'USB-C, 2 SIM (Nano-SIM hoặc eSIM)'],
            'khang-nuoc-bui' => ['Kháng nước & bụi', 'IP67 (Kháng bụi và ngâm nước độ sâu 1 mét)'],
            'trong-luong-kich-thuoc' => ['Trọng lượng & Kích thước', '213 g | 161.1 x 77.4 x 8.2 mm'],
        ],
        'content' => <<<HTML
<div class="ktd-article-intro">
    <p class="ktd-lead-text">
        <strong>Samsung Galaxy A55 5G 128GB</strong> là chiếc điện thoại tầm trung quốc dân sở hữu thiết kế khung kim loại cao cấp như dòng S, chip <strong>Exynos 1480</strong> hợp tác cùng AMD, chuẩn kháng nước <strong>IP67</strong> và hệ thống bảo mật <strong>Samsung Knox Vault</strong> chuẩn quân đội.
    </p>
</div>

<h2>1. Khung Kim Loại Cao Cấp & Thiết Kế Key Island Độc Đáo</h2>
<p>
    Lần đầu tiên trên dòng Galaxy A, khung viền kim loại phay xước sang trọng xuất hiện, nâng tầm độ cứng cáp và cảm giác cao cấp khi cầm trên tay. Cụm phím tăng giảm âm lượng và nút nguồn được đặt nổi theo thiết kế Key Island tiện dụng.
</p>

<h2>2. Màn Hình Super AMOLED 120Hz & Đồ Họa Hợp Tác AMD</h2>
<p>
    Màn hình Super AMOLED 6.6 inch màu sắc tươi tắn, tần số quét 120Hz vuốt mượt mà. Chip xử lý Exynos 1480 tích hợp GPU kiến trúc AMD RDNA giúp chiến mượt mà các tựa game Liên Quân, Free Fire và PUBG Mobile.
</p>

<div class="ktd-highlight-box">
    <strong>Bảo Mật Samsung Knox Vault:</strong> Phần cứng bảo mật cách ly chống lại mọi nguy cơ xâm nhập mã độc và đánh cắp mã PIN, mật khẩu ngân hàng.
</div>

<h2>3. Camera 50MP Chống Rung Quang Học OIS & Pin 5.000 mAh</h2>
<p>
    Camera chính 50MP chụp đêm sắc nét, hỗ trợ chống rung OIS và VDIS giữ video luôn ổn định. Viên pin 5.000 mAh thoải mái sử dụng tới 2 ngày liền.
</p>

<div class="ktd-article-conclusion">
    <h3>Mua Galaxy A55 5G Tại KTD Store</h3>
    <p>
        Sở hữu Galaxy A55 5G chính hãng giá tốt nhất phân khúc tại <strong>KTD Store</strong> với chính sách bảo hành 12 tháng và quà tặng hấp dẫn.
    </p>
</div>
HTML
    ],

    // -------------------------------------------------------------------------
    // 18. Samsung Galaxy A35 5G (ID 150)
    // -------------------------------------------------------------------------
    150 => [
        'name' => 'Samsung Galaxy A35 5G',
        'specs' => [
            'man-hinh' => ['Màn hình', '6.6 inch, Super AMOLED, 120Hz, Infinity-O, FHD+, 1.000 nits'],
            'he-dieu-hanh' => ['Hệ điều hành', 'Android 14 (One UI 6.1, 4 năm cập nhật OS)'],
            'chip-xu-ly-cpu' => ['Chip xử lý (CPU)', 'Exynos 1380 8 nhân (Tiến trình 5nm)'],
            'bo-nho-trong-rom' => ['Bộ nhớ trong (ROM)', '128 GB (Hỗ trợ thẻ nhớ MicroSD)'],
            'ram' => ['RAM', '8 GB (Hỗ trợ RAM Plus)'],
            'camera-sau' => ['Camera sau', 'Chính 50MP (OIS) + Siêu rộng 8MP + Macro 5MP'],
            'camera-truoc' => ['Camera trước', '13MP'],
            'pin-sac' => ['Pin & Sạc', '5.000 mAh, Sạc nhanh 25W'],
            'chat-lieu' => ['Chất liệu', 'Mặt lưng kính cao cấp, Thiết kế Key Island năng động'],
            'cong-ket-noi' => ['Cổng kết nối', 'USB-C 2.0'],
            'khang-nuoc-bui' => ['Kháng nước & bụi', 'IP67 (Kháng bụi và nước ở độ sâu 1 mét)'],
            'trong-luong-kich-thuoc' => ['Trọng lượng & Kích thước', '209 g | 161.7 x 78.0 x 8.2 mm'],
        ],
        'content' => <<<HTML
<div class="ktd-article-intro">
    <p class="ktd-lead-text">
        <strong>Samsung Galaxy A35 5G 128GB</strong> nổi bật trong phân khúc tầm trung với màn hình đục lỗ <strong>Infinity-O Super AMOLED 120Hz</strong>, mặt lưng kính sang trọng, chuẩn kháng nước <strong>IP67</strong> và camera chính 50MP có chống rung quang học OIS.
    </p>
</div>

<h2>1. Nâng Cấp Màn Hình Đục Lỗ Infinity-O Hiện Đại</h2>
<p>
    Tạm biệt màn hình giọt nước cũ, Galaxy A35 sở hữu màn hình Infinity-O tràn viền 6.6 inch hiện đại, tấm nền Super AMOLED 120Hz tái hiện màu sắc sống động và mượt mà cho trải nghiệm xem phim và lướt web.
</p>

<h2>2. Thiết Kế Lưng Kính Sang Trọng & Kháng Nước IP67</h2>
<p>
    Mặt lưng kính phủ bóng thời thượng mang lại vẻ đẹp thanh lịch. Tiêu chuẩn kháng nước IP67 giúp bạn an tâm đi dưới những cơn mưa rào bất chợt mà không lo hỏng hóc.
</p>

<div class="ktd-highlight-box">
    <strong>Cam Kết Nâng Cấp Lâu Dài:</strong> Samsung cam kết hỗ trợ tới 4 phiên bản cập nhật hệ điều hành Android và 5 năm vá lỗi bảo mật, đảm bảo máy luôn bền bỉ theo thời gian.
</div>

<h2>3. Hiệu Năng Ổn Định & Pin 5.000 mAh</h2>
<p>
    Trang bị chip Exynos 1380 8 nhân cùng pin 5.000 mAh và sạc nhanh 25W, máy đáp ứng mượt mà mọi nhu cầu giải trí và liên lạc suốt ngày dài.
</p>

<div class="ktd-article-conclusion">
    <h3>Đặt Mua Galaxy A35 5G Tại KTD Store</h3>
    <p>
        Mua Galaxy A35 5G chính hãng tại <strong>KTD Store</strong> để nhận mức giá ưu đãi cùng chính sách trả góp 0% tiện lợi.
    </p>
</div>
HTML
    ],

    // -------------------------------------------------------------------------
    // 19. OPPO Find X9 Pro (ID 151)
    // -------------------------------------------------------------------------
    151 => [
        'name' => 'OPPO Find X9 Pro',
        'specs' => [
            'man-hinh' => ['Màn hình', '6.82 inch, AMOLED LTPO 1-120Hz, 1.5K/2K, 4.500 nits, Dolby Vision'],
            'he-dieu-hanh' => ['Hệ điều hành', 'ColorOS 16 (Android 16, Trợ lý AI AndesGPT tiếng Việt)'],
            'chip-xu-ly-cpu' => ['Chip xử lý (CPU)', 'MediaTek Dimensity 9500 / Snapdragon 8 Elite (3nm)'],
            'bo-nho-trong-rom' => ['Bộ nhớ trong (ROM)', '512 GB UFS 4.1 siêu tốc'],
            'ram' => ['RAM', '16 GB LPDDR5X'],
            'camera-sau' => ['Camera sau', 'Cụm 3 camera Hasselblad 50MP (Cảm biến 1-inch Sony LYT-900, Tele kép tiềm vọng)'],
            'camera-truoc' => ['Camera trước', '32MP Sony IMX709 với cảm biến RGBW'],
            'pin-sac' => ['Pin & Sạc', '5.500 mAh pin Silicon-Carbon, SuperVOOC 100W, Không dây 50W'],
            'chat-lieu' => ['Chất liệu', 'Khung hợp kim hàng không, Kính nhám Ag / Gốm vi tinh thể cao cấp'],
            'cong-ket-noi' => ['Cổng kết nối', 'USB-C 3.2 Gen 2, Cần gạt Alert Slider'],
            'khang-nuoc-bui' => ['Kháng nước & bụi', 'IP68 & IP69 (Chịu áp lực tia nước nóng cường độ cao)'],
            'trong-luong-kich-thuoc' => ['Trọng lượng & Kích thước', '221 g | 164.8 x 76.5 x 9.1 mm'],
        ],
        'content' => <<<HTML
<div class="ktd-article-intro">
    <p class="ktd-lead-text">
        <strong>OPPO Find X9 Pro 512GB</strong> đại diện cho đỉnh cao nhiếp ảnh di động với sự hợp tác huyền thoại cùng <strong>Hasselblad</strong>. Cảm biến 1-inch <strong>Sony LYT-900</strong>, màn hình 2K siêu sáng <strong>4.500 nits</strong> và công nghệ sạc thần tốc <strong>SuperVOOC 100W</strong> định hình vị thế siêu flagship nhiếp ảnh.
    </p>
</div>

<h2>1. Thiết Kế Đĩa Vệ Tinh Đẳng Cấp & Chuẩn Kháng Nước Kép IP68/IP69</h2>
<p>
    Cụm camera đĩa vệ tinh được hoàn thiện tỉ mỉ với viền thép chạm khắc tinh xảo. Máy đạt cả hai tiêu chuẩn kháng nước khắc nghiệt <strong>IP68</strong> và <strong>IP69</strong>, có khả năng chống chọi trước tia nước nóng áp lực cao.
</p>

<h2>2. Màn Hình AMOLED 2K LTPO 4.500 Nits Đỉnh Cao</h2>
<p>
    Tấm nền AMOLED LTPO thế hệ mới đạt độ sáng cực đại 4.500 nits, hiển thị màu sắc đạt độ chính xác chuẩn Hollywood kết hợp công nghệ bảo vệ mắt chống mỏi mắt ban đêm.
</p>

<div class="ktd-highlight-box">
    <strong>Nhiếp Ảnh Hasselblad Huyền Thoại:</strong> Tái tạo màu sắc tự nhiên, chụp chân dung quang học với bokeh xoay mềm mại và độ sâu trường ảnh chân thực như máy ảnh medium format.
</div>

<h2>3. Hiệu Năng Vượt Bậc Cùng Sạc Nhanh SuperVOOC 100W</h2>
<p>
    Sức mạnh phần cứng mạnh mẽ kết hợp pin công nghệ Silicon-Carbon 5.500 mAh và sạc 100W giúp bạn sạc đầy pin chỉ trong chưa đầy 25 phút.
</p>

<div class="ktd-article-conclusion">
    <h3>Sở Hữu Siêu Phẩm OPPO Find X9 Pro Tại KTD Store</h3>
    <p>
        Khám phá ngay OPPO Find X9 Pro chính hãng tại <strong>KTD Store</strong> với chính sách bảo hành VIP 1 đổi 1 và nhiều phần quà công nghệ giá trị.
    </p>
</div>
HTML
    ],

    // -------------------------------------------------------------------------
    // 20. OPPO Find X8 Pro (ID 152)
    // -------------------------------------------------------------------------
    152 => [
        'name' => 'OPPO Find X8 Pro',
        'specs' => [
            'man-hinh' => ['Màn hình', '6.78 inch, AMOLED cong nhẹ 4 cạnh, 1-120Hz, 4.500 nits, ProXDR'],
            'he-dieu-hanh' => ['Hệ điều hành', 'ColorOS 15 (Android 15, AI thông minh)'],
            'chip-xu-ly-cpu' => ['Chip xử lý (CPU)', 'MediaTek Dimensity 9400 (Tiến trình 3nm tiên tiến)'],
            'bo-nho-trong-rom' => ['Bộ nhớ trong (ROM)', '512 GB UFS 4.0 siêu tốc'],
            'ram' => ['RAM', '16 GB LPDDR5X'],
            'camera-sau' => ['Camera sau', 'Hệ thống 4 camera Hasselblad 50MP (Tele tiềm vọng kép 3x & 6x)'],
            'camera-truoc' => ['Camera trước', '32MP 4K selfie siêu nét'],
            'pin-sac' => ['Pin & Sạc', '5.910 mAh Silicon-Carbon, SuperVOOC 80W, Sạc không dây 50W'],
            'chat-lieu' => ['Chất liệu', 'Kính cường lực thế hệ mới, Phím chụp ảnh cảm ứng nhanh Quick Button'],
            'cong-ket-noi' => ['Cổng kết nối', 'USB-C 3.1'],
            'khang-nuoc-bui' => ['Kháng nước & bụi', 'IP68 & IP69'],
            'trong-luong-kich-thuoc' => ['Trọng lượng & Kích thước', '215 g | 162.27 x 76.67 x 8.24 mm'],
        ],
        'content' => <<<HTML
<div class="ktd-article-intro">
    <p class="ktd-lead-text">
        <strong>OPPO Find X8 Pro 512GB</strong> là đột phá nhiếp ảnh với <strong>hệ thống camera tiềm vọng kép</strong> độc nhất vô nhị. Sở hữu viên pin khủng <strong>5.910 mAh</strong> công nghệ Silicon-Carbon trong thân máy mỏng chỉ 8.24mm cùng chip <strong>Dimensity 9400</strong> 3nm, đây là chiếc máy lý tưởng cho tín đồ xê dịch.
    </p>
</div>

<h2>1. Thiết Kế Cong Nhẹ 4 Cạnh & Phím Bấm Chụp Nhanh Quick Button</h2>
<p>
    Mặt kính cong nhẹ đều cả 4 cạnh tạo cảm giác vuốt chạm không viền tuyệt hảo. Phím bấm cảm ứng Quick Button cho phép mở nhanh máy ảnh và trượt zoom chỉ trong 0.4 giây.
</p>

<h2>2. Hệ Thống 4 Camera 50MP Tiềm Vọng Kép Độc Đáo</h2>
<p>
    Sở hữu đồng thời 2 ống kính tiềm vọng zoom quang 3x và 6x, Find X8 Pro xóa bỏ mọi giới hạn chụp ảnh chân dung cự ly xa với độ trong trẻo và chi tiết tuyệt đối.
</p>

<div class="ktd-highlight-box">
    <strong>Dung Lượng Pin 5.910 mAh Kỷ Lục:</strong> Nhờ mật độ năng lượng siêu cao của pin Silicon-Carbon, máy duy trì thời gian sáng màn hình on-screen lên tới hơn 10 tiếng liên tục.
</div>

<h2>3. Chip Dimensity 9400 & Bộ Công Cụ Trí Tuệ Nhân Tạo ColorOS 15</h2>
<p>
    Kiến trúc All Big Core trên tiến trình 3nm mang đến hiệu năng gaming siêu đẳng, tiết kiệm 40% điện năng và hỗ trợ các công cụ AI xóa bóng phản chiếu qua cửa kính kỳ diệu.
</p>

<div class="ktd-article-conclusion">
    <h3>Mua OPPO Find X8 Pro Tại KTD Store</h3>
    <p>
        Sở hữu ngay OPPO Find X8 Pro chính hãng tại <strong>KTD Store</strong> với chính sách thu cũ đổi mới trợ giá cao và hỗ trợ trả góp 0%.
    </p>
</div>
HTML
    ],

    // -------------------------------------------------------------------------
    // 21. OPPO Find X7 Ultra (ID 153)
    // -------------------------------------------------------------------------
    153 => [
        'name' => 'OPPO Find X7 Ultra',
        'specs' => [
            'man-hinh' => ['Màn hình', '6.82 inch, AMOLED LTPO 120Hz, QHD+, 4.500 nits, ProXDR'],
            'he-dieu-hanh' => ['Hệ điều hành', 'ColorOS 14 (Android 14)'],
            'chip-xu-ly-cpu' => ['Chip xử lý (CPU)', 'Snapdragon 8 Gen 3 8 nhân (4nm)'],
            'bo-nho-trong-rom' => ['Bộ nhớ trong (ROM)', '256 GB UFS 4.0 siêu tốc'],
            'ram' => ['RAM', '16 GB LPDDR5X'],
            'camera-sau' => ['Camera sau', 'Cụm 4 camera 50MP Hasselblad, 2 camera tele tiềm vọng (3x & 6x)'],
            'camera-truoc' => ['Camera trước', '32MP Sony cảm biến RGBW'],
            'pin-sac' => ['Pin & Sạc', '5.000 mAh, SuperVOOC 100W, Sạc không dây AirVOOC 50W'],
            'chat-lieu' => ['Chất liệu', 'Lưng kết hợp Da thuần chay & Thủy tinh hữu cơ cao cấp'],
            'cong-ket-noi' => ['Cổng kết nối', 'USB-C 3.2, Cần gạt Alert Slider bảo mật'],
            'khang-nuoc-bui' => ['Kháng nước & bụi', 'IP68 (Độ sâu 1.5 mét trong thời gian tối đa 30 phút)'],
            'trong-luong-kich-thuoc' => ['Trọng lượng & Kích thước', '221 g | 164.3 x 76.2 x 9.5 mm'],
        ],
        'content' => <<<HTML
<div class="ktd-article-intro">
    <p class="ktd-lead-text">
        <strong>OPPO Find X7 Ultra 256GB</strong> là chiếc điện thoại đầu tiên trên thế giới trang bị cảm biến <strong>Sony LYT-900 1-inch</strong> thế hệ thứ hai cùng hệ thống camera tele tiềm vọng kép. Thiết kế lưng da phối kính hai tông màu mang đậm dấu ấn phong cách máy ảnh cổ điển sang trọng.
    </p>
</div>

<h2>1. Thiết Kế Da Cao Cấp Phối Kính Cổ Điển</h2>
<p>
    Sự kết hợp giữa chất liệu da thuần chay cao cấp và mặt kính nhám phía trên tạo nên cảm giác sang trọng độc nhất, không bám mồ hôi và mang lại phong thái của một nhiếp ảnh gia chuyên nghiệp.
</p>

<h2>2. 4 Camera 50MP Toàn Năng – Thước Đo Mới Của Nhiếp Ảnh</h2>
<p>
    Tất cả 4 camera sau đều đạt độ phân giải 50MP. Cảm biến 1-inch ghi lại dải tương phản động rộng lớn, cho ảnh chụp đêm trong vắt và hiệu ứng xóa phông quang học tự nhiên không cần can thiệp phần mềm.
</p>

<div class="ktd-highlight-box">
    <strong>Cần Gạt VIP Mode:</strong> Chuyển nhanh sang chế độ bảo mật tuyệt đối, vô hiệu hóa micro, máy ảnh và định vị GPS tức thì để bảo vệ quyền riêng tư.
</div>

<h2>3. Hiệu Năng Snapdragon 8 Gen 3 & Sạc 100W</h2>
<p>
    Trang bị chipset đầu bảng Snapdragon 8 Gen 3, kết hợp sạc nhanh SuperVOOC 100W sạc đầy viên pin 5.000 mAh chỉ trong 26 phút ngắn ngủi.
</p>

<div class="ktd-article-conclusion">
    <h3>Mua OPPO Find X7 Ultra Tại KTD Store</h3>
    <p>
        Trải nghiệm Find X7 Ultra với chất ảnh Hasselblad mê hoặc tại <strong>KTD Store</strong>, bảo hành chu đáo và nhiều quà tặng hấp dẫn.
    </p>
</div>
HTML
    ],

    // -------------------------------------------------------------------------
    // 22. OPPO Find N3 5G (ID 154)
    // -------------------------------------------------------------------------
    154 => [
        'name' => 'OPPO Find N3 5G',
        'specs' => [
            'man-hinh' => ['Màn hình', 'Chính gập 7.82 inch OLED (120Hz) + Phụ 6.31 inch (120Hz, 2.800 nits)'],
            'he-dieu-hanh' => ['Hệ điều hành', 'ColorOS 13.2 (Đa nhiệm không giới hạn Boundless View)'],
            'chip-xu-ly-cpu' => ['Chip xử lý (CPU)', 'Snapdragon 8 Gen 2 8 nhân (Tiến trình 4nm)'],
            'bo-nho-trong-rom' => ['Bộ nhớ trong (ROM)', '512 GB UFS 4.0 siêu tốc'],
            'ram' => ['RAM', '16 GB LPDDR5X'],
            'camera-sau' => ['Camera sau', 'Camera Hasselblad 48MP (Sony LYT-T808) + 48MP siêu rộng + 64MP tiềm vọng (3x)'],
            'camera-truoc' => ['Camera trước', 'Ngoài 32MP + Trong 20MP'],
            'pin-sac' => ['Pin & Sạc', '4.805 mAh, SuperVOOC 67W siêu tốc'],
            'chat-lieu' => ['Chất liệu', 'Bản lề giọt nước hợp kim Zirconium, Kính cường lực Ultra Thin Glass'],
            'cong-ket-noi' => ['Cổng kết nối', 'USB-C, Cần gạt rung Alert Slider'],
            'khang-nuoc-bui' => ['Kháng nước & bụi', 'IPX4 (Chống tia nước bắn)'],
            'trong-luong-kich-thuoc' => ['Trọng lượng & Kích thước', '239 g | Siêu mỏng chỉ 5.8 mm khi mở ra'],
        ],
        'content' => <<<HTML
<div class="ktd-article-intro">
    <p class="ktd-lead-text">
        <strong>OPPO Find N3 5G 512GB</strong> là tuyệt tác smartphone màn hình gập mỏng nhẹ bậc nhất với nếp gấp gần như vô hình. Màn hình ngoài tỷ lệ vàng <strong>20:9</strong> dùng như điện thoại thanh bình thường, kết hợp màn hình trong <strong>7.82 inch</strong> và camera Hasselblad 48MP đưa trải nghiệm gập lên tầm cao mới.
    </p>
</div>

<h2>1. Bản Lề Giọt Nước Siêu Nhẹ & Nếp Gấp Vô Hình</h2>
<p>
    Bản lề hợp kim Zirconium hàng không vũ trụ giúp giảm tới 30% linh kiện so với thế hệ trước, đạt độ bền hơn 1.000.000 lần gập mở được chứng nhận bởi TÜV Rheinland.
</p>

<h2>2. Đa Nhiệm Không Giới Hạn Boundless View</h2>
<p>
    Tính năng Boundless View cho phép bạn chạy song song 3 ứng dụng cỡ lớn toàn màn hình trên không gian 7.82 inch, chuyển đổi qua lại nhanh chóng như trên màn hình máy tính để bàn.
</p>

<div class="ktd-highlight-box">
    <strong>Màn Hình Ngoài Tỷ Lệ Chuẩn 20:9:</strong> Không còn bị hẹp ngang như các mẫu gập khác, màn hình phụ 6.31 inch cho cảm giác gõ phím và xem tin tức tự nhiên như smartphone thông thường.
</div>

<h2>3. Hệ Thống Camera Hasselblad Gập Đỉnh Nhất</h2>
<p>
    Camera chính công nghệ pixel xếp chồng Sony LYT-T808 cùng ống kính tiềm vọng 64MP 3x mang lại chất lượng ảnh chụp tương đương các flagship dạng thanh hàng đầu thế giới.
</p>

<div class="ktd-article-conclusion">
    <h3>Sở Hữu OPPO Find N3 Tại KTD Store</h3>
    <p>
        Mua OPPO Find N3 chính hãng tại <strong>KTD Store</strong> với chính sách bảo hành VIP toàn diện và hỗ trợ trả góp 0% nhanh chóng.
    </p>
</div>
HTML
    ],

    // -------------------------------------------------------------------------
    // 23. OPPO Reno12 Pro 5G (ID 155)
    // -------------------------------------------------------------------------
    155 => [
        'name' => 'OPPO Reno12 Pro 5G',
        'specs' => [
            'man-hinh' => ['Màn hình', '6.7 inch, 3D AMOLED cong 4 cạnh, 120Hz, 1.200 nits, Gorilla Glass Victus 2'],
            'he-dieu-hanh' => ['Hệ điều hành', 'ColorOS 14.1 (Tính năng AI Eraser 2.0, AI Studio chân dung)'],
            'chip-xu-ly-cpu' => ['Chip xử lý (CPU)', 'MediaTek Dimensity 7300-Energy (4nm)'],
            'bo-nho-trong-rom' => ['Bộ nhớ trong (ROM)', '512 GB UFS 3.1'],
            'ram' => ['RAM', '12 GB (+12GB RAM ảo mở rộng)'],
            'camera-sau' => ['Camera sau', 'Chính 50MP Sony LYT-600 (OIS) + Tele 50MP chân dung (2x) + Siêu rộng 8MP'],
            'camera-truoc' => ['Camera trước', '50MP tự động lấy nét mắt (Eye AF)'],
            'pin-sac' => ['Pin & Sạc', '5.000 mAh, SuperVOOC 80W (Sạc đầy trong 46 phút)'],
            'chat-lieu' => ['Chất liệu', 'Khung hợp kim độ bền cao đệm bọt biển chống rơi vỡ, Lưng bạc lượn sóng'],
            'cong-ket-noi' => ['Cổng kết nối', 'USB-C, Cảm biến hồng ngoại điều khiển từ xa'],
            'khang-nuoc-bui' => ['Kháng nước & bụi', 'IP65, Cảm ứng chạm khi tay ướt Splash Touch'],
            'trong-luong-kich-thuoc' => ['Trọng lượng & Kích thước', '180 g | 161.5 x 74.8 x 7.4 mm'],
        ],
        'content' => <<<HTML
<div class="ktd-article-intro">
    <p class="ktd-lead-text">
        <strong>OPPO Reno12 Pro 5G 512GB</strong> được mệnh danh là <em>Chuyên Gia Chân Dung AI</em> với bộ đôi camera trước sau cùng đạt độ phân giải khủng <strong>50MP</strong>. Thiết kế dòng chảy tương lai siêu mỏng <strong>7.4mm</strong> cùng kết cấu khung bảo vệ chống va đập toàn diện đem đến chiếc smartphone bền đẹp xuất chúng.
    </p>
</div>

<h2>1. Thiết Kế Bạc Lượn Sóng 3D & Độ Bền Vượt Trội</h2>
<p>
    Mặt lưng ứng dụng kỹ thuật tạo hiệu ứng thị giác sóng nước bồng bềnh. Cấu trúc đệm bọt biển bảo vệ linh kiện bên trong cùng kính Gorilla Glass Victus 2 giúp máy chịu được va đập khi rơi từ độ cao 1.5 mét.
</p>

<h2>2. Chuyên Gia Chân Dung AI: Camera Kép 50MP</h2>
<p>
    Ống kính tele chân dung 50MP tiêu cự vàng 47mm mang lại những bức ảnh chân dung xóa phông xóa mù mịt với chi tiết tóc và làn da tự nhiên. Camera selfie 50MP tự động bám nét mắt cho ảnh chụp tự sướng rạng ngời.
</p>

<div class="ktd-highlight-box">
    <strong>Công Nghệ AI Eraser 2.0:</strong> Khoanh vùng xóa người lạ xuất hiện trong khung hình du lịch chỉ trong 1 giây mà hậu cảnh vẫn được tái tạo liền mạch hoàn hảo.
</div>

<h2>3. Sạc Nhanh 80W & Cảm Ứng Tay Ướt Splash Touch</h2>
<p>
    Pin 5.000 mAh sạc đầy thần tốc trong 46 phút. Công nghệ Splash Touch cho phép bạn thao tác màn hình chính xác ngay cả khi tay ướt nước hoặc đang đi dưới trời mưa.
</p>

<div class="ktd-article-conclusion">
    <h3>Mua OPPO Reno12 Pro Tại KTD Store</h3>
    <p>
        Mua ngay OPPO Reno12 Pro 5G chính hãng tại <strong>KTD Store</strong> để nhận mức giá tốt nhất kèm quà tặng hấp dẫn.
    </p>
</div>
HTML
    ],

    // -------------------------------------------------------------------------
    // 24. OPPO Reno12 5G (ID 156)
    // -------------------------------------------------------------------------
    156 => [
        'name' => 'OPPO Reno12 5G',
        'specs' => [
            'man-hinh' => ['Màn hình', '6.7 inch, AMOLED 120Hz cong nhẹ, HDR10+, 1.200 nits, Kính Gorilla Glass 7i'],
            'he-dieu-hanh' => ['Hệ điều hành', 'ColorOS 14.1 (Bộ công cụ OPPO AI thông minh)'],
            'chip-xu-ly-cpu' => ['Chip xử lý (CPU)', 'MediaTek Dimensity 7300-Energy 8 nhân (Tiến trình 4nm)'],
            'bo-nho-trong-rom' => ['Bộ nhớ trong (ROM)', '256 GB UFS 3.1'],
            'ram' => ['RAM', '12 GB (+12GB RAM ảo)'],
            'camera-sau' => ['Camera sau', 'Chính 50MP Sony (OIS) + Siêu rộng 8MP + Macro 2MP'],
            'camera-truoc' => ['Camera trước', '32MP bắt nét nhanh, làm đẹp chân dung AI'],
            'pin-sac' => ['Pin & Sạc', '5.000 mAh, Sạc siêu nhanh SuperVOOC 80W'],
            'chat-lieu' => ['Chất liệu', 'Thiết kế dòng chảy tương lai Futuristic Fluid, Kính Gorilla Glass 7i'],
            'cong-ket-noi' => ['Cổng kết nối', 'USB-C 2.0'],
            'khang-nuoc-bui' => ['Kháng nước & bụi', 'IP65, Cảm ứng khi tay ướt Splash Touch'],
            'trong-luong-kich-thuoc' => ['Trọng lượng & Kích thước', '177 g | 161.4 x 74.1 x 7.6 mm'],
        ],
        'content' => <<<HTML
<div class="ktd-article-intro">
    <p class="ktd-lead-text">
        <strong>OPPO Reno12 5G 256GB</strong> chinh phục giới trẻ bởi vẻ đẹp bóng bẩy phong cách <em>Futuristic Fluid</em>, màn hình cong nhẹ <strong>120Hz</strong>, bộ tính năng <strong>OPPO AI</strong> thông minh và khả năng sạc siêu tốc <strong>SuperVOOC 80W</strong> chỉ trong chớp mắt.
    </p>
</div>

<h2>1. Thiết Kế Mỏng Nhẹ Thời Thượng 177g</h2>
<p>
    Thân máy chỉ mỏng 7.6mm và nhẹ 177g cho cảm giác cầm nắm vô cùng thanh thoát. Hiệu ứng ánh sáng dòng chảy phía sau tạo nên phong cách trẻ trung, nổi bật giữa đám đông.
</p>

<h2>2. Màn Hình AMOLED 120Hz Vuốt Chạm Mượt Mà</h2>
<p>
    Tấm nền AMOLED 6.7 inch hiển thị 1 tỷ màu rực rỡ, hỗ trợ tần số quét 120Hz mang đến những phút giây giải trí, xem TikTok và phim ảnh sống động, đã mắt.
</p>

<div class="ktd-highlight-box">
    <strong>Tính Năng AI Tách Nền Một Chạm:</strong> Chạm giữ vào chủ thể trong ảnh để cắt dán sticker đáng yêu chia sẻ trực tiếp lên mạng xã hội trong tích tắc.
</div>

<h2>3. Camera 50MP OIS & Pin Bền 5.000 mAh Sạc 80W</h2>
<p>
    Camera chính 50MP chống rung quang học OIS giữ cho khung hình luôn sắc nét, viên pin 5.000 mAh nạp đầy năng lượng siêu tốc với củ sạc 80W đi kèm.
</p>

<div class="ktd-article-conclusion">
    <h3>Mua Ngay OPPO Reno12 Tại KTD Store</h3>
    <p>
        Sở hữu OPPO Reno12 5G chính hãng tại <strong>KTD Store</strong> với chương trình trả góp 0% duyệt hồ sơ nhanh chóng và chế độ bảo hành 12 tháng uy tín.
    </p>
</div>
HTML
    ],
];

$success_count = 0;
$error_count = 0;

foreach ( $products_data as $product_id => $data ) {
    echo "Processing [ID: {$product_id}] - {$data['name']}... ";
    
    $product = wc_get_product( $product_id );
    if ( ! $product ) {
        echo "FAILED (Product not found in WooCommerce)\n";
        $error_count++;
        continue;
    }

    // 1. Update Post Content (Rich Article)
    $post_data = [
        'ID' => $product_id,
        'post_content' => $data['content'],
    ];
    $updated_post = wp_update_post( $post_data, true );
    if ( is_wp_error( $updated_post ) ) {
        echo "ERROR updating content: " . $updated_post->get_error_message() . "\n";
        $error_count++;
        continue;
    }

    // 2. Build Attributes Array (Preserving existing variation attributes pa_dung-luong and pa_mau-sac)
    $existing_attributes = get_post_meta( $product_id, '_product_attributes', true );
    if ( ! is_array( $existing_attributes ) ) {
        $existing_attributes = [];
    }

    $new_attributes = [];
    
    // Keep variation taxonomies first
    if ( isset( $existing_attributes['pa_dung-luong'] ) ) {
        $new_attributes['pa_dung-luong'] = $existing_attributes['pa_dung-luong'];
        $new_attributes['pa_dung-luong']['position'] = 0;
    }
    if ( isset( $existing_attributes['pa_mau-sac'] ) ) {
        $new_attributes['pa_mau-sac'] = $existing_attributes['pa_mau-sac'];
        $new_attributes['pa_mau-sac']['position'] = 1;
    }

    // Add the 12 hardware specifications
    $pos = 2;
    foreach ( $data['specs'] as $slug => $spec ) {
        $new_attributes[ $slug ] = [
            'name'         => $spec[0],
            'value'        => $spec[1],
            'position'     => $pos++,
            'is_visible'   => 1,
            'is_variation' => 0,
            'is_taxonomy'  => 0,
        ];
    }

    update_post_meta( $product_id, '_product_attributes', $new_attributes );

    // Clear WooCommerce transients and caches for this product
    wc_delete_product_transients( $product_id );
    clean_post_cache( $product_id );

    echo "OK (Content: " . strlen( $data['content'] ) . " chars | Attrs: " . count( $new_attributes ) . ")\n";
    $success_count++;
}

echo "\n============================================================\n";
echo " Synchronization Summary: {$success_count} succeeded, {$error_count} failed.\n";
echo "============================================================\n";
