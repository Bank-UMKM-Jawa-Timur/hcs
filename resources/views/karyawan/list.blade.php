@extends('layouts.template')

@section('content')
      <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Data Karyawan</h5>
            <p class="card-title"><a href="">Manajemen Karyawan</a> > <a href="/karyawan">Karyawan</a></p>
        </div>

        <div class="card-body">
            <div class="col">
                <div class="row">
                    <a class="mb-3" href="{{ route('karyawan.create') }}">
                      <button class="btn btn-primary">tambah karyawan</button>
                    </a>
                    <a class="ml-3" href="{{ route('import') }}">
                      <button class="btn btn-primary">import karyawan</button>
                    </a>
                    <a class="ml-3" href="{{ route('klasifikasi_karyawan') }}">
                      <button class="btn btn-primary">Export Karyawan</button>
                    </a>
                    <div class="table-responsive overflow-hidden content-center">
                        <table class="table whitespace-nowrap" id="karyawan-table" style="width: 100%">
                          <thead class="text-primary">
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>NIK</th>
                                <th>Nama karyawan</th>
                                <th>Kantor</th>
                                {{--  <th>
                                Jabatan
                                </th>  --}}
                                <th>
                                    Aksi
                                </th>
                            </tr>
                          </thead>
                          <tbody>
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
        var i = 1;
        $('#karyawan-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{route('list_karyawan_json')}}",
                type: "GET",
                data: function(data) {
                    //i = 1;
                }
            },
            buttons: false,
            searching: true,
            scrollY: 500,
            scrollX: true,
            scrollCollapse: true,
            columns: [
                {
                    data: "id",
                    render:function(data , type , row){
                        return i++;
                    },
                },
                {
                    data: "nip",
                    name: "nip"
                },
                {
                    data: "nik",
                    name: "nik"
                },
                {
                    data: "nama_karyawan",
                    name: "nama_karyawan"
                },
                {
                    data: "entitas",
                    name: "kantor",
                    render: function(data, type, row) {
                        let kantor;
                        if (row.entitas.type == 2) {
                            kantor = row.entitas.cab.nama_cabang
                        } else {
                            kantor = 'Pusat'
                        }
                        
                        return kantor
                    }
                },
                {
                    data: "nip",
                    name: "aksi",
                    render: function(data, type, row) {
                        var nip = row.nip
                        var buttons = `<div class="container">
                            <div class="row">
                              <a href="{{ url('karyawan') }}/${nip}/edit">
                                <button class="btn btn-outline-warning p-1 mr-2" style="min-width: 60px">
                                  Edit
                                </button>
                              </a>

                              <a href="{{ url('karyawan') }}/${nip}/show">
                                <button class="btn btn-outline-info p-1" style="min-width: 60px">
                                  Detail
                                </button>
                              </a>
                            </div>
                          </div>`
                        
                        return buttons
                    }
                },
            ]
            
        });
    });
  </script>
@endsection
