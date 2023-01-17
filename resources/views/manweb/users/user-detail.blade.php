@extends('layouts.app')

@section('cssRequired')
<link rel="stylesheet" href="{{url('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css')}}">
<link rel="stylesheet" href="{{url('vendors/select2/dist/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ url('vendors/hummingbird-treeview/hummingbird-treeview.css') }}">
<link rel="stylesheet" href="{{url('vendors/izitoast/dist/css/iziToast.min.css')}}">
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        @if($btnPrev)
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
                @if(Auth::user()->can('do_update', 'ubah-data-user') OR Auth::id() == $user->id)
                    @include('manweb.users.fedit-data-user')
                @else
                <div class="profile-widget-header">
                    <img alt="image" src="{{url('storage/users/'.$user->id.'/'.$user->avatar)}}" class="rounded-circle profile-widget-picture">
                </div>
                <div class="profile-widget-description">
                    <div class="row">
                        <div class="form-group col-12 mb-4">
                            <label>Nama Lengkap: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{$user->nama}}" placeholder="Nama Lengkap" readonly>
                        </div>
                        <div class="form-group col-12 col-md-6 mb-4">
                            <label>Tanggal Lahir: </label>
                            <input type="text" class="form-control datepicker" value="{{$user->tanggal_lahir}}" placeholder="Hari Bulan Tahun" readonly>
                        </div>
                        <div class="form-group col-12 col-md-6 mb-4">
                            <label>Tempat Lahir: </label>
                            <input type="text" class="form-control" value="{{$user->tempat_lahir}}" placeholder="Kota / Daerah Kelahiran" readonly>
                        </div>
                        <div class="form-group col-12 mb-4">
                            <label>Alamat Lengkap: </label>
                            <textarea class="form-control" placeholder="Alamat Lengkap" readonly>{{$user->alamat}}</textarea>
                        </div>
                        <div class="form-group col-12 col-md-6 mb-4">
                            <label>Alamat Email: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{$user->email}}" placeholder="Email Aktif" readonly>
                        </div>
                        <div class="form-group col-12 col-md-6 mb-4">
                            <label>Cabang: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{$user->cabang}}" readonly>
                        </div>
                        <div class="form-group col-12 col-md-6 mb-4">
                            <label>Divisi: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{$user->divisi}}" readonly>
                        </div>
                        <div class="form-group col-12 col-md-6 mb-4">
                            <label>Jabatan: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{$user->jabatan}}" readonly>
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
                            <a class="nav-link active" id="access-tab" data-toggle="tab" href="#access" role="tab"
                                aria-controls="access" aria-selected="true">Access Menu</a>
                        </li>
                        @endcan

                        @if(auth()->id() == $user->id)
                        <li class="nav-item">
                            <a class="nav-link {{Auth::user()->cannot('do_update', 'ubah-data-user')?'active':''}}"
                                id="password-tab" data-toggle="tab" href="#password" role="tab" aria-controls="password" aria-selected="false">Password</a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link"
                                id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact"
                                aria-selected="false">Contact</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        @can('do_update', 'ubah-data-user')
                            @include('manweb.users.fadd-accessmenu')
                        @endcan

                        <div class="tab-pane fade {{Auth::user()->cannot('do_update', 'ubah-data-user')?'show active':''}}" id="password" role="tabpanel" aria-labelledby="password-tab">
                            <form id="form_UpdatePassword">
                            <div class="row">
                                <div class="form-group col-12 mb-4">
                                    <label>Password Lama: <span class="text-danger">*</span></label>
                                    <input type="hidden" name="uedit_pwd_id" value="{{$user->id}}">
                                    <input type="password" class="form-control" name="uedit_pwd_old" placeholder="Password Lama" required>
                                    <div id="err_uedit_pwd_old"></div>
                                </div>
                                <div class="form-group col-12 mb-4">
                                    <label>Password Baru: <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="uedit_pwd_new" placeholder="Password Baru" required>
                                    <div id="err_uedit_pwd_new"></div>
                                </div>
                                <div class="form-group col-12 mb-4">
                                    <label>Password Baru (Confirm): <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="uedit_pwd_confirm" placeholder="Konfirmasi Password Baru" required>
                                    <div id="err_uedit_pwd_confirm"></div>
                                </div>
                            </div>
                            <div class="row float-right">
                                <button type="submit" class="btn btn-sm btn-warning">Simpan</button>
                            </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            Sed sed metus vel lacus hendrerit tempus. Sed efficitur velit tortor, ac efficitur est lobortis quis. Nullam lacinia metus erat, sed fermentum justo rutrum ultrices. Proin quis iaculis tellus. Etiam ac vehicula eros, pharetra consectetur dui. Aliquam convallis neque eget tellus efficitur, eget maximus massa imperdiet. Morbi a mattis velit. Donec hendrerit venenatis justo, eget scelerisque tellus pharetra a.
                        </div>
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            Vestibulum imperdiet odio sed neque ultricies, ut dapibus mi maximus. Proin ligula massa, gravida in lacinia efficitur, hendrerit eget mauris. Pellentesque fermentum, sem interdum molestie finibus, nulla diam varius leo, nec varius lectus elit id dolor. Nam malesuada orci non ornare vulputate. Ut ut sollicitudin magna. Vestibulum eget ligula ut ipsum venenatis ultrices. Proin bibendum bibendum augue ut luctus.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</section>
@endsection


