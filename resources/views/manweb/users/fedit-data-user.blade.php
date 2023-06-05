<style>
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
<form id="fm_EditUser">
    <div class="profile-widget-header">
        <div id="container-pp">
            @php
                $words = explode(' ', $user->nama);
                $acronym = '';

                foreach ($words as $w) {
                    $acronym .= mb_substr($w, 0, 1);
                }
            @endphp
            <a href="{{ url('storage/users/' . $user->id . '/' . $user->avatar) }}" data-magnify="gallery">
                <img alt="{{ Str::upper($acronym) }}" src="{{ url('storage/users/' . $user->id . '/' . $user->avatar) }}"
                    class="rounded-circle profile-widget-picture image-output">
            </a>
            <div id="btn-pp" class="btn btn-sm">
                <input class="inputfile" type="file" name="uedit_pp" data-id="{{ $user->id }}" id="cover_image"
                    accept="image/*">
                <i class="fa fa-camera"></i>
            </div>
            <div id="err_uedit_pp" style="display: block;"></div>
        </div>
    </div>
    <div class="profile-widget-description">
        <div class="row">
            <div class="col-12 d-flex justify-content-start mb-4">
                @switch($user->status_activity)
                    @case('online')
                        @php
                            $text = 'Online';
                            $cls = 'online';
                        @endphp
                        @break
                    @case('away')
                        @php
                            $text = 'Away';
                            $cls = 'away';
                        @endphp
                        @break
                    @default
                        @php
                            $text = 'Offline';
                            $cls = '';
                        @endphp
                    @endswitch
                    <span class="indicator {{$cls}} mt-2 mr-1"></span>
                    <span><b>{{$text}}</b></span>
            </div>
            <div class="form-group col-12 mb-4">
                <label>Nama Lengkap: <span class="text-danger">*</span></label>
                <input type="hidden" name="uedit_id" value="{{ $user->id }}">
                <input type="text" class="form-control" name="uedit_nama" value="{{ $user->nama }}"
                    placeholder="Nama Lengkap" required>
                <div id="err_uedit_nama"></div>
            </div>
            <div class="form-group col-12 col-md-6 mb-4">
                <label>Tanggal Lahir: </label>
                <input type="text" class="form-control datepicker" name="uedit_tanggal_lahir"
                    value="{{ $user->tanggal_lahir }}" placeholder="Hari Bulan Tahun">
                <div id="err_uedit_tanggal_lahir"></div>
            </div>
            <div class="form-group col-12 col-md-6 mb-4">
                <label>Tempat Lahir: </label>
                <input type="text" class="form-control" name="uedit_tempat_lahir" value="{{ $user->tempat_lahir }}"
                    placeholder="Kota / Daerah Kelahiran">
                <div id="err_uedit_tempat_lahir"></div>
            </div>
            <div class="form-group col-12 mb-4">
                <label>Alamat Lengkap: </label>
                <textarea class="form-control" name="uedit_alamat" placeholder="Alamat Lengkap">{{ $user->alamat }}</textarea>
                <div id="err_uedit_alamat"></div>
            </div>
            <div class="form-group col-12 col-md-6 mb-4">
                <label>Alamat Email: <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="uedit_email" value="{{ $user->email }}"
                    placeholder="Email Aktif" required>
                <div id="err_uedit_email"></div>
            </div>

            @can('do_update', 'ubah-data-user')
                <div class="form-group col-12 col-md-6 mb-4">
                    <label>Cabang: <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="uedit_cabang" required>
                        <option label="Pilih"></option>
                        @foreach ($lcab as $c)
                            <option value="{{ $c->id }}" {{ $c->id == $user->cabang_id ? 'Selected' : '' }}>
                                {{ $c->nama }}</option>
                        @endforeach
                    </select>
                    <div id="err_uedit_cabang"></div>
                </div>
                <div class="form-group col-12 col-md-6 mb-4">
                    <label>Divisi: <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="uedit_divisi" required>
                        <option label="Pilih"></option>
                        @foreach ($ldiv as $d)
                            <option value="{{ $d->id }}" {{ $d->id == $user->divisi_id ? 'Selected' : '' }}>
                                {{ $d->nama }}</option>
                        @endforeach
                    </select>
                    <div id="err_uedit_divisi"></div>
                </div>
                <div class="form-group col-12 col-md-6 mb-4">
                    <label>Jabatan: <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="uedit_jabatan" required>
                        <option label="Pilih"></option>
                        @foreach ($ljab as $j)
                            <option value="{{ $j->id }}" {{ $j->id == $user->jabatan_id ? 'Selected' : '' }}>
                                {{ $j->nama }}</option>
                        @endforeach
                    </select>
                    <div id="err_uedit_jabatan"></div>
                </div>
            @else
                <div class="form-group col-12 col-md-6 mb-4">
                    <label>Cabang: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="{{ $user->cabang }}" disabled>
                </div>
                <div class="form-group col-12 col-md-6 mb-4">
                    <label>Divisi: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="{{ $user->divisi }}" disabled>
                </div>
                <div class="form-group col-12 col-md-6 mb-4">
                    <label>Jabatan: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="{{ $user->jabatan }}" disabled>
                </div>
            @endcan

        </div>
    </div>
    <div class="card-footer text-right">
        <button type="submit" class="btn btn-sm btn-warning">Simpan</button>
    </div>

</form>
