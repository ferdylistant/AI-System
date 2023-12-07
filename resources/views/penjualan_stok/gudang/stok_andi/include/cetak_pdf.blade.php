<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>
        Data Stok Andi
    </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
    <link rel="icon" type="image/x-icon" href="{{ url('images/logo.png') }}">
</head>
<body>
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card card-primary">
                <div class="card-header justify-content-between">
                        <h4 class="section-title">Data Stok Andi</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($data as $k => $value)
                        <div class="col-12 col-lg-4 mb-1">
                            <div class="list-group-item flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                    @switch ($k)
                                        @case ('isbn')
                                            @php $head = Str::upper($k); @endphp
                                            @break;
                                        @case ('kode_sku')
                                            @php $head = 'Kode SKU'; @endphp
                                            @break;
                                        @case ('nama_kb')
                                            @php $head = 'Kelompok Buku'; @endphp
                                            @break;
                                        @case ('nama_skb')
                                            @php $head = 'Sub-Kelompok Buku'; @endphp
                                            @break;
                                        @case ('is_active')
                                            @php $head = 'Status'; @endphp
                                            @break;

                                        @default
                                            @php $head = Str::headline($k); @endphp
                                            @break;
                                    @endswitch
                                    <h6 class="mb-1">{{$head}}</h6>
                                </div>
                                <p class="mb-1 text-monospace">{!! $value !!}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="row mb-4">
                        <div class="col-12 col-lg-12 mb-1">
                            <div class="list-group-item flex-column align-items-start">
                                <div class="d-flex w-100">
                                    <h6 class="mb-1">Harga Jual</h6>
                                    <a href="javascript:void(0)" class="text-primary" tabindex="0" role="button"
                                    data-toggle="popover" data-trigger="focus" title="Informasi"
                                    data-content="Harga jual yang ditampilkan merupakan data yang hanya diinputkan saja.">
                                    <abbr title="">
                                    <i class="fas fa-info-circle me-3"></i>
                                    </abbr>
                                    </a>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4 mb-1">
                                        <span class="mb-1 text-monospace font-weight-bold">Zona 1</span>
                                        <p>@format_rupiah($zone1)</p>
                                    </div>
                                @foreach ($harga_jual as $value)
                                <div class="col-sm-4 mb-1">
                                    <span class="mb-1 text-monospace font-weight-bold">{!! $value->nama !!}</span>
                                    <p>@format_rupiah($value->harga)</p>
                                </div>
                                @endforeach
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
    <script src="{{ url('vendors/bootstrap/dist/js/bootstrap.js') }}"></script>
    <script src="{{ url('vendors/stisla/js/stisla.js') }}"></script>
</html>
