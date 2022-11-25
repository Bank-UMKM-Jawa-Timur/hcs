@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-header">
            <p class="card-title"><a href="/">Dashboard</a> / <a href="/">Karyawan</a> / Data Karyawan </p>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                <a href="/data_karyawan/add">
                    <button  class="btn btn-primary">Tambah</button>
                </a>
                <div class="table-responsive overflow-hidden">
                    <table class="table" id="table">
                        <thead class="text-primary">
                            <th>
                                NIP
                            </th>
                            <th>
                                Nama Karyawan
                            </th>
                            <th>
                                Pangkat
                            </th>
                            <th>
                                SK Pangkat
                            </th>
                            <th>
                                Tanggal Pengangkatan
                            </th>
                            <th>
                                Aksi
                            </th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_script')
    <script>
        $(document).ready( function () {
            $('#table').DataTable();
        });
    </script>
@endsection