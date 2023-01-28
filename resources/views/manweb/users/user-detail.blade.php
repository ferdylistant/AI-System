@extends('layouts.app')

@section('cssRequired')
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/rowreorder/1.2.3/css/rowReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="{{ url('vendors/jquery-magnify/dist/jquery.magnify.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/hummingbird-treeview/hummingbird-treeview.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/izitoast/dist/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.css" />
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            @if ($btnPrev)
                <div class="section-header-back">
                    <button class="btn btn-icon" onclick="history.back()"><i class="fas fa-arrow-left"></i></button>
                </div>
            @endif
            <h1>Detail User</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="card card-dark profile-widget">
                        @if (Auth::user()->can('do_update', 'ubah-data-user') or Auth::id() == $user->id)
                            @include('manweb.users.fedit-data-user')
                        @else
                            @php
                                $words = explode(' ', $user->nama);
                                $acronym = '';

                                foreach ($words as $w) {
                                    $acronym .= mb_substr($w, 0, 1);
                                }
                            @endphp
                            <div class="profile-widget-header">
                                <a href="{{ url('storage/users/' . $user->id . '/' . $user->avatar) }}" data-magnify="gallery">
                                    <img alt="{{ Str::upper($acronym) }}"
                                    src="{{ url('storage/users/' . $user->id . '/' . $user->avatar) }}"
                                    class="rounded-circle profile-widget-picture image-output">
                                </a>
                            </div>
                            <div class="profile-widget-description">
                                <div class="row">
                                    <div class="form-group col-12 mb-4">
                                        <label>Nama Lengkap: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ $user->nama }}"
                                            placeholder="Nama Lengkap" readonly>
                                    </div>
                                    <div class="form-group col-12 col-md-6 mb-4">
                                        <label>Tanggal Lahir: </label>
                                        <input type="text" class="form-control datepicker"
                                            value="{{ $user->tanggal_lahir }}" placeholder="Hari Bulan Tahun" readonly>
                                    </div>
                                    <div class="form-group col-12 col-md-6 mb-4">
                                        <label>Tempat Lahir: </label>
                                        <input type="text" class="form-control" value="{{ $user->tempat_lahir }}"
                                            placeholder="Kota / Daerah Kelahiran" readonly>
                                    </div>
                                    <div class="form-group col-12 mb-4">
                                        <label>Alamat Lengkap: </label>
                                        <textarea class="form-control" placeholder="Alamat Lengkap" readonly>{{ $user->alamat }}</textarea>
                                    </div>
                                    <div class="form-group col-12 col-md-6 mb-4">
                                        <label>Alamat Email: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ $user->email }}"
                                            placeholder="Email Aktif" readonly>
                                    </div>
                                    <div class="form-group col-12 col-md-6 mb-4">
                                        <label>Cabang: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ $user->cabang }}" readonly>
                                    </div>
                                    <div class="form-group col-12 col-md-6 mb-4">
                                        <label>Divisi: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ $user->divisi }}" readonly>
                                    </div>
                                    <div class="form-group col-12 col-md-6 mb-4">
                                        <label>Jabatan: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ $user->jabatan }}" readonly>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="card card-primary">
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                @can('do_update', 'ubah-data-user')
                                    <li class="nav-item">
                                        <a class="nav-link active" id="access-tab" data-toggle="tab" href="#access"
                                            role="tab" aria-controls="access" aria-selected="true">Access Menu</a>
                                    </li>
                                @endcan
                                <li class="nav-item">
                                    <a class="nav-link" id="user-log-tab" data-toggle="tab" href="#log_user" role="tab"
                                        aria-controls="log_user" aria-selected="true">User log</a>
                                </li>
                                @if (auth()->id() == $user->id)
                                    <li class="nav-item">
                                        <a class="nav-link {{ Auth::user()->cannot('do_update', 'ubah-data-user') ? 'active' : '' }}"
                                            id="password-tab" data-toggle="tab" href="#password" role="tab"
                                            aria-controls="password" aria-selected="false">Password</a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link" id="user-status-tab" data-toggle="tab" href="#user-status"
                                        role="tab" aria-controls="user-status" aria-selected="false">User status</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                @can('do_update', 'ubah-data-user')
                                    @include('manweb.users.fadd-accessmenu')
                                @endcan
                                <div class="tab-pane fade " id="log_user" role="tabpanel"
                                    aria-labelledby="user-log-tab">
                                    <table class="table table-striped" id="tb_UserLog" style="width:100%"></table>
                                </div>

                                @if (auth()->id() == $user->id)
                                    <div class="tab-pane fade {{ Auth::user()->cannot('do_update', 'ubah-data-user') ? 'show active' : '' }}"
                                        id="password" role="tabpanel" aria-labelledby="password-tab">
                                        <form id="form_UpdatePassword">
                                            <div class="row">
                                                <div class="form-group col-12 mb-4">
                                                    <label>Password Lama: <span class="text-danger">*</span></label>
                                                    <input type="hidden" name="uedit_pwd_id"
                                                        value="{{ $user->id }}">
                                                    <input type="password" class="form-control" name="uedit_pwd_old"
                                                        placeholder="Password Lama" required>
                                                    <div id="err_uedit_pwd_old"></div>
                                                </div>
                                                <div class="form-group col-12 mb-4">
                                                    <label>Password Baru: <span class="text-danger">*</span></label>
                                                    <input type="password" class="form-control" name="uedit_pwd_new"
                                                        placeholder="Password Baru" required>
                                                    <div id="err_uedit_pwd_new"></div>
                                                </div>
                                                <div class="form-group col-12 mb-4">
                                                    <label>Password Baru (Confirm): <span
                                                            class="text-danger">*</span></label>
                                                    <input type="password" class="form-control" name="uedit_pwd_confirm"
                                                        placeholder="Konfirmasi Password Baru" required>
                                                    <div id="err_uedit_pwd_confirm"></div>
                                                </div>
                                            </div>
                                            <div class="row float-right">
                                                <button type="submit" class="btn btn-sm btn-warning">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                                <div class="tab-pane fade" id="user-status" role="tabpanel"
                                    aria-labelledby="user-status-tab">
                                    <span class="badge badge-success">{{ $userStatus }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- This is the modal -->
<div class="modal" tabindex="-1" role="dialog" aria-labelledby="titleCrop" aria-hidden="true" id="uploadimageModal">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleCrop"><span class="fas fa-crop"></span>&nbsp;Crop Photo Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <input type="hidden" name="id" id="idInModal" value="">
                        <div id="image_demo"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary crop_image"><i class="fa fa-crop"></i> Crop and Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection


@section('jsRequired')
    <script src="{{ url('vendors/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/rowreorder/1.2.3/js/dataTables.rowReorder.min.js"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script>
    <script src="{{ url('vendors/jquery-magnify/dist/jquery.magnify.min.js') }}"></script>
    <script src="{{ url('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ url('vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ url('vendors/hummingbird-treeview/hummingbird-treeview.js') }}"></script>
    <script src="{{ url('vendors/jquery-validation/dist/jquery.validate.js') }}"></script>
    <script src="{{ url('vendors/jquery-validation/dist/additional-methods.min.js') }}"></script>
    <script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
@endsection

@section('jsNeeded')
    <script src="{{ url('js/user-log.js') }}" defer></script>
    <script>
        $(document).ready(function() {

            // Initial
            $('#treeview').hummingbird();
            $('.datepicker').datepicker({
                format: 'dd MM yyyy'
            });

            $(".select2").select2({
                placeholder: 'Pilih',
            }).on('change', function(e) {
                if (this.value) {
                    $(this).valid();
                }
            });
            $('#uploadimageModal').on('hidden.bs.modal', function () {
                $('#image_demo').croppie('destroy');
            });
            $('#cover_image').on('change', function(){
                var id = $(this).data('id');
                $('#idInModal').val(id);
                $('#uploadimageModal').modal('show');
                var boundaryWidth = $('.modal-body').width();
                var boundaryHeight = boundaryWidth / 2;
                var viewportWidth = boundaryWidth - (boundaryWidth/100*50);
                var viewportHeight = boundaryHeight - (boundaryHeight/100*20);
                var image_crop = $('#image_demo').croppie({
                    viewport: {
                        width: viewportWidth,
                        height: viewportWidth,
                        type:'circle'
                    },
                    boundary:{
                        width: boundaryWidth,
                        height: boundaryHeight
                    }
                });
                var reader = new FileReader();
                reader.onload = function (event) {
                    image_crop.croppie('bind', {
                        url: event.target.result,
                    });
                }
                reader.readAsDataURL(this.files[0]);
                // $('#uploadimageModal').modal('show');
            });
            /// Get button click event and get the current crop image
            $('.crop_image').click(function(event){
                var formData = new FormData();
                let id = $('[name=id]').val();
                image_crop.croppie('result', {type: 'canvas',size: 'viewport', format: 'jpeg'|'png'|'webp'}).then(function(blob) {
                    formData.append('cropped_image', blob);
                    ajaxFormPost(formData, window.location.origin+"/manajemen-web/user/ajax/save-image/"+id); /// Calling my ajax function with my blob data passed to it
                });
                $('#uploadimageModal').modal('hide');
            });/// Ajax Function
            function ajaxFormPost(formData, actionURL){
                $.ajax({
                    url: actionURL,
                    type: 'POST',
                    data: formData,
                    cache: false,
                    async: true,
                    processData: false,
                    contentType: false,
                    timeout: 5000,
                    beforeSend: function () {
                        $("#overlay").fadeIn(300);
                    },
                    success: function(response) {
                        // console.log(formData);
                        // console.log(response);
                        if (response.status === 'success') {
                            $('.image-output').attr('src',response.path);
                        }
                        notifToast(response.status, response.message);
                    },
                    complete: function(){
                        setTimeout(function () {
                            $("#overlay").fadeOut(300);
                        }, 500);
                    }
                });
            }
            $('[data-magnify]').magnify({
                resizable: false,
                title: false,
                draggable: false,
                headerToolbar: [
                    'close'
                ],
            });
            // let editUser = jqueryValidation_('#fm_EditUser', {
            //     uedit_pp: {
            //         extension: "jpg,jpeg,png",
            //         maxsize: 300000,
            //     },
            // });

            function ajaxEditUser(data) {
                let el = data.get(0);
                console.log(el);
                $.ajax({
                    type: "POST",
                    url: "{{ url('manajemen-web/user/ajax/update/') }}",
                    data: new FormData(el),
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        data.closest('.card').addClass('card-progress');
                    },
                    success: function(data) {
                        console.log(data)
                        notifToast('success', 'Data user berhasil diubah!');
                    },
                    error: function(err) {
                        console.log(err)
                        rs = err.responseJSON.errors;
                        if (rs !== undefined) {
                            err = {};
                            Object.entries(rs).forEach(entry => {
                                let [key, value] = entry;
                                err[key] = value
                            })
                            // editUser.showErrors(err);
                        }
                        notifToast('error', 'Data user gagal diubah!');
                    },
                    complete: function() {
                        data.closest('.card').removeClass('card-progress');
                    }
                })

            }

            // $('.profile-widget').on('change', '[name="uedit_pp"]', function(e) {
            //     let file = e.currentTarget.files[0];
            //     $('#container-pp img').attr('src', URL.createObjectURL(file));
            // })
            $('.profile-widget').on('submit', '#fm_EditUser', function(e) {
                e.preventDefault();
                if ($(this).valid()) {
                    swal({
                            text: 'Simpan Perubahan?',
                            icon: 'warning',
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((confirm) => {
                            if (confirm) {
                                ajaxEditUser($(this));
                            }
                        });
                }
            });

            $('#form_UpdateAccess').on('submit', function(e) {
                e.preventDefault();
                swal({
                        text: 'Simpan Perubahan Akses?',
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((confirm) => {
                        if (confirm) {
                            $.ajax({
                                type: 'POST',
                                url: "{{ url('manajemen-web/user/ajax/update-access') }}",
                                data: new FormData(this),
                                processData: false,
                                contentType: false,
                                success: function(data) {
                                    console.log(data);
                                    notifToast('success', 'Akses berhasil diperbarui.');
                                },
                                error: function(err) {
                                    console.log(err)
                                    notifToast('success', 'Akses gagal diperbarui.');
                                }
                            });
                        }
                    });
            })

            let editPassword = jqueryValidation_('#form_UpdatePassword', {
                uedit_pwd_old: {
                    minlength: 6
                },
                uedit_pwd_new: {
                    minlength: 6
                },
                uedit_pwd_confirm: {
                    minlength: 6,
                    equalTo: "[name='uedit_pwd_new']"
                }
            });

            function ajaxEditPassword(data) {
                let el = data.get(0);

                $.ajax({
                    type: "POST",
                    url: "{{ url('manajemen-web/user/ajax/update-password/') }}",
                    data: new FormData(el),
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        data.closest('.card').addClass('card-progress');
                    },
                    success: function(data) {
                        notifToast('success', 'Password berhasil diubah!', true);
                    },
                    error: function(err) {
                        rs = err.responseJSON.errors;
                        if (rs !== undefined) {
                            err = {};
                            Object.entries(rs).forEach(entry => {
                                let [key, value] = entry;
                                err[key] = value
                            })
                            editPassword.showErrors(err);
                        }
                        notifToast('error', 'Password gagal diubah!');
                    },
                    complete: function() {
                        data.closest('.card').removeClass('card-progress');
                    }
                })
            }

            $('#form_UpdatePassword').on('submit', function(e) {
                e.preventDefault();
                if ($(this).valid()) {
                    swal({
                            text: 'Ubah Password?',
                            icon: 'warning',
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((confirm) => {
                            if (confirm) {
                                ajaxEditPassword($(this));
                            }
                        });
                }
            })
        });
    </script>
@endsection
