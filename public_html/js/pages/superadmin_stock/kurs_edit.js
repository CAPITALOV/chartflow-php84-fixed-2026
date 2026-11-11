$(document).ready(function(){
    var val;
    var functionUpdate = function() {
        var t = $(this);
        if (val != t.val()) {
            ajaxJson({
                url: '/stock/kurs/update',
                data: {
                    id: t.data('id'),
                    value: t.val(),
                    type: t.data('type')
                },
                success: function(ret) {
                    t.parent().addClass('has-success');
                    setTimeout(function() {
                        t.parent().removeClass('has-success');
                    }, 2000);
                }
            })
        }
    };
    $('.inputKurs').on('focus', function() {
        val = $(this).val();
    })
    $('.inputKurs').on('blur', functionUpdate);
    $('.inputDate').on('focus', function() {
        val = $(this).val();
    })
    $('.inputDate').on('blur', functionUpdate);
});