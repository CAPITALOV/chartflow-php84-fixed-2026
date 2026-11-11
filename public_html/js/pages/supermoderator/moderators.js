$(document).ready(function() {
    $('.moderatorsDelete').on('click', function () {
        var $this = $(this);
        ajaxJson({
            url: '/moderators/' + $(this).data('id') + '/delete',
            success: function() {
                $this.parent().parent().remove();
                alert('ok');
            }
        });
    });
    $('.moderatorsEdit').on('click', function() {
        var $this = $(this);
        window.location = '/moderators/' + $(this).data('id') + '/edit';
    });
});