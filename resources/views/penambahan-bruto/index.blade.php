@extends('layouts.app-template')
@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <div class="text-2xl font-bold tracking-tighter">
                    Data Penambahan Bruto
                </div>
                <div class="flex gap-3">
                    <a href="#" class="text-sm text-gray-500">Setting</a>
                    <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                    <a href="{{ route('cabang.index') }}" class="text-sm text-gray-500">Kantor Cabang</a>
                    <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                    <a href="" class="text-sm text-gray-500 font-bold">Penambah Bruto</a>
                </div>
            </div>
            @can('setting - kantor pusat - penambahan bruto - create penambahan bruto')
                <a class="mb-3" href="{{ route('penambahan-bruto.create') }}?profil_kantor={{$_GET['profil_kantor']}}">
                    <button class="btn btn-primary is-btn is-primary">Tambah</button>
                </a>
            @endcan
        </div>
    </div>

    <div class="body-pages p-4">
        <div class="table-wrapping">
            <table class="tables-stripped border-none" id="table" style="width: 100%">
                <thead class=" text-primary">
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">Nama Cabang</th>
                        <th rowspan="2">JKK(%)</th>
                        <th rowspan="2">JHT(%)</th>
                        <th rowspan="2">JKM(%)</th>
                        <th colspan="3" class="text-center">Kesehatan</th>
                        <th rowspan="2">JP(%)</th>
                        <th rowspan="2">Total(%)</th>
                        <th rowspan="2">Status</th>
                        <th rowspan="2" class="text-center">Aksi</th>
                    </tr>
                    <tr>
                        <th class="text-center">(%)</th>
                        <th class="text-center">Batas atas(Rp)</th>
                        <th class="text-center">Batas bawah(Rp)</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nama_cabang }}</td>
                        <td>{{ $item->jkk }}</td>
                        <td>{{ $item->jht }}</td>
                        <td>{{ $item->jkm }}</td>
                        <td class="text-center">{{ $item->kesehatan }}</td>
                        <td class="text-center">{{ number_format($item->kesehatan_batas_atas, 0, '.', '.') }}</td>
                        <td class="text-center">{{ number_format($item->kesehatan_batas_bawah, 0, '.', '.') }}</td>
                        <td>{{ $item->jp }}</td>
                        <td>{{ $item->total }}</td>
                        <td>
                            <input type="checkbox" name="check" id="check" @if($item->active) checked @endif>
                        </td>
                        <td class="text-center flex gap-3">
                            @can('setting - kantor pusat - penambahan bruto - edit penambahan bruto')
                                <a href="{{ route('penambahan-bruto.edit', $item->id) }}" class="btn btn-warning is-btn">
                                    Edit
                                </a>
                            @endcan
                            @can('setting - kantor pusat - penambahan bruto - delete penambahan bruto')
                                <form action="{{ route('penambahan-bruto.destroy', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="id_profil_kantor" value="{{$item->id_profil_kantor}}">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
@endsection

@section('custom_script')
<script>
    $(document).ready(function() {
        var table = $('#table').DataTable({
            'autoWidth': false,
            'dom': 'Rlfrtip',
            'colReorder': {
                'allowReorder': false
            }
        });
        $("[data-toggle='switch']").bootstrapSwitch();

    });

</script>
@endsection
