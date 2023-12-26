@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title font-weight-bold">Proses Gaji Bulanan</h5>
            <p class="card-title"><a href="">Penghasilan </a> > <a href="{{ route('gaji_perbulan.index') }}">Proses Gaji Bulanan</a></p>
        </div>
    </div>
    @php
        $already_selected_value = date('Y');
        $earliest_year = 2022;
    @endphp
    @if (auth()->user()->hasRole(['kepegawaian']))
        <div class="card-body">
            <div class="alert alert-success" role="alert">
                Harap cek kembali data tunjangan sebelum melakukan proses tunjangan
            </div>
            <form id="form" action="{{ route('gaji_perbulan.store') }}"
                method="POST" enctype="multipart/form-data">
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
                    <div class="col-md-5 pt-4 pb-4">
                        <button class="is-btn is-primary" id="btn-submit">proses</button>
                    </div>
                </div>
            </form>
            <hr>
        </div>
    @endif

    <div class="card-body ">
        <div class="card shadow">
            <div class="row m-0 p-5">
                <p class="col-sm-12 text-center" style="font-size: 20px; font-weight: 700">DATA GAJI YANG TELAH DI PROSES</p>
            </div>
            <div class="row m-0">
                <div class="card-body">
                    <div class="col-l-12">
                        <div class="table-responsive overflow-hidden">
                            <table class="table stripe" id="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">No</th>
                                        <th style="text-align: center">Bulan</th>
                                        <th style="text-align: center">Tahun</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                        $months = array(1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember');
                                    @endphp
                                    @foreach ($data_gaji as $item)
                                        <tr>
                                            <td style="text-align: center">{{ $i++ }}</td>
                                            <td style="text-align: center">{{ $months[$item->bulan] }}</td>
                                            <td style="text-align: center">{{ $item->tahun }}</td>
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
        $('#btn-submit').on('click', function(e) {
            e.preventDefault();
            $('.loader-wrapper').removeAttr('style')
            $('#form').submit()
        })
    </script>
@endsection
