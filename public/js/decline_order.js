$(document).ready(function() {
    var id = $('#id').val();
    $('#btn-decline').on('click', function() {
        $('#titleModal').html('Konfirmasi Penolakan');
        $('#id_').val(id);
        $('#modalDecline').modal('show');
    });
});
