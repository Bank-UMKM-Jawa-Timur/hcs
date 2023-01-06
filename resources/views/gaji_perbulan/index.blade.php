@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Proses Gaji Bulanan</h5>
            <p class="card-title"><a href="/">Dashboard </a> > Proses Gaji Bulanan</p>
        </div>
    </div>
    @php
        $already_selected_value = date('Y');
        $earliest_year = 2022;
    @endphp
    <div class="card-body">
        <form action="{{ route('gaji_perbulan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row m-0">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_bulan">Tahun</label>
                        <select name="tahun" id="tahun" class="form-control">
                            <option value="">--- Pilih Tahun ---</option>
                            @foreach (range(date('Y'), $earliest_year) as $x)
                                <option value="{{ $x }}">{{ $x }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="Bulan">Bulan</label>
                        <select name="bulan" id="bulan" class="form-control">
                            <option value="">--- Pilih Bulan ---</option>
                            <option value='1'>Januari</option>
                            <option value='2'>Februari </option>
                            <option value='3'>Maret</option>
                            <option value='4'>April</option>
                            <option value='5'>Mei</option>
                            <option value='6'>Juni</option>
                            <option value='7'>Juli</option>
                            <option value='8'>Agustus</option>
                            <option value='9'>September</option>
                            <option value='10'>Oktober</option>
                            <option value='11'>November</option>
                            <option value='12'>Desember</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row m-0">
                <div class="col-md-5">
                    <button class="btn btn-info">proses</button>
                </div>
            </div>
        </form>
    </div>

    <div class="card-body">
        <div class="row m-0">
            <div class="col">
                <hr>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-header">
                    <h5 class="card-title">Data Gaji yang Telah Terproses</h5>
                </div>
            </div>
            <div class="row m-0">
                <div class="card-body">
                    <div class="col-l-12">
                        <div class="table-responsive overflow-hidden">
                            <table class="table table-bordered table-striped" id="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                        $months = array(1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember');
                                    @endphp
                                    @foreach ($data_gaji as $item)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $months[$item->bulan] }}</td>
                                            <td>{{ $item->tahun }}</td>
                                        </tr>
                                    @endforeach
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
        $("#table").DataTable({
            'autoWidth': false,
            'dom': 'Rlfrtip',
            'colReorder': {
                'allowReorder': false
            }
        });
        $("#tahun").change(function(e){
            var tahun = $(this).val();
            $('#bulan option').removeAttr('disabled');

            $.ajax({
                url: "{{ route('getBulan') }}?tahun="+tahun,
                type: "get",
                datatype: "json",
                success: function(res){
                    $.each(res, function(i, v){
                        console.log(v.bulan);
                        $('#bulan option[value="'+v.bulan+'"]').prop('disabled', true);
                    })
                }
            })
        })
    </script>
@endsection