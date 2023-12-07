
$(function () {
    loadDataValue();
    function loadDataValue() {
        let id = window.location.search.split('?').pop();
        $.ajax({
            url: window.location.origin + "/penerbitan/order-cetak/detail?" + id,
            beforeSend: function () {
                var obj = [];

                $('p').each(function(i,val){

                    // var obj = ['no_order','jalur_proses'];
                    obj[i] = $(this).attr('id');
                });
                // obj.push('no_orderTop');
                $.each(obj,function (key,val){
                    new Loader(val).render([
                        // avatar shape: round, line, drawline
                        // ['line'],
                        // number of text lines
                        ['line*1',
                        {
                            // styles
                            style: [{
                            borderRadius: "5px",
                            height: "8px",
                            // width: "100%"
                            }]
                        }]
                        ],
                    );
                });
            },
            success: function (data) {
                for (let n in data) {
                    // console.log([n]);
                    switch (n) {
                        case 'proses':
                            if (data[n] != '') {
                                $('#buttonAct').html(data[n]).change();
                            }
                            break;
                        case 'jilid':
                            $('.' + n).text(data[n]['data']).change();
                            if (data[n]['hidden'] == true) {
                                $('#divBinding').attr('hidden','hidden').change();
                            } else {
                                $('#divBinding').removeAttr("hidden").change();
                            }
                            break;
                        case 'jumlah_cetak':
                            $('.' + n).html(data[n]+' eks').change();
                            break;
                        default:
                            $('.' + n).html(data[n]).change();
                            break;
                    }
                }
            },
            error: function (err) {
                console.log(err);
                notifToast("error", "Terjadi kesalahan!");
            },
            // complete: function () {
            //     cardWrap.removeClass('card-progress');
            // }

        }).done(function () {
            var obj = [];
            setTimeout(function () {
                $.each(obj,function (key,val){

                    $("#"+val).attr('id',false);
                });
            }, 500);
        });
    }
})
