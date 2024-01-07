@extends('layouts.template')
{{--  @push('style')
    <style>
        /*DL, DT, DD TAGS LIST DATA*/
        dl {
            margin-bottom:50px;
        }
        
        dl dt {
            background:#5f9be3;
            color:#fff;
            float:left; 
            font-weight:bold; 
            margin-right:10px; 
            padding:5px;  
            width:100px; 
        }
        
        dl dd {
            margin:2px 0; 
            padding:5px 0;
        }
    </style>
@endpush  --}}
@section('content')
    @include('gaji_perbulan.modal.perbarui')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title font-weight-bold">Proses Penghasilan Bulanan</h5>
            <p class="card-title"><a href="">Penghasilan </a> > Proses Penghasilan Bulanan</p>
        </div>
    </div>
    @php
        $already_selected_value = date('Y');
        $earliest_year = 2022;
    @endphp
    @can('penghasilan - proses penghasilan')
        <div class="card-body">
            <div class="alert alert-info mx-3" role="alert">
                Harap cek kembali data tunjangan sebelum melakukan proses tunjangan.
            </div>
            <form id="form" action="{{ route('gaji_perbulan.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row m-0">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nama_bulan">Tahun</label>
                            <select name="tahun" id="tahun" class="form-control">
                                <option value="0">--- Pilih Tahun ---</option>
                                @foreach (range(date('Y'), $earliest_year) as $x)
                                    <option value="{{ $x }}">{{ $x }}</option>
                                @endforeach
                            </select>
                            @error('tahun')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="Bulan">Bulan</label>
                            <select name="bulan" id="bulan" class="form-control">
                                <option value="0">--- Pilih Bulan ---</option>
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
                            @error('bulan')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tanggal">Tanggal Penghasilan</label>
                            <input type="date" name="tanggal" id="tanggal"
                                class="form-control" required>
                            @error('tanggal')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row m-0">
                    <div class="col-md-5 pt-4 pb-4">
                        <button type="submit" class="is-btn is-primary" id="btn-submit">proses</button>
                    </div>
                </div>
            </form>
            <hr>
        </div>
    @endcan

    <div class="card-body">
        <div class="card shadow">
            <div class="row m-0 p-5">
                <p class="col-sm-12 text-center" style="font-size: 20px; font-weight: 700">DATA PENGHASILAN YANG SEDANG DI PROSES</p>
            </div>
            <div class="row m-0">
                <div class="card-body">
                    <div class="col-l-12">
                        <div class="table-responsive overflow-hidden">
                            <table class="table stripe" id="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" rowspan="2">No</th>
                                        <th class="text-center" rowspan="2">Tahun</th>
                                        <th class="text-center" rowspan="2">Bulan</th>
                                        <th class="text-center" rowspan="2">Tanggal</th>
                                        <th class="text-center" colspan="2">Total</th>
                                        <th class="text-center" rowspan="2">Status</th>
                                        <th class="text-center" rowspan="2">File</th>
                                        <th class="text-center" rowspan="2">Aksi</th>
                                    </tr>
                                    <tr>
                                        <th>Bruto</th>
                                        <th>Neto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                        $months = array(1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember');
                                    @endphp
                                    @forelse ($data_gaji as $item)
                                        <tr>
                                            <td class="text-center">{{ $i++ }}</td>
                                            <td class="text-center">{{ $item->tahun }}</td>
                                            <td class="text-center">{{ $months[$item->bulan] }}</td>
                                            <td class="text-center">{{date('d-m-y', strtotime($item->tanggal_input))}}</td>
                                            <td>Rp {{number_format($item->bruto, 0, ',', '.')}}</td>
                                            <td>
                                                @if ($item->neto < 0)
                                                    Rp ({{number_format(str_replace('-', '', $item->neto), 0, ',', '.')}})
                                                @else
                                                    Rp {{number_format($item->neto, 0, ',', '.')}}
                                                @endif
                                            </td>
                                            <td class="text-center">{{ucwords($item->status)}}</td>
                                            <td class="text-center">dummy.xlsx</td>
                                            <td class="text-center">
                                                @if($item->status == 'proses')
                                                    <a href="#" class="btn btn-outline-warning p-1 btn-perbarui"
                                                        data-batch_id="{{$item->id}}">Perbarui</a>
                                                    <a href="#" class="btn btn-outline-success p-1 btn-final"
                                                        data-batch_id="{{$item->id}}">Proses Final</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">Belum ada penghasilan yang diproses.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="form-final" action="{{route('gaji_perbulan.proses_final')}}" method="post">
        <input type="hidden" name="_token" id="token">
        <input type="hidden" name="batch_id" id="batch_id">
    </form>
@endsection

@section('custom_script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
            const bulan = $('#bulan').val()
            const tahun = $('#tahun').val()
            const tanggal = $('#tanggal').val()

            if (bulan != 0 && tahun != 0 && tanggal)
                $('.loader-wrapper').removeAttr('style')
        })

        function showDetail(data) {
            let elements = ``;
            $.each(data, function(i, item) {
                var keyNames = Object.keys(item);
                var values = Object.values(item);
                let title = 'undifined';
                let old_val = 0;
                let new_val = 0;
                title = keyNames[0].replaceAll('tj', '')
                title = title.replaceAll('_', ' ')
                title = title.ucwords()
                old_val = formatRupiah(values[0].toString(), 0)
                new_val = formatRupiah(values[1].toString(), 0)
                const item_element = `
                    <div class="col-md-3">
                        <dt>${title}</dt>
                        <dd>${old_val} -> ${new_val}</dd>
                    </div>
                `
                elements += (item_element)
            })

            let row_element = `
                <div class="row">
                    ${elements}
                </div>
            `;
            return row_element;
        }

        $('.btn-perbarui').on('click', function() {
            const batch_id = $(this).data('batch_id');
            var iteration = 1;
            let table = $('#penyesuaian-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: `{{ route('gaji_perbulan.penyesuian_json') }}?batch_id=${batch_id}`,
                columns: [
                    {
                        className: 'dt-control',
                        orderable: false,
                        data: null,
                        defaultContent: ''
                    },
                    {
                        data: null,
                        render:function(data , type , row){
                            return iteration++;
                        },
                    },
                    {"data": "nip"},
                    {"data": "nama_karyawan"},
                ]
            });
            
            // Add event listener for opening and closing details
            table.on('click', 'td.dt-control', function (e) {
                let tr = e.target.closest('tr');
                let row = table.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                }
                else {
                    // Open this row
                    row.child(showDetail(row.data().penyesuaian)).show();
                }
            });
            $('#penyesuaian-modal #batch_id').val(batch_id); 
            $('#penyesuaian-modal').modal('show'); 
        })

        $('.btn-final').on('click', function() {
            const token = generateCsrfToken()
            const batch_id = $(this).data('batch_id')
            $('#form-final #token').val(token)
            $('#form-final #batch_id').val(batch_id)

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Anda yakin akan memproses data ini?',
                icon: 'question',
                iconColor: '#da271f',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                showCancelButton: true,
                confirmButtonColor: "#da271f",
                cancelButtonColor: "#fccf71",
                inputValidator: (value) => {
                    if (!value) {
                        return "You need to write something!";
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $('.loader-wrapper').removeAttr('style')
                    $('#form-final').submit()
                }
            })
        })

        $('#penyesuaian-modal #btn-update').on('click', function(e) {
            e.preventDefault();
            $('.loader-wrapper').removeAttr('style')
            $('#penyesuaian-modal #form').submit()
        })
    </script>
@endsection
