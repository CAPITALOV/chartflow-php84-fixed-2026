$(document).ready(function() {
    $('.btn-userMenuDelete').click(function(){
        if (confirm('Подтвердите удаление')) {
            var $this = $(this);
            ajaxJson({
                url: '/userMenu/' + $(this).data('id') + '/delete',
                success: function() {
                    $this.parent().parent().remove();
                    alert('ok');
                }
            });
        }
    });
    $.getScript('/vendor/TableDnD/js/jquery.tablednd.js', function () {
        $('#userMenuTable table').tableDnD({
            onDragClass: 'myDragClass',
            onDrop: function (table, row) {
                var ids = [];
                $(table).find('tbody tr').each(function () {
                    ids.push($(this).find('td:first').html().trim());
                })
                ajaxJson({
                    url: '/userMenu/resort',
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