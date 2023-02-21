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
                            <a href="{{ url('/penerbitan/penulis/detail-penulis/' . $pen->id) }}">{{ $pen->nama }}</a>
                        </p>
                    @endforeach
                </li>
            </ul>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Nama Pena</h6>
            </div>
            @if ((is_null($data->nama_pena)))
            <p class="mb-1 text-monospace">
                    -
            </p>
            @else
            <ul class="list-unstyled list-inline">
                @foreach (json_decode($data->nama_pena) as $pen)
                    <li class="list-inline-item">
                    <p class="mb-1 text-monospace">
                        <span class="bullet"></span>
                        <span>{{ $pen }}</span>
                    </p>
                    </li>
                @endforeach

            </ul>
            @endif
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
                    {{ $imprint }}
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
                    <a href="{{ url('/manajemen-web/user/' . $pic->id) }}">{{ $pic->nama }}</a>
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
                <h6 class="mb-1">Format Buku</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->format_buku))
                    -
                @else
                    {{ $format_buku . ' cm' }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Jilid</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->jilid))
                    -
                @else
                    {{ $data->jilid }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Warna</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->warna))
                    -
                @else
                    {{ $data->warna }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Tipografi</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->tipografi))
                    -
                @else
                    {{ $data->tipografi }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Finishing Cover</h6>
            </div>
            @if (is_null($data->finishing_cover) || $data->finishing_cover == '[]')
                <p class="mb-1 text-monospace">
                    -
                </p>
            @else
                <ul class="list-unstyled list-inline">
                    @foreach (json_decode($data->finishing_cover) as $finishing_cover)
                        <li class="list-inline-item">
                            <p class="mb-1 text-monospace">
                                <span class="bullet"></span>
                                <span>{{ $finishing_cover }}</span>
                            </p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Contoh Cover</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->contoh_cover))
                    -
                @else
                    <a href="{{$data->contoh_cover}}" class="text-primary">{{$data->contoh_cover}}</a>
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Deskripsi Front Cover</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->des_front_cover))
                    -
                @else
                    {{$data->des_front_cover}}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Deskripsi Back Cover</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->des_back_cover))
                    -
                @else
                    {{$data->des_back_cover}}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Tanggal Mulai Approval Prodev</h6>
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
                <h6 class="mb-1">Tanggal Selesai Approval Prodev</h6>
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
                @if (is_null($data->tgl_masuk_cover))
                    -
                @else
                    {{ Carbon\Carbon::parse($data->tgl_masuk_cover)->translatedFormat('l d F Y, H:i') }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Desainer</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (($data->desainer == '[null]')||(is_null($data->desainer)))
                    -
                @else
                    @foreach ($nama_desainer as $des)
                        <span class="bullet"></span>
                        <a
                            href="{{ url('/manajemen-web/user/' . $des->id) }}">{{ $des->nama }}</a>
                        <br>
                    @endforeach
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Tanggal Mulai Pengajuan Cover</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->mulai_pengajuan_cover))
                    -
                @else
                    {{ Carbon\Carbon::parse($data->mulai_pengajuan_cover)->translatedFormat('l d F Y, H:i') }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Tanggal Selesai Pengajuan Cover</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->selesai_pengajuan_cover))
                    -
                @else
                    {{ Carbon\Carbon::parse($data->selesai_pengajuan_cover)->translatedFormat('l d F Y, H:i') }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Tanggal Mulai Back Cover</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->mulai_cover))
                    -
                @else
                    {{ Carbon\Carbon::parse($data->mulai_cover)->translatedFormat('l d F Y, H:i') }}
                @endif
            </p>
        </div>
        <div class="list-group-item flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">Tanggal Selesai Back Cover</h6>
            </div>
            <p class="mb-1 text-monospace">
                @if (is_null($data->selesai_cover))
                    -
                @else
                    {{ Carbon\Carbon::parse($data->selesai_cover)->translatedFormat('l d F Y, H:i') }}
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
