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
                            <th class="table-secondary" style="width: 25%">Judul Asli:</th>
                            <input type="hidden" name="judul_asli"
                                value="{{ $data->judul_asli }}">
                            <td class="table-active text-right">{{ $data->judul_asli }}</td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Judul Final:</th>
                            <input type="hidden" name="judul_final"
                                value="{{ $data->judul_final }}">
                            <td class="table-active text-right">{{ $data->judul_final }}</td>
                        </tr>
                        <tr>
                            <th class="table-secondary" style="width: 25%">Sub-Judul Final:
                            </th>
                            <td class="table-active text-right">
                                @if (!is_null($data->sub_judul_final))
                                    {{ $data->sub_judul_final }}
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
                            <th class="table-secondary" style="width: 25%">Penulis:</th>
                            <td class="table-active text-right">
                                @foreach ($penulis as $p)
                                    {{ $p->nama }}-<br>
                                @endforeach
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
                        <tr>
                            <th class="table-secondary" style="width: 25%">Contoh Cover: </th>
                            <td class="table-active text-left">
                                @if (!is_null($data->contoh_cover))
                                    {{ $data->contoh_cover }}
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
