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

$(document).on("click", "#restore-platform", function (e) {
    e.preventDefault();
    var getLink = $(this).attr("href");
    swal({
        title: "Apakah anda yakin?",
        text: "Data akan dikembalikan",
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
