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
    <div class="row">
        <div class="col-md-12">
            <div id="cabang-graph" class="w-100"></div>
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
    </script>
@endsection