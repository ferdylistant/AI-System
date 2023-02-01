$(function () {
    // User log start
    let id = window.location.pathname.split("/").pop();
    $("#tb_UserLog").DataTable({
        responsive: true,
        autoWidth: true,
        responsive: true,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Search...",
            sSearch: "",
            lengthMenu: "_MENU_ items/page",
        },
        ajax: {
            url: window.location.origin + "/manajemen-web/user/" + id,
        },
        columns: [
            {
                data: "no",
                name: "no",
                title: "No",
            },
            {
                data: "ip",
                name: "ip",
                title: "Ip Address",
            },
            {
                data: "browser",
                name: "browser",
                title: "Browser",
            },
            {
                data: "terakhir_login",
                name: "terakhir_login",
                title: "Terakhir Login",
            },
        ],
    });
    // User log end

    // User status start
    $(function () {
        $("#userStatus").click(function () {
            var id = $(this).data("id");
            var desainer = $(".select-desainer").val();
            var korektor = $(".select-korektor").val();
            if (this.checked) {
                value = "1";
            } else {
                value = "0";
            }
            let val = value;
            $.ajax({
                url:
                    window.location.origin +
                    "/penerbitan/pracetak/designer/proses-kerja",
                type: "POST",
                data: {
                    id: id,
                    proses: val,
                    desainer: desainer,
                    korektor: korektor,
                },
                dataType: "json",
                beforeSend: function () {
                    $("#overlay").fadeIn(300);
                },
                success: function (result) {
                    // console.log(result);
                    if (result.status == "error") {
                        notifToast(result.status, result.message);
                        resetFrom($("#fup_pracetakDesainer"));
                    } else {
                        notifToast(result.status, result.message);
                        location.reload();
                    }
                },
            }).done(function () {
                setTimeout(function () {
                    $("#overlay").fadeOut(300);
                }, 500);
            });
        });
    });
    // User status end
});
