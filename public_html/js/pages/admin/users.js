var functionBlock = function () {
    if (confirm('Подтвердите блокировку пользователя')) {
        var $this = $(this);
        ajaxJson({
            url: '/users/' + $(this).data('id') + '/block',
            success: function() {
                $this.removeClass('usersBlock');
                $this.addClass('usersUnBlock');
                $this.html('разблокировать');
                $this.one('click', functionUnBlock);
            }
        });
    }
};
var functionUnBlock = function () {
    if (confirm('Подтвердите разблокировку пользователя')) {
        var $this = $(this);
        ajaxJson({
            url: '/users/' + $(this).data('id') + '/unblock',
            success: function() {
                $this.removeClass('usersUnBlock');
                $this.addClass('usersBlock');
                $this.html('заблокировать');
                $this.one('click', functionBlock);
            }
        });
    }
};

$(document).ready(function() {
    $('.usersBlock').one('click', functionBlock);
    $('.usersUnBlock').one('click', functionUnBlock);
});