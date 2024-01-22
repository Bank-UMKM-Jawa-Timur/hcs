@extends('layouts.app-template')

@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <div class="text-2xl font-bold tracking-tighter">
                Tambah Penghasilan Tanpa Pajak
            </div>
            <div class="flex gap-3">
                <a href="#" class="text-sm text-gray-500">Setting</a>
                <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                <a href="#" class="text-sm text-gray-500">Master</a>
                <i class="ti ti-circle-filled text-theme-primary mt-1"></i>
                <a href="{{ route('umur.index') }}" class="text-sm text-gray-500 font-bold">Penghasilan Tanpa Pajak</a>
            </div>
        </div>
        @can('setting - master - penghasilan tanpa pajak - create penghasilan tanpa pajak')
            <a class="mb-3" href="{{ route('ptkp.create') }}">
                <button  class="btn btn-primary is-btn is-primary">Tambah Data</button>
            </a>
        @endcan

    </div>
</div>

<div class="body-pages">
    <div class="table-wrapping">
        <div class="">
            <div class="col-lg-12">
                <div class="tables table-responsive overflow-hidden content-center">
                    <table class="table whitespace-nowrap" id="table" style="width: 100%">
                        <thead class="text-primary">
                            <th>
                                No
                            </th>
                            <th>
                                Kode
                            </th>
                            <th>
                                PTKP Per Tahun
                            </th>
                            <th>
                                PTKP Per Bulan
                            </th>
                            <th>
                                Keterangan
                            </th>
                            <th>
                                Aksi
                            </th>
                        </thead>
                        @php
                            $no = 1;
                        @endphp
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $item->kode }}</td>
                                    <td> @currency($item->ptkp_tahun) </td>
                                    <td> @currency($item->ptkp_bulan) </td>
                                    <td>{{ $item->keterangan }}</td>
                                    <td>
                                        <div class="flex gap-2">
                                            @can('setting - master - penghasilan tanpa pajak - edit penghasilan tanpa pajak')
                                                <div class="">
                                                    <a class="btn btn-warning is-btn btn-warning" href="{{ route('ptkp.edit', $item->id) }}">
                                                        Edit
                                                    </a>
                                                </div>
                                            @endcan
                                            <div class="">
                                                <a href="javascript:void(0)" class="btn btn-danger"
                                                    data-modal-target="confirmHapusModal{{$item->id}}"
                                                    data-modal-toggle="confirmHapusModal{{$item->id}}">
                                                    Hapus
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                {{-- modal hapus --}}
                                <div class="modal-layout hidden" id="confirmHapusModal{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
                                    <div class="relative p-4 w-full max-w-4xl max-h-full" role="document">
                                        <div class="relative bg-white rounded-lg shadow">
                                            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t ">
                                                <h3 class="text-xl font-semibold text-gray-900">
                                                    Konfirmasi Hapus Data
                                                </h3>
                                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal">
                                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                    </svg>
                                                    <span class="sr-only">Close modal</span>
                                                </button>
                                            </div>
                                            <div class="p-4 md:p-5 space-y-4">
                                                <p>Apakah Anda Yakin Ingin Menghapus penghasilan Tanpa Pajak, Kode <b>{{$item->kode}}</b>?</p>
                                            </div>
                                            <div class="body-pages flex justify-end gap-2">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <form action="{{ route('ptkp.destroy', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                    <input type="hidden" name="idPengajuan" value="{{$item->id}}">
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
    </script>
@endsection
