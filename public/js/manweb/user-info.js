(() => {
    "use strict";

    // Initial

    $('.datepicker').datepicker({
        format: 'dd MM yyyy'
    });

    $(".select2").each(function(e) {
        $(this).select2({
            placeholder: 'Pilih',
        }).on('change', function(e) {
            if (this.value) {
                $(this).valid();
            }
        });
    });

    $('#uploadimageModal').on('hidden.bs.modal', function() {
        $('#image_demo').croppie('destroy');
    });
    $('#cover_image').on('change', function() {
        var id = $(this).data('id');
        $('#idInModal').val(id);
        $('#uploadimageModal').modal('show');
        var boundaryWidth = $('.modal-body').width();
        var boundaryHeight = boundaryWidth / 2;
        var viewportWidth = boundaryWidth - (boundaryWidth / 100 * 50);
        var viewportHeight = boundaryHeight - (boundaryHeight / 100 * 20);
        $('#image_demo').croppie({
            viewport: {
                width: viewportWidth,
                height: viewportWidth,
                type: 'circle'
            },
            boundary: {
                width: boundaryWidth,
                height: boundaryHeight
            }
        });
        var reader = new FileReader();
        reader.onload = function(event) {
            $('#image_demo').croppie('bind', {
                url: event.target.result,
            });
        }
        reader.readAsDataURL(this.files[0]);
        // $('#uploadimageModal').modal('show');
    });
    /// Get button click event and get the current crop image
    $('.crop_image').click(function(event) {
        var formData = new FormData();
        let id = $('[name=id]').val();
        $('#image_demo').croppie('result', {
            type: 'canvas',
            size: 'viewport',
            format: 'jpeg' | 'png' | 'webp'
        }).then(function(blob) {
            formData.append('cropped_image', blob);
            ajaxFormPost(formData, window.location.origin +
                "/manajemen-web/user/ajax/save-image/" + id
            ); /// Calling my ajax function with my blob data passed to it
        });
        $('#uploadimageModal').modal('hide');
    }); /// Ajax Function
    function ajaxFormPost(formData, actionURL) {
        $.ajax({
            url: actionURL,
            type: 'POST',
            data: formData,
            cache: false,
            async: true,
            processData: false,
            contentType: false,
            timeout: 5000,
            beforeSend: function() {
                $("#overlay").fadeIn(300);
            },
            success: function(response) {
                // console.log(formData);
                // console.log(response);
                if (response.status === 'success') {
                    $('.image-output').attr('src', response.path);
                }
                notifToast(response.status, response.message);
            },
            complete: function() {
                setTimeout(function() {
                    $("#overlay").fadeOut(300);
                }, 500);
            }
        });
    }
    $('[data-magnify]').magnify({
        resizable: false,
        title: false,
        draggable: false,
        headerToolbar: [
            'close'
        ],
    });
    // let editUser = jqueryValidation_('#fm_EditUser', {
    //     uedit_pp: {
    //         extension: "jpg,jpeg,png",
    //         maxsize: 300000,
    //     },
    // });

    function ajaxEditUser(data) {
        let el = data.get(0);
        // console.log(el);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/manajemen-web/user/ajax/update",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function() {
                data.closest('.card').addClass('card-progress');
            },
            success: function(data) {
                console.log(data)
                notifToast('success', 'Data user berhasil diubah!');
            },
            error: function(err) {
                console.log(err)
                rs = err.responseJSON.errors;
                if (rs !== undefined) {
                    err = {};
                    Object.entries(rs).forEach(entry => {
                        let [key, value] = entry;
                        err[key] = value
                    })
                    // editUser.showErrors(err);
                }
                notifToast('error', 'Data user gagal diubah!');
            },
            complete: function() {
                data.closest('.card').removeClass('card-progress');
            }
        })

    }

    // $('.profile-widget').on('change', '[name="uedit_pp"]', function(e) {
    //     let file = e.currentTarget.files[0];
    //     $('#container-pp img').attr('src', URL.createObjectURL(file));
    // })
    $('#fm_EditUser').on('submit', function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            swal({
                    text: 'Simpan Perubahan?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((confirm) => {
                    if (confirm) {
                        ajaxEditUser($(this));
                    }
                });
        }
    });
    let editUserJob = jqueryValidation_('#fm_EditUserJob', {
        uedit_id_karyawan: {
            required: true,
            number: true,
            // remote: window.location.origin + "/manajemen-web/user/ajax/check-id-karyawan/"+  window.location.pathname.split("/").pop(),
        },
        uedit_cabang: {
            required: true,
        },
        uedit_divisi: {
            required: true,
        },
        uedit_jabatan: {
            required: true,
        },
        uedit_tgl_bergabung: {
            required: true,
        },
        uedit_tgl_berakhir: {
            required: true,
        },
        uedit_status_pekerjaan: {
            required: true,
        },
        uedit_level_pekerjaan_id: {
          required: true
        },
        uedit_approval_absensi: {
            required: true,
        },
        uedit_approval_shift: {
            required: true,
        },
        uedit_approval_lembur: {
            required: true,
        },
        uedit_approval_izin_kembali: {
            required: true,
        },
        uedit_approval_istirahat_telat: {
            required: true,
        }
    });
    function ajaxEditUserJob(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/manajemen-web/user/ajax/update-job",
            data: new FormData(el),
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function() {
                data.closest('.card').addClass('card-progress');
            },
            success: function(data) {
                console.log(data);
                notifToast('success', 'Data info pekerjaan user berhasil diubah!');
            },
            error: function(err) {
                notifToast('error', 'Data info pekerjaan user gagal diubah!');
                let rs = err.responseJSON.errors;
                if (rs !== undefined) {
                    err = {};
                    Object.entries(rs).forEach(entry => {
                        let [key, value] = entry;
                        err[key] = value
                    })
                    editUserJob.showErrors(err);
                }
            },
            complete: function() {
                data.closest('.card').removeClass('card-progress');
            }
        })
    }
    $('#fm_EditUserJob').on('submit', function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            swal({
                    text: 'Simpan Perubahan?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((confirm) => {
                    if (confirm) {
                        ajaxEditUserJob($(this));
                    }
                });
        }
    })
})()
;
