@extends('layouts.template')

@section('content')
<div class="card-header">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <div>
                <h5 class="card-title">Dashboard</h5>
                <p class="card-title"><a href="/">Dashboard </a></p>
            </div>
            <div>
                <button class="btn btn-info">Detail</button>
            </div>
        </div>
    </div>
</div>
<div class="card-body">
    <div class="row mb-4">
        <div class="col-md-12">
            <div id="cabang-graph" class="w-100"></div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-6">
            <div id="gaji-graph" class="w-100"></div>
        </div>
        <div class="col-md-6">
            <div id="percabang-graph" class="w-100"></div>
        </div>
    </div>
    <div class="p-4">
        <div class="mb-2 p-2">
            <div class="card-title mb-2">
                <h4 class="font-weight-bold">
                    Data Table Karyawan
                </h4>
            </div>
            <div class="table-responsive overflow-hidden content-center mt-4">
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
                <div class="row">
                <div class="col">
                </div>
                </div>
            </div>
            <div class="mb-2 p-2">
                <div class="card-title mb-2">
                   <h4 class="font-weight-bold">
                        Data Table Karyawan SP
                   </h4>
                </div>
                <div class="table-responsive overflow-hidden content-center mt-4">
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
                    <div class="row">
                    <div class="col">
                    </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('custom_script')
    <script>
        var options = {
          series: [{
          name: 'Net Profit',
          data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
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
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return "$ " + val + " thousands"
            }
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#cabang-graph"), options);
        chart.render();

        var optionsPe = {
          series: [{
          name: 'series1',
          data: [11, 32, 45, 32, 34, 52, 41,11,44, 32, 34, 42]
        }, {
          name: 'series2',
          data: [11, 32, 45, 32, 34, 52, 41,11,44, 32, 34, 52]
        }],
          chart: {
          height: 350,
          type: 'area'
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth'
        },
        xaxis: {
          type: 'text',
          categories: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul","Okt", "Nov", "Des"]
        },
        };

        var perCabangGraph = new ApexCharts(document.querySelector("#percabang-graph"), optionsPe);
        perCabangGraph.render();

        var optionsPerGaji = {
          series: [
          {
            name: "High - 2013",
            data: [28, 29, 33, 36, 32, 32, 33]
          },
          {
            name: "Low - 2013",
            data: [12, 11, 14, 18, 17, 13, 13]
          }
        ],
          chart: {
          height: 350,
          type: 'line',
          dropShadow: {
            enabled: true,
            color: '#000',
            top: 18,
            left: 7,
            blur: 10,
            opacity: 0.2
          },
          toolbar: {
            show: false
          }
        },
        colors: ['#77B6EA', '#545454'],
        dataLabels: {
          enabled: true,
        },
        stroke: {
          curve: 'smooth'
        },
        title: {
          text: 'Average High & Low Temperature',
          align: 'left'
        },
        grid: {
          borderColor: '#e7e7e7',
          row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
          },
        },
        markers: {
          size: 1
        },
        xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
          title: {
            text: 'Month'
          }
        },
        yaxis: {
          title: {
            text: 'Temperature'
          },
          min: 5,
          max: 40
        },
        legend: {
          position: 'top',
          horizontalAlign: 'right',
          floating: true,
          offsetY: -25,
          offsetX: -5
        }
        };


        var perGaji = new ApexCharts(document.querySelector("#gaji-graph"), optionsPerGaji);
        perGaji.render();
    </script>
@endsection