<form id="fup_OrderEbook">
    <div class="card-body">
        <div class="row">
            <div class="form-group col-12 col-md-6 mb-4">
                <label class="d-block">Platform E-book:</label>
                @foreach ($platformDigital as $pD)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="{{ $pD->nama }}"
                            value="{{ $pD->nama }}"
                            {{ ($data->platform_digital_ebook_id == [] ? '' : in_array($pD->nama, json_decode($data->platform_digital_ebook_id, true))) ? 'checked' : '' }}
                            disabled>
                        <label class="form-check-label"
                            for="{{ $pD->nama }}">{{ $pD->nama }}</label>
                    </div>
                @endforeach
            </div>
            <div class="form-group col-12 col-md-6 mb-4">
                <label>Jalur Buku: <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fa fa-check-circle"></i></div>
                    </div>
                    <input type="text" class="form-control"
                        value="{{ is_null($data->jalur_buku) ? '-' : $data->jalur_buku }}" disabled
                        readonly>
                </div>
            </div>
            <div class="form-group col-12 col-md-6 mb-4">
                <label>Judul Buku:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-book"></i></div>
                    </div>
                    <input type="text" class="form-control" value="{{ $data->judul_final }}"
                        placeholder="Judul buku" disabled readonly>
                </div>
            </div>
            <div class="form-group col-12 col-md-6 mb-4">
                <label>Sub Judul Buku:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-book-open"></i></div>
                    </div>
                    <input type="text" class="form-control"
                        value="{{ is_null($data->sub_judul_final) ? '-' : $data->sub_judul_final }}"
                        disabled readonly>
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
                    <input type="text" class="form-control"
                        value="{{ is_null($data->imprint) ? '-' : $data->imprint }}" disabled readonly>
                </div>
            </div>
            <div class="form-group col-12 col-md-3 mb-4">
                <label>Edisi Cetak: <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-bookmark"></i></div>
                    </div>
                    <input type="text" class="form-control" name="up_edisi_cetak"
                        placeholder="Edisi Cetak" value="{{ $data->edisi_cetak }}" required>
                    <div id="err_up_edisi"></div>
                </div>
            </div>
            <div class="form-group col-12 col-md-3 mb-4">
                <label>Jumlah Halaman: <span class="text-danger">*</span></label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <input type="text" class="form-control" name="up_jml_hal_perkiraan"
                            placeholder="Format Romawi" value="{{ $data->jml_hal_perkiraan }}"
                            required>
                    </div>
                </div>
            </div>
            <div class="form-group col-12 col-md-6 mb-4">
                <label>Kelompok Buku: <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-table"></i></div>
                    </div>
                    <select class="form-control select2" name="up_kelompok_buku" required>
                        <option label="Pilih"></option>
                        @foreach ($kbuku as $kb)
                            <option value="{{ $kb->id }}"
                                {{ $data->nama == $kb->nama ? 'Selected' : '' }}>{{ $kb->nama }}
                            </option>
                        @endforeach
                    </select>
                    <div id="err_up_kelompok_buku"></div>
                </div>
            </div>
            <div class="form-group col-12 col-md-6 mb-4">
                <label>Tipe Order: <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-boxes"></i></div>
                    </div>
                    <select class="form-control select2" name="up_tipe_order">
                        <option label="Pilih"></option>
                        @foreach ($tipeOrd as $value)
                            <option value="{{ $value['id'] }}"
                                {{ $data->tipe_order == $value['id'] ? 'Selected' : '' }}>
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
                    <input type="text" class="form-control" name="up_eisbn"
                        value="{{ $data->eisbn }}" placeholder="Kode E-ISBN" required>
                    <div id="err_up_eisbn"></div>
                </div>
            </div>
            <div class="form-group col-12 col-md-6 mb-4">
                <label>SPP: </label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fa fa-pen-square"></i></div>
                    </div>
                    <input type="text" class="form-control" name="up_spp"
                        value="{{ $data->spp }}" placeholder="Surat Perjanjian Penulis">
                    <div id="err_up_spp"></div>
                </div>
            </div>
            <div class="form-group col-12 col-md-3 mb-4">
                <label>Tahun Terbit: <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                    </div>
                    <input type="text" class="form-control datepicker-year"
                        value="{{ $data->tahun_terbit }}" name="up_tahun_terbit"
                        placeholder="Tahun" readonly required>
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
                        placeholder="Tanggal Upload" readonly required>
                    <div id="err_up_tgl_upload"></div>
                </div>
            </div>
            <div class="form-group col-12 col-md-12 mb-4">
                <label>Perlengkapan: </label>
                <textarea class="form-control" name="up_perlengkapan" value="{{ $data->perlengkapan }}" placeholder="Perlengkapan"></textarea>
                <div id="err_up_perlengkapan"></div>

            </div>
            <div class="form-group col-12 col-md-12 mb-4">
                <label>Keterangan: </label>
                <textarea class="form-control" name="up_keterangan" value="{{ $data->keterangan }}" placeholder="Keterangan"></textarea>
                <div id="err_up_keterangan"></div>

            </div>
        </div>
    </div>
    <div class="card-footer text-right">
        <button type="submit" class="btn btn-success" data-id="{{ $data->id }}">Update</button>
    </div>
</form>
