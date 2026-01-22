<!-- CHATBOT WIDGET -->
<div id="chatbot-container" class="fixed-bottom-right">

    <!-- 1. Chat Window -->
    <div id="chat-window" class="card shadow-lg border-0 rounded-4 d-none animate__animated animate__fadeInUp">

        <!-- Header -->
        <div
            class="card-header bg-gradient-primary text-white p-3 rounded-top-4 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm position-relative"
                    style="width: 40px; height: 40px;">
                    <i class="bi bi-stars fs-5"></i>
                    <span
                        class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                        <span class="visually-hidden">Online</span>
                    </span>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Asisten Bengkel</h6>
                    <small class="text-white-50" style="font-size: 0.7rem;">Terhubung dengan Data Bengkel</small>
                </div>
            </div>
            <button type="button" class="btn-close btn-close-white" onclick="toggleChat()"></button>
        </div>

        <!-- Body Chat -->
        <div class="card-body p-3 overflow-auto" id="chat-messages" style="height: 320px; background: #f8f9fa;">
            <!-- Welcome Message -->
            <div class="d-flex flex-column align-items-start mb-3">
                <div class="bg-white p-3 rounded-3 shadow-sm text-dark border msg-bot">
                    Halo, Boss! 👋 <br>
                    Saya sudah mengecek data bengkel hari ini. Mau tau laporan omzet, stok yang menipis, atau butuh
                    saran strategi?
                </div>
                <small class="text-muted ms-1 mt-1" style="font-size: 0.65rem;">AI System</small>
            </div>
        </div>

        <!-- Suggestion Chips (Inovasi Baru) -->
        <div class="px-3 pb-2 bg-white border-top pt-2 d-flex gap-2 overflow-auto"
            style="white-space: nowrap; scrollbar-width: none;">
            <button class="btn btn-outline-primary btn-sm rounded-pill py-1 px-3 small-text"
                onclick="sendSuggestion('Analisis kondisi bengkel hari ini')">📊 Analisis Harian</button>
            <button class="btn btn-outline-danger btn-sm rounded-pill py-1 px-3 small-text"
                onclick="sendSuggestion('Barang apa yang perlu di restock?')">📦 Cek Stok</button>
            <button class="btn btn-outline-success btn-sm rounded-pill py-1 px-3 small-text"
                onclick="sendSuggestion('Buatkan ide promo untuk pelanggan')">💡 Ide Promo</button>
        </div>

        <!-- Footer Input -->
        <div class="card-footer bg-white p-2 border-top-0 rounded-bottom-4">
            <form id="chat-form" class="d-flex gap-2 align-items-center" onsubmit="sendMessage(event)">
                <input type="text" id="chat-input" class="form-control rounded-pill bg-light border-0 ps-3 py-2"
                    placeholder="Ketik pesan..." autocomplete="off">
                <button type="submit"
                    class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                    style="width: 42px; height: 42px;">
                    <i class="bi bi-send-fill ms-1"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- 2. Floating Button -->
    <button id="chat-trigger"
        class="btn btn-primary rounded-circle shadow-lg d-flex align-items-center justify-content-center position-relative"
        onclick="toggleChat()">
        <i class="bi bi-chat-quote-fill fs-3"></i>
        <!-- Notif Dot -->
        <span
            class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
            <span class="visually-hidden">New alerts</span>
        </span>
    </button>
</div>

