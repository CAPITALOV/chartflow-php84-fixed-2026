$(document).ready(function() {
    $('.jsLoggerDelete').on('click', function () {
        if (confirm('Подтвердите удаление')) {
            var $this = $(this);
            ajaxJson({
                url: '/jsLogger/delete/' + $(this).data('id'),
                success: function() {
                    $this.parent().parent().remove();
                    alert('ok');
                }
            });
        }
    });
    $('#jsLoggerDeleteAll').on('click', function () {
        if (confirm('Подтвердите удаление')) {
            ajaxJson({
                url: '/jsLogger/deleteAll',
                success: function() {
                    alert('ok');
                }
            });
        }
    });
});