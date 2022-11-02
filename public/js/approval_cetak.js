$(function () {
    $(".datepicker").datepicker({
        format: "dd MM yyyy",
        startDate: "-10d",
        autoclose: true,
        clearBtn: true,
        todayHighlight: true,
    });
    $("#btn-edit-tgl-jadi").on("click", function () {
        $("#edit-tgl-jadi").removeAttr("style");
        $("#btn-edit-tgl-jadi").hide();
    });
    $(".close").on("click", function () {
        $("#edit-tgl-jadi").attr("style", "display:none");
        $("#btn-edit-tgl-jadi").show();
    });
    $("#upTglJadi").change(function (e) {
        e.preventDefault();

        // get the value of the dropdown
        var id = $("#id").val();
        var hisTglUpload = $("#historyTgl").val();
        var selectedValue = $(this).val();
        // console.log(hisTglUpload);
        // make the ajax call (needs to be POST since you're sending data)
        $.ajax({
            url: window.location.origin + "/update-tanggal-jadi-cetak",
            type: "POST", // change your route to use POST too
            datatype: "JSON",
            context: this,
            data: {
                history: hisTglUpload,
                value: selectedValue,
                id: id,
            },
            success: function (res) {
                //var html = res;// no need to waste a variable, just use it directly
                notifToast(res.status, res.message);
                location.reload();
            },
            error: function () {
                notifToast("error", "Terjadi kesalahan");
            },
        });
    });
    $("#upJmlCetak").keyup(function (e) {
        e.preventDefault();

        // get the value of the dropdown
        var id = $("#id").val();
        var hisJmlCetak = $("#historyJumlahCetak").val();
        var selectedValue = $(this).val();
        // console.log(hisTglUpload);
        // make the ajax call (needs to be POST since you're sending data)
        if (selectedValue.length > 2) {
            $.ajax({
                url: window.location.origin + "/update-jumlah-cetak",
                type: "POST", // change your route to use POST too
                datatype: "JSON",
                context: this,
                data: {
                    history: hisJmlCetak,
                    value: selectedValue,
                    id: id,
                },
                success: function (res) {
                    //var html = res;// no need to waste a variable, just use it directly
                    notifToast(res.status, res.message);
                    location.reload();
                },
                error: function () {
                    notifToast("error", "Terjadi kesalahan");
                },
            });
        } else {
            notifToast("error", "Jumlah cetak minimal 3 digit");
        }
    });
    $("#btn-edit-jml-cetak").on("click", function () {
        $("#edit-jml-cetak").removeAttr("style");
        $("#btn-edit-jml-cetak").hide();
    });
    $(".close").on("click", function () {
        $("#edit-jml-cetak").attr("style", "display:none");
        $("#btn-edit-jml-cetak").show();
    });
    function ajaxApproveProduksiCetak(data) {
        let el = data.get(0);
        // console.log(el);
        $.ajax({
            type: "POST",
            url:
                window.location.origin +
                "/penerbitan/order-cetak/ajax/approval",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('button[type="submit"]')
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                notifToast(result.status, result.message);
                location.reload();
            },
            error: function (err) {
                // console.log(err.responseJSON)
                rs = err.responseJSON.errors;
                if (rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach((entry) => {
                        let [key, value] = entry;
                        err[key] = value;
                    });
                    addForm.showErrors(err);
                }
                notifToast("error", "Gagal melakukan penyetujuan!");
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }

    $("#fadd_Approval").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let kode = $(this).find('[name="kode_order"]').val();
            let judul = $(this).find('[name="judul_buku"]').val();
            swal({
                title:
                    "Yakin menyetujui order cetak #" + kode + "-" + judul + "?",
                text: "Setelah menyetujui, Anda tidak dapat mengubah kembali data yang sudah Anda setujui!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxApproveProduksiCetak($(this));
                }
            });
        }
    });
});
