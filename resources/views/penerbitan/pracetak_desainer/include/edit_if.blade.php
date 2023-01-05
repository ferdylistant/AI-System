<form id="fup_pracetakDesainer" data-id="{{$data->id}}" data-id_praset="{{$data->id_praset}}">
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Kode Naskah:</th>
                                <td class="table-active text-right">{{ $data->kode }}</td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Judul Final:</th>
                                <input type="hidden" name="judul_final" value="{{ $data->judul_final }}">
                                <td class="table-active text-right">{{ $data->judul_final }}
                                </td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Sub-Judul Final:
                                </th>
                                <td class="table-active text-right">
                                    {{ is_null($data->sub_judul_final) ? '-' : $data->sub_judul_final }}
                                </td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Kelompok Buku:
                                </th>
                                <td class="table-active text-right">{{ $data->nama }}</td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Imprint:
                                </th>
                                <td class="table-active text-right">{{ $data->imprint }}</td>
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
                                <th class="table-secondary" style="width: 25%">Format Buku:</th>
                                <td class="table-active text-right">
                                    {{ is_null($data->format_buku) ? '-' : $data->format_buku.' cm' }}
                                </td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Jilid:</th>
                                <td class="table-active text-right">
                                    {{ is_null($data->jilid) ? '-' : $data->jilid }}
                                </td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Warna:</th>
                                <td class="table-active text-right">
                                    {{ is_null($data->warna) ? '-' : $data->warna }}
                                </td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Tipografi:</th>
                                <td class="table-active text-right">
                                    {{ is_null($data->tipografi) ? '-' : $data->tipografi }}
                                </td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Finishing Cover:</th>
                                <td class="table-active text-right">
                                    @foreach (json_decode($data->finishing_cover, true) as $key => $aj)
                                    <span class="bullet"></span>{{ $aj }}<br>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Contoh Cover:</th>
                                <td class="table-active text-right">
                                    @if (!is_null($data->contoh_cover))
                                        <a href="{{$data->contoh_cover}}" class="text-warning"><i class="fas fa-link"></i>&nbsp;{{$data->contoh_cover}}</a>
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Sinopsis:</th>
                                <td class="table-active text-right">
                                    {{ is_null($data->sinopsis) ? '-' : $data->sinopsis }}
                                </td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Bullet:</th>
                                <td class="table-active text-right">
                                    @foreach (json_decode($data->bullet, true) as $key => $aj)
                                    <span class="bullet"></span>{{ $aj }}<br>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Deskripsi Front Cover:</th>
                                <td class="table-active text-right">
                                    {{ is_null($data->des_front_cover) ? '-' : $data->des_front_cover }}
                                </td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Deskripsi Back Cover:</th>
                                <td class="table-active text-right">
                                    {{ is_null($data->des_back_cover) ? '-' : $data->des_back_cover }}
                                </td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Catatan:</th>
                                @if (!is_null($data->catatan))
                                <td class="table-active text-right" id="catCol">
                                    {{ $data->catatan }}
                                    <p class="text-small">
                                        <a href="javascript:void(0)" id="catButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                    </p>
                                </td>
                                <td class="table-active text-left" id="catColInput" hidden>
                                    <div class="input-group">
                                        <textarea name="catatan" class="form-control" cols="30" rows="10">{{ $data->catatan }}</textarea>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-danger batal_edit_cat text-danger" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                </td>
                                @else
                                <td class="table-active text-right" id="catCol">
                                    <a href="javascript:void(0)" id="catButton"><i class="fa fa-plus"></i>&nbsp;Tambahkan</a>
                                </td>
                                <td class="table-active text-left" id="catColInput" hidden>
                                    <div class="input-group">
                                        <textarea name="catatan" class="form-control" cols="30" rows="10"></textarea>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-danger batal_edit_cat text-danger" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Desainer: <span class="text-danger">*</span></th>
                                @if (is_null($data->desainer) || $data->desainer == '[]')
                                <td class="table-active text-left">
                                    <select name="desainer[]" class="form-control select-desainer" multiple="multiple" required>
                                        <option label="Pilih desainer"></option>
                                        @foreach ($desainer as $i => $edList)
                                        <option value="{{ $edList->id }}">
                                            {{ $edList->nama }}&nbsp;&nbsp;
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                @else
                                <td class="table-active text-right" id="desainerCol">
                                    @foreach ($nama_desainer as $key => $aj)
                                    <span class="bullet"></span>{{ $aj }}<br>
                                    @endforeach
                                    <p class="text-small">
                                        <a href="javascript:void(0)" id="desainerButton"><i class="fa fa-pen"></i>&nbsp;Add / Edit</a>
                                    </p>

                                </td>
                                <td class="table-active text-left" id="desainerColInput" hidden>
                                    <div class="input-group">
                                        <select name="desainer[]" class="form-control select-desainer" multiple="multiple">
                                            <option label="Pilih desainer"></option>
                                            @foreach ($desainer as $i => $edList)
                                            {{ $sel = '' }}
                                            @if (in_array($edList->nama, $nama_desainer))
                                            {{ $sel = ' selected="selected" ' }}
                                            @endif
                                            <option value="{{ $edList->id }}" {{ $sel }}>
                                                {{ $edList->nama }}&nbsp;&nbsp;
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-danger batal_edit_desainer text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Korektor:
                                    @if (!is_null($data->selesai_proof))
                                    <span class="text-danger">*</span>
                                    @endif
                                </th>
                                @if (is_null($data->korektor) || $data->korektor == '[]')
                                <td class="table-active text-left">
                                    {{ $dis = '' }}
                                    @if (is_null($data->selesai_proof))
                                    <span class="text-danger"><i class="fas fa-exclamation-circle"></i>
                                        Belum bisa melanjutkan proses koreksi,
                                        proses cover belum selesai.</span>
                                    <span hidden>{{ $dis = 'disabled="disabled"' }}</span>
                                    @endif
                                    <select name="korektor[]" class="form-control select-korektor" multiple="multiple" {{ $dis }} required>
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
                                        <a href="javascript:void(0)" id="korektorButton"><i class="fa fa-pen"></i>&nbsp;Add / Edit</a>
                                    </p>
                                    @endif
                                </td>
                                <td class="table-active text-left" id="korektorColInput" hidden>
                                    <div class="input-group">
                                        <select name="korektor[]" class="form-control select-korektor" multiple="multiple">
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
                                            <button type="button" class="btn btn-outline-danger batal_edit_korektor text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Proses Saat Ini: <span class="text-danger">*</span></th>
                                @if (!is_null($data->proses_saat_ini))
                                <td class="table-active text-right" id="prosCol">
                                    {{$data->proses_saat_ini}}
                                    <p class="text-small">
                                        <a href="javascript:void(0)" id="prosButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                    </p>
                                </td>
                                <td class="table-active text-left" id="prosColInput" hidden>
                                    <div class="input-group">
                                        <select name="proses_saat_ini" class="form-control select-proses" required>
                                            <option label="Pilih proses saat ini"></option>
                                            @foreach ($proses_saat_ini as $k)
                                                <option value="{{$k}}" {{$data->proses_saat_ini==$k?'Selected':''}}>{{$k}}&nbsp;&nbsp;</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-danger batal_edit_proses text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                </td>
                                @else
                                <td class="table-active text-left">
                                    <div class="input-group">
                                        <select name="proses_saat_ini" class="form-control select-proses" required>
                                            <option label="Pilih proses saat ini"></option>
                                            @foreach ($proses_saat_ini as $k)
                                                <option value="{{$k}}" {{$data->proses_saat_ini==$k?'Selected':''}}>{{$k}}&nbsp;&nbsp;</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                @endif
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
        <div class="custom-control custom-switch">
            @if ($data->proses == '1')
            <?php $label = 'Stop'; ?>
            @else
            <?php $label = 'Mulai'; ?>
            @endif
            <?php $disable = ''; ?>
            @if (!is_null($data->mulai_proof) && is_null($data->selesai_proof))
                <?php
                $disable = 'disabled';
                $lbl = 'Sedang proses proof prodev';
                ?>
            @elseif (is_null($data->selesai_pengajuan_cover) && is_null($data->mulai_proof))
                <?php
                $lbl = $label.' proses pengajuan cover';
                ?>
            @elseif (is_null($data->selesai_cover) && is_null($data->selesai_koreksi))
                <?php
                $lbl = $label.' proses cover';
                ?>
            @elseif (is_null($data->selesai_koreksi) && !is_null($data->selesai_cover))
                <?php
                $lbl = $label.' proses koreksi';
                ?>
            @elseif ((!is_null($data->selesai_koreksi)) && ($data->proses_saat_ini == 'Desain Revisi') && ($data->status == 'Proses'))
                <?php
                $lbl = $label.' proses revisi desain cover';
                ?>
            @else
                <?php
                $disable = 'disabled';
                $lbl = '-';
                ?>
            @endif
            <input type="checkbox" name="proses" class="custom-control-input" id="prosesKerja" data-id="{{ $data->id }}" {{ $data->proses == '1' ? 'checked' : '' }} {{$disable}}>
            <label class="custom-control-label mr-3 text-dark" for="prosesKerja">
                {{ $lbl }}
            </label>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </div>
</form>
