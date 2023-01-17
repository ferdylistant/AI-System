function ajaxDelPlatform() {
    $.ajax({
        type: "GET",
        url: window.location.origin + "/kelompok-buku/hapus",
        processData: false,
        contentType: false,
        success: function (result) {
            console.log(result);
            if (result.status == "error") {
                notifToast(result.status, result.message);
            } else {
                notifToast(result.status, result.message);
                window.location.reload();
            }
        },
    });
}
$(document).on("click", "#hapus-platform", function (e) {
    e.preventDefault();
    var getLink = $(this).attr("href");
    swal({
        title: "Apakah anda yakin?",
        text: "Data akan terhapus",
        type: "warning",
        html: true,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((confirm_) => {
        if (confirm_) {
            window.location.href = getLink;
        }
    });
});
