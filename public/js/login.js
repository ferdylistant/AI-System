function jqueryValidation_(element, rules, messages = {}) {
    let _rules = rules === undefined ? {} : rules;
    // var x = 0;
    return $(element).validate({
        errorElement: 'span',
        errorPlacement: function (error, element) {
            // console.log(element)
            let name = element.attr('name');
            name = name.replace('[]', '');
            $('#err_' + name).addClass('invalid-feedback').append(error)
            // x++;
            // $('#err_' + name+x).addClass('invalid-feedback').append(error)
        },
        highlight: function (element) {
            if ($(element).parent().hasClass('image-preview')) {
                $(element).parent().css('border-color', '#dc3545')
            } else {
                $(element).addClass('is-invalid').removeClass('is-valid');
            }

        },
        unhighlight: function (element) {
            if ($(element).parent().hasClass('image-preview')) {
                $(element).parent().css('border-color', '#ddd')
            } else {
                $(element).removeClass('is-invalid');
            }

        },
        rules: _rules,
        ignore: ".note-editor *",
        messages: messages
    })
}
function notifToast(stts, msg, reload = false) {
    if (stts == 'success') {
        iziToast.success({
            title: 'Okay!',
            icon: 'fas fa-check-circle',
            message: msg,
            position: 'topRight',
            timeout: 2000,
            overlayColor: 'rgba(0, 0, 0, 0.6)',
            transitionIn: 'flipInX',
            transitionOut: 'flipOutX',
            transitionInMobile: 'flipInX',
            transitionOutMobile: 'flipOutX',
            onClosing: function () {
                if (reload) {
                    location.reload();
                }
            }
        });
    } else if (stts == 'error') {
        iziToast.error({
            title: 'Oops!',
            icon: 'fas fa-times-circle',
            message: msg,
            position: 'topRight',
            timeout: 2000,
            overlayColor: 'rgba(0, 0, 0, 0.6)',
            transitionIn: 'flipInX',
            transitionOut: 'flipOutX',
            transitionInMobile: 'flipInX',
            transitionOutMobile: 'flipOutX',
        });
    }
}
function resetFrom(form) {
    form.trigger("reset");
    $('[name="email"]').val("").trigger("change");
}
$(document).ready(function () {
    const passwordInput = document.querySelector("#password")
    const eye = document.querySelector("#eye")
    eye.addEventListener("click", function () {
        this.classList.toggle("fa-eye-slash")
        const type = passwordInput.getAttribute("type") === "password" ? "text" : "password"
        passwordInput.setAttribute("type", type)
    });
    let loginForm = jqueryValidation_('#loginSubmitForm', {
        email: {
            required: {
                depends:function(){
                    $(this).val($.trim($(this).val()));
                    return true;
                }
            },
            email: true
        },
        password: {
            required: true,
        },
    });
    $('#loginSubmitForm').on('submit', function (e) {
        // e.preventDefault();
        let email = $(this).find('[name="email"]').val();
        let pass = $(this).find('[name="password"]').val();
        if ($(this).valid()) {
            if (email && pass) {
                $('button[type="submit"]').prop('disabled', true).addClass('btn-progress');
            }
        }
    });
    $('#formResetPasswordModal').on('submit', function (e) {
        e.preventDefault();

        let el = $(this).get(0);
        let form_ = $('#modalForgotPassword');
        $.ajax({
            type: "POST",
            url: window.location.origin + "/forgot-password",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                form_.addClass("modal-progress");
            },
            success: function (result) {
                notifToast(result.status, result.message);
                if (result.status == "success") {
                    resetFrom($('#formResetPasswordModal'));
                }
            },
            error: function (err) {
                rs = err.responseJSON.errors;
                if (rs !== undefined) {
                    err = {};
                    Object.entries(rs).forEach((entry) => {
                        let [key, value] = entry;
                        err[key] = value;
                    });
                    // err.showErrors(err);
                }
                notifToast("error", "Terjadi kesalahan!");
            },
            complete: function () {
                form_.removeClass("modal-progress");
            },
        });
    });
});
