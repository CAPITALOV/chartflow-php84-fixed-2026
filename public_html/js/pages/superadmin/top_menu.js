var functionPublic = function () {
    var $this = $(this);
    ajaxJson({
        url: '/topMenu/' + $(this).data('id') + '/public',
        success: function() {
            $this.removeClass('btn-public');
            $this.addClass('btn-uppublic');
            $this.html('снять с публикации');
            $this.one('click', functionUnPublic);
            alert('ok');
        }
    });
};
var functionUnPublic = function () {
    var $this = $(this);
    ajaxJson({
        url: '/topMenu/' + $(this).data('id') + '/unpublic',
        success: function() {
            $this.removeClass('btn-unpublic');
            $this.addClass('btn-public');
            $this.html('опубликовать');
            $this.one('click', functionPublic);
            alert('ok');
        }
    });
};


$(document).ready(function() {
    $('.btn-public').one('click', functionPublic);
    $('.btn-unpublic').one('click', functionUnPublic);
    $('.btn-topMenuDelete').click(function(){
        if (confirm('Подтвердите удаление')) {
            var $this = $(this);
            ajaxJson({
                url: '/topMenu/' + $(this).data('id') + '/delete',
                success: function() {
                    $this.parent().parent().remove();
                    alert('ok');
                }
            });
        }
    });
    $.getScript('/vendor/TableDnD/js/jquery.tablednd.js', function () {
        $('#topMenuTable table').tableDnD({
            onDragClass: 'myDragClass',
            onDrop: function (table, row) {
                var ids = [];
                $(table).find('tbody tr').each(function () {
                    ids.push($(this).find('td:first').html().trim());
                })
                ajaxJson({
                    url: '/topMenu/resort',
                    type: 'post',
                    data: {
                        ids: ids
                    },
                    success: function () {
                        alert('Отсортировано успешно');
                    }
                });
            },
        });
    });
});