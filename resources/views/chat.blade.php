@extends('layout')

@section('title')
    Chatbot
@endsection

@section('content')
<div id="chat-container">
    <div id="chat-messages"></div>
    <form id="chat-form">
        <input type="text" id="user-input" placeholder="Type your message...">
        <button type="submit">Send</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        const chatForm = $('#chat-form');
        const userInput = $('#user-input');
        const chatMessages = $('#chat-messages');

        chatForm.on('submit', function(e) {
            e.preventDefault();
            const message = userInput.val().trim();
            if (!message) return;

            // Display user message
            appendMessage('You', message);
            userInput.val('');

            $.ajax({
                url: '/chat',
                method: 'POST',
                data: { message: message },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Display AI response
                    appendMessage('AI', response.response);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    appendMessage('Error', 'Failed to get AI response.');
                }
            });
        });

        function appendMessage(sender, message) {
            const messageElement = $('<p>').html(`<strong>${sender}:</strong> ${message}`);
            chatMessages.append(messageElement);
            chatMessages.scrollTop(chatMessages[0].scrollHeight);
        }
    });
</script>
@endsection
