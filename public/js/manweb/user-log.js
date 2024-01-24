async function loadDataAccess(){
    let id = window.location.pathname.split("/").pop();
    $.ajax({
        url:
            window.location.origin +
            "/manajemen-web/user/" + id + "?type=access",
        type: "GET",
        dataType: "html",
        beforeSend: function () {
            $('#loadAction').parent().addClass('card-progress')
        },
        success: function (result) {
            // console.log(result);
            $(".hummingbird-treeview").html(result);
            $('#treeview').hummingbird();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(thrownError);
            notifToast("error", "Terjadi kesalahan, silahkan coba lagi.");
        },
        complete: function () {
            $('#loadAction').parent().removeClass('card-progress')
        }
    });
}
async function loadUserLog(){
    // User log start
    let id = window.location.pathname.split("/").pop();
    // if ($.fn.DataTable.isDataTable('#tb_UserLog')) {
    //     $('#tb_UserLog').DataTable().destroy();
    // }
    $("#tb_UserLog").DataTable({
        destroy:true,
        responsive: true,
        autoWidth: true,
        responsive: true,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Cari...",
            sSearch: "",
            lengthMenu: "_MENU_ /halaman",
        },
        ajax: {
            url: window.location.origin + "/manajemen-web/user/" + id +"?type=log",
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
                title: "IP Address",
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
}
async function ajaxUserStatus(id, value) {
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
            }
        },
    }).done(function () {
        setTimeout(function () {
            $("#overlay").fadeOut(300);
        }, 500);
    });
}
async function confirmSweetAlert(id, value) {
    swal({
        title: "Yakin ingin menonaktifkan akun user?",
        text: "Harap diperiksa kembali, supaya tidak terjadi kesalahan.",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((confirm_) => {
        if (confirm_) {
            ajaxUserStatus(id, value);
        } else {
            $("#userStatus").prop("checked", true);
        }
    });
}
async function loadTab(tab) {
    if (tab === 'access-menu') {
        await loadDataAccess();
    } else if (tab === 'user-log') {
        await loadUserLog();
    }
}
$(document).ready(function () {
    loadTab($('#loadAction .nav-link.active').data('typeget'));
    // Each change tab form penilaian
    $(document).on('show.bs.tab', function(e) {
        loadTab($(e.delegateTarget.activeElement).data('typeget'));
    });
    $("#userStatus").click(function () {
        var id = $(this).data("id");
        if (this.checked) {
            value = "1";
            ajaxUserStatus(id, value);
        } else {
            value = "0";
            confirmSweetAlert(id, value);
        }

    });
});

