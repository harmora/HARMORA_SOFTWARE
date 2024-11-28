@extends('layout')

@section('title')
    Chatbot
@endsection

@section('content')
<div class="container mt-1">
    <div class="row">
        <div class="card-body text-center">
            <div class="d-flex justify-content-center align-items-center gap-3">
                <h4 class="card-title text-primary mb-0" id="chatbot-title">{{ get_label('custumer', 'Customer Service Chatbot') }}</h4>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="serviceToggle">
                    <label class="form-check-label" for="serviceToggle">Switch to Data Service</label>
                </div>
            </div>
        </div>
        <div class="col-md-10 offset-md-1">
            <div class="chat-container">
                <div id="chat-window">
                    <!-- Separate chat outputs for each service -->
                    <div id="assistant-chat-output" class="chat-output active"></div>
                    <div id="data-chat-output" class="chat-output"></div>
                </div>
                <div class="chat-input">
                    <input type="text" id="chat-input" class="form-control" placeholder="Ask me anything...">
                    <button id="send-btn" class="btn btn-primary">
                        <span class="normal-state">
                            <i class="fas fa-paper-plane"></i> Send
                        </span>
                        <span class="loading-state d-none">
                            <i class="fas fa-spinner fa-spin"></i> Generating...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .chat-container {
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
 
    #chat-window {
        height: 400px;
        overflow-y: auto;
        padding: 10px;
        background-color: #e5ddd5;
        position: relative;
    }
    .chat-output {
        display: none;
        flex-direction: column;
    }
    .chat-output.active {
        display: flex;
    }
    .message {
        max-width: 80%;
        margin-bottom: 10px;
        padding: 8px 12px;
        border-radius: 8px;
        position: relative;
    }
    .message-user {
        background-color: #c6f8f6;
        align-self: flex-end;
    }
    .message-bot {
        background-color: #fff;
        align-self: flex-start;
    }
    .chat-input {
        display: flex;
        padding: 10px;
        background-color: #f0f0f0;
    }
    #chat-input {
        border-radius: 20px;
        margin-right: 10px;
    }
    .form-check-input {
        width: 3em;
        height: 1.5em;
        cursor: pointer;
    }
    .form-check-label {
        cursor: pointer;
    }
    #send-btn {
        min-width: 120px;
    }
    #send-btn:disabled {
        cursor: not-allowed;
    }
    .loading-state {
        color: white;
    }
    .typing-indicator {
        background-color: #fff;
        padding: 8px 12px;
        border-radius: 8px;
        margin-bottom: 10px;
        align-self: flex-start;
        display: flex;
        align-items: center;
    }
    .typing-indicator span {
        height: 8px;
        width: 8px;
        background: #3b5998;
        display: block;
        border-radius: 50%;
        opacity: 0.4;
        margin: 0 2px;
        animation: typing 1s infinite;
    }
    @keyframes typing {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-5px); }
        100% { transform: translateY(0px); }
    }
    .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
    .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }

    .chat-switch-animation {
        animation: fadeSwitch 0.3s ease-in-out;
    }
    @keyframes fadeSwitch {
        0% { opacity: 0; }
        100% { opacity: 1; }
    }
</style>

<script>
    $(document).ready(function () {
        let isDataService = false;
        let isGenerating = false;
        
        // Save chat histories in memory
        const chatHistories = {
            assistant: [],
            data: []
        };

        function getCurrentChatOutput() {
            return isDataService ? '#data-chat-output' : '#assistant-chat-output';
        }

        function appendMessage(sender, message) {
            const messageObj = {
                sender: sender,
                message: message,
                timestamp: new Date().getTime()
            };

            // Save to appropriate history
            if (isDataService) {
                chatHistories.data.push(messageObj);
            } else {
                chatHistories.assistant.push(messageObj);
            }

            var messageClass = sender === 'user' ? 'message-user' : 'message-bot';
            var messageHtml = '<div class="message ' + messageClass + '">' + message + '</div>';
            $(getCurrentChatOutput()).append(messageHtml);
            $('#chat-window').scrollTop($('#chat-window')[0].scrollHeight);
        }

        function showTypingIndicator() {
            $(getCurrentChatOutput()).append(`
                <div class="typing-indicator" id="typing-indicator">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            `);
            $('#chat-window').scrollTop($('#chat-window')[0].scrollHeight);
        }

        function removeTypingIndicator() {
            $('#typing-indicator').remove();
        }

        function setLoadingState(loading) {
            isGenerating = loading;
            const btn = $('#send-btn');
            const input = $('#chat-input');
            
            if (loading) {
                btn.prop('disabled', true);
                input.prop('disabled', true);
                btn.find('.normal-state').addClass('d-none');
                btn.find('.loading-state').removeClass('d-none');
            } else {
                btn.prop('disabled', false);
                input.prop('disabled', false);
                btn.find('.loading-state').addClass('d-none');
                btn.find('.normal-state').removeClass('d-none');
            }
        }

        function switchChatService() {
            // Hide all chat outputs
            $('.chat-output').removeClass('active');
            
            // Show the appropriate chat output
            const currentOutput = getCurrentChatOutput();
            $(currentOutput).addClass('active chat-switch-animation');
            
            // Update title
            const title = isDataService ? 'Data Service Chatbot' : '{{ get_label('custumer', 'Customer Service Chatbot') }}';
            $('#chatbot-title').text(title);
            
            // Scroll to bottom of new chat
            $('#chat-window').scrollTop($('#chat-window')[0].scrollHeight);
        }

        function renderStoredMessages(history) {
            const outputDiv = $(getCurrentChatOutput());
            outputDiv.empty();
            
            history.forEach(item => {
                const messageClass = item.sender === 'user' ? 'message-user' : 'message-bot';
                const messageHtml = '<div class="message ' + messageClass + '">' + item.message + '</div>';
                outputDiv.append(messageHtml);
            });
            
            $('#chat-window').scrollTop($('#chat-window')[0].scrollHeight);
        }

        $('#serviceToggle').change(function() {
            isDataService = $(this).is(':checked');
            switchChatService();
            
            // Render appropriate history
            const history = isDataService ? chatHistories.data : chatHistories.assistant;
            renderStoredMessages(history);
        });

        function sendMessage() {
            var userInput = $('#chat-input').val();
            if (userInput.trim() === '' || isGenerating) return;

            appendMessage('user', userInput);
            $('#chat-input').val('');
            setLoadingState(true);
            showTypingIndicator();

            $.ajax({
                url: isDataService ? '{{ route("chatbot.getResponse2") }}' : '{{ route("chatbot.getResponse") }}',
                method: 'POST',
                data: {
                    message: userInput,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    removeTypingIndicator();
                    appendMessage('bot', response.response);
                },
                error: function () {
                    removeTypingIndicator();
                    appendMessage('bot', 'Sorry, something went wrong. Please try again.');
                },
                complete: function() {
                    setLoadingState(false);
                    $('#chat-input').focus();
                }
            });
        }

        $('#send-btn').click(sendMessage);

        $('#chat-input').keypress(function (e) {
            if (e.which == 13) {
                sendMessage();
                return false;
            }
        });
    });
</script>
@endsection