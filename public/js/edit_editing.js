$(document).ready(function () {
    var max_fields = 15; //maximum input boxes allowed
    var wrapper = $(".input_fields_wrap"); //Fields wrapper
    var add_button = $(".add_field_button"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function (e) {
        //on add input button click
        e.preventDefault();
        if (x < max_fields) {
            //max input box allowed
            x++; //text box increment
            $(wrapper).append(
                '<div class="input-group-append"><input type="text" name="bullet[]" class="form-control" placeholder="Bullet"/><button type="button" class="btn btn-outline-danger remove_field text-danger align-self-center" data-toggle="tooltip" title="Hapus Field"><i class="fas fa-times"></i></button></div>'
            ); //add input box
        }
    });

    $(wrapper).on("click", ".remove_field", function (e) {
        //user click on remove text
        e.preventDefault();
        $(this).parent("div").remove();
        x--;
    });
});
$(document).ready(function () {
    var max_fields = 3; //maximum input boxes allowed
    var wrapper = $(".input_fields_wrap_editor"); //Fields wrapper
    var add_button = $(".add_field_button_editor"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function (e) {
        //on add input button click
        e.preventDefault();
        if (x < max_fields) {
            //max input box allowed
            x++; //text box increment
            $(wrapper).append(
                '<div class="input-group-append"><input type="text" name="editor[]" class="form-control" placeholder="Editor"/><button type="button" class="btn btn-outline-danger remove_field text-danger align-self-center" data-toggle="tooltip" title="Hapus Field"><i class="fas fa-times"></i></button></div>'
            ); //add input box
        }
    });

    $(wrapper).on("click", ".remove_field", function (e) {
        //user click on remove text
        e.preventDefault();
        $(this).parent("div").remove();
        x--;
    });
});
$(document).ready(function () {
    var max_fields = 3; //maximum input boxes allowed
    var wrapper = $(".input_fields_wrap_copyeditor"); //Fields wrapper
    var add_button = $(".add_field_button_copyeditor"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function (e) {
        //on add input button click
        e.preventDefault();
        if (x < max_fields) {
            //max input box allowed
            x++; //text box increment
            $(wrapper).append(
                '<div class="input-group-append"><input type="text" name="copy_editor[]" class="form-control" placeholder="Copy Editor"/><button type="button" class="btn btn-outline-danger remove_field text-danger align-self-center" data-toggle="tooltip" title="Hapus Field"><i class="fas fa-times"></i></button></div>'
            ); //add input box
        }
    });

    $(wrapper).on("click", ".remove_field", function (e) {
        //user click on remove text
        e.preventDefault();
        $(this).parent("div").remove();
        x--;
    });
});
$(document).ready(function () {
    $("#formatBukuButton").click(function (e) {
        e.preventDefault();
        $("#formatBukuCol").attr("hidden", "hidden");
        $("#formatBukuColInput").removeAttr("hidden");
    });
    $(".batal_edit_format").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#formatBukuColInput").attr("hidden", "hidden");
        $("#formatBukuCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#subJudulFinalButton").click(function (e) {
        e.preventDefault();
        $("#subJudulFinalCol").attr("hidden", "hidden");
        $("#subJudulFinalColInput").removeAttr("hidden");
    });
    $(".batal_edit_sub_judul_final").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#subJudulFinalColInput").attr("hidden", "hidden");
        $("#subJudulFinalCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#jmlHalButton").click(function (e) {
        e.preventDefault();
        $("#jmlHalCol").attr("hidden", "hidden");
        $("#jmlHalColInput").removeAttr("hidden");
    });
    $(".batal_edit_jml").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#jmlHalColInput").attr("hidden", "hidden");
        $("#jmlHalCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#editorButton").click(function (e) {
        e.preventDefault();
        $("#editorCol").attr("hidden", "hidden");
        $("#editorColInput").removeAttr("hidden");
    });
    $(".batal_edit_editor").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#editorColInput").attr("hidden", "hidden");
        $("#editorCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#catButton").click(function (e) {
        e.preventDefault();
        $("#catCol").attr("hidden", "hidden");
        $("#catColInput").removeAttr("hidden");
    });
    $(".batal_edit_cat").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#catColInput").attr("hidden", "hidden");
        $("#catCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#copyEditorButton").click(function (e) {
        e.preventDefault();
        $("#copyEditorCol").attr("hidden", "hidden");
        $("#copyEditorColInput").removeAttr("hidden");
    });
    $(".batal_edit_copyeditor").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#copyEditorColInput").attr("hidden", "hidden");
        $("#copyEditorCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#bulanButton").click(function (e) {
        e.preventDefault();
        $("#bulanCol").attr("hidden", "hidden");
        $("#bulanColInput").removeAttr("hidden");
    });
    $(".batal_edit_bulan").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#bulanColInput").attr("hidden", "hidden");
        $("#bulanCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#bulletButton").click(function (e) {
        e.preventDefault();
        $("#bulletCol").attr("hidden", "hidden");
        $("#bulletColInput").removeAttr("hidden");
    });
    $(".batal_edit_bullet").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#bulletColInput").attr("hidden", "hidden");
        $("#bulletCol").removeAttr("hidden");
    });
});
$(function () {
    $(".select-editor-editing")
        .select2({
            placeholder: "Pilih editor",
            multiple: true,
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });
    $(".select-copyeditor")
        .select2({
            placeholder: "Pilih copy editor",
            multiple: true,
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });

    $(".datepicker").datepicker({
        format: "MM yyyy",
        viewMode: "months",
        minViewMode: "months",
        autoclose: true,
        clearBtn: true,
    });

    function ajaxUpEditingEdit(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/penerbitan/editing/edit",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('button[type="submit"]')
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                // resetFrom(data);
                if (result.status == 'error'){
                    notifToast(result.status, result.message);
                } else {
                    notifToast(result.status, result.message);
                    location.href = result.route;
                }
            },
            error: function (err) {
                console.log(err.responseJSON);
                rs = err.responseJSON.errors;
                if (rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach((entry) => {
                        let [key, value] = entry;
                        err[key] = value;
                    });
                    // upNaskah.showErrors(err);
                }
                notifToast("error", "Data editing proses gagal disimpan!");
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }

    $("#fup_editingProses").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let nama = $(this).find('[name="judul_final"]').val();
            swal({
                text: "Update data editing proses naskah, (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxUpEditingEdit($(this));
                }
            });
        } else {
            notifToast("error", "Periksa kembali form Anda!");
        }
    });
});
