@extends('layouts.template')
@section('content')
@php
$profilKantor = \DB::table('mst_profil_kantor')->select('id','kd_cabang')->find($_GET['profil_kantor']);
@endphp
<div class="d-lg-flex justify-content-between w-100 p-3">
    <div class="card-header">
        <h5 class="card-title font-weight-bold">Data Pengurangan Bruto</h5>
        @if ($profilKantor)
            @if ($profilKantor->kd_cabang == '000')
                <p class="card-title"><a href="">Setting </a> > <a href="">Kantor Pusat</a> > <a href="" class="text-secondary">Pengurangan Bruto</a></p>
            @else
                <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('cabang.index') }}">Kantor Cabang > <a href="" class="text-secondary">Pengurangan Bruto</a></p>
            @endif
        @endif
    </div>
    <div class="card-header row mt-3 mr-8 pr-5">
        @can('setting - kantor pusat - pengurangan bruto - create pengurangan bruto')
        <a class="mb-3" href="{{ route('pengurangan-bruto.create') }}?profil_kantor={{$_GET['profil_kantor']}}">
            <button class="is-btn is-primary">Tambah</button>
        </a>
        @endcan
    </div>
</div>

<div class="card-body p-3">
    <div class="col">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive overflow-hidden content-center">
                    <table class="table whitespace-nowrap" id="table" style="width: 100%">
                        <thead class=" text-primary">
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Nama Cabang</th>
                                <th rowspan="2">DPP(%)</th>
                                <th colspan="3" class="text-center">JP(%)</th>
                                <th rowspan="2" class="text-center">Status</th>
                                <th rowspan="2" class="text-center">Aksi</th>
                            </tr>
                            <tr>
                                <th class="text-center">(%)</th>
                                <th class="text-center">Januari - Februari(Rp)</th>
                                <th class="text-center">Maret - Desember(Rp)</th>
                                <th rowspan="2"></th>
                                <th rowspan="2"></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_cabang }}</td>
                                <td>{{ $item->dpp }}</td>
                                <td class="text-center">{{ $item->jp }}</td>
                                <td class="text-center">{{ number_format($item->jp_jan_feb, 0, '.', '.') }}</td>
                                <td class="text-center">{{ number_format($item->jp_mar_des, 0, '.', '.') }}</td>
                                <td class="text-center">
                                    <input type="checkbox" name="check" id="check" @if($item->active) checked @endif>
                                </td>
                                <td class="text-center">
                                    @can('setting - kantor pusat - pengurangan bruto - edit pengurangan bruto')
                                    <a href="{{ route('pengurangan-bruto.edit', $item->id) }}">
                                        <button class="btn btn-warning">
                                            Edit
                                        </button>
                                    </a>
                                    @endcan
                                    @can('setting - kantor pusat - pengurangan bruto - delete pengurangan bruto')
                                    <form action="{{ route('pengurangan-bruto.destroy', $item->id) }}" method="POST">
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

    function formatRupiah(angka, prefix){
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka satuan ribuan
            if(ribuan){
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

</script>
@endsection
