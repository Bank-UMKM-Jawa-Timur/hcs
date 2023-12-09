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
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card"  style="border: 1px solid #dcdcdc">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">
                                Total Karyawan Per Cabang
                            </h6>
                        </div>
                        <div class="mt-2">
                            <a href="{{ route('per-cabang') }}" class="is-btn is-primary">Detail</a>
                        </div>
                    </div>
                </div>
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
                        <h2 class="font-weight-bold" style="letter-spacing: -2px;">Rp {{number_format($tunjangan->rata_rata ?? 0)}}</h2>
                    </div>
                    <div class="col-lg-12">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td><p class="font-weight-bold">Gaji Pokok</p></td>
                                    <td><p class="text-success">Rp {{number_format($tunjangan->gj_pokok ?? 0)}}</p></td>
                                    <td><p class="font-weight-bold">Tunjangan Pelaksana</p></td>
                                    <td><p class="text-success">Rp {{number_format($tunjangan->tj_pelaksana ?? 0)}}</p></td>
                                </tr>
                                <tr>
                                    <td><p class="font-weight-bold">Gaji Penyesuaian</p></td>
                                    <td><p class="text-success">Rp {{number_format($tunjangan->gj_penyesuaian ?? 0)}}</p></td>
                                    <td><p class="font-weight-bold">Tunjangan Kesejahteraan</p></td>
                                    <td><p class="text-success">Rp {{number_format($tunjangan->tj_kesejahteraan ?? 0)}}</p></td>
                                </tr>
                                <tr>
                                    <td><p class="font-weight-bold">Tunjangan Keluarga</p></td>
                                    <td><p class="text-success">Rp {{number_format($tunjangan->tj_keluarga ?? 0)}}</p></td>
                                    <td><p class="font-weight-bold">Tunjangan Multilevel</p></td>
                                    <td><p class="text-success">Rp {{number_format($tunjangan->tj_multilevel ?? 0)}}</p></td>
                                </tr>
                                <tr>
                                    <td><p class="font-weight-bold">Tunjangan Telepon</p></td>
                                    <td><p class="text-success">Rp {{number_format($tunjangan->tj_telepon ?? 0)}}</p></td>
                                    <td><p class="font-weight-bold">Tunjangan Pulsa</p></td>
                                    <td><p class="text-success">Rp {{number_format($tunjangan->tj_pulsa ?? 0)}}</p></td>
                                </tr>
                                <tr>
                                    <td><p class="font-weight-bold">Tunjangan Jabatan</p></td>
                                    <td><p class="text-success">Rp {{number_format($tunjangan->tj_jabatan ?? 0)}}</p></td>
                                    <td><p class="font-weight-bold">Tunjangan Transport</p></td>
                                    <td><p class="text-success">Rp {{number_format($tunjangan->tj_transport ?? 0)}}</p></td>
                                </tr>
                                <tr>
                                    <td><p class="font-weight-bold">Tunjangan Perumahan</p></td>
                                    <td><p class="text-success">Rp {{number_format($tunjangan->tj_perumahan ?? 0)}}</p></td>
                                    <td><p class="font-weight-bold">Tunjangan Vitamin</p></td>
                                    <td><p class="text-success">Rp {{number_format($tunjangan->tj_vitamin ?? 0)}}</p></td>
                                </tr>
                                <tr>
                                    <td><p class="font-weight-bold">Tunjangan Kemahalan</p></td>
                                    <td><p class="text-success">Rp {{number_format($tunjangan->tj_kemahalan ?? 0)}}</p></td>
                                    <td><p class="font-weight-bold">Uang Makan</p></td>
                                    <td><p class="text-success">Rp {{number_format($tunjangan->uang_makan ?? 0)}}</p></td>
                                </tr>
                            </tbody>
                        </table>
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
        {{--  title: {
            text: 'Total Karyawan Per Cabang',
            align: 'left'
        },  --}}
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
