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
                            <td class="table-active text-right">{{ $data->judul_final }}</td>
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
                            <th class="table-secondary" style="width: 25%">Nama Pena:</th>
                            <td class="table-active text-right">
                                @if (!is_null($data->nama_penulis))
                                    {{ $data->nama_penulis }}
                                @else
                                    <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Tipe Order:<span class="text-danger">*</span></th>
                            <td class="table-active text-right">
                                @if (!is_null($data->tipe_order))
                                {{ $data->tipe_order == '1'? 'Umum':'Rohani' }}
                                @else
                                <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Edisi Cetak:</th>
                            <td class="table-active text-right">
                                @if (!is_null($data->edisi_cetak))
                                    {{ $data->edisi_cetak }}
                                @else
                                    <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">ISBN:</th>
                            <td class="table-active text-right">
                                @if (!is_null($data->isbn))
                                    {{ $data->isbn }}
                                @else
                                    <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Format Buku: <span
                                    class="text-danger">*</span></th>
                            <td class="table-active text-right">
                                @if (!is_null($data->format_buku))
                                    {{ $data->format_buku }} cm
                                @else
                                    <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Kelompok Buku:</th>
                            <td class="table-active text-right">{{ $data->nama }}</td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Jumlah Halaman:</th>
                            <td class="table-active text-right">
                                @if (!is_null($data->jml_hal_final))
                                    {{ $data->jml_hal_final }}
                                @else
                                    <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Tanggal Masuk:</th>
                            <td class="table-active text-right">{{ Carbon\Carbon::parse($data->tgl_masuk)->translatedFormat('l d F Y') }}</td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Sasaran Pasar:</th>
                            <td class="table-active text-right">{{ $sasaran_pasar === null ? '-' : $sasaran_pasar }}</td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Bulan: <span
                                    class="text-danger">*</span></th>
                            <td class="table-active text-right">
                                @if (!is_null($data->bulan))
                                    {{ Carbon\Carbon::parse($data->bulan)->translatedFormat('F Y') }}
                                @else
                                    <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
