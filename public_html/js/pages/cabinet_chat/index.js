/**
 */
$(document).ready(function()
{
    var chatObject = $('#chatMessages');

    /**
     * lastDatetime - максимальное время всех сообщений в начале
     и потом время последнего сообщения “от”. Нужно для того чтобы не добавлять сообщения из скрипта /chat/getNewMessages
     * @type {number}
     */
    var lastDatetime = 0;

    $('.chat-datetime').tooltip();
    $('#btn-chat-input').on('keypress', function (e) {
        // Производит нажатие по кнопке отправить сообщение. Отлавливает ctrl+enter
        e = e || window.event;

        if (e.keyCode == 10 || e.keyCode == 13) {
            functionSend();
        }
    });

    var functionSend = function() {
        var text = $('#btn-chat-input').val();
        var user_id_to = chatObject.data('user-to');
        if (text == '') {
            showInfo('Нужно ввести ссобщение');
        }
        ajaxJson({
            url: '/chat/send',
            data: {
                text: text,
                to: user_id_to
            },
            success: function(ret) {
                var html = $(ret);
                html.find('.chat-datetime').tooltip();
                var  p = chatObject.find('p.alert-success');
                if (p.length == 1) {
                    p.remove();
                }
                chatObject.append(html);
                $('#btn-chat-input').val('');
            }
        });
    };

    $('#btn-chat-send').click(functionSend);

    setInterval(function() {
        if (lastDatetime == 0) {
            var items = chatObject.find('.chat-datetime');
            if (items.length == 0) {
                lastDatetime = 0;
            } else {
                lastDatetime = $(items[items.length - 1]).data('time');
            }
        }
        ajaxJson({
            url: '/chat/getNewMessages',
            data: {
                from: chatObject.data('user-to'),
                last_datetime: lastDatetime
            },
            success: function(ret) {
                if (ret.length > 0) {
                    // нужно вставить всесообщения
                    // просматриваю все сообщения куда вставить
                    var max = 0;
                    $.each(ret, function(i, v) {
                        var isInserted = false;
                        var html = $(v.html);
                        chatObject.find('li').each(function(index, itemLi) {
                            // вставляю туда где
                            // itemLi.time < v.datetime и itemLi(next).time > v.datetime
                            // или в последний если
                            // после itemLi(последний) < v.datetime
                            html.find('.chat-datetime').tooltip();
                            if ($(itemLi).find('.chat-datetime').data('time') > v.datetime) {
                                console.log($(itemLi).find('.chat-datetime').data('time'));
                                html.insertBefore(itemLi);
                                isInserted = true;

                                return false;
                            }
                        });
                        if (isInserted == false) {
                            chatObject.append(html);
                        }

                        if (max < html.find('.chat-datetime').data('time')) {
                            max = html.find('.chat-datetime').data('time');
                        }
                    });
                    lastDatetime = max;
                }
            }
        });
    }, 10 * 1000);
});