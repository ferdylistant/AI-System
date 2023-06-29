<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title }}</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.7.2/font/bootstrap-icons.min.css"
        integrity="sha512-1fPmaHba3v4A7PaUsComSM4TBsrrRGs+/fv0vrzafQ+Rw+siILTiJa0NtFfvGeyY5E182SDTaF5PqP+XOHgJag=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
        <div class="row justify-content-between">
            <div class="col-auto">
                <h2>{{ $title }} <small>{{ $date }}</small></h2>
            </div>
            <div class="col-auto">
                <span>Total penulis:<b>{{$data->count()}}</b></span>
            </div>

        </div>

    <div class="table-responsive">

        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Email</th>
                    <th scope="col">TTL</th>
                    <th scope="col">Alamat Domisili</th>
                    <th scope="col">Ponsel</th>
                    <th scope="col">NPWP</th>
                    <th scope="col">KTP</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $no => $d)
                    <tr>
                        <td>{{ $no += 1 }}</td>
                        <td>{{ $d->nama }}</td>
                        <td>{{ $d->email ?? '-' }}</td>
                        <td>
                            {{ $d->tempat_lahir ?? '-' }}
                            <small class="d-block">{{ $d->tanggal_lahir ?? '-' }}</small>
                        </td>
                        <td>{{ $d->alamat_domisili ?? '-' }}</td>
                        <td>{{ $d->ponsel_domisili ?? '-' }}</td>
                        <td>{{ $d->npwp ?? '-' }}</td>
                        <td>{{ $d->ktp ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- <div class="page-break"></div> --}}
    </div>
    <!-- Bootstap JavaScript and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
        integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
    </script>
</body>

</html>
