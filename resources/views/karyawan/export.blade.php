<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Karyawan</title>
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Kantor</th>
                <th>Jabatan</th>
                <th>No Rekening</th>
                <th>NPWP</th>
                <th>Status PTKP</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($karyawan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nip }}</td>
                    <td>{{ $item->nama_karyawan }}</td>
                    <td>{{ $item->entitas->type == 2 ? $item->entitas->cab->nama_cabang : 'Pusat' }}</td>
                    <td>{{ $item->display_jabatan }}</td>
                    <td>{{ $item->no_rekening ?? '-' }}</td>
                    <td>{{ $item->npwp ?? '-' }}</td>
                    <td>{{ $item->status_ptkp ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>