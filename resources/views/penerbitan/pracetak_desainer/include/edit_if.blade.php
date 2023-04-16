<form id="fup_pracetakDesainer" data-id="" data-id_praset="">
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Kode Naskah:</th>
                                <td class="table-active text-right" id="kode"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Judul Final:</th>
                                <input type="hidden" name="judul_final" value="">
                                <td class="table-active text-right" id="judul_final"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Sub-Judul Final:
                                </th>
                                <td class="table-active text-right" id="sub_judul_final"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Kelompok Buku:
                                </th>
                                <td class="table-active text-right" id="nama"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Imprint:
                                </th>
                                <td class="table-active text-right" id="imprint"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Penulis:</th>
                                <td class="table-active text-right" id="penulis"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Format Buku:</th>
                                <td class="table-active text-right" id="format_buku"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Jilid:</th>
                                <td class="table-active text-right" id="jilid"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Warna:</th>
                                <td class="table-active text-right" id="warna"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Tipografi:</th>
                                <td class="table-active text-right" id="tipografi"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Finishing Cover:</th>
                                <td class="table-active text-right" id="finishing_cover"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Contoh Cover:</th>
                                <td class="table-active text-right" id="contoh_cover"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Sinopsis:</th>
                                <td class="table-active text-right" id="sinopsis"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Bullet:</th>
                                <td class="table-active text-right" id="bullet"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Deskripsi Front Cover:</th>
                                <td class="table-active text-right" id="des_front_cover"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Deskripsi Back Cover:</th>
                                <td class="table-active text-right" id="des_back_cover"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Catatan:</th>
                                <td class="table-active catatan"></td>
                                <td class="table-active text-left catColInput" id="catColInput" hidden></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Desainer: <span class="text-danger">*</span></th>
                                <td class="table-active desainer"></td>
                                <td class="table-active text-left desainerColInput" id="desainerColInput" hidden></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Korektor:
                                    <span id="requiredKorektor"></span>
                                </th>
                                <td class="table-active korektor"></td>
                                <td class="table-active text-left korektorColInput" id="korektorColInput" hidden></td>
                            </tr>
                            {{--<tr>
                                <th class="table-secondary" style="width: 25%">Korektor:
                                    @if (!is_null($data->selesai_proof))
                                        <span class="text-danger">*</span>
                                    @endif
                                </th>
                                @if (is_null($data->korektor) || $data->korektor == '[]')
                                    <td class="table-active text-left">
                                        {{ $dis = '' }}
                                        @if (is_null($data->selesai_cover))
                                            <span class="text-danger"><i class="fas fa-exclamation-circle"></i>
                                                Belum bisa melanjutkan proses koreksi,
                                                proses cover belum selesai.</span>
                                            <span hidden>{{ $dis = 'disabled="disabled"' }}</span>
                                        @endif
                                        <select name="korektor[]" class="form-control select-korektor"
                                            multiple="multiple" {{ $dis }} required>
                                            <option label="Pilih korektor"></option>
                                            @if (!is_null($korektor))
                                                @foreach ($korektor as $cpeList)
                                                    <option value="{{ $cpeList->id }}">
                                                        {{ $cpeList->nama }}&nbsp;&nbsp;
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </td>
                                @else
                                    <td class="table-active text-right" id="korektorCol">
                                        @foreach ($nama_korektor as $key => $aj)
                                            <span class="bullet"></span>{{ $aj }}<br>
                                        @endforeach
                                        @if (is_null($data->selesai_koreksi))
                                            <p class="text-small">
                                                <a href="javascript:void(0)" id="korektorButton"><i
                                                        class="fa fa-pen"></i>&nbsp;Add / Edit</a>
                                            </p>
                                        @endif
                                    </td>
                                    <td class="table-active text-left" id="korektorColInput" hidden>
                                        <div class="input-group">
                                            <select name="korektor[]" class="form-control select-korektor"
                                                multiple="multiple">
                                                <option label="Pilih korektor"></option>
                                                @foreach ($korektor as $i => $cpeList)
                                                    {{ $sl = '' }}
                                                    @if (in_array($cpeList->nama, $nama_korektor))
                                                        {{ $sl = ' selected="selected" ' }}
                                                    @endif
                                                    <option value="{{ $cpeList->id }}" {{ $sl }}>
                                                        {{ $cpeList->nama }}&nbsp;&nbsp;
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button type="button"
                                                    class="btn btn-outline-danger batal_edit_korektor text-danger align-self-center"
                                                    data-toggle="tooltip" title="Batal Edit"><i
                                                        class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                    </td>
                                @endif
                            </tr>--}}
                            <tr>
                                <th class="table-secondary" style="width: 25%">Proses Saat Ini: <span class="text-danger">*</span></th>
                                <td class="table-active proses_saat_ini"></td>
                                <td class="table-active text-left prosColInput" id="prosColInput" hidden></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Bulan: <span class="text-danger">*</span></th>
                                <td class="table-active bulan"></td>
                                <td class="table-active text-left bulanColInput" id="bulanColInput" hidden></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-right">
        <div class="custom-control custom-switch">

            <input type="checkbox" name="proses" class="custom-control-input" id="prosesKerja" data-id="">
            <label class="custom-control-label mr-3 text-dark" for="prosesKerja" id="labelProses"></label>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </div>
</form>