@section('jsRequired')
<script src="{{url('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')}}"></script>
<script src="{{url('vendors/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{ url('vendors/hummingbird-treeview/hummingbird-treeview.js') }}"></script>
<script src="{{url('vendors/jquery-validation/dist/jquery.validate.js')}}"></script>
<script src="{{url('vendors/jquery-validation/dist/additional-methods.min.js')}}"></script>
<script src="{{url('vendors/sweetalert/dist/sweetalert.min.js')}}"></script>
<script src="{{url('vendors/izitoast/dist/js/iziToast.min.js')}}"></script>
@endsection

@section('jsNeeded')
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
        if(this.value) {
            $(this).valid();
        }
    });

    let editUser = jqueryValidation_('#fm_EditUser', {
        uedit_pp: { extension: "jpg,jpeg,png", maxsize: 300000, },
    });
    function ajaxEditUser(data) {
        let el = data.get(0);

        $.ajax({
            type: "POST",
            url: "{{url('manajemen-web/user/ajax/update/')}}",
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
                if(rs !== undefined) {
                    err = {};
                    Object.entries(rs).forEach(entry => {
                        let [key, value] = entry;
                        err[key] = value
                    })
                    editUser.showErrors(err);
                }
                notifToast('error', 'Data user gagal diubah!');
            },
            complete: function() {
                data.closest('.card').removeClass('card-progress');
            }
        })

    }

    $('.profile-widget').on('change', '[name="uedit_pp"]', function(e) {
        let file = e.currentTarget.files[0];
        $('#container-pp img').attr('src', URL.createObjectURL(file));
    })
    $('.profile-widget').on('submit', '#fm_EditUser', function(e) {
        e.preventDefault();
        if($(this).valid()) {
            swal({
                text: 'Simpan Perubahan?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm) => {
                if(confirm) {
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
                    type:'POST',
                    url: "{{ url('manajemen-web/user/ajax/update-access') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(data){
                        console.log(data);
                        notifToast('success', 'Akses berhasil diperbarui.');
                    },
                    error: function(err){
                        console.log(err)
                        notifToast('success', 'Akses gagal diperbarui.');
                    }
                });
            }
        });
    })

    let editPassword = jqueryValidation_('#form_UpdatePassword', {
        uedit_pwd_old: {minlength: 6},
        uedit_pwd_new: {minlength: 6},
        uedit_pwd_confirm: {minlength: 6, equalTo: "[name='uedit_pwd_new']" }
    });
    function ajaxEditPassword(data) {
        let el = data.get(0);

        $.ajax({
            type: "POST",
            url: "{{url('manajemen-web/user/ajax/update-password/')}}",
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
                if(rs !== undefined) {
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
        if($(this).valid()) {
            swal({
                text: 'Ubah Password?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm) => {
                if(confirm) {
                    ajaxEditPassword($(this));
                }
            });
        }
    })
});
</script>
@endsection
