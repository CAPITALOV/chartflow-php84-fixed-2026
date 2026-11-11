/**
 * Created by Дмитрий on 19.08.2015.
 */
$(document).ready(function(){
    // форма авторизации
    {
        $('#modalLogin').click(function () {
            $('#loginModal').modal('show');
        });
        $('#buttonLogin').click(function () {
            if ($('#field-email').val() == '') return showError('Введите логин');
            if ($('#field-password').val() == '') return showError('Введите пароль');
            ajaxJson({
                url: '/loginAjax',
                data: {
                    email: $('#field-email').val(),
                    password: $('#field-password').val(),
                    is_stay: $('#loginFormIsStay').is(':checked')? 1:0
                },
                beforeSend: function () {
                    $('#buttonLogin').html($('#loginFormLoading').html());
                },
                success: function (ret) {
                    window.location.reload();
                },
                errorScript: function (ret) {
                    $('#buttonLogin').html('Войти');
                    $('#loginFormError').html(ret.data).show();
                }
            })
        });
        $('#field-email').on('focus', function () {
            $('#loginFormError').hide();
        });
        $('#field-password').on('focus', function () {
            $('#loginFormError').hide();
        });
        $('#field-password').on('keyup', function (event) {
            if (event.keyCode == 13) {
                $('#buttonLogin').click();
            }
        });
        $('#loginBarButton').on('mouseover', function() {
            $(this).css('opacity','1');
        });
        $('#loginBarButton').on('mouseout', function() {
            $(this).css('opacity','0.5');
        });
    }
});