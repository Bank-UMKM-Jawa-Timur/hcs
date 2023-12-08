@extends('layouts.template')
@section('content')

    <div class="card-header">
        <div class="d-lg-flex justify-content-between w-100 p-3">
            <div class="card-header">
                <h5 class="card-title font-weight-bold">Data Penambahan Bruto</h5>
                <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('cabang.index') }}">Kantor Cabang > <a href="" class="text-secondary">Penambahan Bruto</a></p>
            </div>
            <div class="card-header row mt-3 mr-8 pr-5" >
                <a class="mb-3" href="{{ route('penambahan-bruto.create') }}?profil_kantor={{$_GET['profil_kantor']}}">
                    <button class="is-btn is-primary">Tambah</button>
                </a>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="col">
                <div class="row">
                    @can('setting - kantor pusat - penambahan bruto - create penambahan bruto')
                    <a class="mb-3" href="{{ route('penambahan-bruto.create') }}?profil_kantor={{$_GET['profil_kantor']}}">
                        <button class="btn btn-primary">Tambah</button>
                    </a>
                    @endcan
                    <div class="table-responsive overflow-hidden content-center">
                        <table class="table whitespace-nowrap" id="table" style="width: 100%">
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
                                    <td class="text-center">
                                        @can('setting - kantor pusat - penambahan bruto - edit penambahan bruto')
                                            <a href="{{ route('penambahan-bruto.edit', $item->id) }}">
                                                <button class="btn btn-warning">
                                                    Edit
                                                </button>
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
