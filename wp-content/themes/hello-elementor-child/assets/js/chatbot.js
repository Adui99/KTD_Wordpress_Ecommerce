/**
 * ==============================================================================
 * KTD Native AI Chatbot Controller (EV Assistant)
 * Theme: Hello Elementor Child (KTD E-Commerce)
 * Description: Client-side messenger controller for Dify REST API via WordPress AJAX.
 * ==============================================================================
 */

(function() {
	'use strict';

	const config = window.ktdChatConfig || {};
	const AJAX_URL = config.ajax_url || '/wp-admin/admin-ajax.php';
	const NONCE = config.nonce || '';
	const STORAGE_MSG_KEY = 'ktd_chat_messages_v3';
	const STORAGE_CONV_KEY = 'ktd_chat_conv_id_v3';
	const FALLBACK_MSG = 'Dạ em chưa có thông tin về vấn đề này, anh/chị vui lòng liên hệ Hotline 1900 8888 để được hỗ trợ ạ.';
	const DEFAULT_GREETING = 'Dạ em chào anh/chị! Em là EV - Trợ lý AI KTD Store. Anh/chị cần em hỗ trợ gì hôm nay ạ? 😊';

	function initChatbot() {
		const launcher = document.getElementById('ktd-chat-launcher');
		const chatWindow = document.getElementById('ktd-chat-window');
		const closeBtn = document.getElementById('ktd-chat-close-btn');
		const resetBtn = document.getElementById('ktd-chat-reset-btn');
		const chatForm = document.getElementById('ktd-chat-form');
		const chatInput = document.getElementById('ktd-chat-input');
		const chatSend = document.getElementById('ktd-chat-send');
		const messagesEl = document.getElementById('ktd-chat-messages');
		const typingEl = document.getElementById('ktd-chat-typing');

		if (!launcher || !chatWindow) {
			return;
		}

		let conversationId = localStorage.getItem(STORAGE_CONV_KEY) || '';
		let isRequesting = false;
		let safetyTimer = null;

		// Markdown Parser nhẹ an toàn
		function parseMarkdown(text) {
			if (!text) return '';
			let cleanText = text.replace(/<think>[\s\S]*?<\/think>/gi, '').trim();
			let escaped = cleanText
				.replace(/&/g, '&amp;')
				.replace(/</g, '&lt;')
				.replace(/>/g, '&gt;');

			// Bold **text**
			escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
			// Italic *text*
			escaped = escaped.replace(/\*(.*?)\*/g, '<em>$1</em>');

			// Phone links (1900 8888 or 19008888)
			escaped = escaped.replace(/(1900\s?8888)/g, '<a href="tel:19008888">$1</a>');

			// Bullet list lines (- item or * item)
			const lines = escaped.split('\n');
			let inList = false;
			let result = [];

			for (let line of lines) {
				const trimmed = line.trim();
				if (trimmed.startsWith('- ') || trimmed.startsWith('* ')) {
					if (!inList) {
						result.push('<ul>');
						inList = true;
					}
					result.push('<li>' + trimmed.substring(2) + '</li>');
				} else {
					if (inList) {
						result.push('</ul>');
						inList = false;
					}
					if (trimmed.length > 0) {
						result.push('<p>' + trimmed + '</p>');
					}
				}
			}
			if (inList) result.push('</ul>');

			return result.join('');
		}

		function getCurrentTime() {
			const now = new Date();
			return now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
		}

		function appendMessage(role, text, time, save) {
			if (save === undefined) save = true;
			const msgTime = time || getCurrentTime();
			const item = document.createElement('div');
			item.className = 'ktd-msg-item ' + (role === 'user' ? 'ktd-msg-user' : 'ktd-msg-bot');

			const bubble = document.createElement('div');
			bubble.className = 'ktd-bubble';
			if (role === 'bot') {
				bubble.innerHTML = parseMarkdown(text);
			} else {
				bubble.textContent = text;
			}

			const timeEl = document.createElement('div');
			timeEl.className = 'ktd-msg-time';
			timeEl.textContent = msgTime;

			item.appendChild(bubble);
			item.appendChild(timeEl);
			messagesEl.appendChild(item);

			scrollToBottom();

			if (save) {
				const history = getStoredMessages();
				history.push({ role, text, time: msgTime });
				localStorage.setItem(STORAGE_MSG_KEY, JSON.stringify(history));
			}
		}

		function getStoredMessages() {
			try {
				const raw = localStorage.getItem(STORAGE_MSG_KEY);
				return raw ? JSON.parse(raw) : [];
			} catch(e) {
				return [];
			}
		}

		function scrollToBottom() {
			const body = document.getElementById('ktd-chat-body');
			if (body) {
				body.scrollTop = body.scrollHeight;
			}
		}

		function loadHistory() {
			messagesEl.innerHTML = '';
			if (typingEl) typingEl.classList.remove('is-typing');
			const history = getStoredMessages();
			if (history.length === 0) {
				appendMessage('bot', DEFAULT_GREETING, getCurrentTime(), true);
			} else {
				history.forEach(m => appendMessage(m.role, m.text, m.time, false));
			}
			scrollToBottom();
		}

		function toggleChat(forceOpen) {
			const isCurrentlyHidden = chatWindow.classList.contains('ktd-chat-hidden');
			const open = typeof forceOpen === 'boolean' ? forceOpen : isCurrentlyHidden;

			if (open) {
				chatWindow.classList.remove('ktd-chat-hidden');
				launcher.classList.add('is-active');
				setTimeout(() => {
					if (chatInput) chatInput.focus();
					scrollToBottom();
				}, 200);
			} else {
				chatWindow.classList.add('ktd-chat-hidden');
				launcher.classList.remove('is-active');
			}
		}

		function finishRequest() {
			if (safetyTimer) {
				clearTimeout(safetyTimer);
				safetyTimer = null;
			}
			isRequesting = false;
			if (chatSend) chatSend.disabled = false;
			if (typingEl) typingEl.classList.remove('is-typing');
		}

		function sendMessage(text) {
			const query = (text || (chatInput ? chatInput.value : '') || '').trim();
			if (!query || isRequesting) return;

			appendMessage('user', query, getCurrentTime(), true);
			if (chatInput) chatInput.value = '';

			isRequesting = true;
			if (chatSend) chatSend.disabled = true;
			if (typingEl) typingEl.classList.add('is-typing');
			scrollToBottom();

			// Safety watchdog: tự động hoàn tất sau 30s nếu kết nối mạng bị nghẽn
			safetyTimer = setTimeout(() => {
				if (isRequesting) {
					finishRequest();
					appendMessage('bot', FALLBACK_MSG, getCurrentTime(), true);
				}
			}, 30000);

			const formData = new URLSearchParams();
			formData.append('action', 'ktd_dify_chat');
			formData.append('nonce', NONCE);
			formData.append('query', query);
			if (conversationId) {
				formData.append('conversation_id', conversationId);
			}

			fetch(AJAX_URL, {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: formData.toString()
			})
			.then(res => res.json())
			.then(data => {
				finishRequest();
				if (data && data.success && data.data && data.data.answer) {
					if (data.data.conversation_id) {
						conversationId = data.data.conversation_id;
						localStorage.setItem(STORAGE_CONV_KEY, conversationId);
					}
					appendMessage('bot', data.data.answer, getCurrentTime(), true);
				} else {
					appendMessage('bot', FALLBACK_MSG, getCurrentTime(), true);
				}
			})
			.catch(() => {
				finishRequest();
				appendMessage('bot', FALLBACK_MSG, getCurrentTime(), true);
			});
		}

		// Gán sự kiện
		launcher.addEventListener('click', () => toggleChat());
		if (closeBtn) {
			closeBtn.addEventListener('click', () => toggleChat(false));
		}

		if (resetBtn) {
			resetBtn.addEventListener('click', () => {
				if (confirm('Bắt đầu cuộc trò chuyện mới với EV?')) {
					localStorage.removeItem(STORAGE_MSG_KEY);
					localStorage.removeItem(STORAGE_CONV_KEY);
					conversationId = '';
					loadHistory();
				}
			});
		}

		if (chatForm) {
			chatForm.addEventListener('submit', (e) => {
				e.preventDefault();
				sendMessage();
			});
		}

		document.addEventListener('keydown', (e) => {
			if (e.key === 'Escape' && !chatWindow.classList.contains('ktd-chat-hidden')) {
				toggleChat(false);
			}
		});

		// Khởi tạo
		loadHistory();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initChatbot);
	} else {
		initChatbot();
	}
})();
