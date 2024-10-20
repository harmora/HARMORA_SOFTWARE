@extends('layout')

@section('title')
    Chatbot
@endsection

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="chat-container">
                <div class="bg-primary p-2">
                    <h3 class="text-center m-0 text-bold text-white-10">Customer Service Chatbot</h3>
                </div>
                <div id="chat-window">
                    <div id="chat-output"></div>
                </div>
                <div class="chat-input">
                    <input type="text" id="chat-input" class="form-control" placeholder="Ask me anything...">
                    <button id="send-btn" class="btn btn-primary ">Send</button>
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
    }
    #chat-output {
        display: flex;
        flex-direction: column;
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

</style>

<script>
    $(document).ready(function () {
        function appendMessage(sender, message) {
            var messageClass = sender === 'user' ? 'message-user' : 'message-bot';
            var messageHtml = '<div class="message ' + messageClass + '">' + message + '</div>';
            $('#chat-output').append(messageHtml);
            $('#chat-window').scrollTop($('#chat-window')[0].scrollHeight);
        }

        $('#send-btn').click(function () {
            var userInput = $('#chat-input').val();
            if (userInput.trim() === '') return;

            appendMessage('user', userInput);

            $.ajax({
                url: '{{ route("chatbot.getResponse") }}',
                method: 'POST',
                data: {
                    message: userInput,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    appendMessage('bot', response.response);
                    $('#chat-input').val('');
                },
                error: function () {
                    alert('Something went wrong. Please try again.');
                }
            });
        });

        $('#chat-input').keypress(function (e) {
            if (e.which == 13) {
                $('#send-btn').click();
                return false;
            }
        });
    });
</script>
@endsection