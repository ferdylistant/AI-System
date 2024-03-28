@if (Auth::user()->can('do_update', 'ubah-data-user'))
    <form id="fm_EditUserJob">
        {!! csrf_field() !!}
        <div class="profile-widget-description">
            <div class="row">
                <div class="form-group col-12 mb-4">
                    <label>ID Karyawan: <span class="text-danger">*</span></label>
                    <input type="hidden" name="uedit_id" value="{!! $user->id !!}">
                    <input type="text" class="form-control" name="uedit_id_karyawan" value="{{ $user->id_karyawan }}"
                        placeholder="ID Karyawan" required>
                    <div id="err_uedit_id_karyawan"></div>
                </div>
                <div class="form-group col-12 col-md-6 mb-4">
                    <label>Cabang: <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="uedit_cabang" required>
                        <option label="Pilih"></option>
                        @foreach ($lcab as $c)
                            <option value="{!! $c->id !!}" {{ $c->id == $user->cabang_id ? 'Selected' : '' }}>
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
                            <option value="{!! $d->id !!}" {{ $d->id == $user->divisi_id ? 'Selected' : '' }}>
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
                            <option value="{!! $j->id !!}" {{ $j->id == $user->jabatan_id ? 'Selected' : '' }}>
                                {{ $j->nama }}</option>
                        @endforeach
                    </select>
                    <div id="err_uedit_jabatan"></div>
                </div>
                <div class="form-group col-12 col-md-6 mb-4">
                    <label>Tanggal Bergabung: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control datepicker" name="uedit_tgl_bergabung"
                        value="{{ Carbon\Carbon::parse($user->tgl_bergabung)->translatedFormat('d F Y') }}" placeholder="Hari Bulan Tahun" readonly>
                    <div id="err_uedit_tgl_bergabung"></div>
                </div>
                <div class="form-group col-12 col-md-6 mb-4">
                    <label>Tempat Berakhir Kontrak: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control datepicker" name="uedit_tgl_berakhir"
                        value="{{ $user->tgl_berakhir }}" placeholder="Hari Bulan Tahun" readonly>
                    <div id="err_uedit_tgl_berakhir"></div>
                </div>
                <div class="form-group col-12 col-md-6 mb-4">
                    <label>Status: <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="uedit_status_pekerjaan" required>
                        <option label="Pilih"></option>
                        @foreach ($statusPekerjaan as $sp)
                            <option value="{{ $sp }}" {{ $sp == $user->status_pekerjaan ? 'Selected' : '' }}>
                                {{ $sp }}</option>
                        @endforeach
                    </select>
                    <div id="err_uedit_status_pekerjaan"></div>
                </div>
                <div class="form-group col-12 col-md-12 mb-4">
                    <label>Level Pekerjaan: <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="uedit_level_pekerjaan_id" required>
                        <option label="Pilih"></option>
                        @foreach ($levelPekerjaan as $lp)
                            <option value="{{ $lp->id }}" {{ $lp->id == $user->level_pekerjaan_id ? 'Selected' : '' }}>
                                {{ $lp->nama }}</option>
                        @endforeach
                    </select>
                    <div id="err_uedit_level_pekerjaan_id"></div>
                </div>
                <div class="form-group col-12 col-md-12 mb-4">
                    <label>Approval Absensi: <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="uedit_approval_absensi" required>
                        <option label="Pilih"></option>
                        @foreach ($userApproval as $ap)
                            <option value="{{ $ap->id }}"
                                {{ $ap->id == $user->approval_absensi ? 'Selected' : '' }}>
                                {{ $ap->nama }}</option>
                        @endforeach
                    </select>
                    <div id="err_uedit_approval_absensi"></div>
                </div>
                <div class="form-group col-12 col-md-12 mb-4">
                    <label>Approval Perubahan Shift: <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="uedit_approval_shift" required>
                        <option label="Pilih"></option>
                        @foreach ($userApproval as $ap)
                            <option value="{{ $ap->id }}"
                                {{ $ap->id == $user->approval_shift ? 'Selected' : '' }}>
                                {{ $ap->nama }}</option>
                        @endforeach
                    </select>
                    <div id="err_uedit_approval_shift"></div>
                </div>
                <div class="form-group col-12 col-md-12 mb-4">
                    <label>Approval Lembur: <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="uedit_approval_lembur" required>
                        <option label="Pilih"></option>
                        @foreach ($userApproval as $ap)
                            <option value="{{ $ap->id }}"
                                {{ $ap->id == $user->approval_lembur ? 'Selected' : '' }}>
                                {{ $ap->nama }}</option>
                        @endforeach
                    </select>
                    <div id="err_uedit_approval_lembur"></div>
                </div>
                <div class="form-group col-12 col-md-12 mb-4">
                    <label>Approval Izin Kembali: <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="uedit_approval_izin_kembali" required>
                        <option label="Pilih"></option>
                        @foreach ($userApproval as $ap)
                            <option value="{{ $ap->id }}"
                                {{ $ap->id == $user->approval_izin_kembali ? 'Selected' : '' }}>
                                {{ $ap->nama }}</option>
                        @endforeach
                    </select>
                    <div id="err_uedit_approval_izin_kembali"></div>
                </div>
                <div class="form-group col-12 col-md-12 mb-4">
                    <label>Approval Istirahat Telat: <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="uedit_approval_istirahat_telat" required>
                        <option label="Pilih"></option>
                        @foreach ($userApproval as $ap)
                            <option value="{{ $ap->id }}"
                                {{ $ap->id == $user->approval_istirahat_telat ? 'Selected' : '' }}>
                                {{ $ap->nama }}</option>
                        @endforeach
                    </select>
                    <div id="err_uedit_approval_istirahat_telat"></div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-sm btn-warning">Simpan</button>
        </div>

    </form>
