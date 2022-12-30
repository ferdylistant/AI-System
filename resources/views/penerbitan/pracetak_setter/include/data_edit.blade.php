<div class="row mb-4">
    <div class="col-12 col-md-4">
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Kode</h6>
            </div>
            <p class="mb-1 text-monospace">{{ $data->kode }}</p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Judul Final</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->judul_final))
                    -
                @else
                    {{ $data->judul_final }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Sub-Judul Final</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->sub_judul_final))
                    -
                @else
                    {{ $data->sub_judul_final }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Bullet</h6>
            </div>
            @if (is_null($data->bullet) || $data->bullet == '[]')
                <p class="mb-1 text-monospace">
                    -
                </p>
            @else
                <ul class="list-unstyled list-inline">
                    @foreach (json_decode($data->bullet) as $bullet)
                        <li class="list-inline-item">
                            <p class="mb-1 text-monospace">
                                <span class="bullet"></span>
                                <span>{{ $bullet }}</span>
                            </p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Penulis</h6>
            </div>
            <ul class="list-unstyled list-inline">
                <li class="list-inline-item">
                    @foreach ($penulis as $pen)
                        <p class="mb-1 text-monospace">
                            <span class="bullet"></span>
                            <a
                                href="{{ url('/penerbitan/penulis/detail-penulis/' . $pen->id) }}">{{ $pen->nama }}</a>
                        </p>
                    @endforeach
                </li>
            </ul>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Kelompok Buku</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->nama))
                    -
                @else
                    {{ $data->nama }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Imprint</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->imprint))
                    -
                @else
                    {{ $data->imprint }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Edisi Cetak</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->edisi_cetak))
                    -
                @else
                    {{ $data->edisi_cetak }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">PIC Prodev</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($pic->nama))
                    -
                @else
                    <a
                        href="{{ url('/manajemen-web/user/' . $pic->id) }}">{{ $pic->nama }}</a>
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Sinopsis</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->sinopsis))
                    -
                @else
                    {{ $data->sinopsis }}
                @endif
            </p>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Ukuran Asli</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->ukuran_asli))
                    -
                @else
                    {{ $data->ukuran_asli }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Jml Halaman Final</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->jml_hal_final))
                    -
                @else
                    {{ $data->jml_hal_final }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Format Buku</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->format_buku))
                    -
                @else
                    {{ $data->format_buku }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Kertas Isi</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->kertas_isi))
                    -
                @else
                    {{ $data->kertas_isi }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Isi Warna</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->isi_warna))
                    -
                @else
                    {{ $data->isi_warna }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Isi Huruf</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->isi_huruf))
                    -
                @else
                    {{ $data->isi_huruf }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">ISBN</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->isbn))
                    -
                @else
                    {{ $data->isbn }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Pengajuan Harga</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->pengajuan_harga))
                    -
                @else
                    @format_rupiah($data->pengajuan_harga)
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Tanggal Mulai Proses Copyright</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->mulai_p_copyright))
                    -
                @else
                    {{ Carbon\Carbon::parse($data->mulai_p_copyright)->translatedFormat('l d F Y, H:i') }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Tanggal Selesai Proses Copyright</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->selesai_p_copyright))
                    -
                @else
                    {{ Carbon\Carbon::parse($data->selesai_p_copyright)->translatedFormat('l d F Y, H:i') }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Tanggal Mulai Proof Prodev</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->mulai_proof))
                    -
                @else
                    {{ Carbon\Carbon::parse($data->mulai_proof)->translatedFormat('l d F Y, H:i') }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Tanggal Selesai Proof Prodev</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->selesai_proof))
                    -
                @else
                    {{ Carbon\Carbon::parse($data->selesai_proof)->translatedFormat('l d F Y, H:i') }}
                @endif
            </p>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Tanggal Masuk Pracetak</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->tgl_masuk_pracetak))
                    -
                @else
                    {{ Carbon\Carbon::parse($data->tgl_masuk_pracetak)->translatedFormat('l d F Y, H:i') }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Setter</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->setter))
                    -
                @else
                    @foreach ($nama_setter as $set)
                        <span class="bullet"></span>
                        <a
                            href="{{ url('/manajemen-web/user/' . $set->id) }}">{{ $set->nama }}</a>
                        <br>
                    @endforeach
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Tanggal Mulai Setting</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->mulai_setting))
                    -
                @else
                    {{ Carbon\Carbon::parse($data->mulai_setting)->translatedFormat('l d F Y, H:i') }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Tanggal Selesai Setting</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->selesai_setting))
                    -
                @else
                    {{ Carbon\Carbon::parse($data->selesai_setting)->translatedFormat('l d F Y, H:i') }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Korektor</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->korektor))
                    -
                @else
                    @foreach ($nama_korektor as $kor)
                        <span class="bullet"></span>
                        <a
                            href="{{ url('/manajemen-web/user/' . $kor->id) }}">{{ $kor->nama }}</a>
                        <br>
                    @endforeach
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Tanggal Mulai Koreksi</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->mulai_koreksi))
                    -
                @else
                    {{ Carbon\Carbon::parse($data->mulai_koreksi)->translatedFormat('l d F Y, H:i') }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Tanggal Selesai Koreksi</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->selesai_koreksi))
                    -
                @else
                    {{ Carbon\Carbon::parse($data->selesai_koreksi)->translatedFormat('l d F Y, H:i') }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Bulan</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->bulan))
                    -
                @else
                    {{ Carbon\Carbon::parse($data->bulan)->translatedFormat('F Y') }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Catatan</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->catatan))
                    -
                @else
                    {{ $data->catatan }}
                @endif
            </p>
        </div>
    </div>
</div>
