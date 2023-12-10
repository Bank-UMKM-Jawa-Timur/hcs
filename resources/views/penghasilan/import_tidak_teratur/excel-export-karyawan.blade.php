<table>
    <thead>
    <tr>
        <th>NIP</th>
        <th>Kredit Koprasi</th>
        <th>Iuran Koprasi</th>
        <th>Kredit Pegawai</th>
        <th>Iuran IK</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        <tr>
            <td>{{ $item->nip }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>
