function ajaxJson(options){
    return $.ajax({
        url: options.url,
        type: (typeof options.type != 'undefined')? options.type:'post',
        data: options.data,
        dataType: 'json',
        beforeSend: options.beforeSend,
        success: function(ret){
            var status;
            for (i in ret) {
                if (i == 'error') status = 'error';
                if (i == 'success') status = 'success';
            }
            if (status == 'success') {
                options.success(ret.success);
                return;
            }
            if (status == 'error') {
                if (typeof options.errorScript != 'undefined') {
                    options.errorScript(ret.error);
                } else {
                    alert(ret.error);
                }
            }
        },
        error: function (ret) {
        }
    });
}

function showInfo(message, functionClose) {
    if (typeof functionClose != 'undefined') {
        $('#modalInfo .btn-default').on('click', functionClose);
    }
    $('#modalInfo .modal-body').html(message);
    $('#modalInfo').modal('show');
}
