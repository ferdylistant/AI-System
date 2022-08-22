$(document).ready(function() {
    var authSess = $('#authSession').val();
    var dirop = $('#dirop').val();
    var dirke = $('#dirke').val();
    var dirut = $('#dirut').val();
    var dirop_act = $('#dirop_act').val();
    var dirke_act = $('#dirke_act').val();
    var dirut_act = $('#dirut_act').val();
    var id = $('#id').val();
    var jb = $('#judul_buku').val();
    var ko = $('#kode_order').val();
    var sc = $('#statusCetak').val();
    var kp = $('#ketVal').val();
    var ps = $('#pendingSampai').val();
    $('#btn-pending').on('click', function() {
        $('#titleModal').html('Konfirmasi Persetujuan Pending');
        $('#id_').val(id);
        $('#kode_Order').val(ko);
        $('#judul_Buku').val(jb);
        $('#judulBuku').text('#'+ko+'-'+jb);
        $('#status_cetak').val(sc);
        if(!kp || !ps){
            $('#contentData').html("<div class='form-group mb-4'><label for='pending_sampai' class='col-form-label'>Pending Sampai Tanggal</label><div class='input-group'><div class='input-group-prepend'><div class='input-group-text'><i class='fas fa-calendar-alt'></i></div></div><input type='text' class='form-control datepicker' id='pending_sampai' name='pending_sampai' readonly required></div><div class='form-group'><label for='keterangan' class='col-form-label'>Alasan</label><textarea class='form-control' name='keterangan' id='keterangan' rows='4'></textarea></div>");
            $('#footerDecline').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Konfirmasi</button>');
        } else {
            if(authSess == dirop){
                if(dirop_act == '2'){
                    $('#contentData').html("<div class='form-group mb-4'><label for='pending_sampai'>Pending Sampai Tanggal:</label><p id='pending_sampai'>"+ps+"</p></div><div class='form-group mb-4'><label for='keterangan'>Alasan:</label><p id='keterangan'>"+kp+"</p></div><div class='form-group mb-4'><a href='"+window.location.origin+"/batal/pending/order-cetak/"+id+"' id='batalkan-pending' class='btn btn-danger'>Batalkan Pending</a></div>");
                } else {
                    $('#contentData').html("<div class='form-group mb-4'><label for='pending_sampai'>Pending Sampai Tanggal:</label><p id='pending_sampai'>"+ps+"</p></div><div class='form-group mb-4'><label for='keterangan'>Alasan:</label><p id='keterangan'>"+kp+"</p></div>");
                }
            } else if(authSess == dirke){
                if(dirke_act == '2'){
                    $('#contentData').html("<div class='form-group mb-4'><label for='pending_sampai'>Pending Sampai Tanggal:</label><p id='pending_sampai'>"+ps+"</p></div><div class='form-group mb-4'><label for='keterangan'>Alasan:</label><p id='keterangan'>"+kp+"</p></div><div class='form-group mb-4'><a href='"+window.location.origin+"/batal/pending/order-cetak/"+id+"' id='batalkan-pending' class='btn btn-danger'>Batalkan Pending</a></div>");
                } else {
                    $('#contentData').html("<div class='form-group mb-4'><label for='pending_sampai'>Pending Sampai Tanggal:</label><p id='pending_sampai'>"+ps+"</p></div><div class='form-group mb-4'><label for='keterangan'>Alasan:</label><p id='keterangan'>"+kp+"</p></div>");
                }
            } else if(authSess == dirut){
                if(dirut_act == '2'){
                    $('#contentData').html("<div class='form-group mb-4'><label for='pending_sampai'>Pending Sampai Tanggal:</label><p id='pending_sampai'>"+ps+"</p></div><div class='form-group mb-4'><label for='keterangan'>Alasan:</label><p id='keterangan'>"+kp+"</p></div><div class='form-group mb-4'><a href='"+window.location.origin+"/batal/pending/order-cetak/"+id+"' id='batalkan-pending' class='btn btn-danger'>Batalkan Pending</a></div>");
                } else {
                    $('#contentData').html("<div class='form-group mb-4'><label for='pending_sampai'>Pending Sampai Tanggal:</label><p id='pending_sampai'>"+ps+"</p></div><div class='form-group mb-4'><label for='keterangan'>Alasan:</label><p id='keterangan'>"+kp+"</p></div>");
                }
            } else {
                $('#contentData').html("<div class='form-group mb-4'><label for='pending_sampai'>Pending Sampai Tanggal:</label><p id='pending_sampai'>"+ps+"</p></div><div class='form-group mb-4'><label for='keterangan'>Alasan:</label><p id='keterangan'>"+kp+"</p></div>");
            }
            $('#footerDecline').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>');
        }
        $('.datepicker').datepicker({
            format: 'dd MM yyyy',
            startDate: '-10d',
            autoclose:true,
            clearBtn: true,
            todayHighlight: true
        });
        $('#modalDecline').modal('show');
        $(document).on('click', '#batalkan-pending',function(e){
            e.preventDefault();
            var getLink = $(this).attr('href');
            swal({
                title: 'Apakah anda yakin?',
                text:  'Pembatalan pending akan meneruskan proses penyetujuan',
                type: 'warning',
                html: true,
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm_) => {
                if (confirm_) {
                    window.location.href = getLink
                }
            });
        });
    });
});
$(function() {
function resetFrom(form) {
    form.trigger('reset');
        $('[name="pending_sampai"]').val('').trigger('change');
        $('[name="keterangan"]').val('').trigger('change');
    }
    let addForm = jqueryValidation_('#fadd_Keterangan', {
        pending_sampai: {required: true},
        keterangan: {required: true},
    });

    function ajaxDeclineProduksi(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/penerbitan/order-cetak/ajax/pending",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).
                    addClass('btn-progress')
            },
            success: function(result) {
                resetFrom(data);
                if(result.status == 'success') {
                    notifToast(result.status, result.message);
                    location.reload();
                } else {
                    $('#modalPending').modal('hide');
                    notifToast(result.status, result.message);
                }
            },
            error: function(err) {
                // console.log(err.responseJSON)
                rs = err.responseJSON.errors;
                if(rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach(entry => {
                        let [key, value] = entry;
                        err[key] = value
                    })
                    addForm.showErrors(err);
                }
                notifToast('error', 'Gagal melakukan penolakkan!');
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).
                    removeClass('btn-progress')
            }
        })
    }

    $('#fadd_Keterangan').on('submit', function(e) {
        e.preventDefault();
        if($(this).valid()) {
            let kode = $(this).find('[name="kode_order"]').val();
            let judul = $(this).find('[name="judul_buku"]').val();
            swal({
                title: 'Yakin order cetak #'+kode+'-'+judul+' dipending?',
                    text: 'Setelah tanggal dipending selesai, Anda dapat menyetujui kembali data yang sudah Anda pending',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
            })
            .then((confirm_) => {
                if (confirm_) {
                    ajaxDeclineProduksi($(this))
                }
            });

        }
    })
});