@else
    <ul class="list-group list-group-flush">
        <li class="list-group-item flex-column align-items-start">
            <span class="text-small text-muted font-weight-bold">ID Karyawan</span>
            <p class="mb-0">{{ $user->id_karyawan ?? '-' }}</p>
        </li>
        <li class="list-group-item flex-column align-items-start">
            <span class="text-small text-muted font-weight-bold">Cabang</span>
            <p class="mb-0">{{ $user->cabang ?? '-' }}</p>
        </li>
        <li class="list-group-item flex-column align-items-start">
            <span class="text-small text-muted font-weight-bold">Divisi</span>
            <p class="mb-0">{{$user->divisi ?? '-'}}</p>
        </li>
        <li class="list-group-item flex-column align-items-start">
            <span class="text-small text-muted font-weight-bold">Jabatan</span>
            <p class="mb-0">{{$user->jabatan ?? '-'}}</p>
        </li>
        <li class="list-group-item flex-column align-items-start">
            <span class="text-small text-muted font-weight-bold">Status Pekerjaan</span>
            <p class="mb-0">{{$user->status_pekerjaan ?? '-'}}</p>
        </li>
        <li class="list-group-item flex-column align-items-start">
            <span class="text-small text-muted font-weight-bold">Level Pekerjaan</span>
            <p class="mb-0">{{DB::table('user_job_level_master')->where('id', $user->level_pekerjaan_id)->first()->nama ?? '-'}}</p>
        </li>
        <li class="list-group-item flex-column align-items-start">
            <span class="text-small text-muted font-weight-bold">Approval Absensi</span>
            <p class="mb-0">{{DB::table('users')->where('id',$user->approval_absensi)->first()->nama ?? '-'}}</p>
        </li>
        <li class="list-group-item flex-column align-items-start">
            <span class="text-small text-muted font-weight-bold">Approval Shift</span>
            <p class="mb-0">{{DB::table('users')->where('id',$user->approval_shift)->first()->nama ?? '-'}}</p>
        </li>
        <li class="list-group-item flex-column align-items-start">
            <span class="text-small text-muted font-weight-bold">Approval Lembur</span>
            <p class="mb-0">{{DB::table('users')->where('id',$user->approval_lembur)->first()->nama ?? '-'}}</p>
        </li>
        <li class="list-group-item flex-column align-items-start">
            <span class="text-small text-muted font-weight-bold">Approval Izin Kembali</span>
            <p class="mb-0">{{DB::table('users')->where('id',$user->approval_izin_kembali)->first()->nama ?? '-'}}</p>
        </li>
        <li class="list-group-item flex-column align-items-start">
            <span class="text-small text-muted font-weight-bold">Approval Istirahat Telat</span>
            <p class="mb-0">{{DB::table('users')->where('id',$user->approval_istirahat_telat)->first()->nama ?? '-'}}</p>
        </li>
        <li class="list-group-item flex-column align-items-start">
            <span class="text-small text-muted font-weight-bold">Tanggal Bergabung</span>
            <p class="mb-0">{{ Carbon\Carbon::parse($user->tgl_bergabung)->translatedFormat('d F Y') ?? '-'}}</p>
        </li>
        <li class="list-group-item flex-column align-items-start">
            <span class="text-small text-muted font-weight-bold">Masa Berakhir Kontrak</span>
            <p class="mb-0">{{$user->tgl_berakhir ?? '-'}}</p>
        </li>
        <li class="list-group-item flex-column align-items-start">
            <span class="text-small text-muted font-weight-bold">Masa Kerja</span>
            <p class="mb-0">{{ Carbon\Carbon::parse($user->tgl_bergabung)->diffForHumans(now(),Carbon\CarbonInterface::DIFF_ABSOLUTE,false,4) ?? '-'}}</p>
        </li>
    </ul>
@endif
