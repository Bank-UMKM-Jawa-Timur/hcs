@extends('layouts.template')
@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <style>
        .hidden{
            display: none;
        }
        .custom-file-label::after{
            padding: 10px 5px 30px 5px;
        }
    </style>
@endpush
@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
    <script>
        $(document).ready(function() {
            var kategori;
            var url;

            // 1. Import Data dan cek validasi jika ada yang salah ditampilkan alert eror row berapa kesalahan nya dan upload ulang
                // -- tombol simpan tampil jika proses data selesai
            // 2. Jika data valid maka muncul alert success
            // 3. jika success maka data dapat disimpan




            $('.btn-import').on('click',function(element) {
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
                            var cnt = 0;
                            sheet_name_list.forEach(function(y) {
                                var exceljson = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[y]);
                                var excel = workbook.Sheets[y];
                                var cell_range = excel['!ref'] != '' ? excel['!ref'].split(':') : [];
                                var cell_from = cell_range.length == 2 ? cell_range[0] : ''
                                var cell_to = cell_range.length == 2 ? cell_range[1] : ''
                                var letterPattern = /[a-z]+/gi;
                                var cell_from_letter = cell_from.match(letterPattern)[0]
                                var cell_to_letter = cell_to.match(letterPattern)[0]
                                var numberPattern = /\d+/g;
                                var cell_from_number = cell_from.match(numberPattern)[0]
                                var cell_to_number = cell_to.match(numberPattern)[0]
                                var cell_range_letter = ['A', 'B']

                                var arr_data = [];

                                for (var i = 2; i <= cell_to_number; i++) {
                                    var arr_row = [];
                                    for (var j = 0; j < cell_range_letter.length; j++) {
                                        var index = `${cell_range_letter[j]}${i}`
                                        arr_row.push(excel[index].v)
                                    }
                                    arr_data.push(arr_row)
                                }

                                handleRow(arr_data);


                            })
                        }
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
            function handleRow(arr_data) {
                // var row = arr_data;
                // console.log(arr_data);
                $('#total-data').html(`
                    <span id="total-data" class="font-weight-bold">Total Data : ${arr_data.length}</span>
                `)


                var dataNip = [];
                var dataNominal = [];
                var nipDataRequest = [];

                var checkNip = [];

                var hasError = false;
                var hasSuccess = false;

                var invalidNamaRows = [];
                $.each(arr_data,function(key, value) {
                    dataNip.push({ nip: value[0], row: key + 1 });
                })
                var grand_total = 0;
                $.ajax({
                        type: "GET",
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
                                dataNominal.push(arr_data[key][1]);
                                nipDataRequest.push(value.nip);
                                // if (res.some(checkUsername)) {
                                if (value.nip == '-') {
                                    checkNip.push(value.row);
                                    hasError = true
                                }
                                grand_total += arr_data[key][1]
                                new_body_tr += `
                                    <tr>
                                        <td>
                                            <span>${key + 1}</span>
                                        </td>
                                        <td>
                                            <span>${value.nip}</span>
                                        </td>
                                        <td>
                                            <span>${value.nama_karyawan}</span>
                                        </td>
                                        <td>
                                            <span>${arr_data[key][1]}</span>

                                        </td>
                                    </tr>
                                `;

                            })
                            if (hasError == true) {
                                var message = ``;
                                message += `Data tidak ditemukan di row : ${checkNip}`
                                alertDanger(message)
                            }
                            if (hasError != true) {
                                alertSuccess('Data Valid.');
                                $('.nominal-input').val(dataNominal)
                                $('.nip').val(nipDataRequest);
                                $('#button-simpan').removeClass('hidden');
                            }
                            $('#grand-total').html(`
                                <span id="grand-total" class="font-weight-bold">Grand Total : ${
                                    new Intl.NumberFormat("id-ID", {
                                    style: "currency",
                                    currency: "IDR"
                                    }).format(grand_total)
                                }</span>
                            `)
                            $('#table_item tbody').append(new_body_tr);

                        },
                        complete: function () {
                            // Remove the loading message or indicator after the API call is complete
                            $('#loading-message').empty();
                        }
                });
            }
            function formatRupiah(angka, prefix) {
                var number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                // tambahkan titik jika yang di input sudah menjadi angka ribuan
                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
            }

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
        <div class="card-header">
            <h5 class="card-title">Import Bonus</h5>
            <p class="card-title"><a href="">Dashboard</a> > <a href="{{ route('bonus.index') }}">Bonus</a> >Import</p>
            <a href="{{ route('bonus.excel') }}"  class="btn btn-primary">Download Template Excel</a>

        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('bonus.store') }}" enctype="multipart/form-data" method="POST" class="form-group mt-4">
            @csrf
        <div class="row">
            <div class="col-md-12">
                <div id="alert-container">

                </div>
            </div>
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
            </div>
            <div class="col-md-12">
                    <div class="form-row">
                        <div class="col">
                            <label for="">Kategori</label>
                            <select name="kategori_bonus" id="kategori-bonus" class="form-control">
                                <option value="">Pilih Kategori Tunjangan</option>
                                @forelse ($data_tunjangan as $item)
                                    <option value="{{ $item->id }}">{{ ucwords($item->nama_tunjangan) }}</option>
                                @empty
                                    <option value="">Tidak Ada Tunjangan</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col kategori-tunjangan-select">
                            <label for="">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal" id="">
                        </div>
                        <div class="col">
                            <label for="">Data Excel</label>

                            <div class=" col-md-12 ">
                                <input type="file" name="upload_csv" class="custom-file-input form-control"  id="upload_csv"  accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                <label class="custom-file-label overflow-hidden" for="validatedCustomFile" style="padding: 10px 4px 30px 5px">Choose file...</label>
                            </div>
                        </div>
                        <div class="col align-items-center mt-2">
                            <button type="button" class="btn btn-info btn-import">Import</button>
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
                    <input type="text" name="nip" class="form-control nip" value="" readonly hidden>
                    <button type="submit" class="btn btn-info hidden" id="button-simpan">Simpan</button>
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
