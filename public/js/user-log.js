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
            if (this.checked) {
                value = "1";
            } else {
                value = "0";
            }
            let val = value;
            $.ajax({
                url:
                    window.location.origin +
                    "/manajemen-web/user/ajax/update-status-user/" +
                    id,
                type: "POST",
                data: {
                    proses: val,
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
                        $("#statusShow").removeAttr("class");
                        $("#statusShow").html(result.data);
                        // location.reload();
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
