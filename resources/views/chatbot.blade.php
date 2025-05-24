<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bjorwi Chatbot</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .chat-container {
            width: 400px;
            height: 600px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .chat-header h1 {
            font-size: 1.2em;
            font-weight: 600;
        }

        .chat-header .status {
            font-size: 0.8em;
            opacity: 0.9;
            margin-top: 5px;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #f8f9fa;
        }

        .message {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-end;
        }

        .message.user {
            justify-content: flex-end;
        }

        .message.bot {
            justify-content: flex-start;
        }

        .message-bubble {
            max-width: 80%;
            padding: 12px 16px;
            border-radius: 18px;
            font-size: 0.9em;
            line-height: 1.4;
            position: relative;
            animation: fadeIn 0.3s ease-in;
            white-space: pre-line;
        }

        .message.user .message-bubble {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-bottom-right-radius: 8px;
        }

        .message.bot .message-bubble {
            background: white;
            color: #333;
            border: 1px solid #e0e0e0;
            border-bottom-left-radius: 8px;
        }

        .chat-input-container {
            padding: 20px;
            background: white;
            border-top: 1px solid #e0e0e0;
        }

        .chat-input {
            display: flex;
            align-items: center;
            background: #f8f9fa;
            border-radius: 25px;
            padding: 5px;
            border: 1px solid #e0e0e0;
        }

        .chat-input input {
            flex: 1;
            border: none;
            outline: none;
            padding: 12px 15px;
            background: transparent;
            font-size: 0.9em;
        }

        .chat-input button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s;
        }

        .chat-input button:hover {
            transform: scale(1.1);
        }

        .chat-input button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .typing-indicator {
            display: none;
            padding: 15px;
            text-align: center;
            color: #666;
            font-style: italic;
        }

        .typing-dots {
            display: inline-block;
        }

        .typing-dots span {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #667eea;
            margin: 0 2px;
            animation: typing 1.4s infinite ease-in-out;
        }

        .typing-dots span:nth-child(1) {
            animation-delay: -0.32s;
        }

        .typing-dots span:nth-child(2) {
            animation-delay: -0.16s;
        }

        @keyframes typing {

            0%,
            80%,
            100% {
                transform: scale(0);
            }

            40% {
                transform: scale(1);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .quick-replies {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .quick-reply {
            background: #e3f2fd;
            color: #1976d2;
            border: 1px solid #bbdefb;
            padding: 8px 12px;
            border-radius: 15px;
            font-size: 0.8em;
            cursor: pointer;
            transition: all 0.2s;
        }

        .quick-reply:hover {
            background: #1976d2;
            color: white;
        }

        .bot-avatar {
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 8px;
            font-size: 0.8em;
        }

        .connection-status {
            position: absolute;
            top: 10px;
            right: 20px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #4caf50;
        }

        .connection-status.disconnected {
            background: #f44336;
        }
    </style>
</head>

<body>
    <div class="chat-container">
        <div class="chat-header">
            <div class="connection-status" id="connectionStatus"></div>
            <h1>🤖 Bjorwi Chatbot</h1>
            <div class="status">Online • Siap membantu Anda</div>
        </div>

        <div class="chat-messages" id="chatMessages">
            <div class="message bot">
                <div class="bot-avatar">B</div>
                <div class="message-bubble">
                    Halo! 👋 Saya adalah Bjorwi Chatbot yang dibuat dengan Laravel dan Botman. Bagaimana saya bisa
                    membantu Anda
                    hari ini?
                    <div class="quick-replies">
                        <div class="quick-reply" onclick="sendQuickReply('Apa itu Botman?')">Apa itu Botman?</div>
                        <div class="quick-reply" onclick="sendQuickReply('Kata-kata hari ini')">Kata-kata hari ini</div>
                        <div class="quick-reply" onclick="sendQuickReply('Jokes bapak-bapak')">Jokes bapak-bapak</div>
                        <div class="quick-reply" onclick="sendQuickReply('List prompt')">List prompt</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="typing-indicator" id="typingIndicator">
            Bot sedang mengetik
            <div class="typing-dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <div class="chat-input-container">
            <div class="chat-input">
                <input type="text" id="messageInput" placeholder="Ketik pesan Anda..."
                    onkeypress="handleKeyPress(event)">
                <button onclick="sendMessage()" id="sendButton">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22,2 15,22 11,13 2,9 22,2"></polygon>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        // Setup CSRF token untuk AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function handleKeyPress(event) {
            if (event.key === 'Enter') {
                sendMessage();
            }
        }

        function sendQuickReply(message) {
            document.getElementById('messageInput').value = message;
            sendMessage();
        }

        async function sendMessage() {
            const input = document.getElementById('messageInput');
            const message = input.value.trim();
            const sendButton = document.getElementById('sendButton');

            if (message === '') return;

            // Disable input dan button
            input.disabled = true;
            sendButton.disabled = true;

            // Add user message
            addMessage(message, 'user');
            input.value = '';

            // Show typing indicator
            showTypingIndicator();

            try {
                // Send message to BotMan
                const formData = new FormData();
                formData.append('driver', 'web');
                formData.append('message', message);
                formData.append('userId', 'web-user-' + Math.random().toString(36).substr(2, 9));

                const response = await fetch('/botman', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                hideTypingIndicator();

                // untuk setiap message yang dikirim BotMan:
                data.messages.forEach(msg => {
                    // msg.text berisi string, msg.type bisa dipakai kalau mau handle attachments
                    addBotMessage(msg.text);
                });

                updateConnectionStatus(true);

            } catch (error) {
                console.error('Error:', error);
                hideTypingIndicator();
                addBotMessage('Maaf, koneksi ke server terputus. Silakan coba lagi.');
                updateConnectionStatus(false);
            } finally {
                // Re-enable input dan button
                input.disabled = false;
                sendButton.disabled = false;
            }
        }

        function addMessage(text, sender) {
            const messagesContainer = document.getElementById('chatMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${sender}`;

            if (sender === 'bot') {
                messageDiv.innerHTML = `
                    <div class="bot-avatar">B</div>
                    <div class="message-bubble">${text}</div>
                `;
            } else {
                messageDiv.innerHTML = `
                    <div class="message-bubble">${text}</div>
                `;
            }

            messagesContainer.appendChild(messageDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function addBotMessage(text) {
            const messagesContainer = document.getElementById('chatMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'message bot';

            // Check jika ada gambar dalam response
            let messageContent = text;
            let imageHtml = '';

            // Pattern untuk detect gambar: [IMAGE]url
            const imageMatch = text.match(/\[IMAGE\](.+)/);
            if (imageMatch) {
                const imageUrl = imageMatch[1].trim();
                imageHtml =
                    `<br><img src="${imageUrl}" alt="Gambar" style="max-width: 100%; border-radius: 10px; margin-top: 10px;">`;
                messageContent = text.replace(/\[IMAGE\].+/, ''); // Hapus bagian [IMAGE] dari text
            }

            // Parse quick replies dari response jika ada
            const quickReplies = extractQuickReplies(text);
            let quickRepliesHtml = '';

            if (quickReplies.length > 0) {
                quickRepliesHtml = '<div class="quick-replies">';
                quickReplies.forEach(reply => {
                    quickRepliesHtml +=
                        `<div class="quick-reply" onclick="sendQuickReply('${reply}')">${reply}</div>`;
                });
                quickRepliesHtml += '</div>';
            }

            messageDiv.innerHTML = `
        <div class="bot-avatar">B</div>
        <div class="message-bubble">
            ${messageContent}
            ${imageHtml}
            ${quickRepliesHtml}
        </div>
    `;

            messagesContainer.appendChild(messageDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function extractQuickReplies(text) {
            // Extract quick replies jika ada pattern "Tanyakan lagi: option1 | option2 | option3"
            const match = text.match(/Tanyakan lagi: (.+)/);
            if (match) {
                return match[1].split(' | ').map(option => option.trim());
            }
            return [];
        }

        function showTypingIndicator() {
            document.getElementById('typingIndicator').style.display = 'block';
            const messagesContainer = document.getElementById('chatMessages');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function hideTypingIndicator() {
            document.getElementById('typingIndicator').style.display = 'none';
        }

        function updateConnectionStatus(connected) {
            const status = document.getElementById('connectionStatus');
            if (connected) {
                status.classList.remove('disconnected');
            } else {
                status.classList.add('disconnected');
            }
        }

        // Auto focus input on load
        window.onload = function() {
            document.getElementById('messageInput').focus();
            updateConnectionStatus(true);
        }
    </script>
</body>

</html>
