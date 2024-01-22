@extends('layouts.app-template')
@include('vendor.select2')
@push('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
<style>
    .hidden {
        display: none;
    }
    .custom-file-label::after {
        padding: 10px 4px 30px 4px;
    }
</style>
@endpush
@push('extraScript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

            function findDuplicateNIP(data) {
                const nipMap = new Map();
                const duplicates = [];

                data.forEach(employee => {
                    const nip = employee.nip;

                    if (nipMap.has(nip)) {
                        // NIP already found, increment the count
                        nipMap.set(nip, nipMap.get(nip) + 1);

                        // Check if it's the second occurrence (or more)
                        if (nipMap.get(nip) === 2) {
                        duplicates.push(nip);
                        }
                    } else {
                        // Add NIP to the map with an initial count of 1
                        nipMap.set(nip, 1);
                    }
                });

                return duplicates;
            }

            $('.btn-import').on('click',function(element) {
                $('#card-alert').removeClass('hidden');
                var kategori = $("#kategori").val();
                var tanggal = $(`#tanggal`).val();
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

                    url = "{{ route('api.get.karyawan2') }}";

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
                                var dataKdEntitas = [];
                                var nipDataRequest = [];
                                var no_rek = [];

                                var checkNip = [];
                                var checkFinal = [];
                                var NoCheckFinal = false;
                                var noCheckEmpty = 1;
                                var no = 1;

                                var hasError = false;
                                var hasSuccess = false;

                                var invalidNamaRows = [];
                                $.each(sheet_data,function(key, value) {
                                    if (sheet_data[key].hasOwnProperty('Nominal') && sheet_data[key].hasOwnProperty('NIP')) {
                                        dataNip.push({ nip: value['NIP'], row: key + 1 });
                                        dataNominal.push(value['Nominal'].replace(/[ ,.Rprp]/g, ""));
                                        no_rek.push(value['No Rekening']);
                                        // dataKdEntitas.push(value['Nominal'])
                                        if(sheet_data[key].hasOwnProperty(keterangan)){
                                            dataKeterangan.push(value[keterangan]);
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
                                            nip: JSON.stringify(dataNip),
                                            tanggal: tanggal
                                        },
                                        beforeSend: function () {
                                            // Display a loading message or indicator before the API call
                                            $('#loading-message').html(`
                                                <div class="bg-orange-100 border-t-4 border-orange-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert">
                                                    <div class="flex justify-between">
                                                        <p class="font-bold">Loading...</p>
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close" id="loading-alert">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            `);
                                        },
                                        success: function (res) {
                                            console.log(res);
                                            $('#table-data').removeClass('hidden');
                                            var new_body_tr = ``
                                            const duplicateNIP = findDuplicateNIP(res);
                                            $.each(res,function(key,value) {
                                                let nipDuplicate = duplicateNIP.find((item) => item == value.nip)
                                                if (value.status == 1) {
                                                    checkFinal.push(value.nip);
                                                    hasError = true
                                                    NoCheckFinal = true
                                                } else {
                                                    NoCheckFinal = false
                                                    if (value.cek == '-') {
                                                        // Jika nip tidak ditemukan
                                                        if (nipDuplicate) {
                                                            checkNip.push("Duplikasi " + value.nip + " baris " + noCheckEmpty++);
                                                            hasError = true
                                                        }
                                                        else {
                                                            checkNip.push(value.nip + " baris " + noCheckEmpty++);
                                                            hasError = true
                                                        }
                                                        nipDataRequest.push(value.nip);
                                                        grand_total += parseInt(dataNominal[key])
                                                        var rowKeterangan = kategori == 'uang duka' || kategori == 'pengganti biaya kesehatan' ? `
                                                            <td>
                                                                <span>${dataKeterangan[key]}</span>
                                                            </td>` : ``;
                                                        new_body_tr += `
                                                            <tr class="bg-red-400">
                                                                <td>
                                                                    <span>${no++}</span>
                                                                </td>
                                                                <td>
                                                                    <span>${value.nip}</span>
                                                                </td>
                                                                <td>
                                                                    <span>${value.nama_karyawan}</span>
                                                                </td>
                                                                <td>
                                                                    <span>${no_rek[key] == undefined ? '-' : no_rek[key]}</span>
                                                                </td>
                                                                <td>
                                                                    <span>${formatRupiah(dataNominal[key])}</span>

                                                                </td>
                                                                ${rowKeterangan}
                                                            </tr>
                                                        `;
                                                    }
                                                    else {
                                                        // Nip ditemukan
                                                        if (nipDuplicate) {
                                                            checkNip.push("Duplikasi " + value.nip + " baris " + noCheckEmpty++);
                                                            hasError = true

                                                            nipDataRequest.push(value.nip);
                                                            grand_total += parseInt(dataNominal[key])
                                                            var rowKeterangan = kategori == 'uang duka' || kategori == 'pengganti biaya kesehatan' ? `
                                                                <td class="table-danger">
                                                                    <span>${dataKeterangan[key]}</span>
                                                                </td>` : ``;
                                                            new_body_tr += `
                                                                <tr>
                                                                    <td class="table-danger">
                                                                        <span>${no++}</span>
                                                                    </td>
                                                                    <td class="table-danger">
                                                                        <span class="text-danger">${value.nip}</span>
                                                                    </td>
                                                                    <td class="table-danger">
                                                                        <span class="text-danger">${value.nama_karyawan}</span>
                                                                    </td>
                                                                    <td class="table-danger">
                                                                        <span class="text-danger">${no_rek[key] == undefined ? '-' : no_rek[key]}</span>
                                                                    </td>
                                                                    <td class="table-danger">
                                                                        <span>${formatRupiah(dataNominal[key])}</span>

                                                                    </td>
                                                                    ${rowKeterangan}
                                                                </tr>
                                                            `;
                                                        }
                                                    }
                                                }
                                            })
                                            $.each(res,function(key,value) {
                                                // if (res.some(checkUsername)) {
                                                if (value.cek == '-') {
                                                    // checkNip.push(value.nip);
                                                    hasError = true
                                                }
                                                if (value.cek == '-') {
                                                }else{
                                                    let nipDuplicate = duplicateNIP.find((item) => item == value.nip)
                                                    if (!nipDuplicate) {
                                                        dataKdEntitas.push(value.kd_entitas)
                                                        nipDataRequest.push(value.nip);
                                                        grand_total += parseInt(dataNominal[key])
                                                        var rowKeterangan = kategori == 'uang duka' || kategori == 'pengganti biaya kesehatan' ? `
                                                            <td class="">
                                                                <span>${dataKeterangan[key]}</span>
                                                            </td>` : ``;
                                                        new_body_tr += `
                                                            <tr>
                                                                <td class="">
                                                                    <span>${no++}</span>
                                                                </td>
                                                                <td class="">
                                                                    <span class="${value.cek == '-' ? 'text-danger' : ''}">${value.nip}</span>
                                                                </td>
                                                                <td class="">
                                                                    <span class="${value.cek == '-' ? 'text-danger' : ''}">${value.nama_karyawan}</span>
                                                                </td>
                                                                <td class="">
                                                                    <span class="${value.cek == '-' ? 'text-danger' : ''}">${no_rek[key] == undefined ? '-' : no_rek[key]}</span>
                                                                </td>
                                                                <td class="">
                                                                    <span>${formatRupiah(dataNominal[key])}</span>

                                                                </td>
                                                                ${rowKeterangan}
                                                            </tr>
                                                        `;
                                                    }
                                                }
                                            })
                                            if (hasError == true) {
                                                if (NoCheckFinal == true) {
                                                    var message = ``;
                                                    message += `Tidak bisa memilih tanggal ` + tanggal + `, karena sudah melakukan finalisasi.`
                                                    alertDanger(message)
                                                    $('#button-simpan').addClass('hidden');
                                                    $('#table-data').addClass('hidden');
                                                    $('#grand_total').addClass('hidden');
                                                } else {
                                                    var message = ``;
                                                    message += `Data tidak ditemukan di NIP :${checkNip}.</br> Silahkan cek kembali di excel dan upload ulang.`
                                                    $('#button-simpan').addClass('hidden');
                                                    alertDanger(message)
                                                }
                                            }
                                            if (hasError != true) {
                                                alertSuccess('Data Valid.');
                                                $('.nominal-input').val(dataNominal)
                                                $('.nip').val(nipDataRequest);
                                                $('.kd-entitas-input').val(dataKdEntitas);
                                                if(kategori == 'uang duka' || kategori == 'pengganti biaya kesehatan'){
                                                    $('.keterangan-input').val(dataKeterangan)
                                                }
                                                $('#button-simpan').removeClass('hidden');
                                            }
                                            var total_grand = grand_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')
                                            $('#grand-total').html(`
                                                <p id="total-data" class="text-lg font-bold text-gray-400">Total Data : <b class="text-black">${dataNip.length}</b></p>
                                                <p id="grand-total" class="ml-10 text-lg font-bold text-gray-400">Grand Total : <b class="text-black">${total_grand}</b></p>
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
                    <div id="alert-border-2" class="flex items-center p-4 mb-4 text-red-800 border-t-4 border-red-300 bg-red-50">
                        <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <div class="ms-3 text-sm font-medium">
                            ${message}
                        </div>
                        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8"  data-dismiss-target="#alert-border-2" aria-label="Close">
                        <span class="sr-only">Dismiss</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        </button>
                    </div>
                `);
            }
            function alertSuccess(message) {
                // Display an alert with danger style
                $('#alert-container').removeClass('hidden');

                $('#alert-container').html(`
                    <div id="alert-border-3" class="flex items-center p-4 mb-4 text-green-800 border-t-4 border-green-300 bg-green-50" role="alert">
                        <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <div class="ms-3 text-sm font-medium">
                            ${message}
                        </div>
                        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8"  data-dismiss-target="#alert-border-3" aria-label="Close">
                        <span class="sr-only">Dismiss</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        </button>
                    </div>
                `);
            }

            $('#button-simpan').on('click', function(){
                $("#loadingModal").modal({
                    keyboard: false
                });
                $("#loadingModal").modal("show");
            })

        })
</script>
@endpush
@section('content')
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">Import Penghasilan Tidak Teratur</h2>
            <div class="breadcrumb">
                <a href="/" class="text-sm text-gray-500 font-bold">Dashboard</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('penghasilan-tidak-teratur.index') }}"
                    class="text-sm text-gray-500 font-bold">Penghasilan Tidak Teratur</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <p class="text-sm text-gray-500 font-bold"> Import Penghasilan Tidak Teratur</p>
            </div>
        </div>
        <div class="button-wrapper flex gap-3">
            <a class="btn btn-outline-excel" href="{{ route('penghasilan-tidak-teratur.templateTidakTeratur') }}"
                download>
                <i class="ti ti-download"></i> Download Template Excel
            </a>
        </div>
    </div>
</div>

{{-- card Form --}}
<form action="{{ route('pajak_penghasilan.store') }}" enctype="multipart/form-data" method="POST">
@csrf
<div class="body-pages">
    <div class="table-wrapping">
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
        <div class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-5 mt-5 mb-5 items-end">
            <div class="input-box">
                <label for="">Kategori penghasilan teratur</label>
                <select name="kategori" class="form-input" id="kategori">
                    <option value="">-- Pilih Kategori --</option>
                    @forelse ($data as $item)
                    <option value="{{ strtolower($item->nama_tunjangan) }}">{{ $item->nama_tunjangan }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
            <div class="col">
                <label for="tanggal">Tanggal</label>
                <input type="date" class="form-input" name="tanggal" id="tanggal" required>
            </div>
            <div class="input-box">
                <label for="">Data Excel</label>
                <div class="input-group">
                    <input type="file" name="upload_excel" class="form-upload" id="upload_csv" accept=".xlsx, .xls">
                    <button class="upload-group-icon">
                        <label for="file-penghasilan">
                            <i class="ti ti-upload"></i>
                        </label>
                    </button>
                </div>
            </div>
            <div class="col align-items-center mt-4">
                <button type="button" class="btn btn-primary-light btn-import">Import</button>
            </div>
        </div>
    </div>
</div>

{{-- card alert & btn simpan --}}
<div class="body-pages pt-0 hidden" id="card-alert">
    <div class="table-wrapping">
        <div class="" id="alert-container"></div>
        <div class="" id="loading-message"></div>
        {{-- <div id="alert-massage"></div> --}}
        <div class="flex justify-between items-center">
            <div class="flex" id="grand-total">
            </div>
            <div class="d-flex justify-content-start hidden">
                <input type="text" name="nominal" class="form-control nominal-input" value="" readonly hidden>
                <input type="text" name="keterangan" class="form-control keterangan-input" value="" readonly hidden>
                <input type="text" name="nip" class="form-control nip" value="" readonly hidden>
                <input type="text" name="kd_entitas" class="form-control kd-entitas-input" value="" readonly hidden>
            </div>
            <button type="submit" class="btn btn-primary hidden" id="button-simpan">Simpan</button>
        </div>
    </div>
</div>

{{-- table --}}
<div class="body-pages pt-0 hidden" id="table-data">
    <div class="table-wrapping">
        <table class="tables" id="table_item">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nip</th>
                    <th>Nama Karyawan</th>
                    <th>No Rekening</th>
                    <th>Nominal</th>
                    <th class="hidden" id="keterangan">
                        Keterangan
                    </th>
                </tr>
            </thead>
            <tbody id="t_body">
            </tbody>
        </table>
    </div>
</div>
</form>
@endsection
