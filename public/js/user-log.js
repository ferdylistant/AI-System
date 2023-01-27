$(function () {
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
});
