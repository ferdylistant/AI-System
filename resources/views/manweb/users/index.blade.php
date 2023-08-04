@extends('layouts.app')

@section('cssRequired')
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/rowreorder/1.2.3/css/rowReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="{{ url('vendors/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/izitoast/dist/css/iziToast.min.css') }}">
    <style>
        .scrollbar-deep-purple::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
            background-color: #F5F5F5;
            border-radius: 10px;
        }

        .scrollbar-deep-purple::-webkit-scrollbar {
            width: 12px;
            background-color: #F5F5F5;
        }

        .scrollbar-deep-purple::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
            background-color: #6777EF;
        }

        .scrollbar-deep-purple {
            scrollbar-color: #6777EF #F5F5F5;
        }

        .bordered-deep-purple::-webkit-scrollbar-track {
            -webkit-box-shadow: none;
            border: 1px solid #6777EF;
        }

        .bordered-deep-purple::-webkit-scrollbar-thumb {
            -webkit-box-shadow: none;
        }

        .square::-webkit-scrollbar-track {
            border-radius: 0 !important;
        }

        .square::-webkit-scrollbar-thumb {
            border-radius: 0 !important;
        }

        .thin::-webkit-scrollbar {
            width: 6px;
        }

        .example-1 {
            position: relative;
            overflow-y: scroll;
            height: 200px;
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Users</h1>
            <div class="section-header-button">
                <a href="javascript:void(0)" class="btn btn-success" data-toggle="modal"
                    data-target="#md_AddUser">Tambah</a>
            </div>
            <div class="section-header-button">
                <a href="{{ route('user.telah_dihapus') }}" class="btn btn-danger">User Telah Dihapus</a>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped" id="tb_Users">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <div id="md_AddUser" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success">
                </div>

                <form id="fm_AddUser">
                    <div class="modal-body">
                        <h5 class="modal-title mb-3">#Tambah User</h5>
                        <div class="form-group">
                            <label for="adduser_nama">Nama: <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="adduser_nama" placeholder="Nama User" required>
                            <div id="err_adduser_nama"></div>
                        </div>
                        <div class="form-group">
                            <label for="adduser_email">Email: <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="adduser_email" placeholder="Alamat Email"
                                required>
                            <div id="err_adduser_email"></div>
                        </div>
                        <div class="form-group">
                            <label for="adduser_password">Password: </label>
                            <input class="form-control" type="text" name="adduser_password" placeholder="Password User">
                            <div id="err_adduser_password"></div>
                        </div>
                        <div class="form-group">
                            <label for="adduser_cabang">Cabang: <span class="text-danger">*</span></label>
                            <select id="adduser_cabang" class="form-control select2" name="adduser_cabang" required>
                                <option label="Pilih"></option>
                                @foreach ($lcabang as $v)
                                    <option value="{{ $v->id }}">{{ $v->nama }}</option>
                                @endforeach
                            </select>
                            <div id="err_adduser_cabang"></div>
                        </div>
                        <div class="form-group">
                            <label for="adduser_divisi">Divisi: <span class="text-danger">*</span></label>
                            <select id="adduser_divisi" class="form-control select2" name="adduser_divisi" required>
                                <option label="Pilih"></option>
                                @foreach ($ldivisi as $v)
                                    <option value="{{ $v->id }}">{{ $v->nama }}</option>
                                @endforeach
                            </select>
                            <div id="err_adduser_divisi"></div>
                        </div>
                        <div class="form-group">
                            <label for="adduser_jabatan">Jabatan: <span class="text-danger">*</span></label>
                            <select id="adduser_jabatan" class="form-control select2" name="adduser_jabatan" required>
                                <option label="Pilih"></option>
                                @foreach ($ljabatan as $v)
                                    <option value="{{ $v->id }}">{{ $v->nama }}</option>
                                @endforeach
                            </select>
                            <div id="err_adduser_jabatan"></div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Format Buku -->
    @include('manweb.users.modal_history_user')
@endsection

@section('jsRequired')
    <script src="{{ url('vendors/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('vendors/datatables.net-select-bs4/js/select.bootstrap4.min.js') }}"></script>
    <script src="{{ url('vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ url('vendors/jquery-validation/dist/jquery.validate.js') }}"></script>
    <script type="text/javascript" charset="utf8" src="{{ url('vendors/datatables.net-bs4/js/dataTables.input.plugin.js') }}"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/rowreorder/1.2.3/js/dataTables.rowReorder.min.js"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script>
@endsection

@section('jsNeeded')
    <script>
        $(function() {
            // Initial
            $(".select2").select2({
                placeholder: 'Pilih',
            }).on('change', function() {
                $(this).valid();
            });


            let tableUsers = $('#tb_Users').DataTable({
                "responsive": true,
                "autoWidth": true,
                "order": [
                    [1, "asc"]
                ],
                "pagingType": "input",
                processing: true,
                serverSide: true,
                language: {
                    searchPlaceholder: 'Search...',
                    sSearch: '',
                    lengthMenu: '_MENU_ items/page',
                },
                ajax: {
                    url: "{{ url('manajemen-web/users') }}",
                    data: {
                        "request_": "table-users"
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        title: 'No',
                        orderable: false,
                        searchable: false,
                        "width": "5%"
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        title: 'Nama'
                    },
                    {
                        data: 'email',
                        name: 'email',
                        title: 'Email'
                    },
                    {
                        data: 'cabang',
                        name: 'cabang',
                        sDefaultContent: '-',
                        title: 'Cabang'
                    },
                    {
                        data: 'bagian',
                        name: 'bagian',
                        sDefaultContent: '-',
                        title: 'Bagian'
                    },
                    {
                        data: 'status_activity',
                        name: 'status_activity',
                        title: 'Aktivitas'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        title: 'Status'
                    },
                    {
                        data: 'history',
                        name: 'history',
                        title: 'History'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        title: 'Action',
                        searchable: false,
                        orderable: false
                    },
                ],
            });

            let addUser = jqueryValidation_('#fm_AddUser', {
                adduser_email: {
                    required : true,
                    email: true,
                    remote: window.location.origin + "/manajemen-web/user/ajax/check-email"
                }
            },
            {
                adduser_email: {
                    remote: "The email already in use."
                }
            });

            function resetForm(form) {
                console.log(form)
            }

            function ajaxAddUser(data) {
                let el = data.get(0);

                $.ajax({
                    type: "POST",
                    url: "{{ url('manajemen-web/user/ajax/create/') }}",
                    data: new FormData(el),
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $("#md_AddUser").addClass('modal-progress');
                    },
                    success: function(data) {
                        console.log(data);
                        $('#fm_AddUser').trigger('reset');
                        $("#md_AddUser").modal("hide");
                        tableUsers.ajax.reload();
                        notifToast(data.status, data.message);
                    },
                    error: function(err) {
                        notifToast('error', 'Data user gagal ditambahkan!');
                        rs = err.responseJSON.errors;
                        err = {};
                        Object.entries(rs).forEach(entry => {
                            let [key, value] = entry;
                            err[key] = value
                        })
                        addUser.showErrors(err);
                    },
                    complete: function() {
                        $("#md_AddUser").removeClass('modal-progress');
                    }
                })
            }

            function ajaxDeleteUser(data) {
                $.ajax({
                    type: "POST",
                    url: "{{ url('/manajemen-web/user/ajax/delete/') }}",
                    data: data,
                    beforeSend: function() {
                        $('.btn_DelUser').prop('disabled', true).addClass('btn-progress')
                    },
                    success: function(result) {
                        // console.log(result)
                        notifToast('success', 'User (' + data.nama + ') berhasil dihapus!');
                        tableUsers.ajax.reload();
                    },
                    error: function(err) {},
                    complete: function() {
                        $('.btn_DelUser').prop('disabled', true).removeClass('btn-progress')
                    }
                })
            }

            $('#fm_AddUser').on('submit', function(e) {
                e.preventDefault();
                if ($(this).valid()) {
                    swal({
                            text: 'Buat User Baru?',
                            icon: 'warning',
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((confirm) => {
                            if (confirm) {
                                ajaxAddUser($(this));
                            }
                        });
                }
            });

            $('#tb_Users').on('click', '.btn_DelUser', function(e) {
                e.preventDefault();
                let nama = $(this).data('nama'),
                    id = $(this).data('id');

                swal({
                        text: 'Hapus data user (' + nama + ')?',
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((confirm_) => {
                        if (confirm_) {
                            ajaxDeleteUser({
                                id: id,
                                nama: nama
                            });
                        }
                    });
            });
            function ajaxResetDefaultPasswordUser(id,nama) {
                let cardWrap = $(this).closest(".card");
                $.ajax({
                    url: "{{url('/manajemen-web/user/ajax/reset-default-password/')}}",
                    data: {
                        id: id
                    },
                    cache: false,
                    beforeSend: function () {
                        cardWrap.addClass("card-progress");
                    },
                    success: function (result) {
                        notifToast('success', 'Password user (' + nama + ') berhasil direset default!');
                        tableUsers.ajax.reload();
                    },
                    error: function (err) {
                        notifToast("error", "Terjadi kesalahan!");
                    },
                    complete: function () {
                        cardWrap.removeClass('card-progress');
                    }
                })
            }
            tableUsers.on("click", ".btn_ResetDefault", function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let nama = $(this).data('nama');
                swal({
                    title: 'Reset password '+nama+'?',
                    text: 'Password akan direset default, yaitu "password".',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                    })
                    .then((confirm) => {
                        if (confirm) {
                            ajaxResetDefaultPasswordUser(id,nama);

                        }
                    });
            });
            // History User Start
            tableUsers.on("click", ".btn-history", function(e) {
                e.preventDefault();
                var id = $(this).data("id");
                var judul = $(this).data("nama");
                $.post(
                    window.location.origin + "/manajemen-web/user/lihat-history", {
                        id: id,
                    },
                    function(data) {
                        $("#titleModalUser").html(
                            '<i class="fas fa-history"></i>&nbsp;History Perubahan User "' +
                            judul +
                            '"'
                        );
                        $("#load_more").data("id", id);
                        $("#dataHistory").html(data);
                        $("#md_UserHistory").modal("show");
                    }
                );
            });
            $(".load-more").click(function(e) {
                e.preventDefault();
                var page = $(this).data("paginate");
                var id = $(this).data("id");
                let form = $("#md_UserHistory");
                $(this).data("paginate", page + 1);

                $.ajax({
                    url: window.location.origin + "/manajemen-web/user/lihat-history",
                    data: {
                        id: id,
                        page: page,
                    },
                    type: "post",
                    cache: false,
                    beforeSend: function() {
                        form.addClass("modal-progress");
                    },
                    success: function(response) {
                        if (response.length == 0) {
                            $(".load-more").attr("disabled", true);
                            notifToast("error", "Tidak ada data lagi");
                        } else {
                            $("#dataHistory").append(response);
                            $('.thin').animate({scrollTop: $('.thin').prop("scrollHeight")}, 800);
                        }
                        // Setting little delay while displaying new content
                        // setTimeout(function() {
                        //     // appending posts after last post with class="post"
                        //     $("#dataHistory:last").htnl(response).show().fadeIn("slow");
                        // }, 2000);
                    },
                    complete: function(params) {
                        form.removeClass("modal-progress");
                    },
                });
            });
            // History User End
            $("#md_UserHistory").on("hidden.bs.modal", function() {
                $(".load-more").data("paginate", 2);
                $(".load-more").attr("disabled", false);
            });
        });
    </script>
@endsection
