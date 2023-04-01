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
                            <input type="hidden" name="judul_final" value="{{ $data->judul_final }}">
                            <td class="table-active text-right">{{ $data->judul_final }}</td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Sub-Judul Final:
                            </th>
                            <input type="hidden" name="sub_judul_final" value="{{ $data->sub_judul_final }}">
                            <td class="table-active text-right">
                                {{ is_null($data->sub_judul_final) ? '-' : $data->sub_judul_final }}
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Kelompok Buku:</th>
                            <td class="table-active text-right">{{ $data->nama }}</td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Imprint:</th>
                            <td class="table-active text-right">{{ $nama_imprint }}</td>
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
                                @if (is_null($data->nama_pena))
                                <span class="text-danger text-small">Belum diinput</span>
                                @else
                                    @foreach (json_decode($data->nama_pena) as $p)
                                    {{ $p }}-<br>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Format Buku:</th>
                            <td class="table-active text-right">
                                @if (!is_null($data->format_buku))
                                {{ $format_buku.' cm' }}
                                @else
                                <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Kertas Isi:</th>
                            <td class="table-active text-right">
                                @if (!is_null($data->kertas_isi))
                                {{ $data->kertas_isi }}
                                @else
                                <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Isi Warna:</th>
                            <td class="table-active text-right">
                                @if (!is_null($data->isi_warna))
                                {{ $data->isi_warna }}
                                @else
                                <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Isi Huruf:</th>
                            <td class="table-active text-right">
                                @if (!is_null($data->isi_huruf))
                                {{ $data->isi_huruf }}
                                @else
                                <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Ukuran Asli:</th>
                            <td class="table-active text-right">
                                @if (!is_null($data->ukuran_asli))
                                {{ $data->ukuran_asli }}
                                @else
                                <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Sinopsis:</th>
                            <td class="table-active text-left">
                                @if (!is_null($data->sinopsis))
                                {{ $data->sinopsis }}
                                @else
                                <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Bullet</th>
                            <td class="table-active text-right">
                                @if (is_null($data->bullet) || $data->bullet == '[]')
                                <span class="text-danger text-small">Belum diinput</span>
                                @else
                                @foreach (json_decode($data->bullet, true) as $key => $aj)
                                <span class="bullet"></span>{{ $aj }}<br>
                                @endforeach
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Jumlah halaman
                                final: <span class="text-danger">*</span></th>
                            <td class="table-active text-right">
                                @if (!is_null($data->jml_hal_perkiraan))
                                {{ $data->jml_hal_perkiraan }}
                                @else
                                <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Catatan:</th>
                            <td class="table-active text-right">
                                @if (!is_null($data->catatan))
                                {{ $data->catatan }}
                                @else
                                <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Setter: <span class="text-danger">*</span></th>
                            <td class="table-active text-right">
                                @if ((!is_null($data->setter)) && ($data->setter != '[null]'))
                                @foreach ($nama_setter as $ne)
                                <span class="bullet"></span>{{ $ne }}<br>
                                @endforeach
                                @else
                                <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Korektor:
                            </th>
                            <td class="table-active text-right">
                                @if ((!is_null($data->korektor)) && ($data->korektor != '[null]'))
                                @foreach ($nama_korektor as $nc)
                                    <span class="bullet"></span>{{ $nc }}<br>
                                    @endforeach
                                @else
                                <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Edisi Cetak: <span class="text-danger">*</span></th>
                            <td class="table-active text-right">
                                @if (!is_null($data->edisi_cetak))
                                {{ $data->edisi_cetak }}
                                @else
                                <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Mulai Proses Copyright: <span class="text-danger">*</span></th>
                            <td class="table-active text-right">
                                @if (!is_null($data->mulai_p_copyright))
                                {{ Carbon\Carbon::parse($data->mulai_p_copyright)->translatedFormat('l d F Y, H:i') }}
                                @else
                                <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Selesai Proses Copyright: <span class="text-danger">*</span></th>
                            <td class="table-active text-right">
                                @if (!is_null($data->selesai_p_copyright))
                                {{ Carbon\Carbon::parse($data->selesai_p_copyright)->translatedFormat('l d F Y, H:i') }}
                                @else
                                <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">ISBN: <span class="text-danger">*Wajib ketika ingin menyelesaikan proses</span></th>
                            <td class="table-active text-right">
                                @if (!is_null($data->isbn))
                                {{ $data->isbn }}
                                @else
                                <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Pengajuan Harga: <span class="text-danger">*Wajib ketika ingin menyelesaikan proses</span></th>
                            <td class="table-active text-right">
                                @if (!is_null($data->pengajuan_harga))
                                {{ $data->pengajuan_harga }}
                                @else
                                <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Proses Saat Ini: <span class="text-danger">*</span></th>
                            <td class="table-active text-right">
                                @if (!is_null($data->proses_saat_ini))
                                {{ $data->proses_saat_ini }}
                                @else
                                <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Bulan: <span class="text-danger">*</span></th>
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
