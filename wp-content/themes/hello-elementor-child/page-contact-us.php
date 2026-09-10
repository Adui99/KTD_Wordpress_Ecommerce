<?php
/**
 * Template Name: KTD Contact Page
 * Description: Clean, minimalist Contact page with 2 columns (info + form), embedded map, and FAQ section.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
?>

<main id="content" class="site-main ktd-contact-page">
	<!-- Hero Section -->
	<section class="ktd-contact-hero">
		<div class="ktd-page-container">
			<span class="ktd-section-badge">HỖ TRỢ TẬN TÂM • KẾT NỐI NHANH CHÓNG</span>
			<h1 class="ktd-contact-hero-title">Liên Hệ Với KTD Store</h1>
			<p class="ktd-contact-hero-desc">
				Đội ngũ tư vấn viên và kỹ thuật viên chuyên sâu luôn sẵn sàng hỗ trợ bạn lựa chọn sản phẩm phù hợp nhất hoặc giải đáp mọi yêu cầu bảo hành, dịch vụ.
			</p>
		</div>
	</section>

	<!-- Main 2-Column Section -->
	<section class="ktd-contact-main">
		<div class="ktd-page-container">
			<div class="ktd-contact-grid">
				<!-- Left Column: Showroom Info & Map -->
				<div class="ktd-contact-info-col">
					<div class="ktd-info-card">
						<h2 class="ktd-info-title">Hệ Thống Showroom & Kênh Hỗ Trợ</h2>
						<p class="ktd-info-desc">Quý khách có thể ghé thăm trực tiếp để trải nghiệm sản phẩm thực tế hoặc liên hệ qua các đường dây nóng chính thức.</p>

						<div class="ktd-info-list">
							<!-- Item 1: Address -->
							<div class="ktd-info-item">
								<div class="ktd-info-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
										<circle cx="12" cy="10" r="3"/>
									</svg>
								</div>
								<div class="ktd-info-text">
									<h4>Địa Chỉ Showroom Trải Nghiệm</h4>
									<p>280 An Dương Vương, Phường 4, Quận 5, TP. Hồ Chí Minh</p>
								</div>
							</div>

							<!-- Item 2: Hotline -->
							<div class="ktd-info-item">
								<div class="ktd-info-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
									</svg>
								</div>
								<div class="ktd-info-text">
									<h4>Tổng Đài Bán Hàng & Hỗ Trợ Kỹ Thuật</h4>
									<p><strong>Hotline: 1900 8888</strong> (Miễn phí cuộc gọi, 8:00 - 21:30)</p>
								</div>
							</div>

							<!-- Item 3: Email -->
							<div class="ktd-info-item">
								<div class="ktd-info-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<rect width="20" height="16" x="2" y="4" rx="2"/>
										<path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
									</svg>
								</div>
								<div class="ktd-info-text">
									<h4>Email Chăm Sóc Khách Hàng</h4>
									<p>support@ktd-store.com • cskh@ktd-store.com</p>
								</div>
							</div>

							<!-- Item 4: Hours -->
							<div class="ktd-info-item">
								<div class="ktd-info-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<circle cx="12" cy="12" r="10"/>
										<polyline points="12 6 12 12 16 14"/>
									</svg>
								</div>
								<div class="ktd-info-text">
									<h4>Thời Gian Mở Cửa Phục Vụ</h4>
									<p>08:30 – 21:30 (Mở cửa tất cả các ngày trong tuần, kể cả ngày lễ)</p>
								</div>
							</div>
						</div>

						<!-- Map Embed Container -->
						<div class="ktd-contact-map-wrapper">
							<iframe
								title="Showroom KTD Store Map"
								src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.651034458882!2d106.67969847583803!3d10.761354659468923!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f1b7c3fd411%3A0xa0787e9140df9857!2zMjgwIEFuIETGsMahbmcgVsawxqFuZywgUGjGsOG7nW5nIDQsIFF14bqtbiA1LCBUaMOgbmggcGjhu5EgSOG7kyBDaMOtIE1pbmgsIFZp4buHdCBOYW0!5e0!3m2!1svi!2s!4v1700000000000!5m2!1svi!2s"
								width="100%"
								height="240"
								style="border:0;"
								allowfullscreen=""
								loading="lazy"
								referrerpolicy="no-referrer-when-downgrade">
							</iframe>
						</div>
					</div>
				</div>

				<!-- Right Column: Contact Form -->
				<div class="ktd-contact-form-col">
					<div class="ktd-form-card">
						<h3 class="ktd-form-title">Gửi Tin Nhắn / Yêu Cầu Tư Vấn</h3>
						<p class="ktd-form-desc">Hãy để lại thông tin, chuyên viên tư vấn của KTD Store sẽ liên hệ lại qua điện thoại trong vòng 15 phút.</p>

						<form id="ktdContactForm" class="ktd-contact-form" onsubmit="event.preventDefault(); document.getElementById('ktdFormAlert').style.display='block'; this.reset();">
							<div class="ktd-form-group">
								<label for="c_name">Họ và tên của bạn <span class="required">*</span></label>
								<input type="text" id="c_name" name="c_name" placeholder="Ví dụ: Nguyễn Văn An" required class="ktd-input">
							</div>

							<div class="ktd-form-row">
								<div class="ktd-form-group">
									<label for="c_phone">Số điện thoại liên hệ <span class="required">*</span></label>
									<input type="tel" id="c_phone" name="c_phone" placeholder="090x xxx xxx" required class="ktd-input">
								</div>
								<div class="ktd-form-group">
									<label for="c_email">Địa chỉ Email</label>
									<input type="email" id="c_email" name="c_email" placeholder="email@example.com" class="ktd-input">
								</div>
							</div>

							<div class="ktd-form-group">
								<label for="c_service">Dịch vụ bạn đang quan tâm</label>
								<select id="c_service" name="c_service" class="ktd-input ktd-select">
									<option value="buy_phone">Tư vấn mua điện thoại mới (iPhone, Samsung, OPPO)</option>
									<option value="trade_in">Thu cũ đổi mới - Lên đời trợ giá 30%</option>
									<option value="installment">Thủ tục mua hàng trả góp 0% lãi suất</option>
									<option value="warranty">Tra cứu bảo hành & Dịch vụ kỹ thuật</option>
									<option value="other">Ý kiến đóng góp & Khác</option>
								</select>
							</div>

							<div class="ktd-form-group">
								<label for="c_message">Nội dung ghi chú / Dòng máy quan tâm</label>
								<textarea id="c_message" name="c_message" rows="4" placeholder="Nhập tên thiết bị bạn muốn tư vấn hoặc câu hỏi cần giải đáp..." class="ktd-input ktd-textarea"></textarea>
							</div>

							<div class="ktd-form-submit">
								<button type="submit" class="ktd-btn-primary ktd-btn-submit">
									Gửi Thông Tin Ngay
								</button>
							</div>

							<!-- Success Message (Dismissible) -->
							<div id="ktdFormAlert" class="ktd-form-success" style="display:none;">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
									<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
								</svg>
								<span>Cảm ơn bạn! Yêu cầu của bạn đã được gửi thành công. Chuyên viên KTD Store sẽ liên hệ lại trong ít phút.</span>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- FAQ Section -->
	<section class="ktd-contact-faq">
		<div class="ktd-page-container">
			<div class="ktd-section-header">
				<h2 class="ktd-section-title">Câu Hỏi Thường Gặp (FAQ)</h2>
				<p class="ktd-section-subtitle">Giải đáp nhanh chóng những thắc mắc phổ biến của khách hàng khi mua sắm tại KTD Store.</p>
			</div>

			<div class="ktd-faq-grid">
				<div class="ktd-faq-card">
					<h3 class="ktd-faq-q">1. KTD Store có hỗ trợ giao hàng hỏa tốc trong ngày không?</h3>
					<p class="ktd-faq-a">
						Có. Đối với khách hàng tại khu vực TP.HCM, KTD Store cung cấp dịch vụ giao hàng hỏa tốc chỉ trong 2 giờ sau khi xác nhận đơn. Với các tỉnh thành khác, đơn hàng được gửi chuyển phát nhanh bảo hiểm 100% trong 24 – 48 giờ.
					</p>
				</div>

				<div class="ktd-faq-card">
					<h3 class="ktd-faq-q">2. Chính sách bảo hành vàng 1 đổi 1 (30 ngày) áp dụng như thế nào?</h3>
					<p class="ktd-faq-a">
						Tất cả sản phẩm flagship bán ra nếu có lỗi phần cứng từ nhà sản xuất trong 30 ngày đầu tiên sẽ được đổi ngay 1 máy mới nguyên hộp cùng loại tại showroom mà không cần chờ gửi hãng thẩm định lâu ngày.
					</p>
				</div>

				<div class="ktd-faq-card">
					<h3 class="ktd-faq-q">3. Tôi muốn mua trả góp 0% lãi suất thì cần những giấy tờ gì?</h3>
					<p class="ktd-faq-a">
						Bạn có thể trả góp 0% qua thẻ tín dụng của hơn 25 ngân hàng đối tác chỉ trong 3 phút không cần giấy tờ. Ngoài ra chúng tôi hỗ trợ trả góp duyệt tự động qua CCCD gắn chip (từ 18 tuổi) với tỷ lệ xét duyệt thành công đến 98%.
					</p>
				</div>

				<div class="ktd-faq-card">
					<h3 class="ktd-faq-q">4. Chương trình "Thu cũ đổi mới" lên đời máy có được trợ giá không?</h3>
					<p class="ktd-faq-a">
						Có. KTD Store nhận thu mua điện thoại cũ các hãng với quy trình kiểm tra 5 phút minh bạch và trợ giá cộng thêm lên tới 30% khi bạn quyết định lên đời flagship mới tại cửa hàng.
					</p>
				</div>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
