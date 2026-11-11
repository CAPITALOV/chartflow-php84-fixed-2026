$(document).ready(function() {
    $('.moderatorsDelete').on('click', function () {
        if (confirm('Подтвердите удаление')) {
            var $this = $(this);
            ajaxJson({
                url: '/adminUsers/' + $(this).data('id') + '/delete',
                success: function() {
                    $this.parent().parent().remove();
                    alert('ok');
                }
            });
        }
    });
});