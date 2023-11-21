@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <h5 class="card-title">Per Divisi</h5>
                    <p class="card-title"><a href="/">Dashboard </a> > <a href="">Per Divisi</a> </p>
                </div>
                <div>
                    <button class="btn btn-info">Detail</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 p-4">
                <div class="table-responsive overflow-hidden content-center">
                    <table class="table whitespace-nowrap" id="table" style="width: 100%">
                        <thead class="text-primary">
                            <th>
                                No
                            </th>
                            <th>
                                Kode Divisi
                            </th>
                            <th>
                                Nama Divisi
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
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div id="cabang-graph" class="w-100"></div>
            </div>
        </div>

    </div>
@endsection

@section('custom_script')
    <script>
        var optionsPerDevisi = {
            series: [{
                name: 'Net Profit',
                data: [44]
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
                categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
            },
            yaxis: {
                title: {
                    text: '$ (thousands)'
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return "$ " + val + " thousands"
                    }
                }
            }
        };
        var perDevisiChart = new ApexCharts(document.querySelector("#cabang-graph"), optionsPerDevisi);
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
