@extends('layouts.app')

@section('cssRequired')
    <link rel="stylesheet" href="{{ asset('vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/rowreorder/1.2.3/css/rowReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('vendors/jquery-magnify/dist/jquery.magnify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/hummingbird-treeview/hummingbird-treeview.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/izitoast/dist/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.css" />
    <style>
        .indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #808080;
            /* default color for offline */
        }

        .indicator.away {
            background-color: #DAA520;
            /* color for away */
        }

        .indicator.online {
            background-color: #008000;
            /* color for online */
        }

        #container-pp {
            position: relative;
            margin: -35px -5px 0 30px;
            width: 100px;
            height: 100px;
        }

        .profile-widget .profile-widget-picture {
            margin: 0 !important;
        }

        #btn-pp {
            background-color: #F8F9FA;
            border-radius: 50%;
            z-index: 1;
            position: absolute;
            right: 0;
            bottom: 0;
        }

        #btn-pp:hover {
            background-color: #999999;
        }

        #btn-pp input {
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: 1;
            width: 20px;
            height: 20px;
        }
    </style>
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
                    <div class="card card-primary profile-widget mt-0">
                        <div id="loadActionUser" class="card-body">
                            <ul class="nav nav-tabs" id="myTabUser" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="info-personal-tab" data-toggle="tab"
                                        data-typeget="info-personal" href="#info-personal" role="tab"
                                        aria-controls="info-personal" aria-selected="true">Info
                                        Personal</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="info-pekerjaan-tab" data-toggle="tab"
                                        data-typeget="info-pekerjaan" href="#info-pekerjaan" role="tab"
                                        aria-controls="info-pekerjaan" aria-selected="true">Info
                                        Pekerjaan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="kontak-darurat-tab" data-toggle="tab"
                                        data-typeget="kontak-darurat" href="#kontak-darurat" role="tab"
                                        aria-controls="kontak-darurat" aria-selected="false">Info
                                        Kontak Darurat</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pendidikan-pengalaman-tab" data-toggle="tab"
                                        data-typeget="pendidikan-pengalaman" href="#pendidikan-pengalaman" role="tab"
                                        aria-controls="pendidikan-pengalaman" aria-selected="false">Pendidikan &
                                        Pengalaman</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="info-payroll-tab" data-toggle="tab" data-typeget="info-payroll"
                                        href="#info-payroll" role="tab" aria-controls="info-payroll"
                                        aria-selected="false">Info Payroll</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContentUser">
                                <div class="tab-pane fade show active" id="info-personal" role="tabpanel"
                                    aria-labelledby="info-personal-tab">
                                    @include('manweb.users.fedit-data-user')
                                </div>
                                <div class="tab-pane fade" id="info-pekerjaan" role="tabpanel"
                                    aria-labelledby="info-pekerjaan-tab">
                                    @include('manweb.users.fedit-data-pekerjaan')
                                </div>
                                <div class="tab-pane fade" id="kontak-darurat" role="tabpanel"
                                    aria-labelledby="kontak-darurat-tab">
                                    @include('manweb.users.fedit-data-kontak-darurat')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="card card-primary">
                        <div id="loadAction" class="card-body">
                            <ul class="nav nav-tabs show-nav-setting" id="myTab" role="tablist">
                                @can('do_update', 'ubah-data-user')
                                    <li class="nav-item">
                                        <a class="nav-link active" id="access-tab" data-toggle="tab"
                                            data-typeget="access-menu" href="#access" role="tab" aria-controls="access"
                                            aria-selected="true">Access Menu</a>
                                    </li>
                                @endcan
                                <li class="nav-item">
                                    <a class="nav-link" id="user-log-tab" data-toggle="tab" data-typeget="user-log"
                                        href="#log_user" role="tab" aria-controls="log_user"
                                        aria-selected="true">User log</a>
                                </li>
                                @if (auth()->id() == $user->id)
                                    <li class="nav-item">
                                        <a class="nav-link {{ Auth::user()->cannot('do_update', 'ubah-data-user') ? 'active' : '' }}"
                                            id="password-tab" data-toggle="tab" data-typeget="password" href="#password"
                                            role="tab" aria-controls="password" aria-selected="false">Password</a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link" id="user-status-tab" data-toggle="tab"
                                        data-typeget="user-status" href="#user-status" role="tab"
                                        aria-controls="user-status" aria-selected="false">User status</a>
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
                                                        value="{!! $user->id !!}">
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
                                <div class="tab-pane fade mt-3" id="user-status" role="tabpanel"
                                    aria-labelledby="user-status-tab">
                                    <div class="custom-control custom-switch"
                                        {{ Auth::user()->cannot('do_update', 'ubah-data-user') ? 'hidden' : '' }}>
                                        <input type="checkbox" name="proses" class="custom-control-input"
                                            id="userStatus" data-id="{!! $user->id !!}"
                                            {{ $user->status == 1 ? 'checked' : '' }}>
                                        <label class="custom-control-label mr-3 text-dark" for="userStatus">
                                            Ubah Status
                                        </label>
                                    </div>
                                    <span class="badge badge-{{ $queryStatus == 1 ? 'success' : 'danger' }}"
                                        id="statusShow">{{ $userStatus }}</span>
                                    <table class="table table-striped my-3">
                                        <tr>
                                            <th>Dibuat Pada</th>
                                            <td>:</td>
                                            <td>{{ $user->created_at }}</td>
                                        </tr>
                                        <tr>
                                            <th>Dibuat Oleh</th>
                                            <td>:</td>
                                            <td>{{ $user->created_by }}</td>
                                        </tr>
                                        <tr>
                                            <th>Terakhir diubah Pada</th>
                                            <td>:</td>
                                            <td>{{ $user->updated_at }}</td>
                                        </tr>
                                        <tr>
                                            <th>Terakhir diubah Oleh</th>
                                            <td>:</td>
                                            <td>{{ $user->updated_by }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- This is the modal -->
<div class="modal" tabindex="-1" role="dialog" aria-labelledby="titleCrop" aria-hidden="true"
id="uploadimageModal">
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
            <button type="button" class="btn btn-primary crop_image"><i class="fa fa-crop"></i> Crop and
                Save</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
</div>
@endsection


@section('jsRequired')
    <script type="text/javascript" src="{{ asset('vendors/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}">
    </script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/rowreorder/1.2.3/js/dataTables.rowReorder.min.js"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendors/jquery-magnify/dist/jquery.magnify.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/hummingbird-treeview/hummingbird-treeview.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/jquery-validation/dist/jquery.validate.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/jquery-validation/dist/additional-methods.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
@endsection

@section('jsNeeded')
    <script type="text/javascript" src="{{ asset('js/manweb/user-info.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('js/manweb/user-log.js') }}" defer></script>
@endsection
