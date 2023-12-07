@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title font-weight-bold">Tambah Penghasilan Tanpa Pajak</h5>
            <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('ptkp.index') }}">Penghasilan Tanpa Pajak</a></p>
        </div>

        <div class="card-body">
            <div class="col">
                <div class="row">
                    <a class="mb-3" href="{{ route('ptkp.create') }}">
                        <button  class="is-btn is-primary">Tambah Data</button>
                    </a>
                    <div class="table-responsive overflow-hidden content-center">
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
                                        {{-- @dd($item->kode) --}}
                                        <td>
                                            {{-- <div class="row"> --}}
                                            <div class="d-flex ">
                                                <div class="m-2">
                                                    <a class="btn btn-warning" href="{{ route('ptkp.edit', $item->id) }}">
                                                    Edit
                                                </a>
                                                </div>
                                            <div class="m-2">
                                                <a href="javascript:void(0)" class="btn btn-danger" data-toggle="modal" data-target="#confirmHapusModal{{$item->id}}">
                                                        Hapus
                                                </a>
                                            </div>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- modal hapus --}}
                            <div class="modal fade" id="confirmHapusModal{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Hapus Data</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda Yakin Ingin Menghapus penghasilan Tanpa Pajak, Kode <b>{{$item->kode}}</b>?</p>
                                        </div>
                                        <div class="modal-footer">
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
