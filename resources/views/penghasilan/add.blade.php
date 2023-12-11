@extends('layouts.template')
@include('vendor.select2')
@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <style>
        .hidden{
            display: none;
        }
        .custom-file-label::after{
            padding: 10px 4px 30px 4px;
        }
    </style>
@endpush
@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
    <script>
        $(document).ready(function() {
            var kategori;
            var totalDataInput = 1;
            var grandTotalNominal = 0;
            $("#kategori").select2();

            $("input[type=file]").on('change', function(){
                var input = document.getElementById('upload_csv');
                var label = document.getElementById('file-label');
                var fileName = input.files[0].name;
                label.innerHTML = fileName;
            })

            $("#kategori").on('change', function(){
                var value = $(this).val();
                kategori = value.toLowerCase()
                if(value == 'pengganti biaya kesehatan'){
                    $('#btnDownloadTemplate').attr('href', "{{ route('penghasilan-tidak-teratur.templateBiayaKesehatan') }}");
                } else if(value == 'uang duka'){
                    $('#btnDownloadTemplate').attr('href', "{{ route('penghasilan-tidak-teratur.templateBiayaDuka') }}");
                } else{
                    $('#btnDownloadTemplate').attr('href', "{{ route('penghasilan-tidak-teratur.templateTidakTeratur') }}");
                }
            })

            $('.btn-import').on('click',function(element) {
                var kategori = $("#kategori").val();
                if(kategori == ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan!',
                        text: `Harap pilih kategori terlebih dahulu.`
                    });
                    $('#table_item tbody').empty();
                    $('#table-data').addClass('hidden');
                    $('#button-simpan').addClass('hidden');
                } else{
                    var keterangan = 'Keterangan';
                    if(kategori == 'uang duka' || kategori == 'pengganti biaya kesehatan'){
                        keterangan = kategori == 'uang duka' ? 'Yang Meninggal' : 'Keterangan';
                        $("#keterangan").html(keterangan);
                        $("#keterangan").removeClass('hidden');
                    } else{
                        $("#keterangan").addClass('hidden')
                    }

                    url = "{{ route('api.get.karyawan') }}";

                    $('#table-data').addClass('hidden');
                    $('#table_item tbody').empty();
                    $('#alert-container').addClass('hidden');

                    var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;
                    var test = $("#upload_csv").val();
                    if (regex.test($("#upload_csv").val().toLowerCase())) {
                    var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/
                    if ($('#upload_csv').val().toLowerCase().indexOf(".xlsx") > 0) {
                        xlsxflag = true;
                    }
                    // check browser support html5
                    if (typeof(FileReader) != 'undefined') {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            var data = e.target.result;
                            // convert the excel data in to object
                            if (xlsxflag) {
                                var workbook = XLSX.read(data, {type: 'binary'});
                            }
                            // all element sheetnames of excel
                            var sheet_name_list = workbook.SheetNames;
                            if (typeof(sheet_name_list) != 'undefined') {
                                sheet_data = XLSX.utils.sheet_to_json(workbook.Sheets[sheet_name_list[0]], {header:2});

                                var dataNip = [];
                                var dataNominal = [];
                                var dataKeterangan = [];
                                var nipDataRequest = [];

                                var checkNip = [];

                                var hasError = false;
                                var hasSuccess = false;

                                var invalidNamaRows = [];
                                console.log(sheet_data);
                                $.each(sheet_data,function(key, value) {
                                    if (sheet_data[key].hasOwnProperty('Nominal') && sheet_data[key].hasOwnProperty('NIP')) {

                                        dataNip.push({ nip: value['NIP'], row: key + 1 });
                                        dataNominal.push(value['Nominal'])
                                        if(sheet_data[key].hasOwnProperty(keterangan)){
                                            console.log(value[keterangan]);
                                            dataKeterangan.push(value[keterangan])
                                        }
                                    }
                                })
                                var grand_total = 0;
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $.ajax({
                                        type: "POST",
                                        url: url,
                                        data: {
                                            nip: JSON.stringify(dataNip)
                                        },
                                        beforeSend: function () {
                                            // Display a loading message or indicator before the API call
                                            $('#loading-message').html(`
                                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                Loading Data...
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            `);
                                        },
                                        success: function (res) {
                                            $('#table-data').removeClass('hidden');
                                            var new_body_tr = ``
                                            $.each(res,function(key,value) {
                                                nipDataRequest.push(value.nip);
                                                // if (res.some(checkUsername)) {
                                                if (value.cek == '-') {
                                                    checkNip.push(value.nip);
                                                    hasError = true
                                                }
                                                grand_total += parseInt(dataNominal[key])
                                                var rowKeterangan = kategori == 'uang duka' || kategori == 'pengganti biaya kesehatan' ? `
                                                    <td class="${value.cek == '-' ? 'table-danger' : ''}">
                                                        <span>${dataKeterangan[key]}</span>
                                                    </td>` : ``;
                                                new_body_tr += `
                                                    <tr>
                                                        <td class="${value.cek == '-' ? 'table-danger' : ''}">
                                                            <span>${key + 1}</span>
                                                        </td>
                                                        <td class="${value.cek == '-' ? 'table-danger' : ''}">
                                                            <span class="${value.cek == '-' ? 'text-danger' : ''}">${value.nip}</span>
                                                        </td>
                                                        <td class="${value.cek == '-' ? 'table-danger' : ''}">
                                                            <span class="${value.cek == '-' ? 'text-danger' : ''}">${value.nama_karyawan}</span>
                                                        </td>
                                                        <td class="${value.cek == '-' ? 'table-danger' : ''}">
                                                            <span>${formatRupiah(dataNominal[key])}</span>

                                                        </td>
                                                        ${rowKeterangan}
                                                    </tr>
                                                `;

                                            })
                                            if (hasError == true) {
                                                var message = ``;
                                                message += `Data tidak ditemukan di NIP :${checkNip}.</br> Silahkan cek kembali di excel dan upload ulang.`
                                                $('#button-simpan').addClass('hidden');
                                                alertDanger(message)
                                            }
                                            if (hasError != true) {
                                                alertSuccess('Data Valid.');
                                                $('.nominal-input').val(dataNominal)
                                                $('.nip').val(nipDataRequest);
                                                if(kategori == 'uang duka' || kategori == 'pengganti biaya kesehatan'){
                                                    $('.keterangan-input').val(dataKeterangan)
                                                }
                                                $('#button-simpan').removeClass('hidden');
                                            }
                                            var total_grand = grand_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')
                                            $('#grand-total').html(`
                                                <span id="grand-total" class="font-weight-bold">Grand Total : ${total_grand}</span>
                                            `)
                                            $('#total-data').html(`
                                                <span id="total-data" class="font-weight-bold">Total Data : ${dataNip.length}</span>
                                            `)
                                            $('#table_item tbody').append(new_body_tr);

                                        },
                                        complete: function () {
                                            // Remove the loading message or indicator after the API call is complete
                                            $('#loading-message').empty();
                                        }
                                });
                            }
                        }

                        reader.onerror = function(ex) {
                            console.log(ex);
                        };
                        if (xlsxflag) {/*If excel file is .xlsx extension than creates a Array Buffer from excel*/
                            reader.readAsArrayBuffer($("#upload_csv")[0].files[0]);
                        }
                        else {
                            reader.readAsBinaryString($("#upload_csv")[0].files[0]);
                        }
                    } else {
                        console.log('tidak support');
                    }
                } else {
                    alert("Unggah file Excel yang valid!");
                    $('#table_item tbody').empty();
                    $('#table-data').addClass('hidden');
                    $('#button-simpan').addClass('hidden');
                }
                }
            })
            function alertDanger(message) {
                // Display an alert with danger style
                $('#alert-container').removeClass('hidden');

                $('#alert-container').html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `);
            }
            function alertSuccess(message) {
                // Display an alert with danger style
                $('#alert-container').removeClass('hidden');

                $('#alert-container').html(`
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `);
            }

        })
    </script>
@endpush
@section('content')
    <div class="card-header">
        <h5 class="card-title">Import Penghasilan Tidak Teratur</h5>
        <p class="card-title"><a href="{{ route('home') }}">Dashboard</a> > <a href="{{ route('penghasilan-tidak-teratur.index') }}">Penghasilan Tidak Teratur</a> >Import</p>
    </div>
    <div class="card-body p-3">
        <form action="{{ route('pajak_penghasilan.store') }}" enctype="multipart/form-data" method="POST" class="form-group">
            @csrf
            <div class="row">
                <div class="col">
                    <a href="{{ route('penghasilan-tidak-teratur.templateTidakTeratur') }}" class="btn is-btn is-primary" id="btnDownloadTemplate" download>Download Template Excel</a>
                </div>
            </div>
            <div class="row">
            <div class="col-md-12 justify-content-center">
                    @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <span class="alert-link">Terjadi Kesalahan</span>
                        <ul>
                            @foreach ($errors->all() as $item)
                            <tr class="justify-content-center">
                                <td>
                                    {{ $item }}
                                </td>
                            </tr>
                            @endforeach
                        </ul>
                    </div>

                    @endif
                </div>
                <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="alert-container">

                                </div>
                            </div>
                            <div class="col">
                                <label for="">Kategori</label>
                                <select name="kategori" id="kategori" class="form-control">
                                    <option value="">-- Pilih Kategori --</option>
                                    @forelse ($data as $item)
                                        <option value="{{ strtolower($item->nama_tunjangan) }}">{{ $item->nama_tunjangan }}</option>
                                    @empty

                                    @endforelse
                                </select>
                            </div>
                            <div class="col">
                                <label for="tanggal">Tanggal</label>
                                <input type="date" class="form-control" name="tanggal" required>
                            </div>
                            <div class="col">
                                <label for="">Data Excel</label>
                                <div class="custom-file">
                                    <input type="file" name="upload_excel" class="custom-file-input" id="upload_csv" accept=".xlsx, .xls">
                                    <label class="custom-file-label overflow-hidden" for="validatedCustomFile" id="file-label" style="padding: 10px 4px 30px 5px">Choose file...</label>
                                </div>
                            </div>
                            <div class="col align-items-center mt-4">
                                <button type="button" class="is-btn is-primary btn-import">Import</button>
                            </div>
                        </div>
                </div>

                <div class="col-md-4 align-self-center mt-4" id="total-data">
                </div>
                <div class="col-md-4 align-self-center mt-4" id="grand-total">
                </div>
                <div class="col-md-4 align-self-center mt-4">
                    <div class="d-flex justify-content-start hidden">
                        <input type="text" name="nominal" class="form-control nominal-input" value="" readonly hidden>
                        <input type="text" name="keterangan" class="form-control keterangan-input" value="" readonly hidden>
                        <input type="text" name="nip" class="form-control nip" value="" readonly hidden>
                        <button type="submit" class="is-btn btn-info hidden" id="button-simpan">Simpan</button>
                    </div>
                </div>
                <div class="col-md-12" id="loading-message">
                </div>
                <div class="col-md-12 hidden" id="table-data">
                    <div class="table-responsive overflow-hidden content-center">
                        <table class="table whitespace-nowrap table-bondered" id="table_item" style="width: 100%">
                        <thead class="text-primary">
                            <th>
                                No
                            </th>
                            <th>
                                NIP
                            </th>
                            <th>
                                Nama
                            </th>
                            <th>
                                Nominal
                            </th>
                            <th class="hidden" id="keterangan">
                                Keterangan
                            </th>
                        </thead>
                        <tbody>

                        </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
