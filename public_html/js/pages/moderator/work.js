$(document).ready(function () {
    $(document).on('click', '.js-mod-btn', function (e) {
        e.preventDefault();
        var d = $(this).data();
        $.get('/moderator/obj/' + [d.pid, d.t, d.id].join('/')).done(function (d) {
            $('#obj').remove();
            $(d).insertBefore('body div:first');
            $('#obj').modal('show');
        }).fail(function (d) {
            if (d.status == 404)
                alert(d.responseText);
            else
                console.log(d);
        });
    });

    $(document).on('click', '.js-mod-action', function (e) {
        var that = $(this);
        $.ajax({
            url: "api/moderation/apply.action",
            type: "POST",
            data: {ac: that.data('action'), oid: that.parent().data('oid')}
        }).done(function (d) {
            if (d.success)
                window.location.reload();
        });
    });

    /* dynamic time */
    setInterval(function () {
        var h, m, s;
        var current_date = new Date();
        var current_time = current_date.getTime() + current_date.getTimezoneOffset() * 60 * 1000;
        $('.otime').each(function (i, e) {
            var s_left = (new Date($(e).data('ts') * 1000).getTime() - current_time) / 1000;
            var pos = 0 > s_left;
            s_left = Math.abs(s_left);
            h = parseInt(s_left / 3600);
            s_left = s_left % 3600;
            m = parseInt(s_left / 60);
            s = parseInt(s_left % 60);
            $(e).text((pos ? '+' : '-') + [h < 10 ? '0' + h : h, m < 10 ? '0' + m : m, s < 10 ? '0' + s : s].join(':'));
        });
    }, 1000);

}); 