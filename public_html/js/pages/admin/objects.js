$(document).ready(function() {
    $('.fileListDelete').on('click', function (e) {
        if (confirm('Подтвердите удаление файла')) {
            var $this = $(this);
            ajaxJson({
                url: '/users/objects/fileList/' + $(this).data('id') + '/delete',
                success: function() {
                    $this.parent().parent().remove();
                    alert('ok');
                }
            });
        }

    });
    $('.fileListBlock').on('click', function (e) {
        var $this = $(this);
        ajaxJson({
            url: '/users/objects/fileList/' + $(this).data('id') + '/block',
            success: function() {
                alert('ok');
            }
        });
    });
    $('.videoListDelete').on('click', function (e) {
        if (confirm('Подтвердите удаление видео')) {
            var $this = $(this);
            ajaxJson({
                url: '/users/objects/videoList/' + $(this).data('id') + '/delete',
                success: function() {
                    $this.parent().parent().remove();
                    alert('ok');
                }
            });
        }
    });
    $('.videoListBlock').on('click', function (e) {
        var $this = $(this);
        ajaxJson({
            url: '/users/objects/videoList/' + $(this).data('id') + '/block',
            success: function() {
                alert('ok');
            }
        });
    });
    $('.votingListDelete').on('click', function (e) {
        if (confirm('Подтвердите удаление опроса')) {
            var $this = $(this);
            ajaxJson({
                url: '/users/objects/votingList/' + $(this).data('id') + '/delete',
                success: function() {
                    $this.parent().parent().remove();
                    alert('ok');
                }
            });
        }
    });
    $('.votingListBlock').on('click', function (e) {
        var $this = $(this);
        ajaxJson({
            url: '/users/objects/votingList/' + $(this).data('id') + '/block',
            success: function() {
                alert('ok');
            }
        });
    });
});