<form id="fup_deskripsiTurunCetak" data-id="{{ $data->id }}">
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Kode naskah:</th>
                                <td class="table-active text-right">{{ $data->kode }}</td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Judul Final:</th>
                                <input type="hidden" name="judul_final"
                                    value="{{ $data->judul_final }}">
                                @if (!is_null($data->judul_final))
                                    <td class="table-active text-right">
                                        {{ $data->judul_final }}
                                    </td>
                                @endif
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Penulis:</th>
                                <td class="table-active text-right">
                                    @foreach ($penulis as $p)
                                        {{ $p->nama }}-<br>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Edisi Cetak
                                    Tahun:
                                    <span class="text-danger">*</span>
                                </th>
                                @if (!is_null($data->edisi_cetak))
                                    <td class="table-active text-right" id="isiEdisiCetakCol">
                                        {{ $data->edisi_cetak }}
                                        <p class="text-small">
                                            <a href="javascript:void(0)"
                                                id="isiEdisiCetakButton"><i
                                                    class="fa fa-pen"></i>&nbsp;Edit</a>
                                        </p>
                                    </td>
                                    <td class="table-active text-left"
                                        id="isiEdisiCetakColInput" hidden>
                                        <div class="input-group">
                                            <input type="text" name="edisi_cetak"
                                                class="form-control"
                                                value="{{ $data->edisi_cetak }}"
                                                placeholder="Edisi cetak">
                                            <div class="input-group-append">
                                                <button type="button"
                                                    class="btn btn-outline-danger batal_edit_edisi_cetak text-danger"
                                                    data-toggle="tooltip" title="Batal Edit"><i
                                                        class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                    </td>
                                @else
                                    <td class="table-active text-left">
                                        <input type="text" name="edisi_cetak"
                                            class="form-control" placeholder="Edisi cetak">
                                    </td>
                                @endif
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">ISBN:</th>
                                <td class="table-active text-right">
                                    {{ $data->isbn }}
                                </td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Format Buku:
                                    <span class="text-danger">*</span>
                                </th>
                                @if (!is_null($data->format_buku))
                                    <td class="table-active text-right" id="formatBukuCol">
                                        {{ $data->format_buku }} cm
                                        <p class="text-small">
                                            <a href="javascript:void(0)"
                                                id="formatBukuButton"><i
                                                    class="fa fa-pen"></i>&nbsp;Edit</a>
                                        </p>
                                    </td>
                                    <td class="table-active text-left" id="formatBukuColInput"
                                        hidden>
                                        <div class="input-group">
                                            <select name="format_buku"
                                                class="form-control select-format-buku"
                                                required>
                                                <option label="Pilih format buku"></option>
                                                @foreach ($format_buku as $fb)
                                                    <option value="{{ $fb->jenis_format }}"
                                                        {{ $data->format_buku == $fb->jenis_format ? 'Selected' : '' }}>
                                                        {{ $fb->jenis_format }}&nbsp;cm&nbsp;&nbsp;
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button type="button"
                                                    class="btn btn-outline-danger batal_edit_format text-danger align-self-center"
                                                    data-toggle="tooltip" title="Batal Edit"><i
                                                        class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                    </td>
                                @else
                                    <td class="table-active text-left">
                                        <select name="format_buku"
                                            class="form-control select-format-buku" required>
                                            <option label="Pilih format buku"></option>
                                            @foreach ($format_buku as $fb)
                                                <option value="{{ $fb->jenis_format }}">
                                                    {{ $fb->jenis_format }}&nbsp;cm&nbsp;&nbsp;
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                @endif
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Kelompok Buku:
                                </th>
                                <td class="table-active text-right">{{ $data->nama }}</td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Jumlah Halaman
                                    Final:
                                </th>
                                <td class="table-active text-right">{{ $data->jml_hal_final }}
                                    Halaman
                                </td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Tanggal Masuk:
                                </th>
                                <td class="table-active text-right">
                                    {{ Carbon\Carbon::parse($data->tgl_masuk)->translatedFormat('l d F Y') }}
                                </td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Sasaran Pasar:
                                </th>
                                <td class="table-active text-right">
                                    {{ $sasaran_pasar === null ? '-' : $sasaran_pasar }}
                                </td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Bulan: <span class="text-danger">*</span></th>
                                @if (!is_null($data->bulan))
                                <td class="table-active text-right" id="bulanCol">
                                    {{ Carbon\Carbon::parse($data->bulan)->translatedFormat('F Y') }}
                                    <p class="text-small">
                                        <a href="javascript:void(0)" id="bulanButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                    </p>
                                </td>
                                <td class="table-active text-left" id="bulanColInput" hidden>
                                    <div class="input-group">
                                        <input name="bulan" class="form-control datepicker" value="{{Carbon\Carbon::createFromFormat('Y-m-d',$data->bulan,'Asia/Jakarta')->format('F Y')}}" placeholder="Bulan proses" readonly required>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-danger batal_edit_bulan text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                </td>
                                @else
                                <td class="table-active text-left">
                                    <input name="bulan" class="form-control datepicker" placeholder="Bulan proses" readonly required>
                                </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-right">
        <button type="submit" class="btn btn-success">Update</button>
    </div>
</form>
