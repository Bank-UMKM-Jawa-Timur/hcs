@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <h5 class="card-title">Sub Divisi</h5>
                    <p class="card-title"><a href="/">Dashboard </a> > <a href="{{route('per-devisi')}}">Divisi</a> > <a href="">Sub Divisi</a> </p>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 p-4">
                <div class="table-responsive overflow-hidden content-center">
                    <table class="table whitespace-nowrap" style="width: 100%">
                        <thead class="text-primary">
                            <th>
                                No
                            </th>
                            <th>
                                Kode Devisi
                            </th>
                            <th>
                                Kode Sub Devisi
                            </th>
                            <th>
                                Nama Sub Devisi
                            </th>
                            <th>
                                Total Karyawan
                            </th>
                            {{-- <th>
                                Aksi
                            </th> --}}
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$item->kode}}</td>
                                    <td>{{$item->kd_subdiv}}</td>
                                    <td>{{$item->nama_subdivisi}}</td>
                                    <td>{{$item->jumlah_karyawan}}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Data sub devisi tidak ada.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div id="sub-devisi-graph" class="w-100"></div>
            </div>
        </div>

    </div>
@endsection

@section('custom_script')
    <script>
        var dataKaryawan = @json($data);
        var kode = [];
        var total = [];
        $.each(dataKaryawan, function(i, item){
            kode.push(item.kode);
            total.push(item.jumlah_karyawan);
        })
        var optionsSubDevisi = {
            series: [{
                name: 'Total Karyawan',
                data: total
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: kode,
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " Karyawan"
                    }
                }
            }
        };
        var subDevisiChart = new ApexCharts(document.querySelector("#sub-devisi-graph"), optionsSubDevisi);
        subDevisiChart.render();

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
