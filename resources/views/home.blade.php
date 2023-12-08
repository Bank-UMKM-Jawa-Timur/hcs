@extends('layouts.template')

@section('content')
<div class="card-header">
    <div class="d-flex justify-content-between">
        <div>
            <h5 class="card-title font-weight-bold">Dashboard</h5>
            <p class="card-title"><a href="/">Dashboard </a></p>
        </div>
    </div>
</div>
<div class="card-body">
    <div class="d-flex justify-content-end">
        <a href="{{ route('per-cabang') }}" class="is-btn btn-info">Detail</a>
    </div>
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card"  style="border: 1px solid #dcdcdc">
                <div class="card-body">
                <div id="cabang-graph" class="w-100 mt-3"></div >
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card" style="border: 1px solid #dcdcdc">
                <div class="card-body">
                    <div id="gaji-graph" class="w-100"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card"  style="border: 1px solid #dcdcdc">
                <div class="card-header">
                    <p class="card-title h6 font-weight-bold text-muted text-capitalize">Perkiraan Gaji Per Bulan</p>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <h2 class="font-weight-bold" style="letter-spacing: -2px;">Rp.2.550.000</h2>
                    </div>
                    <table class="table">
                    <tbody>
                        <tr>
                            <td><p class="font-weight-bold">Gaji Lembur</p></td>
                            <td><p class="text-success">Rp. 250.000 +</p></td>
                        </tr>
                        <tr>
                            <td><p class="font-weight-bold">Vitamin</p></td>
                            <td><p class="text-success">Rp 300.000 +</p></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_script')
    <script>
        var totalKaryawan = @json($totalKaryawan);
        var cabang = [];
        var total_karyawan = [];
        var pusat = [];
        $.each(totalKaryawan, function(i, item){
            pusat.push(item.pusat);
            cabang.push(item.cabang);
            total_karyawan.push(item.total_karyawan);
        })

        var options = {
        series: [{
          name: 'Total',
          data: total_karyawan
        }],
        chart: {
          type: 'bar',
          height: 350,
          fontFamily: 'Plus Jakarta Sans, sans-serif'
        },
        title: {
            text: 'Total Karyawan Per Cabang',
            align: 'left'
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
          },
        },
        colors: ['#00E396'],
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: cabang,
        },
        fill: {
        opacity: 1
        },
        };

        var chart = new ApexCharts(document.querySelector("#cabang-graph"), options);
        chart.render();
        //end chart total karyawan by cabang

        // chart total gaji karyawan per cabang
        var totalGaji = @json($gajiPerCabang);
        var cabang = [];
        var gaji_pokok = [];
         $.each(totalGaji, function(i, item){
            cabang.push(item.cabang);
            gaji_pokok.push(item.gaji_pokok);
         })
        var optionsPe = {
          series: [{
                name: "Total",
                data: gaji_pokok
            }],
            chart: {
                height: 350,
                type: 'line',
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
            enabled: false
            },
            stroke: {
                curve: 'straight'
            },
            title: {
                text: 'Total Gaji Karyawan Per Cabang',
                align: 'left'
            },
            grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
            },
            xaxis: {
                categories: cabang,
            }
        };

        var perCabangGraph = new ApexCharts(document.querySelector("#percabang-graph"), optionsPe);
        perCabangGraph.render();

        //chart gaji perbulan dalam setahun
        var tanggal = new Date();
        var tahun = tanggal.getFullYear();

        var data_gaji = @json($dataGaji);
        var total_gaji = [];
        $.each(data_gaji, function(i, item){
            total_gaji.push(item.gaji);
        })

        let total_salary = total_gaji.map((item) => {
            return typeof item === "string" ? parseInt(item) : item;
        })
        var optionsPerGaji = {
            series: [{
                name: "Desktops",
                data: total_salary
            }],
            chart: {
                height: 350,
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                type: 'area',
            zoom: {
                enabled: false
            }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'straight'
            },
            title: {
                text: 'Total Gaji Karyawan Tahun ' + tahun,
                align: 'left'
            },
            grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'],
                opacity: 0.5
            },
            },
            xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Des'],
            }
        };


        var perGaji = new ApexCharts(document.querySelector("#gaji-graph"), optionsPerGaji);
        perGaji.render();
    </script>
@endsection
