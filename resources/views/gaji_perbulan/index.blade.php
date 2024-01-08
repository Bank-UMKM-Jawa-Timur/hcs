@extends('layouts.template')
@include('gaji_perbulan.modal.proses')
@include('gaji_perbulan.modal.perbarui')
@include('gaji_perbulan.modal.modal-upload')
@include('gaji_perbulan.modal.penghasilan-kantor')
@include('gaji_perbulan.script.index')
@section('content')
    @include('gaji_perbulan.modal.perbarui')
    @include('gaji_perbulan.modal.rincian')
    @include('gaji_perbulan.modal.payroll')
    <div class="card-header">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <h5 class="card-title font-weight-bold">Proses Penghasilan Bulanan</h5>
                    <p class="card-title"><a href="">Penghasilan </a> > Proses Penghasilan Bulanan</p>
                </div>
                <div>
                    @if (auth()->user()->hasRole('kepegawaian'))
                        <button type="button" class="is-btn is-primary btn-show">Penghasilan Semua Kantor</button>
                    @endif
                    @can('penghasilan - proses penghasilan')
                        <button type="button" class="is-btn is-primary btn-proses">Proses</button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    @php
        $already_selected_value = date('Y');
        $earliest_year = 2022;
    @endphp
    <div class="row">
        <div class="col">
            <div class="alert alert-info mx-3" role="alert">
                Harap cek kembali data tunjangan sebelum melakukan proses tunjangan.
            </div>
        </div>
    </div>
    <div class="row m-0">
        <div class="card-body">
            <div class="col-l-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active border" id="home-tab" data-bs-toggle="tab" data-bs-target="#proses" type="button" role="tab" aria-controls="proses" aria-selected="true">
                            Proses
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link border" id="final-tab" data-bs-toggle="tab" data-bs-target="#final" type="button" role="tab" aria-controls="final" aria-selected="false">
                            Final
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="proses" role="tabpanel" aria-labelledby="proses-tab">
                        <div class="table-responsive overflow-hidden">
                            <table class="table table-bordered" id="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">No</th>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">Tahun</th>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">Bulan</th>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">Tanggal</th>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">File</th>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" colspan="3">Total</th>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">Aksi</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Bruto</th>
                                        <th class="text-center">Potongan</th>
                                        <th class="text-center">Netto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                        $months = array(1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember');
                                        $total_bruto = 0;
                                        $total_potongan = 0;
                                        $total_netto = 0;
                                    @endphp
                                    @forelse ($proses_list as $item)
                                        @php
                                            $total_bruto += $item->bruto;
                                            $total_potongan += $item->total_potongan;
                                            $total_netto += $item->netto;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $i++ }}</td>
                                            <td class="text-center">{{ $item->tahun }}</td>
                                            <td class="text-center">{{ $months[$item->bulan] }}</td>
                                            <td class="text-center">{{date('d-m-y', strtotime($item->tanggal_input))}}</td>
                                            <td class="text-center">
                                                <a href="#" class="btn btn-outline-warning p-1 btn-rincian"
                                                    data-batch_id="{{$item->id}}">Rincian</a>
                                                <a href="#" class="btn btn-outline-success p-1 btn-payroll"
                                                    data-batch_id="{{$item->id}}">Payroll</a>
                                            </td>
                                            @if ($item->bruto == 0)
                                                <td class="text-center">-</td>
                                            @else
                                                <td class="text-right">
                                                    Rp {{number_format($item->bruto, 0, ',', '.')}}
                                                </td>
                                            @endif
                                            @if ($item->total_potongan == 0)
                                                <td class="text-center">-</td>
                                            @else
                                                <td class="text-right">
                                                    Rp {{number_format($item->total_potongan, 0, ',', '.')}}
                                                </td>
                                            @endif
                                            @if ($item->netto < 0)
                                                <td class="text-right">
                                                    Rp ({{number_format(str_replace('-', '', $item->netto), 0, ',', '.')}})
                                                </td>
                                            @elseif ($item->netto == 0)
                                                <td class="text-center">-</td>
                                            @else
                                                <td class="text-right">
                                                    Rp {{number_format($item->netto, 0, ',', '.')}}
                                                </td>
                                            @endif
                                            <td class="text-center">
                                                @if($item->status == 'proses')
                                                    @if ($item->tanggal_cetak != null)
                                                        @if ($item->file == null)
                                                            <a class="btn btn-outline-primary p-1" href="#" id="uploadFile"  data-toggle="modal" data-target="#modalUploadfile" data-batch_id="{{ $item->id }}">Upload File</a>
                                                        @endif
                                                    @else
                                                        <a class="btn btn-outline-primary p-1 btn-download-pdf " id="download" href="#" data-id={{ $item->id }}>Download PDF</a>
                                                    @endif
                                                    @if($item->total_penyesuaian > 0)
                                                        <a href="#" class="btn btn-outline-warning p-1 btn-perbarui"
                                                            data-batch_id="{{$item->id}}">Perbarui</a>
                                                    @else
                                                        <a href="#" class="btn btn-outline-success p-1 btn-final"
                                                            data-batch_id="{{$item->id}}">Proses Final</a>
                                                    @endif
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
                                @if ($proses_list)
                                    <tfoot>
                                        <tr>
                                            <th class="text-center" colspan="5">Total</th>
                                            @if ($total_bruto > 0)
                                                <th class="text-right">
                                                    RP {{number_format($total_bruto, 0, ',', '.')}}
                                                </th>
                                            @else
                                                <th class="text-center">-</th>
                                            @endif
                                            @if ($total_potongan > 0)
                                                <th class="text-right">
                                                    RP {{number_format($total_potongan, 0, ',', '.')}}
                                                </th>
                                            @else
                                                <th class="text-center">-</th>
                                            @endif
                                            @if ($total_netto > 0)
                                                <th class="text-right">
                                                    RP {{number_format($total_netto, 0, ',', '.')}}
                                                </th>
                                            @else
                                                <th class="text-center">-</th>
                                            @endif
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="final" role="tabpanel" aria-labelledby="final-tab">
                        <div class="table-responsive overflow-hidden">
                            <table class="table table-bordered" id="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">No</th>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">Tahun</th>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">Bulan</th>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">Tanggal</th>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" rowspan="2">File</th>
                                        <th style="border: 1px solid #dee2e6;" class="text-center" colspan="3">Total</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Bruto</th>
                                        <th class="text-center">Potongan</th>
                                        <th class="text-center">Netto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                        $total_bruto = 0;
                                        $total_potongan = 0;
                                        $total_netto = 0;
                                    @endphp
                                    @forelse ($final_list as $item)
                                        @php
                                            $total_bruto += $item->bruto;
                                            $total_potongan += $item->total_potongan;
                                            $total_netto += $item->netto;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $i++ }}</td>
                                            <td class="text-center">{{ $item->tahun }}</td>
                                            <td class="text-center">{{ $months[$item->bulan] }}</td>
                                            <td class="text-center">{{date('d-m-y', strtotime($item->tanggal_input))}}</td>
                                            <td class="text-center">dummy.xlsx</td>
                                            @if ($item->bruto == 0)
                                                <td class="text-center">-</td>
                                            @else
                                                <td class="text-right">
                                                    Rp {{number_format($item->bruto, 0, ',', '.')}}
                                                </td>
                                            @endif
                                            @if ($item->total_potongan == 0)
                                                <td class="text-center">-</td>
                                            @else
                                                <td class="text-right">
                                                    Rp {{number_format($item->total_potongan, 0, ',', '.')}}
                                                </td>
                                            @endif
                                            @if ($item->netto < 0)
                                                <td class="text-right">
                                                    Rp ({{number_format(str_replace('-', '', $item->netto), 0, ',', '.')}})
                                                </td>
                                            @elseif ($item->netto == 0)
                                                <td class="text-center">-</td>
                                            @else
                                                <td class="text-right">
                                                    Rp {{number_format($item->netto, 0, ',', '.')}}
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">Belum ada penghasilan yang telah selesai diproses.</td>
                                        </tr>
                                    @endforelse
                                    @if ($final_list)
                                    <tfoot>
                                        <tr>
                                            <th class="text-center" colspan="5">Total</th>
                                            @if ($total_bruto > 0)
                                                <th class="text-right">
                                                    RP {{number_format($total_bruto, 0, ',', '.')}}
                                                </th>
                                            @else
                                                <th class="text-center">-</th>
                                            @endif
                                            @if ($total_potongan > 0)
                                                <th class="text-right">
                                                    RP {{number_format($total_potongan, 0, ',', '.')}}
                                                </th>
                                            @else
                                                <th class="text-center">-</th>
                                            @endif
                                            @if ($total_netto > 0)
                                                <th class="text-right">
                                                    RP {{number_format($total_netto, 0, ',', '.')}}
                                                </th>
                                            @else
                                                <th class="text-center">-</th>
                                            @endif
                                        </tr>
                                    </tfoot>
                                @endif
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
@push('script')
<script src="{{asset('vendor/printpage/printpage.min.js')}}"></script>

    <script>
        $('.btn-download-pdf').on('click',function() {
            let id  = $(this).data('id');
            console.log(id);
            let url = "{{ url('') }}"
            let downloadUrl = `${url}/cetak-penghasilan/${id}`;
            console.log(downloadUrl);
            // href="{{ route('cetak.penghasilanPerBulan',$item->id) }}"
            $(this).attr('href',downloadUrl)

        })
        $(document).ready(function() {
            $('#download').printPage();
        })
    </script>
