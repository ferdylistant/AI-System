@if (Auth::user()->can('do_update', 'ubah-data-user'))
    <form id="fm_EditUser">
        {!! csrf_field() !!}
        <div class="profile-widget-header mt-5">
            <div id="container-pp">
                @php
                    $words = explode(' ', $user->nama);
                    $acronym = '';

                    foreach ($words as $w) {
                        $acronym .= mb_substr($w, 0, 1);
                    }
                @endphp
                <a href="{{ asset('storage/users/' . $user->id . '/' . $user->avatar) }}" data-magnify="gallery">
                    <img alt="{{ Str::upper($acronym) }}"
                        src="{{ asset('storage/users/' . $user->id . '/' . $user->avatar) }}"
                        class="rounded-circle profile-widget-picture image-output">
                </a>
                <div id="btn-pp" class="btn btn-sm">
                    <input class="inputfile" type="file" name="uedit_pp" data-id="{!! $user->id !!}"
                        id="cover_image" accept="image/*">
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
                    <span class="indicator {{ $cls }} mt-2 mr-1"></span>
                    <span><b>{{ $text }}</b></span>
                </div>
                <div class="form-group col-12 mb-4">
                    <label>Nama Lengkap: <span class="text-danger">*</span></label>
                    <input type="hidden" name="uedit_id" value="{!! $user->id !!}">
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
                    <input type="text" class="form-control" name="uedit_tempat_lahir"
                        value="{{ $user->tempat_lahir }}" placeholder="Kota / Daerah Kelahiran">
                    <div id="err_uedit_tempat_lahir"></div>
                </div>
                <div class="form-group col-12 mb-4">
                    <label>Alamat Lengkap: </label>
                    <textarea class="form-control" name="uedit_alamat" placeholder="Alamat Lengkap">{{ $user->alamat }}</textarea>
                    <div id="err_uedit_alamat"></div>
                </div>
                <div class="form-group col-12 col-md-12 mb-4">
                    <label>Alamat Email: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="uedit_email" value="{{ $user->email }}"
                        placeholder="Email Aktif" required>
                    <div id="err_uedit_email"></div>
                </div>
                <div class="form-group col-12 col-md-6 mb-4">
                    <label>Jenis kelamin: </label>
                    <select class="form-control select2" name="uedit_jenis_kelamin">
                        <option label="Pilih"></option>
                        @foreach ($jenisKelamin as $jk)
                            <option value="{{ $jk }}" {{ $jk == $user->jenis_kelamin ? 'Selected' : '' }}>{{$jk}}</option>
                        @endforeach
                    </select>
                    <div id="err_uedit_jenis_kelamin"></div>
                </div>
                <div class="form-group col-12 col-md-6 mb-4">
                    <label>Agama: </label>
                    <select class="form-control select2" name="uedit_agama">
                        <option label="Pilih"></option>
                        @foreach ($agamaList as $ag)
                            <option value="{{ $ag }}" {{ $ag == $user->agama ? 'Selected' : '' }}>{{$ag}}</option>
                        @endforeach
                    </select>
                    <div id="err_uedit_agama"></div>
                </div>
                <div class="form-group col-12 col-md-6 mb-4">
                    <label>Status Pernikahan: </label>
                    <select class="form-control select2" name="uedit_status_pernikahan">
                        <option label="Pilih"></option>
                        @foreach ($statusPernikahan as $sp)
                            <option value="{{ $sp }}" {{ $sp == $user->agama ? 'Selected' : '' }}>{{$sp}}</option>
                        @endforeach
                    </select>
                    <div id="err_uedit_status_pernikahan"></div>
                </div>
                <div class="form-group col-12 col-md-6 mb-4">
                    <label>Gol Darah: </label>
                    <input type="text" class="form-control" name="uedit_gol_darah" value="{{ $user->gol_darah }}" placeholder="Gol Darah">
                    <div id="err_uedit_gol_darah"></div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-sm btn-warning">Simpan</button>
        </div>

    </form>
@else
    @php
        $words = explode(' ', $user->nama);
        $acronym = '';

        foreach ($words as $w) {
            $acronym .= mb_substr($w, 0, 1);
        }
    @endphp
    <div class="profile-widget-header mt-5">
        <a href="{{ asset('storage/users/' . $user->id . '/' . $user->avatar) }}" data-magnify="gallery">
            <img alt="{{ Str::upper($acronym) }}"
                src="{{ asset('storage/users/' . $user->id . '/' . $user->avatar) }}"
                class="rounded-circle profile-widget-picture image-output">
        </a>
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
            <span class="indicator {{ $cls }} mt-2 mr-1"></span>
            <span><b>{{ $text }}</b></span>
        </div>
    </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item flex-column align-items-start">
                    <span class="text-small text-muted font-weight-bold">Nama Lengkap:</span>
                    <p class="mb-0">{{$user->nama ?? '-'}}</p>
                </li>
                <li class="list-group-item flex-column align-items-start">
                    <span class="text-small text-muted font-weight-bold">Tanggal Lahir:</span>
                    <p class="mb-0">{{ $user->tanggal_lahir ?? '-' }}</p>
                </li>
                <li class="list-group-item flex-column align-items-start">
                    <span class="text-small text-muted font-weight-bold">Tempat Lahir:</span>
                    <p class="mb-0">{{$user->tempat_lahir ?? '-'}}</p>
                </li>
                <li class="list-group-item flex-column align-items-start">
                    <span class="text-small text-muted font-weight-bold">Alamat Lengkap:</span>
                    <p class="mb-0">{{ $user->alamat ?? '-' }}</p>
                </li>
                <li class="list-group-item flex-column align-items-start">
                    <span class="text-small text-muted font-weight-bold">Email</span>
                    <p class="mb-0">{{ $user->email ?? '-' }}</p>
                </li>
                <li class="list-group-item flex-column align-items-start">
                    <span class="text-small text-muted font-weight-bold">Jenis kelamin</span>
                    <p class="mb-0">{{ $user->jenis_kelamin ?? '-' }}</p>
                </li>
                <li class="list-group-item flex-column align-items-start">
                    <span class="text-small text-muted font-weight-bold">Agama</span>
                    <p class="mb-0">{{ $user->agama ?? '-' }}</p>
                </li>
                <li class="list-group-item flex-column align-items-start">
                    <span class="text-small text-muted font-weight-bold">Status Pernikahan</span>
                    <p class="mb-0">{{ $user->status_pernikahan ?? '-' }}</p>
                </li>
                <li class="list-group-item flex-column align-items-start">
                    <span class="text-small text-muted font-weight-bold">Golongan Darah</span>
                    <p class="mb-0">{{ $user->gol_darah ?? '-' }}</p>
                </li>
            </ul>
@endif

