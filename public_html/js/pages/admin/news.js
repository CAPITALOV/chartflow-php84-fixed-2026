
$(document).ready(function() {
    $('.btn-newsDelete').click(function(){
        if (confirm('Подтвердите удаление')) {
            var $this = $(this);
            ajaxJson({
                url: '/news/' + $(this).data('id') + '/delete',
                success: function() {
                    $this.parent().parent().remove();
                    alert('ok');
                }
            });
        }
    });

});