<table>
    <thead>
    <tr>
        <th>NIP</th>
        <th style="width: 300px">Nama Karyawan</th>
        <th style="width: 100px">No Rekening</th>
        <th>Nominal</th>
        <th>Keterangan</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        <tr>
            <td>{{ $item->nip }}</td>
            <td>{{ $item->nama_karyawan }}</td>
            <td>{{ $item->no_rekening ?? '-' }}</td>
            <td></td>
            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>
