$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    statusCode: {
        401: function() {
            window.location.reload();
        }
    }
});
$(document).ajaxError(function (event, xhr, settings, thrownError) {
    if (xhr.status == 429) {
        // handle the error case
        alert('Request too many!')
    } else if (xhr.status == 401) {
        window.location.reload();
    }
});
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

let resizeImage = function (settings) {
    let file = settings.file;
    let maxSize = settings.maxSize;
    let reader = new FileReader();
    let image = new Image();
    let canvas = document.createElement('canvas');
    let dataURItoBlob = function (dataURI) {
        let bytes = dataURI.split(',')[0].indexOf('base64') >= 0 ?
            atob(dataURI.split(',')[1]) :
            unescape(dataURI.split(',')[1]);
        let mime = dataURI.split(',')[0].split(':')[1].split(';')[0];
        let max = bytes.length;
        let ia = new Uint8Array(max);
        for (let i = 0; i < max; i++)
            ia[i] = bytes.charCodeAt(i);
        return new Blob([ia], {
            type: mime
        });
    };
    let resize = function () {
        let width = image.width;
        let height = image.height;
        if (width > height) {
            if (width > maxSize) {
                height *= maxSize / width;
                width = maxSize;
            }
        } else {
            if (height > maxSize) {
                width *= maxSize / height;
                height = maxSize;
            }
        }
        canvas.width = width;
        canvas.height = height;
        canvas.getContext('2d').drawImage(image, 0, 0, width, height);
        let dataUrl = canvas.toDataURL('image/jpeg');
        return dataURItoBlob(dataUrl);
    };
    return new Promise(function (ok, no) {
        if (!file.type.match(/image.*/)) {
            no(new Error("Not an image"));
            return;
        }
        reader.onload = function (readerEvent) {
            image.onload = function () {
                return ok(resize());
            };
            image.src = readerEvent.target.result;
        };
        reader.readAsDataURL(file);
    });
};

$(function () {
    $.ajax({
        type: "POST",
        url: window.location.origin + "/notification",
        data: {},
        success: function (result) {
            if (result != '') {
                $('#containerNotf').children('a').addClass('beep');
                $('#containerNotf').children('div').children().eq(1).append(result);
            }
        },
        error: function (err) {
            $('#containerNotf').children('div').children().eq(1).append(err);
            // console.log(err)
        }
    })
});

