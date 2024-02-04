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
                 <div class="grid lg:grid-cols-2 gap-5 items-center md:grid-cols-2 grid-cols-1 mb-3">
                     <div class="input-box">
                         <label for="selectfield">Penghasilan</label>
                         <select name="id_tunjangan" class="form-input" id="nip">
                             <option value="">--- Pilih Penghasilan ---</option>
                             @foreach ($dataTunjangan as $item)
                             <option value="{{ $item->id }}" {{$item->id == Request()->get('idTunjangan') ? 'selected' : ''}}>{{ $item->nama_tunjangan }}</option>
                         @endforeach
                         </select>
                     </div>
                     <div class="input-box">
                         <label for="datefield">Tanggal</label>
                         <input type="date" name="tanggal" value="{{$tanggal}}" id="datefield" class="form-input" required>
                     </div>
                </div>
                <input type="hidden" name="createdAt" value="{{Request()->get('tanggal')}}">
                <input type="hidden" name="bulan" value="{{Request()->get('bulan')}}">
                <input type="hidden" name="kd_entitas" value="{{Request()->get('kdEntitas')}}">
                <input type="hidden" name="temp_nip[]" id="temp_nip">
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
                                        <input type="hidden" name="nip[]" value="{{$item->nip}}">
                                        <input type="hidden" name="item_id[]" value="{{$item->id_penghasilan}}">
                                </td>
                                <td>
                                    <button id="btn-hapus" type="button" data-nip="{{$item->nip}}" class="btn btn-danger btn-minus">-</button>
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

        var temp_nip_array = [];

        $(document).ready(function() {
            $("#table_item").on('click', '.btn-minus', function() {
                const nip = $(this).data("nip");
                console.log(nip);

                $("#temp_nip").val('');
                temp_nip_array.push(nip);

                console.log(temp_nip_array);
                $("#temp_nip").val(JSON.stringify(temp_nip_array));

                $(this).closest('tr').remove();
            });
        });
    </script>
@endpush
