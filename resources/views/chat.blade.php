<!DOCTYPE html>
<html>
<head>
    <title>AI Chat</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: auto;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: #10a37f;
            color: white;
            padding: 15px;
            font-size: 20px;
            text-align: center;
        }

        .chat {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .message {
            max-width: 70%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 10px;
            line-height: 1.5;
        }

        .user {
            background: #10a37f;
            color: white;
            margin-left: auto;
        }

        .ai {
            background: #e5e5ea;
            color: black;
            margin-right: auto;
        }

        .input-box {
            display: flex;
            padding: 15px;
            background: white;
            border-top: 1px solid #ddd;
        }

        .input-box input {
            flex: 1;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
        }

        .input-box button {
            margin-left: 10px;
            padding: 10px 20px;
            border: none;
            background: #10a37f;
            color: white;
            border-radius: 8px;
            cursor: pointer;
        }

        .input-box button:hover {
            background: #0e8c6d;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        🤖 AI Chat
    </div>

    <div class="chat">

        @foreach($messages as $msg)
            <div class="message user">
                {{ $msg->question }}
            </div>

            <div class="message ai">
                {{ $msg->answer }}
            </div>
        @endforeach

    </div>

    <form id="chat-form" class="input-box">
        @csrf
        <input type="text" id="message" placeholder="اكتب رسالة..." required>
        <button type="submit">Send</button>
    </form>

</div>

<script>
$('#chat-form').submit(function(e) {
    e.preventDefault();

    var text = $('#message').val();

    // عرض رسالة المستخدم فوراً
    $('.chat').append(`
        <div class="message user">${text}</div>
    `);

    $('#message').val('');

    // loading
    var loading = $(`<div class="message ai">🤖 typing...</div>`);
    $('.chat').append(loading);

    $.ajax({
        type: 'POST',
        url: "{{ route('send') }}",
        data: {
            message: text,
            _token: "{{ csrf_token() }}"
        },

        success: function(res) {

            loading.remove();

            $('.chat').append(`
                <div class="message ai">${res.answer}</div>
            `);

            $('.chat').scrollTop($('.chat')[0].scrollHeight);
        }
    });
});
</script>

</body>
</html>