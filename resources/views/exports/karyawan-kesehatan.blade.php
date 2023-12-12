<table>
    <thead>
    <tr>
        <th>NIP</th>
        <th>Nominal</th>
        <th>Keterangan</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        <tr>
            <td>{{ $item->nip }}</td>
            <td></td>
            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>
