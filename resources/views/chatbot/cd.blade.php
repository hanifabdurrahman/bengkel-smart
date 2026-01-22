<!-- CHATBOT WIDGET -->
<div id="chatbot-container" class="fixed-bottom-right">

    <!-- 1. Chat Window (Hidden by default) -->
    <div id="chat-window" class="card shadow-lg border-0 rounded-4 d-none">
        <!-- Header -->
        <div
            class="card-header bg-primary text-white p-3 rounded-top-4 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center"
                    style="width: 35px; height: 35px;">
                    <i class="bi bi-robot fs-5"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Asisten Bengkel</h6>
                    <small class="text-white-50" style="font-size: 0.75rem;">Online</small>
                </div>
            </div>
            <button type="button" class="btn-close btn-close-white" onclick="toggleChat()"></button>
        </div>

        <!-- Body (Chat Bubble Area) -->
        <div class="card-body p-3 overflow-auto" id="chat-messages" style="height: 350px; background: #f8f9fa;">
            <!-- Welcome Message -->
            <div class="d-flex flex-column align-items-start mb-2">
                <div class="bg-white p-3 rounded-3 shadow-sm text-dark border"
                    style="max-width: 85%; border-radius: 0 15px 15px 15px !important;">
                    Halo! 👋 Saya asisten AI BengkelSmart. Ada yang bisa saya bantu terkait bengkel hari ini?
                </div>
                <small class="text-muted ms-1 mt-1" style="font-size: 0.7rem;">Sekarang</small>
            </div>
        </div>

        <!-- Footer (Input) -->
        <div class="card-footer bg-white p-2 border-top-0 rounded-bottom-4">
            <form id="chat-form" class="d-flex gap-2 align-items-center" onsubmit="sendMessage(event)">
                <input type="text" id="chat-input" class="form-control rounded-pill bg-light border-0 ps-3"
                    placeholder="Tanya sesuatu..." autocomplete="off">
                <button type="submit"
                    class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                    style="width: 40px; height: 40px;">
                    <i class="bi bi-send-fill" style="margin-left: 2px;"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- 2. Floating Button -->
    <button id="chat-trigger"
        class="btn btn-primary rounded-circle shadow-lg d-flex align-items-center justify-content-center"
        onclick="toggleChat()">
        <i class="bi bi-chat-dots-fill fs-3"></i>
    </button>
</div>

<!-- STYLES -->
<style>
    .fixed-bottom-right {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1050;
    }

    #chat-trigger {
        width: 60px;
        height: 60px;
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    #chat-trigger:hover {
        transform: scale(1.1);
    }

    #chat-window {
        width: 350px;
        margin-bottom: 80px;
        /* Space for the button */
        animation: slideUp 0.3s ease-out;
    }

    /* Message Bubbles */
    .msg-user {
        align-self: flex-end;
        background: #4f46e5;
        color: white;
        border-radius: 15px 15px 0 15px !important;
    }

    .msg-bot {
        align-self: flex-start;
        background: white;
        color: #333;
        border: 1px solid #e5e7eb;
        border-radius: 0 15px 15px 15px !important;
    }

    /* Typing Indicator */
    .typing-indicator span {
        display: inline-block;
        width: 6px;
        height: 6px;
        background-color: #adb5bd;
        border-radius: 50%;
        animation: typing 1s infinite;
        margin: 0 2px;
    }

    .typing-indicator span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-indicator span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typing {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-5px);
        }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Dark Mode Support */
    [data-bs-theme="dark"] #chat-messages {
        background: #111827 !important;
    }

    [data-bs-theme="dark"] .msg-bot {
        background: #1f2937;
        color: #e5e7eb;
        border-color: #374151;
    }

    [data-bs-theme="dark"] .card-footer {
        background: #1f2937 !important;
    }

    [data-bs-theme="dark"] #chat-input {
        background: #374151 !important;
        color: white;
    }

    [data-bs-theme="dark"] #chat-input::placeholder {
        color: #9ca3af;
    }
</style>

<!-- SCRIPTS -->
<script>
    function toggleChat() {
        const window = document.getElementById('chat-window');
        const trigger = document.getElementById('chat-trigger');

        if (window.classList.contains('d-none')) {
            window.classList.remove('d-none');
            trigger.innerHTML = '<i class="bi bi-x-lg fs-3"></i>';
            trigger.classList.replace('btn-primary', 'btn-secondary');
            setTimeout(() => document.getElementById('chat-input').focus(), 100);
        } else {
            window.classList.add('d-none');
            trigger.innerHTML = '<i class="bi bi-chat-dots-fill fs-3"></i>';
            trigger.classList.replace('btn-secondary', 'btn-primary');
        }
    }

    async function sendMessage(e) {
        e.preventDefault();
        const input = document.getElementById('chat-input');
        const message = input.value.trim();
        const container = document.getElementById('chat-messages');

        if (!message) return;

        // 1. Tampilkan User Message
        appendMessage(message, 'user');
        input.value = '';

        // 2. Tampilkan Loading (Typing...)
        const loadingId = 'loading-' + Date.now();
        const loadingHtml = `
            <div id="${loadingId}" class="d-flex flex-column align-items-start mb-2">
                <div class="msg-bot p-3 shadow-sm typing-indicator" style="max-width: 85%;">
                    <span></span><span></span><span></span>
                </div>
            </div>`;
        container.insertAdjacentHTML('beforeend', loadingHtml);
        scrollToBottom();

        try {
            // 3. Request ke Server
            const response = await fetch("{{ route('chatbot.send') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    message: message
                })
            });

            const data = await response.json();

            // 4. Hapus Loading & Tampilkan Balasan
            document.getElementById(loadingId).remove();
            appendMessage(data.reply, 'bot');

        } catch (error) {
            document.getElementById(loadingId).remove();
            appendMessage("Maaf, terjadi kesalahan jaringan.", 'bot');
        }
    }

    function appendMessage(text, sender) {
        const container = document.getElementById('chat-messages');
        const align = sender === 'user' ? 'align-items-end' : 'align-items-start';
        const bgClass = sender === 'user' ? 'msg-user' : 'msg-bot';

        const html = `
            <div class="d-flex flex-column ${align} mb-2">
                <div class="${bgClass} p-3 shadow-sm" style="max-width: 85%;">
                    ${text}
                </div>
                <small class="text-muted mx-1 mt-1" style="font-size: 0.6rem;">Just now</small>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', html);
        scrollToBottom();
    }

    function scrollToBottom() {
        const container = document.getElementById('chat-messages');
        container.scrollTop = container.scrollHeight;
    }
</script>
