@extends('layouts.template')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-header">
                <h5 class="card-title">Data Demosi</h5>
                <p class="card-title"><a href="/">Dashboard</a> > <a href="/">Demosi</a> </p>
            </div>
        </div>
        <div class="card-body">
            <div class="col">
                <div class="row">
                    <a class="mb-3" href="/demosi/add">
                        <button  class="btn btn-primary">Tambah Demosi</button>
                    </a>
                    <div class="table-responsive overflow-hidden">
                        <table class="table" id="table">
                            <thead class="text-primary">
                                <th>
                                    Id Demosi
                                </th>
                                <th>
                                    Nama Karyawan
                                </th>
                                <th>
                                    Tanggal Mutasi
                                </th>
                                <th>
                                    Jabatan Lama
                                </th>
                                <th>
                                    Jabatan Baru
                                </th>
                                <th>
                                    Bukti SK
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