$(document).ready(function () {

    var $chatWindow = $('#chat-window ul');

    if (!$chatWindow.length) {
        return;
    }

    var addLine = function(sender, original, translated) {
        var content = [];

        content.push('<li class="' + sender + '">');

        if (sender !== 'sys') {
            content.push('<span class="original">' + original + '</span>');
            content.push('<span class="translated">' + translated + '</span>');
        } else {
            content.push(original);
        }

        content.push('</li>');

        $chatWindow.append(content.join(''));

        $chatWindow.animate({
            scrollTop: $chatWindow.prop("scrollHeight") - $chatWindow.height()
        }, 200);
    };

    var conn = new WebSocket('ws://' + window.location.hostname + ':9090');

    conn.onopen = function(e) {
        addLine('sys', "Connection established!");
    };

    conn.onmessage = function(e) {
        var msg = JSON.parse(e.data);
        if (msg.type == 'sys') {
            addLine('sys', msg.text);
        } else {
            addLine(
                msg.type == 'rcv' ? 'them' : 'me',
                msg.original,
                msg.text
            );
        }
    };

    conn.onerror = function(e) {
        addLine('sys', 'Error: ' + e.data);
    };

    $('#btn-submit').click(function(e) {
        e.preventDefault();

        var text = $('#chat-input').val();
        if (!jQuery.trim(text).length) {
            return;
        }

        var msg = {
            'type'  : 'snt',
            'src'   : $('input[name="src_lang"]:checked').val(),
            'dst'   : $('input[name="dst_lang"]:checked').val(),
            'text'  : text
        };

        if (false === conn.send(JSON.stringify(msg))) {
            addLine('sys', 'Error sending text. Please retry or reload the page');
            return;
        }

        $('#chat-input').val("");
    });

});
