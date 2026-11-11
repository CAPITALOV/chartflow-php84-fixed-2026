
$('#myModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var id = $(button).data('id');
    $(this).attr('data-id', id);

    ajaxJson({
        url: '/cron/' + id + '/execute',
        type: 'post',
        success: function(ret) {
            if (ret.info.http_code == 500) {
                $('#myModal textarea').html('Скрипт был завершен с ошибкой');

            } else {
                $('#myModal textarea').html(ret.output);
            }
        }
    });
});

$(document).ready(function() {
    $('.buttonDelete').on('click', function() {
        if (confirm('Подтвердите удаление действия')) {
            var id = $(this).data('id');
            $this = $(this);

            ajaxJson({
                url: '/cron/' + id + '/delete',
                type: 'post',
                success: function(ret) {
                    $this.hide();
                    $this.parent().parent().remove();
                }
            })
        }
    });
});