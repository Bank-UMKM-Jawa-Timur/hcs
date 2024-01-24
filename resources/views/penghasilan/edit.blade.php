@extends('layouts.app-template')

@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Data Penghasilan Tidak Teratur</h2>
                <div class="breadcrumb">
                    <a href="/" class="text-sm text-gray-500 font-bold">Dashboard</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="{{ route('penghasilan-tidak-teratur.index') }}"
                        class="text-sm text-gray-500 font-bold">Penghasilan Tidak Teratur </a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <p class="text-sm text-gray-500">Edit</p>
                </div>
            </div>
            <div class="button-wrapper flex gap-3">
                <a href="{{ route('penghasilan-tidak-teratur.edit-tunjangan-tidak-teratur')}}?idTunjangan={{Request()->get('idTunjangan')}}&
                                            tanggal={{Request()->get('tanggal')}}&
                                            kdEntitas={{Request()->get('kdEntitas')}}"
                class="btn btn-warning-light ml-1">Import</a>
            </div>
        </div>
    </div>

    <div class="body-pages">
        <div class="table-wrapping">
            <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 flex gap-7" role="alert">
                <h6 class="text-sm text-blue-900 font-semibold"> Cabang : <b>{{ $nameCabang->nama_cabang }}</b></h6> |
                <h6 class="text-sm text-blue-900 font-semibold"> Tunjangan : <b>{{ $tunjangan->nama_tunjangan }}</b></h6>
            </div>
            <form action="{{ route('penghasilan-tidak-teratur.edit-tunjangan-tidak-teratur-new-post') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary mb-2">Simpan</button>
                <table class="tables" id="table_item">
                    <thead>
                        <th>NIP</th>
                        <th>Nama Karyawan</th>
                        {{-- <th>Kantor</th> --}}
                        <th>Jabatan</th>
                        <th>Nominal</th>
                        <th>Aksi</th>
                    </thead>
                    <tbody>
                        @forelse ($data as $key => $item)
                            <tr>
                                <td>{{ $item->nip }}</td>
                                <td>{{ $item->nama_karyawan }}</td>
                                <td>{{ $item->display_jabatan }}</td>
                                <td>
                                    <input type="text" id="nominal_{{ $key }}" name="nominal[]"
                                        onfocus="inputFormatRupiah(this.id)" onkeyup="inputFormatRupiah(this.id)"
                                        class="form-input" value="{{ number_format($item->nominal, 0, ',', '.') }}">
                                        <input type="hidden" name="id_tunjangan" value="{{Request()->get('idTunjangan')}}">
                                        <input type="hidden" name="createdAt" value="{{Request()->get('tanggal')}}">
                                        <input type="hidden" name="bulan" value="{{Request()->get('bulan')}}">
                                        <input type="hidden" name="nip[]" value="{{$item->nip}}">
                                        <input type="hidden" name="item_id[]" value="{{$item->id_penghasilan}}">
                                </td>
                                <td>
                                    <button id="btn-hapus" type="button" class="btn btn-danger btn-minus">-</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <th colspan="6">Data Kosong</th>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
        </div>
    </div>
@endsection
@push('extraScript')
    <script>

        function formatRupiahTwo(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }


        function inputFormatRupiah(id) {
            $('#' + id).on('input', function() {
                var inputValue = $(this).val().replace(/\D/g, "");
                var formattedValue = formatRupiahTwo(inputValue);
                $(this).val(formattedValue);
            });
        }

        $("#table_item").on('click', '.btn-minus', function() {
            $(this).closest('tr').remove();
        })
    </script>
@endpush
