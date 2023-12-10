@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <h5 class="card-title">Per Devisi</h5>
                    <p class="card-title"><a href="/">Dashboard </a> > <a href="">Per Divisi</a> </p>
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
                                Nama Devisi
                            </th>
                            <th>
                                Total Karyawan
                            </th>
                            <th>
                                Aksi
                            </th>
                        </thead>
                        <tbody>
                            @forelse ($datas as $item)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$item->kode}}</td>
                                    <td>{{$item->nama_divisi}}</td>
                                    <td>{{$item->jumlah_karyawan}}</td>
                                    <td><a href="sub-divisi/{{$item->kode}}" class="btn-sm btn-info">Selengkapnya</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">Data devisi belum ada.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6 mt-5 pt-5">
                <div id="divisi-graph" class="w-100"></div>
            </div>
        </div>

    </div>
@endsection

@section('custom_script')
    <script>
        var dataKaryawan = @json($datas);
        var kode = [];
        var total = [];
        $.each(dataKaryawan, function(i, item){
            kode.push(item.kode);
            total.push(item.jumlah_karyawan);
         })

        var optionsPerDevisi = {
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
        var perDevisiChart = new ApexCharts(document.querySelector("#divisi-graph"), optionsPerDevisi);
        perDevisiChart.render();

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
