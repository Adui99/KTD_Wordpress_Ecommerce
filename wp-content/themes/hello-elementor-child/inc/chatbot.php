<?php
/**
 * Native AI Chatbot (EV Assistant)
 * Tích hợp Dify REST API bảo mật qua WordPress AJAX
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. AJAX Backend Handler: Kết nối an toàn đến Dify API (giấu kín API Key, timeout 25s, xử lý lỗi mượt mà)
 */
function ktd_ajax_dify_chat() {
	check_ajax_referer( 'ktd_chat_nonce', 'nonce' );

	$fallback_msg     = 'Dạ em chưa có thông tin về vấn đề này, anh/chị vui lòng liên hệ Hotline 1900 8888 để được hỗ trợ ạ.';
	$system_error_msg = 'Dạ hệ thống AI hiện đang xử lý nhiều lượt truy cập hoặc gián đoạn kết nối tạm thời. Anh/chị vui lòng đợi 15-20 giây và nhắn lại giúp em, hoặc liên hệ trực tiếp Hotline 1900 8888 để được hỗ trợ tức thì nhé ạ!';
	$query            = isset( $_POST['query'] ) ? sanitize_text_field( wp_unslash( $_POST['query'] ) ) : '';
	$conversation_id  = isset( $_POST['conversation_id'] ) ? sanitize_text_field( wp_unslash( $_POST['conversation_id'] ) ) : '';

	if ( empty( $query ) ) {
		wp_send_json_success( array(
			'answer'          => $fallback_msg,
			'conversation_id' => $conversation_id,
		) );
	}

	// API key được đọc từ wp-config.php constant — không hard-code vào source code
	$api_key = defined( 'KTD_DIFY_API_KEY' ) ? KTD_DIFY_API_KEY : '';
	if ( empty( $api_key ) ) {
		wp_send_json_success( array(
			'answer'          => 'Dịch vụ AI hiện không khả dụng.',
			'conversation_id' => $conversation_id,
		) );
		return;
	}

	// User ID — hỗ trợ X-Forwarded-For cho môi trường đứng sau proxy/Cloudflare/CDN
	if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$forwarded = sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) );
		$user_ip   = trim( explode( ',', $forwarded )[0] );
	} elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
		$user_ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
	} else {
		$user_ip = 'guest';
	}
	$user_id = 'ktd_user_' . substr( md5( $user_ip ), 0, 12 );

	$payload = array(
		'inputs'          => (object) array(),
		'query'           => $query,
		'response_mode'   => 'blocking',
		'conversation_id' => ! empty( $conversation_id ) ? $conversation_id : '',
		'user'            => $user_id,
	);

	$response = wp_remote_post( 'https://api.dify.ai/v1/chat-messages', array(
		'headers'   => array(
			'Authorization' => 'Bearer ' . $api_key,
			'Content-Type'  => 'application/json',
			'User-Agent'    => 'WordPress/' . ( function_exists( 'get_bloginfo' ) ? get_bloginfo( 'version' ) : '6.7' ) . '; KTD-Store',
		),
		'body'      => wp_json_encode( $payload ),
		'timeout'   => 25,
		'sslverify' => true, // Dify.ai có SSL hợp lệ — luôn bật xác thực
	) );

	$system_error_msg = 'Dạ hệ thống AI hiện đang xử lý nhiều lượt truy cập hoặc gián đoạn kết nối tạm thời. Anh/chị vui lòng đợi 15-20 giây và nhắn lại giúp em, hoặc liên hệ trực tiếp Hotline 1900 8888 để được hỗ trợ tức thì nhé ạ!';

	if ( is_wp_error( $response ) ) {
		error_log( 'KTD Dify WP_Error: ' . $response->get_error_message() );
		wp_send_json_success( array(
			'answer'          => $system_error_msg,
			'conversation_id' => $conversation_id,
		) );
	}

	$status_code = wp_remote_retrieve_response_code( $response );
	$body        = wp_remote_retrieve_body( $response );
	$data        = json_decode( $body, true );

	if ( 200 !== $status_code || empty( $data['answer'] ) ) {
		// Chỉ log error code, không log body — body có thể chứa dữ liệu PII của khách hàng
		$error_code = isset( $data['code'] ) ? $data['code'] : 'no-answer';
		error_log( 'KTD Dify HTTP ' . $status_code . ' error: ' . $error_code );
		wp_send_json_success( array(
			'answer'          => $system_error_msg,
			'conversation_id' => $conversation_id,
		) );
	}

	$clean_answer = preg_replace( '/<think>[\s\S]*?<\/think>/i', '', $data['answer'] );
	$clean_answer = trim( $clean_answer );
	if ( empty( $clean_answer ) ) {
		$clean_answer = $fallback_msg;
	}

	wp_send_json_success( array(
		'answer'          => $clean_answer,
		'conversation_id' => ! empty( $data['conversation_id'] ) ? $data['conversation_id'] : '',
	) );
}
add_action( 'wp_ajax_ktd_dify_chat', 'ktd_ajax_dify_chat' );
add_action( 'wp_ajax_nopriv_ktd_dify_chat', 'ktd_ajax_dify_chat' );

