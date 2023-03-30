@php
    $arrPilihan_ = ["Baik", "Cukup", "Kurang"];
@endphp
@if($form == 'view')
<div class="row">
    <div class="form-group col-12 col-md-4 mb-4">
        <label>Sistematika: <span class="text-danger">*</span></label>
        <select class="form-control select2" name="pn1_sistematika" disabled>
            <option label="Pilih"></option>
            @foreach($arrPilihan_ as $ap)
            <option value="{{$ap}}" {{$pn_prodev->sistematika==$ap?'Selected':''}}>{{$ap}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-12 col-md-4 mb-4">
        <label>Nilai Keilmuan: <span class="text-danger">*</span></label>
        <select class="form-control select2" name="pn1_nilai_keilmuan" disabled>
            <option label="Pilih"></option>
            @foreach($arrPilihan_ as $ap)
            <option value="{{$ap}}" {{$pn_prodev->nilai_keilmuan==$ap?'Selected':''}}>{{$ap}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-12 col-md-4 mb-4">
        <label>Kelompok Buku: <span class="text-danger">*</span></label>
        <select class="form-control select2ws" name="pn1_kelompok_buku_id" disabled>
            <option label="Pilih"></option>
            @foreach($kbuku as $kb)
            <option value="{{$kb->id}}" {{$kb->id==$pn_prodev->kelompok_buku_id?'Selected':''}}>{{$kb->nama}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-12 col-md-6 mb-4">
        <label>Potensi: </label>
        <input type="text" class="form-control" name="pn1_potensi" value="{{$pn_prodev->potensi}}" readonly>
    </div>
    <div class="form-group col-12 col-md-6 mb-4">
        <label>Sumber Dana Pasar: </label>
        <input type="text" class="form-control" name="pn1_sumber_dana_pasar" value="{{$pn_prodev->sumber_dana_pasar}}" readonly>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Isi Materi: </label>
        <textarea class="form-control" name="pn1_isi_materi" placeholder="Isi Materi Naskah" readonly>{{$pn_prodev->isi_materi}}</textarea>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Sasaran Keilmuan: </label>
        <textarea class="form-control" name="pn1_sasaran_keilmuan" placeholder="Sasaran Keilmuan Naskah" readonly>{{$pn_prodev->sasaran_keilmuan}}</textarea>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Sasaran Pasar: </label>
        <textarea class="form-control" name="pn1_sasaran_pasar" placeholder="Sasaran Pasar Naskah" readonly>{{$pn_prodev->sasaran_pasar}}</textarea>
    </div>
    <div class="form-group col-12 col-md-4 mb-4">
        <label>Skala Penilaian: <span class="text-danger">*</span></label>
        <select class="form-control select2" name="pn1_skala_penilaian" disabled>
            <option label="Pilih"></option>
            @foreach($arrPilihan_ as $ap)
            <option value="{{$ap}}" {{$pn_prodev->skala_penilaian==$ap?'Selected':''}}>{{$ap}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-12 col-md-4 mb-4">
        <label>Saran: <span class="text-danger">*</span></label>
        <select class="form-control select2" name="pn1_saran" disabled>
            <option label="Pilih"></option>
            <option value="Diterima" {{$pn_prodev->saran=='Diterima'?'Selected':''}}>Diterima</option>
            <option value="Ditolak" {{$pn_prodev->saran=='Ditolak'?'Selected':''}}>Ditolak</option>
            <option value="Revisi" {{$pn_prodev->saran=='Revisi'?'Selected':''}}>Revisi</option>
            <option value="eBook" {{$pn_prodev->saran=='eBook'?'Selected':''}}>eBook</option>
        </select>
    </div>
    {{-- <div class="form-group col-12 col-md-4 mb-4">
        <label>File Tambahan (.rar; .zip) <span class="text-danger">**</span></label>
        @if($fileProdev)
        <a href="{{url('storage/penerbitan/naskah/'.$naskah->id.'/'.$fileProdev->file)}}"
            class="btn btn-sm btn-primary text-white form-control">Download</a>
        @else
        <div class="">-</div>
        @endif
    </div> --}}
</div>


@elseif($form == 'add')
<form id="fpn1">
<div class="row">
    <input type="hidden" name="pn1_naskah_id" value="{{$naskah->id}}">
    <input type="hidden" name="pn1_form" value="Add">
    <div class="form-group col-12 col-md-4 mb-4">
        <label>Sistematika: <span class="text-danger">*</span></label>
        <select class="form-control select2" name="pn1_sistematika" required>
            <option label="Pilih"></option>
            <option value="Baik">Baik</option>
            <option value="Cukup">Cukup</option>
            <option value="Kurang">Kurang</option>
        </select>
        <div id="err_pn1_sistematika"></div>
    </div>
    <div class="form-group col-12 col-md-4 mb-4">
        <label>Nilai Keilmuan: <span class="text-danger">*</span></label>
        <select class="form-control select2" name="pn1_nilai_keilmuan" required>
            <option label="Pilih"></option>
            <option value="Baik">Baik</option>
            <option value="Cukup">Cukup</option>
            <option value="Kurang">Kurang</option>
        </select>
        <div id="err_pn1_nilai_keilmuan"></div>
    </div>
    <div class="form-group col-12 col-md-4 mb-4">
        <label>Kelompok Buku: <span class="text-danger">*</span></label>
        <select class="form-control select2ws" name="pn1_kelompok_buku_id" required>
            <option label="Pilih"></option>
            @foreach($kbuku as $kb)
            <option value="{{$kb->id}}">{{$kb->nama}}</option>
            @endforeach
        </select>
        <div id="err_pn1_kelompok_buku_id"></div>
    </div>
    <div class="form-group col-12 col-md-6 mb-4">
        <label>Potensi: </label>
        <input type="text" class="form-control" name="pn1_potensi" placeholder="Potensi Pasar">
        <div id="err_pn1_potensi"></div>
    </div>
    <div class="form-group col-12 col-md-6 mb-4">
        <label>Sumber Dana Pasar: </label>
        <input type="text" class="form-control" name="pn1_sumber_dana_pasar" placeholder="Sumber Dana Pasar">
        <div id="err_pn1_sumber_dana_pasar"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Isi Materi: </label>
        <textarea class="form-control" name="pn1_isi_materi" placeholder="Isi Materi Naskah"></textarea>
        <div id="err_pn1_isi_materi"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Sasaran Keilmuan: </label>
        <textarea class="form-control" name="pn1_sasaran_keilmuan" placeholder="Sasaran Keilmuan Naskah"></textarea>
        <div id="err_pn1_sasaran_keilmuan"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Sasaran Pasar: </label>
        <textarea class="form-control" name="pn1_sasaran_pasar" placeholder="Sasaran Pasar Naskah"></textarea>
        <div id="err_pn1_sasaran_pasar"></div>
    </div>
    <div class="form-group col-12 col-md-4 mb-4">
        <label>Skala Penilaian: <span class="text-danger">*</span></label>
        <select class="form-control select2" name="pn1_skala_penilaian" required>
            <option label="Pilih"></option>
            <option value="Baik">Baik</option>
            <option value="Cukup">Cukup</option>
            <option value="Kurang">Kurang</option>
        </select>
        <div id="err_pn1_skala_penilaian"></div>
    </div>
    <div class="form-group col-12 col-md-4 mb-4">
        <label>Saran: <span class="text-danger">*</span></label>
        <select class="form-control select2" name="pn1_saran" required>
            <option label="Pilih"></option>
            <option value="Diterima">Diterima</option>
            <option value="Ditolak">Ditolak</option>
            <option value="Revisi">Revisi</option>
            <option value="eBook">eBook</option>
        </select>
        <div id="err_pn1_saran"></div>
    </div>
    {{-- <div class="form-group col-12 col-md-4 mb-4">
        <label>File Tambahan (.rar; .zip)(#Abaikan jika tidak ada)</label>
        <div class="custom-file">
            <input type="file" class="custom-file-input" name="pn1_file_tambahan" id="pn1_file_tambahan">
            <label class="custom-file-label" for="pn1_file_tambahan">Choose file</label>
        </div>
        <div id="err_pn1_file_tambahan" style="display: block;"></div>
    </div> --}}
</div>
<div class="card-footer text-right p-0">
    <button type="submit" class="btn btn-success">Simpan</button>
</div>
</form>


@elseif($form == 'edit')
<form id="fpn1">
<div class="row">
    <input type="hidden" name="pn1_naskah_id" value="{{$naskah->id}}">
    <input type="hidden" name="pn1_id" value="{{$pn_prodev->id}}">
    <input type="hidden" name="pn1_form" value="Edit">
    <div class="form-group col-12 col-md-4 mb-4">
        <label>Sistematika: <span class="text-danger">*</span></label>
        <select class="form-control select2" name="pn1_sistematika" required>
            <option label="Pilih"></option>
            @foreach($arrPilihan_ as $ap)
            <option value="{{$ap}}" {{$pn_prodev->sistematika==$ap?'Selected':''}}>{{$ap}}</option>
            @endforeach
        </select>
        <div id="err_pn1_sistematika"></div>
    </div>
    <div class="form-group col-12 col-md-4 mb-4">
        <label>Nilai Keilmuan: <span class="text-danger">*</span></label>
        <select class="form-control select2" name="pn1_nilai_keilmuan" required>
            <option label="Pilih"></option>
            @foreach($arrPilihan_ as $ap)
            <option value="{{$ap}}" {{$pn_prodev->nilai_keilmuan==$ap?'Selected':''}}>{{$ap}}</option>
            @endforeach
        </select>
        <div id="err_pn1_nilai_keilmuan"></div>
    </div>
    <div class="form-group col-12 col-md-4 mb-4">
        <label>Kelompok Buku: <span class="text-danger">*</span></label>
        <select class="form-control select2ws" name="pn1_kelompok_buku_id" required>
            <option label="Pilih"></option>
            @foreach($kbuku as $kb)
            <option value="{{$kb->id}}" {{$kb->id==$pn_prodev->kelompok_buku_id?'Selected':''}}>{{$kb->nama}}</option>
            @endforeach
        </select>
        <div id="err_pn1_kelompok_buku_id"></div>
    </div>
    <div class="form-group col-12 col-md-6 mb-4">
        <label>Potensi: </label>
        <input type="text" class="form-control" name="pn1_potensi" value="{{$pn_prodev->potensi}}" placeholder="Potensi Pasar">
        <div id="err_pn1_potensi"></div>
    </div>
    <div class="form-group col-12 col-md-6 mb-4">
        <label>Sumber Dana Pasar: </label>
        <input type="text" class="form-control" name="pn1_sumber_dana_pasar" value="{{$pn_prodev->sumber_dana_pasar}}" placeholder="Sumber Dana Pasar">
        <div id="err_pn1_sumber_dana_pasar"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Isi Materi: </label>
        <textarea class="form-control" name="pn1_isi_materi" placeholder="Isi Materi Naskah">{{$pn_prodev->isi_materi}}</textarea>
        <div id="err_pn1_isi_materi"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Sasaran Keilmuan: </label>
        <textarea class="form-control" name="pn1_sasaran_keilmuan" placeholder="Sasaran Keilmuan Naskah">{{$pn_prodev->sasaran_keilmuan}}</textarea>
        <div id="err_pn1_sasaran_keilmuan"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Sasaran Pasar: </label>
        <textarea class="form-control" name="pn1_sasaran_pasar" placeholder="Sasaran Pasar Naskah">{{$pn_prodev->sasaran_pasar}}</textarea>
        <div id="err_pn1_sasaran_pasar"></div>
    </div>
    <div class="form-group col-12 col-md-4 mb-4">
        <label>Skala Penilaian: <span class="text-danger">*</span></label>
        <select class="form-control select2" name="pn1_skala_penilaian" required>
            <option label="Pilih"></option>
            @foreach($arrPilihan_ as $ap)
            <option value="{{$ap}}" {{$pn_prodev->skala_penilaian==$ap?'Selected':''}}>{{$ap}}</option>
            @endforeach
        </select>
        <div id="err_pn1_skala_penilaian"></div>
    </div>
    <div class="form-group col-12 col-md-4 mb-4">
        <label>Saran: <span class="text-danger">*</span></label>
        <select class="form-control select2" name="pn1_saran" required>
            <option label="Pilih"></option>
            <option value="Diterima" {{$pn_prodev->saran=='Diterima'?'Selected':''}}>Diterima</option>
            <option value="Ditolak" {{$pn_prodev->saran=='Ditolak'?'Selected':''}}>Ditolak</option>
            <option value="Revisi" {{$pn_prodev->saran=='Revisi'?'Selected':''}}>Revisi</option>
            <option value="eBook" {{$pn_prodev->saran=='eBook'?'Selected':''}}>eBook</option>
        </select>
        <div id="err_saran"></div>
    </div>
    {{-- <div class="form-group col-12 col-md-4 mb-4">
        <label>File Tambahan (.rar; .zip)
            @if($fileProdev)
            (<a href="{{url('storage/penerbitan/naskah/'.$naskah->id.'/'.$fileProdev->file)}}" class="text-primary"><strong>Download</strong></a>)
            @endif
        <span class="text-danger">**</span></label>
        <div class="custom-file">
           <input type="file" class="custom-file-input" name="pn1_file_tambahan" id="pn1_file_tambahan">
            <label class="custom-file-label" for="pn1_file_tambahan">Choose file</label>
        </div>
        <div id="err_pn1_file_tambahan" style="display: block;"></div>
    </div> --}}
</div>
<div class="card-footer  p-0">
    {{-- <span class="text-danger">*Wajib diisi.</span><br/>
    <span class="text-danger">**File Input Abaikan jika tidak diubah/diinput.</span> --}}
    <button type="submit" class="btn btn-warning float-right">Simpan</button>
</div>
</form>

@endif
