@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Penghasilan Tidak Teratur</h5>
            <p class="card-title"><a href="/">Dashboard </a> > <a href="/penghasilan">Penghasilan Tidak Teratur </a> 
          </div>

          <div class="card-body">
            <div class="col">
                <div class="row">
                    <a class="mb-3" href="/penghasilan/add">
                      <button class="btn btn-primary">Tambah Penghasilan Tidak Teratur</button>
                    </a>
                    <div class="table-responsive overflow-hidden content-center">
                      <table class="table whitespace-nowrap" id="table" style="width: 100%">
                          <thead class=" text-primary">
                            <th>
                                No
                            </th>
                            <th>
                                T. Uang Makan
                            </th>
                            <th>
                                T. Uang Pulsa
                            </th>
                            <th>
                                T. Uang Vitamin
                            </th>
                            <th>
                                T. Uang Transport
                            </th>
                            <th>
                                T. Uang Lembur
                            </th>
                            <th>
                                Pengganti Biaya Kesehatan
                            </th>
                            <th>
                                T. Uang Duka
                            </th>
                            <th>
                                SPD
                            </th>
                            <th>
                                SPD Pendidikan
                            </th>
                            <th>
                                SPD Pindah Tugas
                            </th>
                          </thead>
                          <tbody>
                            <td>1</td>
                            <td>125.000</td>
                            <td>125.000</td>
                            <td>125.000</td>
                            <td>125.000</td>
                            <td>125.000</td>
                            <td>125.000</td>
                            <td>125.000</td>
                            <td>125.000</td>
                            <td>125.000</td>
                            <td>125.000</td>
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