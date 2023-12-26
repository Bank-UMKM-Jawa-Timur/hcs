@include('penghasilan-teratur.modal.loading')
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
        document.querySelector('.custom-file-input').addEventListener('change', function (e) {
            var name = document.getElementById("upload_csv").files[0].name;
            var nextSibling = e.target.nextElementSibling
            nextSibling.innerText = name
        });


        $(document).ready(function() {
            var kategori;
            var url;

            $('.btn-import').on('click',function(element) {
                url = "{{ route('api.get.karyawan') }}";
                $('#table-data').addClass('hidden');
                $('#table_item tbody').empty();
                $('#alert-container').addClass('hidden');


                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;
                var grand_total = 0;
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

                                var checkNip = [];

                                var hasError = false;
                                var hasSuccess = false;

                                var invalidNamaRows = [];
                                $.each(sheet_data,function(key, value) {
                                    if (sheet_data[key].hasOwnProperty('Nominal') && sheet_data[key].hasOwnProperty('NIP')) {

                                        dataNip.push({ nip: value['NIP'], row: key + 1 });
                                        dataNominal.push(value['Nominal'])
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
                                                    </tr>
                                                `;

                                            })
                                            if (hasError == true) {
                                                var message = ``;
                                                message += `Data tidak ditemukan pada NIP :<span class="font-weight-bold"> ${checkNip}</span> <br> <span class="pt-3 font-italic">Harap cek pada file excel kembali dan silahkan upload ulang.</span>`
                                                $('#button-simpan').addClass('hidden');
                                                alertDanger(message)
                                            }
                                            if (hasError != true) {
                                                alertSuccess('Data Valid.');
                                                $('.nominal-input').val(dataNominal)
                                                $('.nip').val(nipDataRequest);
                                                $('#button-simpan').removeClass('hidden');
                                            }
                                            var total_grand = grand_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')
                                            $('#grand-total').html(`
                                                <span id="grand-total-value" class="font-weight-bold">Grand Total : ${total_grand}</span>
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
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Import Bonus</h5>
            <p class="card-title"><a href="">Dashboard</a> > <a href="{{ route('bonus.index') }}">Bonus</a> >Import</p>
            <a href="{{ route('bonus.excel') }}"  class="btn is-btn is-primary">Download Template Excel</a>

        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('edit-tunjangan-bonus-post') }}" enctype="multipart/form-data" method="POST" class="form-group mt-4">
            @csrf
        <div class="row px-3">
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
            <div class="col-md-12 px-4">
                    <div class="form-row mb-3">
                        <div class="col">
                            <label for="">Kategori</label>
                            <select name="kategori_bonus" id="kategori-bonus" class="form-control" @readonly(true)>
                                <option value="{{ $penghasilan->id }}">{{ ucwords($penghasilan->nama_tunjangan) }}</option>
                            </select>
                        </div>
                        <div class="col kategori-tunjangan-select">
                            <label for="">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal" id="">
                        </div>
                        <div class="col">
                            <label for="">Data Excel</label>
                            <div class="custom-file col-md-12">
                                <input type="file" name="upload_csv" class="custom-file-input" id="upload_csv" >
                                <label class="custom-file-label overflow-hidden" for="upload_csv"  style="padding: 10px 4px 30px 5px">Choose file...</label>
                            </div>
                            {{-- <div class=" col-md-12 ">
                                <input type="file" name="upload_csv" class="custom-file-input form-control"  id="upload_csv"  accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                <label class="custom-file-label overflow-hidden" for="validatedCustomFile" style="padding: 10px 4px 30px 5px">Choose file...</label>
                            </div> --}}
                        </div>
                        <div class="col align-items-center mt-4">
                            <button type="button" class="is-btn is-primary btn-import">Import</button>
                        </div>
                    </div>
            </div>
            <div class="col-md-4 px-4 align-self-center mt-4" id="total-data">
            </div>
            <div class="col-md-4 px-4 align-self-center mt-4" id="grand-total">
            </div>
            <div class="col-md-4 px-4 align-self-center mt-4">
                <div class="d-flex justify-content-start hidden">
                    <input type="text" name="nominal" class="form-control nominal-input" value="" readonly hidden>
                    <input type="text" name="nip" class="form-control nip" value="" readonly hidden>
                    <input type="hidden" name="old_tanggal" value="{{$old_created_at}}">
                    <input type="hidden" name="old_tunjangan" value="{{$old_id}}">
                    <button type="submit" class="is-btn btn-info hidden" id="button-simpan">Simpan</button>
                </div>
            </div>
            <div class="col-md-12 px-4" id="loading-message">
            </div>
            <div class="col-md-12 px-4 hidden" id="table-data">
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
