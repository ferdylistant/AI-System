@extends('layouts.app')

@section('cssRequired')
<link rel="stylesheet" href="{{url('vendors/jquery-magnify/dist/jquery.magnify.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/summernote/dist/summernote-bs4.css')}}">
<link rel="stylesheet" href="{{url('vendors/flipbook/min_version/ipages.min.css')}}">
@endsection

@section('cssNeeded')
<style>

</style>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ url('penerbitan/penulis') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Detail Penulis</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card card-primary profile-widget">
                    <div class="profile-widget-header">
                        <img alt="image" src="{{url('storage/penerbitan/penulis/'.$penulis->id.'/'.$penulis->foto_penulis)}}" class="rounded-circle profile-widget-picture">
                    </div>
                    <div class="profile-widget-description">
                        <div class="row mb-5">
                            <div class="form-group col-12 mb-4">
                                <label>Nama: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$penulis->nama}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Kewarganegaraan: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$penulis->kewarganegaraan}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Tempat Lahir: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-globe"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$penulis->tempat_lahir}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Tanggal Lahir: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$penulis->tanggal_lahir}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Telepon: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-phone"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$penulis->telepon_domisili}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Ponsel: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-mobile"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$penulis->ponsel_domisili}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Email: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-envelope"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$penulis->email}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-12 mb-4">
                                <label>Alamat Domisili: </label>
                                <div class="input-group">
                                    <textarea class="form-control" readonly>{{$penulis->alamat_domisili}}</textarea>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Facebook: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fab fa-facebook-square"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$penulis->sosmed_fb}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Twitter: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fab fa-twitter-square"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$penulis->sosmed_tw}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Instagram: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fab fa-instagram"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$penulis->sosmed_ig}}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row  mb-5">
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Nama Kantor: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-building"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$penulis->nama_kantor}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Jabatan Kantor: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user-circle"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$penulis->jabatan_dikantor}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Telepon Kantor: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-phone"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$penulis->telepon_kantor}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-12 mb-4">
                                <label>Alamat Kantor: </label>
                                <div class="input-group">
                                    <textarea class="form-control" readonly>{{$penulis->alamat_kantor}}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row  mb-5">
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>No Rekening: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="far fa-credit-card"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$penulis->no_rekening}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Nama Bank: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-university"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$penulis->bank}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Atas Nama: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$penulis->bank_atasnama}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>NPWP: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-gavel"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$penulis->npwp}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>No.KTP: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-id-card"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$penulis->ktp}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Scan NPWP: </label>
                                @if(!is_null($penulis->scan_npwp))
                                <a class="btn btn-sm btn-primary" data-magnify="gallery"
                                    href="{{ url('storage/penerbitan/penulis/'.$penulis->id.'/'.$penulis->scan_npwp) }}"
                                >Lihat</a>
                                @else
                                <div><small class="text-muted">#Tidak ada file diupload.</small></div>
                                @endif
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Scan KTP: </label>
                                @if(!is_null($penulis->scan_ktp))
                                <a class="btn btn-sm btn-primary" data-magnify="gallery"
                                    href="{{ url('storage/penerbitan/penulis/'.$penulis->id.'/'.$penulis->scan_ktp) }}"
                                >Lihat</a>
                                @else
                                <div><small class="text-muted">#Tidak ada file diupload.</small></div>
                                @endif
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>File Hibah Royalti (<span class="text-danger">.pdf</span>) </label>
                                @if(!is_null($penulis->file_hibah_royalti))
                                <div class="ipgs-flipbook" data-pdf-src="{{url('storage/penerbitan/penulis/'.$penulis->id.'/'.$penulis->file_hibah_royalti)}}"
                                    data-book-engine="onepageswipe" style="max-height: 300px;"></div>
                                @else
                                <div><small class="text-muted">#Tidak ada file diupload.</small></div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="form-group col-12 mb-4">
                                <label>URL File Tentang Penulis: (<span class="text-danger">-Format URL</span>)</label>
                                <div class="input-group">
                                    <textarea class="form-control" disabled>{{is_null($penulis->url_tentang_penulis)?'-':$penulis->url_tentang_penulis}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('jsRequired')
<script src="{{url('vendors/jquery-magnify/dist/jquery.magnify.min.js')}}"></script>
<script src="{{url('vendors/summernote/dist/summernote-bs4.js')}}"></script>
<script src="{{url('vendors/flipbook/min_version/pdf.min.js')}}"></script>
<script src="{{url('vendors/flipbook/min_version/jquery.ipages.min.js')}}"></script>
@endsection

@section('jsNeeded')
<script>
$(function() {
    $(".summernote-penulis").summernote({
        dialogsInBody: true,
        minHeight: 300,
        width: 1920,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['para', ['paragraph']]
        ]
    });
    $('.ipgs-flipbook').ipages({
        toolbarControls: [
            {type:'share',        active:false},
            {type:'sound',        active:false, optional: false},
            {type:'outline',      active:false},
            {type:'thumbnails',   active:true},
            {type:'gotofirst',    active:true},
            {type:'prev',         active:true},
            {type:'pagenumber',   active:true},
            {type:'next',         active:true},
            {type:'gotolast',     active:true},
            {type:'zoom-in',      active:false},
            {type:'zoom-out',     active:false},
            {type:'zoom-default', active:false},
            {type:'optional',     active:false},
            {type:'download',     active:false, optional: false},
            {type:'fullscreen',   active:true, optional: false},
        ],
    });
})
</script>
@endsection
