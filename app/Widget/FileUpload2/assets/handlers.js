var FileUpload2 = {
    // - selector - string - селектор JQuery элемента
    deleteCallback: function (selector) {
    },
    onChange: function (selector) {
        $(selector).on('change', function (e) {
            //$(selector + '-img_name').html(e.target.value);
            $(selector + '-value').val(e.target.value);
        });
    },
    init: function(selector){
        $(selector).bootstrapFileInput();
        $(selector).on('change', function (e) {
            $(selector + '-value').val(e.target.value);
            //$(selector + '-img_name').html(e.target.value);
            $(selector + '-img').remove();
        });
        $(selector + '-delete').click(function(){
            $(selector + '-img').remove();
            //$(selector + '-img_name').html('');
            $(selector + '-value').val('');
            //$.ajax({
            //    url: '/upload2',
            //    success: function(ret) {
            //
            //    }
            //});
        });
    }
};


