@extends('layouts.template')

@section('content')
    <div class="card-header">
    <div class="card-header">
        <h5 class="card-title">Data Table Karyawan Sp</h5>
        <p class="card-title"><a href="">Dashboard </a> > <a href="">Table Karyawan Sp</a></p>
    </div>
        <div class="card-body">
        <div class="col">
            <div class="row">
            <a class="mb-3" href="">
                <button class="btn btn-primary">Tambah Karyawan SP</button>
            </a>
            <div class="table-responsive overflow-hidden content-center">
                <table class="table whitespace-nowrap" id="table" style="width: 100%">
                    <thead class=" text-primary">
                    <th>
                        No
                    </th>
                    <th>
                        Kode Divisi
                    </th>
                    <th>
                        Nama Divisi
                    </th>
                    <th>
                        Aksi
                    </th>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            1
                        </td>
                        <td>
                            CSR
                        </td>
                        <td>
                            Corporate Secretary
                        </td>
                        <td>
                            <button class="btn btn-warning">
                                Edit
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            2
                        </td>
                        <td>
                            CSR
                        </td>
                        <td>
                            Corporate Secretary
                        </td>
                        <td>
                            <button class="btn btn-warning">
                                Edit
                            </button>
                        </td>
                    </tr>

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