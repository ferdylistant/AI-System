@if (Auth::id() == $user->id)
    <form id="fm_EditUserKontakDarurat">
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
    </ul>
@endif
