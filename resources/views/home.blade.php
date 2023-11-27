@extends('layouts.template')

@section('content')
<div class="card-header">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <div>
                <h5 class="card-title font-weight-bold">Dashboard</h5>
                <p class="card-title"><a href="/">Dashboard </a></p>
            </div>
        </div>
    </div>
</div>
<div class="card-body">
    <div class="d-flex justify-content-end">
        <a href="{{ route('per-cabang') }}" class="btn btn-info">Detail</a>
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
                        <h2 class="font-weight-bold" style="letter-spacing: -2px;">Rp.2.55 0.000</h2>
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
                        {{-- <tr>
                            <td><p class="font-weight-bold">Total</p></td>
                            <td><p class="">Rp 2.200.000 </p></td>
                        </tr> --}}
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
            {{-- <div id="percabang-graph" class="w-100"></div> --}}
        </div>
    </div>
    {{-- <div class="p-4">
        <div class="mb-2 p-2">
            <div class="card-title mb-2 {{$totalDataMutasi > 5 ? 'd-flex justify-content-between' : ''}}">
                <h4 class="font-weight-bold">
                    Data Mutasi Bulan Ini
                </h4>
                @if ($totalDataMutasi > 5)
                    <button class="btn btn-info">Selengkapnya</button>
                @endif
            </div>
            <div class="table-responsive overflow-hidden content-center mt-4">
                <table class="table whitespace-nowrap" id="table" style="width: 100%">
                    <thead class=" text-primary">
                    <th>#</th>
                    <th>Nip</th>
                    <th>Nama Karyawan</th>
                    <th>Tgl Mutasi</th>
                    <th>Jabatan Lama</th>
                    <th>Jabatan Baru</th>
                    <th>Kantor Lama</th>
                    <th>Kantor Baru</th>
                    <th>Bukti SK</th>
                </thead>
                <tbody>
                    @forelse ($dataMutasi as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{ $item->nip }}</td>
                        <td>{{ $item->nama_karyawan }}</td>
                        <td><span style="display: none;">{{ date('Ymd', strtotime($item->tanggal_pengesahan)) }}</span>{{ date('d-m-Y', strtotime($item->tanggal_pengesahan)) }}</td>
                        <td class="text-nowrap">{{ ($item->status_jabatan_lama != null) ? $item->status_jabatan_lama.' - ' : '' }}{{ $item->jabatan_lama }}</td>
                        <td class="text-nowrap">{{ ($item->status_jabatan_baru != null) ? $item->status_jabatan_baru.' - ' : '' }}{{ $item->jabatan_baru }}</td>
                        <td>{{ $item->kantor_lama ?? '-' }}</td>
                        <td>{{ $item->kantor_baru ?? '-' }}</td>
                        <td>{{ $item->bukti_sk }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Maaf data mutasi bulan ini tidak ada.</td>
                    </tr>
                    @endforelse
                </tbody>
                </table>
                </div>
                <div class="row">
                <div class="col">
                </div>
                </div>
            </div>
            <div class="mb-2 p-2">
                <div class="card-title mb-2 {{$totalDataSP > 5 ? 'd-flex justify-content-between' : ''}}">
                   <h4 class="font-weight-bold">
                        Data Karyawan SP Bulan Ini
                   </h4>
                   @if ($totalDataSP > 5)
                        <button class="btn btn-info">Selengkapnya</button>
                   @endif
                </div>
                <div class="table-responsive overflow-hidden content-center mt-4">
                    <table class="table whitespace-nowrap" id="table" style="width: 100%">
                        <thead class=" text-primary">
                        <tr>
                            <th>No</th>
                            <th>Nomor SP</th>
                            <th>NIP</th>
                            <th>Nama Karyawan</th>
                            <th>Tanggal SP</th>
                            <th>Pelanggaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataSP as $sp)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $sp->no_sp ?? '-' }}</td>
                                <td>{{ $sp->nip }}</td>
                                <td>{{ $sp->karyawan->nama_karyawan }}</td>
                                <td>{{ $sp->tanggal_sp->format('d M Y') }}</td>
                                <td>{{ $sp->pelanggaran }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Maaf data karyawan SP bulan ini tidak ada.</td>
                            </tr>
                        @endforelse
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

    </div> --}}
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