<!-- STYLES -->
<style>
    .fixed-bottom-right {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 9999;
    }

    #chat-trigger {
        width: 60px;
        height: 60px;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        background: linear-gradient(135deg, #4f46e5, #3b82f6);
        border: none;
    }

    #chat-trigger:hover {
        transform: scale(1.1);
        box-shadow: 0 10px 25px rgba(79, 70, 229, 0.4);
    }

    #chat-window {
        width: 360px;
        position: absolute;
        bottom: 80px;
        right: 0;
        transform-origin: bottom right;
        transition: all 0.3s ease;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #4f46e5, #3b82f6);
    }

    .msg-user {
        background: #4f46e5;
        color: white;
        border-radius: 18px 18px 4px 18px !important;
        align-self: flex-end;
        box-shadow: 0 2px 5px rgba(79, 70, 229, 0.2);
    }

    .msg-bot {
        background: white;
        color: #374151;
        border: 1px solid #e5e7eb;
        border-radius: 18px 18px 18px 4px !important;
        align-self: flex-start;
    }

    .small-text {
        font-size: 0.75rem;
        white-space: nowrap;
    }

    /* Scrollbar hide for chips */
    .overflow-auto::-webkit-scrollbar {
        display: none;
    }

    /* Typing Animation */
    .typing-dots span {
        width: 6px;
        height: 6px;
        background: #9ca3af;
        border-radius: 50%;
        display: inline-block;
        animation: bounce 1.4s infinite ease-in-out both;
    }

    .typing-dots span:nth-child(1) {
        animation-delay: -0.32s;
    }

    .typing-dots span:nth-child(2) {
        animation-delay: -0.16s;
    }

    @keyframes bounce {

        0%,
        80%,
        100% {
            transform: scale(0);
        }

        40% {
            transform: scale(1);
        }
    }

    /* Dark Mode */
    [data-bs-theme="dark"] #chat-messages {
        background: #111827 !important;
    }

    [data-bs-theme="dark"] .msg-bot {
        background: #1f2937;
        color: #e5e7eb;
        border-color: #374151;
    }

    [data-bs-theme="dark"] .card-footer,
    [data-bs-theme="dark"] .bg-white {
        background: #1f2937 !important;
        color: white;
    }

    [data-bs-theme="dark"] #chat-input {
        background: #374151 !important;
        color: white;
    }
</style>

<!-- SCRIPTS -->
<script>
    function toggleChat() {
        const window = document.getElementById('chat-window');
        const trigger = document.getElementById('chat-trigger');

        if (window.classList.contains('d-none')) {
            window.classList.remove('d-none');
            trigger.innerHTML = '<i class="bi bi-chevron-down fs-4"></i>';
            setTimeout(() => document.getElementById('chat-input').focus(), 100);
        } else {
            window.classList.add('d-none');
            trigger.innerHTML = '<i class="bi bi-chat-quote-fill fs-3"></i>';
        }
    }

    function sendSuggestion(text) {
        document.getElementById('chat-input').value = text;
        sendMessage(new Event('submit'));
    }

    async function sendMessage(e) {
        e?.preventDefault();
        const input = document.getElementById('chat-input');
        const message = input.value.trim();
        const container = document.getElementById('chat-messages');

        if (!message) return;

        // User Message
        appendMessage(message, 'user');
        input.value = '';

        // Loading
        const loadingId = 'loading-' + Date.now();
        const loadingHtml = `
            <div id="${loadingId}" class="d-flex flex-column align-items-start mb-2">
                <div class="msg-bot p-3 shadow-sm typing-dots" style="max-width: 85%;">
                    <span></span><span></span><span></span>
                </div>
            </div>`;
        container.insertAdjacentHTML('beforeend', loadingHtml);
        scrollToBottom();

        try {
            const response = await fetch("{{ route('chatbot.send') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    message: message
                })
            });

            const data = await response.json();
            document.getElementById(loadingId).remove();
            appendMessage(data.reply, 'bot');

        } catch (error) {
            console.log('Error:', error);

            document.getElementById(loadingId).remove();
            appendMessage("Maaf, koneksi terputus.", 'bot');
        }
    }

    function appendMessage(text, sender) {
        const container = document.getElementById('chat-messages');
        const align = sender === 'user' ? 'align-items-end' : 'align-items-start';
        const bgClass = sender === 'user' ? 'msg-user' : 'msg-bot';

        const html = `
            <div class="d-flex flex-column ${align} mb-3 animate__animated animate__fadeIn">
                <div class="${bgClass} p-3 shadow-sm" style="max-width: 85%; font-size: 0.9rem; line-height: 1.5;">
                    ${text}
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        scrollToBottom();
    }

    function scrollToBottom() {
        const container = document.getElementById('chat-messages');
        container.scrollTo({
            top: container.scrollHeight,
            behavior: 'smooth'
        });
    }
</script>
