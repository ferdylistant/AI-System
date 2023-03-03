$(function () {
    loadDataValue();
});
function loadDataValue() {
    let id = window.location.search.split('?').pop(),
        cardWrap = $('.section-body').find('.card');
    $.ajax({
        url: window.location.origin + "/jasa-cetak/order-buku/detail?" + id+"&request_=getValue",
        beforeSend: function () {
            cardWrap.addClass('card-progress');
        },
        success: function (result) {
            let {
                data,gate
            } = result;
            for (let n in data) {
                // console.log(data[n]);
                switch (n) {
                    case 'id':
                        $('[name="id"]').attr("data-id", data[n]).change();
                        break;
                    case 'jml_order':
                        $('.'+n).html(data[n]).change();
                        break;
                    case 'status':
                        $('.'+n).html(data[n]).change();
                        break;
                    default:
                        $('.' + n).text(data[n]).change();
                        break;
                }
            }
        },
        error: function (err) {
            notifToast("error", "Terjadi kesalahan!");
        },
        complete: function () {
            cardWrap.removeClass('card-progress');
        }

    })
}
