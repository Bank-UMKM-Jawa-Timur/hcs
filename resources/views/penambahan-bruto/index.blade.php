@push('style')
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
@endpush
@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Data Penambahan Bruto</h5>
            <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('cabang.index') }}">Kantor Cabang > <a href="" class="text-secondary">Penambahan Bruto</a></p>
        </div>
        
        <div class="card-body">
            <div class="col">
                <div class="row">
                    <a class="mb-3" href="{{ route('penambahan-bruto.create') }}?profil_kantor={{$_GET['profil_kantor']}}">
                        <button class="btn btn-primary">Tambah</button>
                    </a>
                    <div class="table-responsive overflow-hidden content-center">
                        <table class="table whitespace-nowrap" id="table" style="width: 100%">
                            <thead class=" text-primary">
                                <th rowspan="2">No</th>
                                <th rowspan="2">Nama Cabang</th>
                                <th rowspan="2">JKK</th>
                                <th rowspan="2">JHT</th>
                                <th rowspan="2">JKM</th>
                                <th colspan="3">Kesehatan</th>
                                <th rowspan="2">JP</th>
                                <th rowspan="2">Total</th>
                                <th rowspan="2">Status</th>
                                <th rowspan="2" class="text-center">Aksi</th>
                            </thead>
                            <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama_cabang }}</td>
                                    <td>{{ $item->jkk }}</td>
                                    <td>{{ $item->jht }}</td>
                                    <td>{{ $item->jkm }}</td>
                                    <td>{{ $item->kesehatan }}</td>
                                    <td>{{ $item->kesehatan_batas_atas }}</td>
                                    <td>{{ $item->kesehatan_batas_bawah }}</td>
                                    <td>{{ $item->jp }}</td>
                                    <td>{{ $item->total }}</td>
                                    <td>
                                        <input type="checkbox" name="check" id="check" @if($item->active) checked @endif>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('penambahan-bruto.edit', $item->id) }}">
                                            <button class="btn btn-warning">
                                                Edit
                                            </button>
                                        </a>
                                        <form action="{{ route('penambahan-bruto.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="id_profil_kantor" value="{{$item->id_profil_kantor}}">
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
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
    });
</script>
@endsection 