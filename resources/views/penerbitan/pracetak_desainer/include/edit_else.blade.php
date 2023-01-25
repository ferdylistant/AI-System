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
                                    {{ $data->format_buku . ' cm' }}
                                @else
                                    <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Jilid:</th>
                            <td class="table-active text-right">
                                @if (!is_null($data->jilid))
                                    {{ $data->jilid }}
                                @else
                                    <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Warna:</th>
                            <td class="table-active text-right">
                                @if (!is_null($data->warna))
                                    {{ $data->warna }}
                                @else
                                    <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Tipografi:</th>
                            <td class="table-active text-right">
                                @if (!is_null($data->tipografi))
                                    {{ $data->tipografi }}
                                @else
                                    <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Finishing Cover:</th>
                            <td class="table-active text-right">
                                @if (!is_null($data->finishing_cover))
                                    @foreach (json_decode($data->finishing_cover, true) as $key => $aj)
                                        <span class="bullet"></span>{{ $aj }}<br>
                                    @endforeach
                                @else
                                    <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Contoh Cover:</th>
                            <td class="table-active text-right">
                                @if (!is_null($data->contoh_cover))
                                    <a href="{{ $data->contoh_cover }}" class="text-warning"><i
                                            class="fas fa-link"></i>&nbsp;{{ $data->contoh_cover }}</a>
                                @else
                                    <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Sinopsis:</th>
                            <td class="table-active text-right">
                                @if (!is_null($data->sinopsis))
                                    {{ $data->sinopsis }}
                                @else
                                    <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Bullet:</th>
                            <td class="table-active text-right">
                                @if (!is_null($data->bullet))
                                    @foreach (json_decode($data->bullet, true) as $key => $aj)
                                        <span class="bullet"></span>{{ $aj }}<br>
                                    @endforeach
                                @else
                                    <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Deskripsi Front Cover:</th>
                            <td class="table-active text-right">
                                @if (!is_null($data->des_front_cover))
                                    {{ $data->des_front_cover }}
                                @else
                                    <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Deskripsi Back Cover:</th>
                            <td class="table-active text-right">
                                @if (!is_null($data->des_back_cover))
                                    {{ $data->des_back_cover }}
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
                            <th class="table-secondary" style="width: 25%">Desainer: <span class="text-danger">*</span>
                            </th>
                            <td class="table-active text-right">
                                @if (!is_null($data->desainer))
                                    @foreach ($nama_desainer as $nd)
                                        <span class="bullet"></span>{{ $nd }}<br>
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
                                @if (!is_null($data->korektor))
                                    @foreach ($nama_korektor as $nc)
                                        <span class="bullet"></span>{{ $nc }}<br>
                                    @endforeach
                                @else
                                    <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Proses Saat Ini: <span
                                    class="text-danger">*</span></th>
                            <td class="table-active text-right">
                                @if (!is_null($data->proses_saat_ini))
                                    {{ $data->proses_saat_ini }}
                                @else
                                    <span class="text-danger text-small">Belum diinput</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Bulan: <span class="text-danger">*</span>
                            </th>
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
