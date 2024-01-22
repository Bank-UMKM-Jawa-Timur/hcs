{{-- @include('components.preloader.loader') --}}
@extends('layouts.app-template')
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
@push('extraScript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
@section('content')
    <div class="head mt-5">
        <div class="flex gap-5 justify-between items-center">
            <div class="heading">
                <h2 class="text-2xl font-bold tracking-tighter">Import Penghasilan</h2>
                <div class="breadcrumb">
                    <a href="/" class="text-sm text-gray-500 font-bold">Dashboard</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <a href="{{ route('penghasilan.import-penghasilan-teratur.index') }}" class="text-sm text-gray-500 font-bold">Penghasilan Teratur</a>
                    <i class="ti ti-circle-filled text-theme-primary"></i>
                    <p class="text-sm text-gray-500 font-bold"> Import Penghasilan Teratur</p>
                </div>
            </div>
            <div class="button-wrapper flex gap-3">
                <a class="btn btn-outline-excel" href="{{ route('penghasilan.template-excel') }}" download>
                    <i class="ti ti-download"></i> Download Template Excel
                </a>
            </div>
        </div>
    </div>

    <div class="body-pages">
        <div class="table-wrapping">
            <div class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-5 mt-5 mb-5 items-end">
                <div class="input-box">
                    <label for="">Kategori penghasilan teratur</label>
                    <select name="penghasilan" class="form-input" id="penghasilan-kat">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($penghasilan as $item)
                            <option value="{{$item->id}}">{{$item->nama_tunjangan}}</option>
                        @endforeach
                    </select>
                    <p class="text-red-500 hidden mt-2" id="error-penghasilan"></p>
                </div>
                <div class="input-box">
                    <label for="">Bulan</label>
                    <select name="bulan" class="form-input" id="bulan">
                        <option value="">-- Pilih Bulan --</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option {{ Request()->bulan == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}
                                value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">
                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                        @endfor
                    </select>
                    <p class="text-red-500 hidden mt-2" id="error-bulan">Bulan belum di pilih</p>
                </div>
                <div class="input-box">
                    <label for="file-penghasilan">File</label>
                    <div class="input-group">
                        <input type="file" name="upload_excel" class="form-upload" id="file-penghasilan" accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" onchange="updateFileName()">
                        <button class="upload-group-icon">
                            <label for="file-penghasilan">
                                <i class="ti ti-upload"></i>
                            </label>
                        </button>
                    </div>
                    <p class="text-red-500 hidden mt-2" id="error-file">File belum di pilih.</p>
                </div>
                <div class="w-30">
                    <button class="btn btn-primary-light" id="filter">Import</button>
                </div>
            </div>
        </div>
    </div>
    <div class="body-pages pt-0 row hidden" id="card-alert">
        <div class="table-wrapping">
            <div class="" id="loading-message"></div>
            <div id="alert-massage"></div>
            <div class="flex justify-between items-center">
                <div class="flex" id="grand">
                </div>
                <form action="{{route('penghasilan.import-penghasilan-teratur.store')}}" method="POST">
                    @csrf
                    <input type="hidden" name="nominal" class="form-control nominal-input" value="" readonly>
                    <input type="hidden" name="nip" class="form-control nip-input" value="" readonly>
                    <input type="hidden" name="tunjangan" class="form-control tunjangan-input" value="" readonly>
                    <input type="hidden" name="bulan" class="form-control bulan-input" value="" readonly>
                    <div class="d-flex justify-content-start hidden" id="cover-btn-simpan">
                        <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="body-pages pt-0 row hidden" id="hasil-filter">
        <div class="table-wrapping">
            <table class="tables" id="table_item">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nip</th>
                        <th>Nama Karyawan</th>
                        <th>No Rekening</th>
                        <th>Nominal</th>
                    </tr>
                </thead>
                <tbody id="t_body">
                </tbody>
            </table>
        </div>
    </div>
@endsection

    <script>
    function updateFileName() {
        var input = document.getElementById('file-penghasilan');
        var label = document.getElementById('file-label');
        var fileName = input.files[0].name;
        label.innerHTML = fileName;
    }
    </script>

@section('extraScript')
    <script>
        // $('.preloader').removeClass('hidden')

        // setTimeout(() => {
        //     $('.preloader').addClass('hidden')
        // }, 2000);
        $(document).ready(function() {
            var grandTotalNominal = 0;
            var penghasilan = '';
            $('#penghasilan-kat').on('change', function(){
                var value = $("#penghasilan-kat").val()
                var hari_ini = new Date();
                var tanggal = hari_ini.getDate();
                var message = '';
                var nmbr = 0;
                penghasilan = value;

                // 11 = tranport, 12 = pulsa, 13 = vitamin, 14 = uang makan

                if (value == 12) {
                    if (tanggal >= 1 && tanggal <= 10){
                        var message = "success"
                        $('#error-penghasilan').addClass('hidden')
                    } else {
                        if (value == 12) {
                            var message = 'Transaksi pulsa hanya bisa dilakukan pada tanggal 1 sampai 10'
                        } else if (value == 13 && tanggal > 5) {
                            var message = 'Transaksi pulsa hanya bisa dilakukan pada tanggal 1 sampai 10'
                        }

                        // alertWarning(message)
                        $('#error-penghasilan').removeClass('hidden').html(message)
                        $("#penghasilan-kat").val("")
                    }
                } else if(value == 13){
                    if (tanggal >= 1 && tanggal <= 5 ) {
                        var message = "success"
                        $('#error-penghasilan').addClass('hidden')
                    }
                    else {
                        if (value == 12) {
                            var message = 'Transaksi pulsa hanya bisa dilakukan pada tanggal 1 sampai 10'
                            nmbr++

                            $('#error-penghasilan').removeClass('hidden').html(message)
                            $("#penghasilan-kat").val("")
                        } else if (value == 13) {
                            // var message = 'kosong'
                            $('#error-penghasilan').addClass('hidden')
                        }

                    }
                } else {
                    $('#error-penghasilan').addClass('hidden')
                }
            })

            $('#filter').on('click', function(e) {
                // console.log('askdaskdjasldjldj');
                $('#card-alert').removeClass('hidden');
                var penghasilan = $('#penghasilan-kat').val();
                var filePenghasilan = $('#file-penghasilan').val();
                var bulanInput = $('#bulan').val();
                console.log("penghasilan");

                if (penghasilan && filePenghasilan && bulanInput) {
                    importExcel();
                    $('#table_item tbody').empty();
                    $('#error-penghasilan').addClass('hidden')
                    $('#error-file').addClass('hidden')
                    $('#error-bulan').addClass('hidden')
                } else {
                    if (penghasilan == "" && filePenghasilan && bulanInput) {
                        $('#error-penghasilan').removeClass('hidden').html('Kategori belum di pilih.')
                        $('#error-file').addClass('hidden')
                        $('#error-bulan').addClass('hidden')
                    }
                    else if (filePenghasilan == "" && penghasilan && bulanInput){
                        $('#error-file').removeClass('hidden')
                        $('#error-penghasilan').addClass('hidden')
                        $('#error-bulan').addClass('hidden')
                    }
                    else if (bulanInput = "" && filePenghasilan && penghasilan){
                        $('#error-file').addClass('hidden')
                        $('#error-penghasilan').addClass('hidden')
                        $('#error-bulan').removeClass('hidden')
                    }
                    else {
                        $('#error-bulan').removeClass('hidden')
                        $('#error-penghasilan').removeClass('hidden').html('Kategori belum di pilih.')
                        $('#error-file').removeClass('hidden')
                    }
                }
            });

            function formatNumber(number) {
                number = number.toString();
                var pattern = /(-?\d+)(\d{3})/;
                while (pattern.test(number))
                    number = number.replace(pattern, "$1.$2");
                return number;
            }


            $("#table_item").on('click', '.btn-edit', function () {
                var $row = $(this).closest('tr');
                var namaInput = $row.find('.nama');
                var nominalInput = $row.find('.nominal');

                $row.removeClass('hidden');

                nominalInput.prop('readonly', false);
                namaInput.prop('readonly', true);
            });

            function importExcel() {
                    var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;
                    var test = $("#file-penghasilan").val();
                    var sheet_data = [];
                    if (regex.test($("#file-penghasilan").val().toLowerCase())) {
                        var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/
                        if ($('#file-penghasilan').val().toLowerCase().indexOf(".xlsx") > 0) {
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
                                    showTable(sheet_data);
                                }
                            }

                            reader.onerror = function(ex) {
                                console.log(ex);
                            };
                            if (xlsxflag) {/*If excel file is .xlsx extension than creates a Array Buffer from excel*/
                                reader.readAsArrayBuffer($("#file-penghasilan")[0].files[0]);
                            }
                            else {
                                reader.readAsBinaryString($("#file-penghasilan")[0].files[0]);
                            }
                        } else {
                            console.log('tidak support');
                        }
                    } else {
                        alert("Unggah file Excel yang valid!");
                        $('#table_item tbody').empty();
                        $('#hasil-filter').addClass('hidden');
                        $('#cover-btn-simpan').addClass('hidden');
                    }
            }

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

            function showTable(sheet_data) {
                var no = 0;
                var grandTotalNominal = 0;
                var id_tunjangan = $('#penghasilan-kat').val()
                var bulanReq = $('#bulan').val()
                var date = new Date();
                var hari_ini = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
                var tahun_ini = date.getFullYear();

                var dataNip = [];
                var dataNominal = [];
                var no_rek = [];
                var nipDataRequest = [];

                var checkNip = [];
                var rowEmpty = [];
                var checkFinal = [];
                var NoCheckFinal = false;
                var noEmpty = 1;
                var checkNipTunjangan = [];
                var namaTunjangan = [];

                var hasError = false;
                var hasNip = false;
                var hasTunjangan = false;
                var hasSuccess = false;

                $.each(sheet_data,function(key, value) {
                    console.log(value);
                    if (sheet_data[key].hasOwnProperty('Nominal') && sheet_data[key].hasOwnProperty('NIP')) {
                        // console.log(value['Nominal'].replace(/[ ,.Rprp]/g, ""));
                        dataNip.push({ nip: value['NIP'], row: key + 1 });
                        dataNominal.push(value['Nominal'].replace(/[ ,.Rprp]/g, ""));
                        no_rek.push(value['No Rekening'])
                    }
                })
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: `{{ url('penghasilan/get-karyawan-by-entitas') }}`,
                    data: {
                        nip: JSON.stringify(dataNip),
                        tanggal: hari_ini,
                        bulan: bulanReq,
                        tahun:tahun_ini,
                        id_tunjangan: id_tunjangan,
                    },
                    beforeSend: function () {
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
                    success: function(res){
                        var new_body_tr = ``;
                        var new_body_tr_success = ``;
                        var message = ``;
                        var tittleMessage = ``;
                        var headerMessage = `harap cek kembali pada file excel yang di upload.`;
                        const duplicateNIP = findDuplicateNIP(res);
                        $.each(res,function(key,value) {
                            if (value.status == 1) {
                                checkFinal.push(value.nip);
                                hasError = true
                                NoCheckFinal = true
                            } else {
                                if (value.cek_nip == false) {
                                    checkNip.push(value.nip + " baris " + noEmpty++);
                                    hasError = true;
                                    hasNip = true;
                                    hasTunjangan = false;
                                } else if (value.cek_tunjangan == true) {
                                    checkNipTunjangan.push(value.nip + " baris " + noEmpty++);
                                    namaTunjangan.push(value.tunjangan.nama_tunjangan);
                                    hasError = true;
                                    hasTunjangan = true;
                                    hasNip = false;
                                }
                                if (value.cek_nip == false || value.cek_tunjangan == true) {
                                    hasError = true;
                                    hasNip = true;
                                    hasTunjangan = false;
                                    grandTotalNominal += parseInt(dataNominal[key])
                                    nipDataRequest.push(value.nip);
                                    no++;
                                    new_body_tr += `
                                            <tr class="table-danger">
                                            <td>
                                                ${no}
                                            </td>
                                            <td>
                                                ${value.nip}
                                            </td>
                                            <td>
                                                ${value.nama_karyawan}
                                            </td>
                                            <td>
                                                ${no_rek[key] == undefined ? '-' : no_rek[key]}
                                            </td>
                                            <td>
                                                ${formatRupiah(dataNominal[key].toString())}
                                            </td>
                                        </tr>
                                    `;
                                }
                            }
                            if (value.cek_nip || value.cek_tunjangan) {
                                let nipDuplicate = duplicateNIP.find((item) => item == value.nip)
                                if (nipDuplicate) {
                                    checkNip.push("Duplikasi " + value.nip + " baris " + noEmpty++);
                                    hasError = true;
                                    hasNip = true;
                                    hasTunjangan = false;
                                    grandTotalNominal += parseInt(dataNominal[key])
                                    nipDataRequest.push(value.nip);
                                    no++;
                                    new_body_tr += `
                                            <tr class="table-danger">
                                            <td>
                                                ${no}
                                            </td>
                                            <td>
                                                ${value.nip}
                                            </td>
                                            <td>
                                                ${value.nama_karyawan}
                                            </td>
                                            <td>
                                                ${value.no_rekening != null ? value.no_rekening : '-'}
                                            </td>
                                            <td>
                                                ${formatRupiah(dataNominal[key].toString())}
                                            </td>
                                        </tr>
                                    `;
                                }
                            }
                        })

                        $.each(res,function(key,value) {
                            if (value.cek_nip == false) {
                                // checkNip.push(value.nip);
                                // hasError = true;
                                // hasNip = true;
                                // hasTunjangan = false;
                            } else if (value.cek_tunjangan == true) {
                                // checkNipTunjangan.push(value.nip);
                                // namaTunjangan.push(value.tunjangan.nama_tunjangan);
                                // hasError = true;
                                // hasTunjangan = true;
                                // hasNip = false;
                            }
                            if (value.cek_nip == false || value.cek_tunjangan == true) {
                            }else{
                                grandTotalNominal += parseInt(dataNominal[key])
                                nipDataRequest.push(value.nip);
                                no++;
                                new_body_tr += `
                                        <tr class="">
                                        <td>
                                            ${no}
                                        </td>
                                        <td>
                                            ${value.nip}
                                        </td>
                                        <td>
                                            ${value.nama_karyawan}
                                        </td>
                                        <td>
                                            ${no_rek[key] == undefined ? '-' : no_rek[key]}
                                        </td>
                                        <td>
                                            ${formatRupiah(dataNominal[key].toString())}
                                        </td>
                                    </tr>
                                `;
                            }
                        })
                        if (hasError == true) {
                            if (NoCheckFinal == true) {
                                var message = ``;
                                message += `Tidak bisa memilih bulan ` + getMonth(bulanReq) + ` tahun `+ tahun_ini +`, karena sudah melakukan finalisasi.`
                                alertDanger(message);
                                $('#table_item tbody').empty();
                                $('#hasil-filter').addClass('hidden');
                                $('#btn-simpan').addClass('hidden');
                            } else {
                                if (hasNip == true) {
                                    message += `${checkNip}`
                                    tittleMessage += `Tidak ditemukan`
                                }
                                if (hasTunjangan == true) {
                                    message += `${checkNipTunjangan}`
                                    tittleMessage += `Sudah terdaftar di tunjangan ${namaTunjangan[0]}`
                                }
                                $('#alert-massage').html(`
                                    <div id="alert-border-2" class="flex items-center p-4 mb-4 text-red-800 border-t-4 border-red-300 bg-red-50">
                                        <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                        </svg>
                                        <div class="ms-3 text-sm font-medium">
                                        <strong>Data tidak valid.</strong> Nip : ${message} <br>
                                        ${tittleMessage}, <strong>${headerMessage}</strong>
                                        </div>
                                        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8"  data-dismiss-target="#alert-border-2" aria-label="Close">
                                        <span class="sr-only">Dismiss</span>
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                        </svg>
                                        </button>
                                    </div>
                                `)
                                $('#cover-btn-simpan').addClass('hidden');
                                $('#hasil-filter').removeClass('hidden');
                                $('#table_item tbody').append(new_body_tr);
                                $('#grand').html(`
                                    <p id="total-data" class="text-lg font-bold text-gray-400">Total Data : <b class="text-black">${dataNip.length}</b></p>
                                    <p id="grand-total" class="ml-10 text-lg font-bold text-gray-400">Grand Total : <b class="text-black">${
                                        formatRupiah(grandTotalNominal.toString())
                                    }</b></p>
                                `)
                            }
                        }else{
                            $('#alert-massage').html(`
                                <div id="alert-border-3" class="flex items-center p-4 mb-4 text-green-800 border-t-4 border-green-300 bg-green-50" role="alert">
                                    <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                    </svg>
                                    <div class="ms-3 text-sm font-medium">
                                        Data Valid.
                                    </div>
                                    <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8"  data-dismiss-target="#alert-border-3" aria-label="Close">
                                    <span class="sr-only">Dismiss</span>
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                    </button>
                                </div>
                            `)
                            $('.nominal-input').val(dataNominal)
                            $('.nip-input').val(nipDataRequest);
                            $('.tunjangan-input').val(id_tunjangan);
                            $('.bulan-input').val(bulanReq);

                            $('#table_item tbody').append(new_body_tr);
                            $('#cover-btn-simpan').removeClass('hidden');
                            $('#btn-simpan').removeClass('hidden');
                            $('#hasil-filter').removeClass('hidden');
                            $('#grand').html(`
                                <p id="total-data" class="text-lg font-bold text-gray-400">Total Data : <b class="text-black">${dataNip.length}</b></p>
                                <p id="grand-total" class="ml-10 text-lg font-bold text-gray-400">Grand Total : <b class="text-black">${
                                    formatRupiah(grandTotalNominal.toString())
                                }</b></p>
                            `)
                        }


                    },
                    complete: function () {
                        $('#loading-message').empty();
                    }
                })
                // Start processing rows
                // handleRow(0);
            }

            $('#btn-simpan').on('click', function(){
                // setTimeout(() => {
                //     $('.preloader').addClass('hidden')
                // }, 3000);

                $("#loadingModal").modal({
                    keyboard: false
                });
                $("#loadingModal").modal("show");
            })

            function getMonth(bulan) {
                bulans = parseInt(bulan);
                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

                return months[bulans - 1];
            }

            function alertDanger(message) {
                // Display an alert with danger style
                $('#alert-massage').removeClass('hidden');

                $('#alert-massage').html(`
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
                $('#alert-massage').removeClass('hidden');

                $('#alert-massage').html(`
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

        });
    </script>
@endsection