@endpush
@section('custom_script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('style/assets/js/table2excel.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.1/js/dataTables.fixedColumns.min.js">
    </script>
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

        $(".btn-rincian").on("click", function(){
            var batch_id = $(this).data("batch_id")
            var iteration = 1;
            var table = $("#table-rincian").DataTable({
                processing: true,
                serverSide: true,
                ajax: `{{ route('get-rincian-payroll') }}?batch_id=${batch_id}`,
                columns: [
                    {
                        data:null,
                        render:function(data, type, row){
                            return iteration++
                        }
                    },
                    {
                        data: 'nama_karyawan'
                    },
                    {
                        data: 'gaji.gj_pokok',
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "gaji.tj_keluarga",
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "gaji.tj_telepon",
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "gaji.tj_jabatan",
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "gaji.tj_ti",
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "gaji.tj_perumahan",
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "gaji.tj_pelaksana",
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "gaji.tj_kemahalan",
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "gaji.tj_kesejahteraan",
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "gaji.gj_penyesuaian",
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "gaji.total_gaji",
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: 'perhitungan_pph21.pph_pasal_21.pph_harus_dibayar',
                        defaultContent: 0,
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data.toString().replaceAll('-', ''));

                            return '(' + number + ')'
                        }
                    },
                ],
            })
            $("#rincian-modal").modal("show")
        })

        $(".btn-payroll").on("click", function(){
            var batch_id = $(this).data("batch_id")
            var iteration = 1;
            var table = $("#table-payroll").DataTable({
                processing: true,
                serverSide: true,
                ajax: `{{ route('get-rincian-payroll') }}?batch_id=${batch_id}`,
                columns: [
                    {
                        data:null,
                        render:function(data, type, row){
                            return iteration++
                        }
                    },
                    {
                        data: 'nama_karyawan'
                    },
                    {
                        data: 'gaji.total_gaji',
                        defaultContent: 0,
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: 'no_rekening',
                        defaultContent: '-',
                    },
                    {
                        data: "potongan.dpp",
                        defaultContent: 0,
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "bpjs_tk",
                        defaultContent: 0,
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "potonganGaji.kredit_koperasi",
                        defaultContent: 0,
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "potonganGaji.iuran_koperasi",
                        defaultContent: 0,
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "potonganGaji.kredit_pegawai",
                        defaultContent: 0,
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "potonganGaji.iuran_ik",
                        defaultContent: 0,
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "total_potongan",
                        defaultContent: 0,
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "total_yg_diterima",
                        defaultContent: 0,
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                ],
            })
            $("#payroll-modal").modal("show")
        })
    </script>
@endsection
