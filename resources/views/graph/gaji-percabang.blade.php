@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <h5 class="card-title">Gaji Cabang Per Tahun</h5>
                    <p class="card-title"><a href="/">Dashboard </a> > <a href="">Gaji Cabang</a> </p>
                </div>
                <div>
                    <button class="btn btn-info">Detail</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="percabang-graph" class="w-100"></div>
    </div>
@endsection

@section('custom_script')
    <script>
        var options = {
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

        var chart = new ApexCharts(document.querySelector("#percabang-graph"), options);
        chart.render();
    </script>
@endsection
