<form id="fup_OrderCetak" name="up_id">
    <div class="card-body">
        <div class="row">
            <div class="form-group col-12 col-md-6 mb-4">
                <label>Jalur Buku:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fa fa-check-circle"></i></div>
                    </div>
                    <input type="text" class="form-control" name="up_jalur_buku" disabled readonly>
                </div>
            </div>
            <div class="form-group col-12 col-md-6 mb-4">
                <label>Judul Buku:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-book"></i></div>
                    </div>
                    <input type="text" class="form-control" name="up_judul_final" disabled readonly>
                </div>
            </div>
            <div class="form-group col-12 col-md-6 mb-4">
                <label>Sub Judul Buku:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-book-open"></i></div>
                    </div>
                    <input type="text" class="form-control" name="up_sub_judul_final" disabled readonly>
                </div>
            </div>
            <div class="form-group col-12 col-md-6 mb-4">
                <label>Penulis:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-pen"></i></div>
                    </div>
                    <select id="up_penulis" class="form-control select-penulis" multiple="">
                    </select>
                </div>
            </div>
            <div class="form-group col-12 col-md-6 mb-4">
                <label>Imprint:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-building"></i></div>
                    </div>
                    <input type="text" class="form-control" name="up_imprint" disabled readonly>
                </div>
            </div>
            <div class="form-group col-12 col-md-6 mb-4" id="eISBN">
                <label>ISBN: </label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-fingerprint"></i></div>
                    </div>
                    <input type="text" class="form-control" name="up_imprint" disabled readonly>
                </div>
            </div>
            <div class="form-group col-12 col-md-6 mb-4">
                <label>Status Cetak: <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-file"></i></div>
                    </div>
                    <select class="form-control select2" name="up_status_cetak" required>
                        @foreach ($status_cetak as $st)
                            @switch($st)
                                @case(1)
                                    {{$res = 'Buku Baru'}}
                                    @break
                                @case(2)
                                    {{$res = 'Cetak Ulang Revisi'}}
                                    @break
                                @case(3)
                                    {{$res = 'Cetak Ulang'}}
                                    @break
                            @endswitch
                            <option value="{{$st}}">{{$res}}</option>
                        @endforeach
                    </select>
                    <div id="err_up_status_cetak"></div>
                </div>
            </div>
            <div class="form-group col-12 col-md-6 mb-4">
                <label>Jenis Mesin: <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-hdd"></i></div>
                    </div>
                    <select class="form-control select2" name="up_jenis_mesin" required>
                        @foreach ($jenis_mesin as $jm)
                            <option value="{{$jm}}">{{$jm==1?'POD':'Mesin Besar'}}</option>
                        @endforeach
                    </select>
                    <div id="err_up_jenis_mesin"></div>
                </div>
            </div>
            <div class="form-group col-12 col-md-3 mb-4">
                <label>Edisi Cetak: <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-bookmark"></i></div>
                    </div>
                    <input type="text" class="form-control" name="up_edisi_cetak" placeholder="Edisi Cetak" required>
                    <div id="err_up_edisi_cetak"></div>
                </div>
            </div>
            <div class="form-group col-12 col-md-3 mb-4">
                <label>Jumlah Halaman: <span class="text-danger">*</span></label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-copy"></i></div>
                    </div>
                    <input type="text" class="form-control" name="up_jml_hal_perkiraan"
                        placeholder="Jumlah halaman" required>
                    <div id="err_up_jml_hal_perkiraan"></div>
                </div>
            </div>
            <div class="form-group col-12 col-md-6 mb-4">
                <label>Kelompok Buku: <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-table"></i></div>
                    </div>
                    <select class="form-control select2" name="up_kelompok_buku" required>
                        @foreach ($kbuku as $kb)
                            <option value="{{ $kb->id }}">
                                {{ $kb->nama }}
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
                            <option value="{{ $value['id'] }}">
                                {{ $value['name'] }}</option>
                        @endforeach
                    </select>
                    <div id="err_up_tipe_order"></div>
                </div>
            </div>
            <div class="form-group col-12 col-md-3 mb-4">
                <label>Posisi Layout <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fa fa-arrows-alt"></i></div>
                    </div>
                    <select class="form-control select2" name="up_posisi_layout" id="posisiLayout" required>
                    </select>
                    <div id="err_up_posisi_layout"></div>
                </div>
            </div>
            <div class="form-group col-12 col-md-3 mb-4">
                <label>Dami <span class="text-danger">* Data dari posisi layout </span></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fa fa-quote-left"></i></div>
                    </div>
                    <select class="form-control select2" name="up_dami" id="dami" required>
                    </select>
                    <div id="err_up_dami"></div>
                </div>
            </div>
            <div class="form-group col-12 col-md-6 mb-4" id="formJilid">
                <label>Jilid: <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fa fa-hand-spock"></i></div>
                    </div>
                    <select class="form-control select2" name="up_jilid" id="jilidChange" required>
                        <option label="Pilih"></option>
                        @foreach($jilid as $j)
                            <option value="{{ $j }}">{{ $j}}</option>
                        @endforeach
                    </select>
                    <div id="err_up_jilid"></div>
                </div>
            </div>
            <div class="col-12 col-md-3" id="ukuranBinding" style="display:none"></div>
            {{-- <div class="form-group col-12 col-md-3 mb-4" id="ukuranBending">
                <label>Ukuran Bending: <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fa fa-ruler"></i></div>
                    </div>
                    <input type="text" class="form-control" name="up_ukuran_bending" placeholder="Ukuran Bending" required>
                    <div id="err_up_ukuran_bending"></div>
                    <div class="input-group-append">
                        <span class="input-group-text"><strong>cm</strong></span>
                    </div>
                </div>
            </div> --}}
            <div class="form-group col-12 col-md-6 mb-4">
                <label>SPP: </label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fa fa-pen-square"></i></div>
                    </div>
                    <input type="text" class="form-control" name="up_spp"  placeholder="Surat Perjanjian Penulis">
                    <div id="err_up_spp"></div>
                </div>
            </div>
            <div class="form-group col-12 col-md-3 mb-4">
                <label>Tahun Terbit: <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                    </div>
                    <input type="text" class="form-control datepicker-year" name="up_tahun_terbit" placeholder="Tahun" readonly required>
                    <div id="err_up_tahun_terbit"></div>
                </div>
            </div>
            <div class="form-group col-12 col-md-3 mb-4">
                <label>Tanggal Permintaan Jadi: <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                    </div>
                    <input type="text" class="form-control datepicker" name="up_tgl_permintaan_jadi" placeholder="Hari Bulan Tahun" readonly required>
                    <div id="err_up_tgl_permintaan_jadi"></div>
                </div>
            </div>
            <div class="form-group col-12 col-md-12 mb-4">
                <label>Perlengkapan: </label>
                <textarea class="form-control" name="up_perlengkapan" placeholder="Perlengkapan"></textarea>
                <div id="err_up_perlengkapan"></div>

            </div>
            <div class="form-group col-12 col-md-12 mb-4">
                <label>Keterangan: </label>
                <textarea class="form-control" name="up_keterangan" placeholder="Keterangan"></textarea>
                <div id="err_up_keterangan"></div>

            </div>
        </div>
    </div>
    <div class="card-footer text-right">
        <button type="submit" class="btn btn-success">Update</button>
    </div>
</form>