/**
 * 2. Frontend Widget: Giao diện Native Chatbot HTML ngữ nghĩa (No Iframe)
 */
function ktd_render_native_chatbot() {
	if ( is_admin() ) {
		return;
	}
	?>
	<!-- KTD Store Native AI Chatbot (No Iframe) -->
	<div id="ktd-chatbot-root">
		<!-- Floating Launcher Button -->
		<button id="ktd-chat-launcher" type="button" aria-label="Mở tư vấn AI KTD Store" title="Trò chuyện với EV - Trợ lý KTD Store">
			<svg class="ktd-icon-open" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
			</svg>
			<svg class="ktd-icon-close" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
				<line x1="18" y1="6" x2="6" y2="18"></line>
				<line x1="6" y1="6" x2="18" y2="18"></line>
			</svg>
			<span class="ktd-pulse-badge" title="Đang trực tuyến 24/7"></span>
		</button>

		<!-- Native Chat Window -->
		<div id="ktd-chat-window" class="ktd-chat-hidden" role="dialog" aria-modal="true" aria-label="Khung tư vấn KTD Store">
			<!-- Header -->
			<div class="ktd-chat-header">
				<div class="ktd-chat-header-user">
					<div class="ktd-chat-avatar">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<rect x="3" y="11" width="18" height="10" rx="2"></rect>
							<circle cx="12" cy="5" r="2"></circle>
							<path d="M12 7v4"></path>
							<line x1="8" y1="16" x2="8" y2="16"></line>
							<line x1="16" y1="16" x2="16" y2="16"></line>
						</svg>
						<span class="ktd-avatar-status"></span>
					</div>
					<div class="ktd-chat-header-text">
						<h3 class="ktd-chat-title">EV — Trợ lý AI KTD Store</h3>
						<span class="ktd-chat-status">Trực tuyến 24/7 • Sẵn sàng hỗ trợ</span>
					</div>
				</div>
				<div class="ktd-chat-header-actions">
					<button type="button" id="ktd-chat-reset-btn" title="Làm mới cuộc trò chuyện" aria-label="Làm mới">
						<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
							<path d="M21 3v5h-5"></path>
							<path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
							<path d="M8 16H3v5"></path>
						</svg>
					</button>
					<button type="button" id="ktd-chat-close-btn" title="Đóng cửa sổ" aria-label="Đóng">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
							<line x1="18" y1="6" x2="6" y2="18"></line>
							<line x1="6" y1="6" x2="18" y2="18"></line>
						</svg>
					</button>
				</div>
			</div>

			<!-- Messages Body -->
			<div id="ktd-chat-body" class="ktd-chat-body">
				<div id="ktd-chat-messages" class="ktd-chat-messages"></div>
				<div id="ktd-chat-typing" class="ktd-chat-typing">
					<span class="ktd-typing-dot"></span>
					<span class="ktd-typing-dot"></span>
					<span class="ktd-typing-dot"></span>
					<span class="ktd-typing-text">EV đang soạn câu trả lời...</span>
				</div>
			</div>

			<!-- Input Form -->
			<form id="ktd-chat-form" class="ktd-chat-form" autocomplete="off">
				<input type="text" id="ktd-chat-input" placeholder="Hỏi EV bất kỳ điều gì..." maxlength="500" required />
				<button type="submit" id="ktd-chat-send" aria-label="Gửi tin nhắn">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
						<line x1="22" y1="2" x2="11" y2="13"></line>
						<polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
					</svg>
				</button>
			</form>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'ktd_render_native_chatbot', 99 );
