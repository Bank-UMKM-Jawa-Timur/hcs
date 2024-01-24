{{-- @include('penghasilan-teratur.modal.loading') --}}
@extends('layouts.app-template')
{{-- @push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <style>
        .hidden{
            display: none;
        }
        .custom-file-label::after{
            padding: 10px 5px 30px 5px;
        }
    </style>
@endpush --}}
@push('extraScript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>

        $(document).ready(function() {
            var kategori;
            var url;

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
                url = "{{ route('api.get.karyawan2') }}";
                $('#table-data').addClass('hidden');
                $('#table_item tbody').empty();
                $('#alert-container').addClass('hidden');

                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;
                var grand_total = 0;
                var tanggal = $("#tanggal").val();
                var test = $("#upload_csv").val();
                var sheet_data = [];
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
                                        url: url,
                                        data: {
                                            nip: JSON.stringify(dataNip),
                                            tanggal: tanggal
                                        },
                                        beforeSend: function () {
                                            // Display a loading message or indicator before the API call
                                            $('#loading-message').html(`
                                                <div class="flex items-center p-4 mb-4 text-yellow-800 border-t-4 border-yellow-300 bg-yellow-50" role="alert">
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
                                                        // hasError = true
                                                    }else{
                                                        let nipDuplicate = duplicateNIP.find((item) => item == value.nip)
                                                        if (!nipDuplicate) {
                                                            nipDataRequest.push(value.nip);
                                                            grand_total += parseInt(dataNominal[key])
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
                                                        message += `Data tidak ditemukan pada NIP : <span class="font-weight-bold"> ${checkNip}</span> <br> <span class="font-italic"> Harap cek pada file excel kembali dan silahkan upload ulang.</span>`
                                                        $('#button-simpan').addClass('hidden');
                                                        alertDanger(message)
                                                    }
                                                }
                                                if (hasError != true) {
                                                    alertSuccess('Data Valid.');
                                                    $('.nominal-input').val(dataNominal)
                                                    $('.nip').val(nipDataRequest);
                                                    $('#button-simpan').removeClass('hidden');
                                                }
                                                var total_grand = grand_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')
                                                $('#grand').html(`
                                                    <p id="total-data" class="font-weight-bold">Total Data : ${dataNip.length}</p>
                                                    <p id="grand-total" class="font-weight-bold">Grand Total : ${total_grand}</p>
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

            })
            function formatRupiah(angka, prefix){
                var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split   		= number_string.split(','),
                sisa     		= split[0].length % 3,
                rupiah     		= split[0].substr(0, sisa),
                ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

                // tambahkan titik jika yang di input sudah menjadi angka ribuan
                if(ribuan){
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
            }
            function alertDanger(message) {
                // Display an alert with danger style
                $('#alert-container').removeClass('hidden');

                $('#alert-container').html(`
                    <div class="flex items-center p-4 text-red-800 border-t-4 border-red-300 bg-red-50 dark:text-red-400" role="alert">
                        ${message}
                    </div>
                `);
            }
            function alertSuccess(message) {
                // Display an alert with danger style
                $('#alert-container').removeClass('hidden');

                $('#alert-container').html(`
                    <div class="flex items-center p-4 text-green-800 border-t-4 border-green-300 bg-green-50" role="alert">
                        ${message}

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
            <div class="text-2xl font-bold tracking-tighter">
                Edit Import Bonus
            </div>
            <div class="breadcrumb">
                <a href="/" class="text-sm text-gray-500">Dashboard</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('bonus.index') }}" class="text-sm text-gray-500">Bonus</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="#" class="text-sm text-gray-500 font-bold">Edit Bonus</a>
            </div>
        </div>
        <div class="button-wrapper flex gap-3">
            <a href="{{ route('bonus.excel') }}"  class="btn btn-outline-excel"> <i class="ti ti-download"></i> Download Template Excel</a>
        </div>
    </div>
</div>

    <div class="body-pages">
        <form action="{{ route('edit-tunjangan-bonus-post') }}" enctype="multipart/form-data" method="POST" class="form-group mt-4">
            @csrf
            <input type="hidden" name="kd_entitas" value="{{\Request::get('entitas')}}">
            <div class="table-wrapping">
                <div class="col-md-12 justify-content-center">
                    @if ($errors->any())
                    <div class="p-4 mb-4 text-red-800 border border-red-300 rounded-lg bg-red-50" role="alert">
                        <span class="alert-link">Terjadi Kesalahan</span>
                        <ul>
                            @foreach ($errors->all() as $item)
                            <li class="max-w-md space-y-1 text-gray-500 list-disc list-inside">
                                {{ $item }}
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    @endif
                </div>
                <div class="col-md-12 px-4">
                    <div class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-5 mt-5 mb-5 items-end">
                        <div class="input-box">
                            <label for="">Kategori</label>
                            <select name="kategori_bonus" id="kategori-bonus" class="form-input" @readonly(true)>
                                <option value="{{ $penghasilan->id }}">{{ ucwords($penghasilan->nama_tunjangan) }}</option>
                            </select>
                        </div>
                        <div class="input-box kategori-tunjangan-select">
                            <label for="">Tanggal</label>
                            <input type="date" class="form-input" name="tanggal" id="tanggal" value="{{$old_created_at}}">
                        </div>
                        <div class="input-box">
                            <label for="">Data Excel</label>
                            <div class="input-group custom-file col-md-12">
                                <input type="file" name="upload_csv" class="form-upload custom-file-input" id="upload_csv" >
                                <button class="upload-group-icon">
                                    <label for="file-penghasilan">
                                        <i class="ti ti-upload"></i>
                                    </label>
                                </button>
                            </div>
                        </div>
                        <div class="input-box align-items-center mt-4 w-fit">
                            <button type="button" class="btn btn-primary is-btn is-primary btn-import">Import</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-wrapping hidden my-3" id="card-alert">
                <div class="col-md-12">
                    <div id="alert-container"></div>
                </div>
                <div class="col-md-12 px-4" id="loading-message">
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex">
                        <div class="col-md-4 px-4 align-self-center mt-4" id="total-data">
                        </div>
                        <div class="col-md-4 px-4 align-self-center mt-4" id="grand-total">
                        </div>
                    </div>
                    <div class="col-md-4 px-4 align-self-center mt-4">
                        <div class="d-flex justify-content-start">
                            <input type="text" name="nominal" class="form-control nominal-input" value="" readonly hidden>
                            <input type="text" name="nip" class="form-control nip" value="" readonly hidden>
                            <input type="hidden" name="old_tanggal" value="{{$old_created_at}}">
                            <input type="hidden" name="old_tunjangan" value="{{$old_id}}">
                            <input type="hidden" name="kdEntitas" value="{{Request()->entitas}}">
                            <button type="submit" class="btn btn-primary hidden" id="button-simpan">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-wrapping hidden" id="table-data">
                <table class="tables whitespace-nowrap table-bondered" id="table_item">
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
                            No Rekening
                        </th>
                        <th>
                            Nominal
                        </th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </form>
    </div>
@endsection
