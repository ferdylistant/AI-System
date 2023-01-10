<div class="card-body">
    <div class="row">
        <div class="form-group col-12 col-md-6 mb-4">
            <label class="d-block">Platform E-book:</label>
            @foreach ($platformDigital as $pD)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="{{ $pD->nama }}" value="{{ $pD->nama }}"
                        {{ ($data->platform_digital_ebook_id == [] ? '' : in_array($pD->nama, json_decode($data->platform_digital_ebook_id, true))) ? 'checked' : '' }}
                        disabled>
                    <label class="form-check-label" for="{{ $pD->nama }}">{{ $pD->nama }}</label>
                </div>
            @endforeach

        </div>
        <div class="form-group col-12 col-md-6 mb-4">
            <label>Judul Buku:</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-book"></i></div>
                </div>
                <input type="text" class="form-control" value="{{ $data->judul_final }}" placeholder="Judul buku"
                    disabled readonly>
            </div>
        </div>
        <div class="form-group col-12 col-md-6 mb-4">
            <label>Sub Judul Buku:</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-book-open"></i></div>
                </div>
                <input type="text" class="form-control"
                    value="{{ is_null($data->sub_judul_final) ? '-' : $data->sub_judul_final }}" disabled readonly>
            </div>
        </div>
        <div class="form-group col-12 col-md-6 mb-4">
            <label>Penulis:</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-pen"></i></div>
                </div>
                <select class="form-control select-penulis" multiple="multiple" disabbled>
                    <option label="Pilih penulis"></option>
                    @foreach ($penulis as $p)
                        {{ $sl = '' }}
                        @if (in_array($p->id, $collect_penulis))
                            {{ $sl = ' selected="selected" ' }}
                        @endif
                        <option value="{{ $p->id }}" {{ $sl }}>
                            {{ $p->nama }}&nbsp;&nbsp;</option>
                    @endforeach
                </select>
                <div id="err_up_penulis"></div>
            </div>
        </div>
        <div class="form-group col-12 col-md-6 mb-4">
            <label>Imprint:</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-building"></i></div>
                </div>
                <input type="text" class="form-control" value="{{ is_null($data->imprint) ? '-' : $data->imprint }}"
                    disabled readonly>
            </div>
        </div>
        <div class="form-group col-12 col-md-6 mb-4">
            <label>Jalur Buku: <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fa fa-check-circle"></i></div>
                </div>
                <input type="text" class="form-control"
                    value="{{ is_null($data->jalur_buku) ? '-' : $data->jalur_buku }}" disabled readonly>
            </div>
        </div>
        <div class="form-group col-12 col-md-3 mb-4">
            <label>Edisi Cetak: <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-bookmark"></i></div>
                </div>
                <input type="text" class="form-control" name="up_edisi_cetak" placeholder="Edisi Cetak"
                    value="{{ is_null($data->edisi_cetak)?'-':$data->edisi_cetak }}" disabled readonly>
            </div>
        </div>
        <div class="form-group col-12 col-md-3 mb-4">
            <label>Jumlah Halaman: <span class="text-danger">*</span></label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <input type="text" class="form-control" name="up_jml_hal_perkiraan" placeholder="Format Romawi"
                        value="{{ is_null($data->jml_hal_perkiraan)?'-':$data->jml_hal_perkiraan }}" disabled readonly>
                </div>
            </div>
        </div>
        <div class="form-group col-12 col-md-6 mb-4">
            <label>Kelompok Buku: <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-table"></i></div>
                </div>
                <select class="form-control select2" name="up_kelompok_buku" disabled readonly>
                    <option label="Pilih"></option>
                    @foreach ($kbuku as $kb)
                        <option value="{{ $kb->nama }}" {{ $data->nama == $kb->nama ? 'Selected' : '' }}>
                            {{ $kb->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group col-12 col-md-6 mb-4">
            <label>Tipe Order: <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-boxes"></i></div>
                </div>
                <select class="form-control select2" name="up_tipe_order" disabled readonly>
                    <option label="Pilih"></option>
                    @foreach ($tipeOrd as $value)
                        <option value="{{ $value['id'] }}" {{ $data->tipe_order == $value['id'] ? 'Selected' : '' }}>
                            {{ $value['name'] }}</option>
                    @endforeach
                </select>
                <div id="err_up_tipe_order"></div>
            </div>
        </div>
        <div class="form-group col-12 col-md-6 mb-4" id="eISBN">
            <label>E-ISBN: <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-fingerprint"></i></div>
                </div>
                <input type="text" class="form-control" name="up_eisbn" value="{{ is_null($data->eisbn)?'-':$data->eisbn }}"
                    placeholder="Kode E-ISBN" disabled readonly>
            </div>
        </div>
        <div class="form-group col-12 col-md-6 mb-4">
            <label>SPP: </label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fa fa-pen-square"></i></div>
                </div>
                <input type="text" class="form-control" name="up_spp" value="{{ is_null($data->spp)?'-':$data->spp }}"
                    placeholder="Surat Perjanjian Penulis" disabled readonly>
            </div>
        </div>
        <div class="form-group col-12 col-md-3 mb-4">
            <label>Tahun Terbit: <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                </div>
                <input type="text" class="form-control datepicker-year" value="{{ is_null($data->tahun_terbit)?'-':$data->tahun_terbit }}"
                    name="up_tahun_terbit" placeholder="Tahun" readonly disabled>
                <div id="err_up_tahun_terbit"></div>
            </div>
        </div>
        <div class="form-group col-12 col-md-3 mb-4">
            <label>Tanggal Upload: <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                </div>
                <input type="text" class="form-control datepicker-upload" name="up_tgl_upload"
                    value="{{ Carbon\Carbon::parse($data->tgl_upload)->format('d F Y') }}"
                    placeholder="Tanggal Upload" readonly disabled>
                <div id="err_up_tgl_upload"></div>
            </div>
        </div>
        <div class="form-group col-12 col-md-12 mb-4">
            <label>Perlengkapan: </label>
            <textarea class="form-control" name="up_perlengkapan" disabled readonly>{{is_null($data->perlengkapan)?"-":$data->perlengkapan}}</textarea>

        </div>
        <div class="form-group col-12 col-md-12 mb-4">
            <label>Keterangan: </label>
            <textarea class="form-control" name="up_keterangan" disabled readonly>{{is_null($data->keterangan)?"-":$data->keterangan}}</textarea>

        </div>
    </div>
</div>
