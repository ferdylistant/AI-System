
$(function () {
    let tablePenulis = $("#tb_Penulis").DataTable({
        // bSort: true,
        "responsive": true,
        "autoWidth": false,
        "aaSorting": [],
        "dom": "<'row'<'col-sm-6'B><'col-sm-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [
            'pageLength',
            'spacer',
            {
                extend: 'collection',
                text: 'Exports',
                buttons: [
                    {
                        extend: 'print',
                        text: '<i class="text-secondary fa fa-print"></i> Print',
                        exportOptions: {
                            columns: [0,1,2,3]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="text-danger fa fa-file-pdf"></i> PDF',
                        exportOptions: {
                            columns: [0,1,2,3]
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="text-success fa fa-file-excel"></i> Excel',
                        action: function (e, dt, node, config) {
                            e.preventDefault();
                            $.ajax({
                                url: window.location.origin + '/penerbitan/penulis/export-all',
                                type: 'GET',
                                cache: false,
                                beforeSend: function () {
                                    $("#overlay").fadeIn(300);
                                },
                                success: (res) => {
                                    window.location.href = window.location.origin + '/penerbitan/penulis/export-all';
                                },
                                error: (err) => {
                                    setTimeout(function() {
                                        $("#overlay").fadeOut(300);
                                    }, 500);
                                    notifToast('error','Tabel kosong!');
                                },
                                complete: function () {
                                    setTimeout(function() {
                                        $("#overlay").fadeOut(300);
                                    }, 500);
                                }
                            });
                        }
                    },
                ]
            },
        ],
        pagingType: "input",
        processing: true,
        serverSide: false,
        language: {
            searchPlaceholder: "Cari...",
            sSearch: "",
            lengthMenu: "_MENU_ /halaman",
        },
        drawCallback: function() {
            var popoverTriggerList = [].slice.call(document.querySelectorAll('.popover-table'))
                var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                    return new bootstrap.Popover(popoverTriggerEl, {
                        html: true,
                        container: 'body',
                        placement: 'top',
                    })
                })
                // console.log(popoverList);
        },
        ajax: {
            url: window.location.origin + "/penerbitan/penulis",
            data: {
                request_: "table-penulis",
            }
        },
        columns: [
            {
                data: "ktp",
                name: "ktp",
                title: "KTP",
            },
            {
                data: "nama",
                name: "nama",
                title: "Nama",
            },
            {
                data: "email",
                name: "email",
                title: "Email",
            },
            {
                data: "ponsel_domisili",
                name: "ponsel_domisili",
                title: "Ponsel",
            },
            {
                data: "status",
                name: "status",
                title: "Status Data",
            },
            {
                data: "history",
                name: "history",
                title: "History Data",
            },
            {
                data: "action",
                name: "action",
                title: "Action",
                searchable: false,
                orderable: false,
            },
        ],
    });
    new $.fn.dataTable.Buttons(tablePenulis, {
        buttons: [
            'print', 'excel', 'pdf'
        ]
    });
    $.fn.dataTable.ext.errMode = function (settings, helpPage, message) {
        notifToast("error",settings.jqXHR.statusText)
        if (settings && settings.jqXHR && settings.jqXHR.status == 401) {
            window.location.reload();
        }
    };
    loadCountData();
    // History Penulis Start
    tablePenulis.on("click", ".btn-history", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var judul = $(this).data("nama");
        $.post(
            window.location.origin + "/penerbitan/penulis/lihat-history",
            {
                id: id,
            },
            function (data) {
                $("#titleModalPenulis").html(
                    '<i class="fas fa-history"></i>&nbsp;History Perubahan Penulis "' +
                        judul +
                        '"'
                );
                $("#load_more").data("id", id);
                $("#dataHistory").html(data);
                $("#md_PenulisHistory").modal("show");
            }
        );
    });
    $(".load-more").click(function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        let form = $("#md_PenulisHistory");
        var id = $(this).data("id");
        $(this).data("paginate", page + 1);

        $.ajax({
            url: window.location.origin + "/penerbitan/penulis/lihat-history",
            data: {
                id: id,
                page: page,
            },
            type: "post",
            cache: false,
            beforeSend: function () {
                form.addClass("modal-progress");
            },
            success: function (response) {
                if (response.length == 0) {
                    $(".load-more").attr("disabled", true).css("cursor", "not-allowed");
                    notifToast("error", "Tidak ada data lagi");
                } else {
                    $("#dataHistory").append(response);
                    $('.thin').animate({scrollTop: $('.thin').prop("scrollHeight")}, 800);
                }
                // Setting little delay while displaying new content
                // setTimeout(function() {
                //     // appending posts after last post with class="post"
                //     $("#dataHistory:last").htnl(response).show().fadeIn("slow");
                // }, 2000);
            },
            complete: function (params) {
                form.removeClass("modal-progress");
            },
        });
    });
    $("#md_PenulisHistory").on("hidden.bs.modal", function () {
        $(".load-more").data("paginate", 2);
        $(".load-more").attr("disabled", false).css("cursor", "pointer");
    });
    // History Penulis End

    //Delete Penulis Start
    $(document).ready(function () {
        function ajaxDeletePenulis(data) {
            $.ajax({
                type: "POST",
                url:
                    window.location.origin +
                    "/penerbitan/penulis/hapus-penulis",
                data: data,
                beforeSend: function () {
                    $(".btn_DelPenulis")
                        .prop("disabled", true)
                        .addClass("btn-progress");
                },
                success: function (result) {
                    notifToast(result.status, result.message);
                    tablePenulis.ajax.reload();
                },
                error: function (err) {
                    notifToast("error", "Terjadi kesalahan");
                },
                complete: function () {
                    $(".btn_DelPenulis")
                        .prop("disabled", true)
                        .removeClass("btn-progress");
                },
            });
        }
        $(document).on("click", ".btn_DelPenulis", function (e) {
            let nama = $(this).data("nama"),
                id = $(this).data("id");
            console.log(id, nama);
            swal({
                text: "Hapus data penulis (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxDeletePenulis({
                        id: id,
                    });
                }
            });
        });
    });
    //Delete Penulis End
});
function loadCountData() {
    $.ajax({
        url: window.location.origin + "/penerbitan/penulis",
        data: {
            request_: "count-data",
        },
        type: "get",
        success: function (response) {
            $("#countData").prop('Counter',0).animate({
                Counter: response
            }, {
                duration: 1000,
                easing: 'swing',
                step: function (now) {
                    $(this).text(Math.ceil(now));
                }
            });
        },
    });
}
