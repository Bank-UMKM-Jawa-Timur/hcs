@include('penghasilan-teratur.modal.loading')
@extends('layouts.template')
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
@endpush
@section('content')

    <div class="card-header">
        <div class="card-title">
            <h5 class="card-title">Import Penghasilan</h5>
            <p class="card-title"><a href="/">Dashboard </a> > <a href="{{route('penghasilan.import-penghasilan-teratur.index')}}">Penghasilan Teratur</a> > Import Penghasilan Teratur</p>
        </div>
    </div>

    <div class="card-body">
        <a class="btn is-btn is-primary" href="{{ route('penghasilan.template-excel') }}" download>Download Template Excel</a>
        <div class="row">
            <div class="col-lg-5">
                <div class="form-group">
                    <label for="">Kategori penghasilan teratur</label>
                    <select name="penghasilan" class="form-control" id="penghasilan">
                        <option value="">==Pilih Kategori==</option>
                        @foreach ($penghasilan as $item)
                            <option value="{{$item->id}}">{{$item->nama_tunjangan}}</option>
                        @endforeach
                    </select>
                    <p class="text-danger d-none mt-2" id="error-penghasilan"></p>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="form-group">
                    <label for="">File</label>
                    <div class="custom-file">
                        <input type="file" name="upload_excel" class="custom-file-input form-control"  id="file-penghasilan"  accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" onchange="updateFileName()">
                        <label class="custom-file-label overflow-hidden" for="validatedCustomFile" id="file-label" style="padding: 10px 4px 30px 5px">Choose file...</label>
                        {{-- <input type="file" name="upload_excel" class="custom-file-input form-control" id="file-penghasilan" accept=".xlsx, .xls" >
                        <label class="custom-file-label overflow-hidden" for="file-penghasilan"  id="file-label">Choose Excel file...</label> --}}
                    </div>
                    <p class="text-danger d-none mt-2" id="error-file">File belum di pilih.</p>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <button class="btn btn-primary" id="filter">Tampilkan</button>
            </div>
        </div>
        <div id="alert-massage">

        </div>
        <div class="teks mt-4">
            <div class="col-md-4 align-self-center mt-4" id="grand"></div>
        </div>

        <form action="{{route('penghasilan.import-penghasilan-teratur.store')}}" method="POST">
        @csrf
        <input type="hidden" name="nominal" class="form-control nominal-input" value="" readonly>
        <input type="hidden" name="nip" class="form-control nip-input" value="" readonly>
        <input type="hidden" name="tunjangan" class="form-control tunjangan-input" value="" readonly>
        <div class="d-flex justify-content-start">
            <button type="submit" class="btn btn-primary d-none" id="btn-simpan">Simpan</button>
        </div>
        <div class="col-md-10" id="loading-message"></div>
        <div class="row d-none" id="hasil-filter">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table" id="table_item">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nip</th>
                                    <th>Nama Karyawan</th>
                                    <th>Nominal</th>
                                    {{-- <th>Aksi</th> --}}
                                    {{-- <th>
                                        <button type="button" class="btn btn-sm btn-icon btn-round btn-primary btn-plus">
                                            +
                                        </button>
                                    </th> --}}
                                </tr>
                            </thead>
                            <tbody id="t_body">
                                {{-- <tr>
                                    <td>1</td>
                                    <td>1121212</td>
                                    <td>Saya</td>
                                    <td>123.321</td>
                                    <td>
                                        <button class="btn btn-warning" id="edit-penghasilan">edit</button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-icon btn-round btn-danger btn-minus">
                                            -
                                        </button>
                                    </td>
                                </tr> --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </form>
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

@push('script')
    <script>
        $(document).ready(function() {
            var grandTotalNominal = 0;
            $('#penghasilan').on('change', function(){
                var value = $("#penghasilan").val()
                var hari_ini = new Date();
                var tanggal = hari_ini.getDate();
                var message = '';
                var nmbr = 0;

                // 11 = tranport, 12 = pulsa, 13 = vitamin, 14 = uang makan

                if (value == 11 || value == 14) {
                    if (tanggal == 25) {
                        var message = "success"
                        $('#error-penghasilan').addClass('d-none')
                    } else {
                        if (value == 12) {
                            var message = 'Transaksi pulsa hanya bisa dilakukan pada tanggal 1 sampai 10'
                            nmbr++
                        } else if (value == 13) {
                            var message = 'Transaksi vitamin hanya bisa dilakukan pada tanggal 1 sampai 5'
                            nmbr++
                        } else if (value == 11) {
                            var message = 'Transaksi transport hanya bisa dilakukan pada tanggal 25'
                            nmbr++
                        } else if (value == 14) {
                            var message = 'Transaksi uang makan hanya bisa dilakukan pada tanggal 25'
                            nmbr++
                        }
                        // alertWarning(message)
                        $('#error-penghasilan').removeClass('d-none').html(message)
                        $("#penghasilan").val("")
                    }
                } else if (value == 12) {
                    if (tanggal >= 1 && tanggal <= 10){
                        var message = "success"
                        $('#error-penghasilan').addClass('d-none')
                    } else {
                        if (value == 11) {
                            var message = 'Transaksi transport hanya bisa dilakukan pada tanggal 25'
                            nmbr++
                        } else if (value == 14) {
                            var message = 'Transaksi uang makan hanya bisa dilakukan pada tanggal 25'
                            nmbr++
                        } else if (value == 12) {
                            var message = 'Transaksi pulsa hanya bisa dilakukan pada tanggal 1 sampai 10'
                        } else if (value == 13 && tanggal > 5) {
                            var message = 'Transaksi pulsa hanya bisa dilakukan pada tanggal 1 sampai 10'
                        }

                        // alertWarning(message)
                        $('#error-penghasilan').removeClass('d-none').html(message)
                        $("#penghasilan").val("")
                    }
                } else if(value == 13){
                    if (tanggal >= 1 && tanggal <= 5 ) {
                        var message = "success"
                        $('#error-penghasilan').addClass('d-none')
                    }
                    else {
                        if (value == 11) {
                            var message = 'Transaksi transport hanya bisa dilakukan pada tanggal 25'
                            nmbr++
                        } else if (value == 12) {
                            var message = 'Transaksi pulsa hanya bisa dilakukan pada tanggal 1 sampai 10'
                            nmbr++
                        } else if (value == 13) {
                            var message = 'Transaksi vitamin hanya bisa dilakukan pada tanggal 1 sampai 5'
                            nmbr++
                        } else if (value == 14) {
                            var message = 'Transaksi uang makan hanya bisa dilakukan pada tanggal 25'
                            nmbr++
                        }
                        // alertWarning(message)
                        $('#error-penghasilan').removeClass('d-none').html(message)
                        $("#penghasilan").val("")
                    }
                }
            })

            $('#filter').on('click', function(e) {
                // console.log('askdaskdjasldjldj');
                var penghasilan = $('#penghasilan').val();
                var filePenghasilan = $('#file-penghasilan').val();

                if (penghasilan && filePenghasilan) {
                    importExcel();
                    $('#table_item tbody').empty();
                    $('#error-penghasilan').addClass('d-none')
                    $('#error-file').addClass('d-none')
                } else {
                    if (penghasilan == "" && filePenghasilan) {
                        $('#error-penghasilan').removeClass('d-none').html('Kategori belum di pilih.')
                        $('#error-file').addClass('d-none')
                    }
                    else if (!filePenghasilan && penghasilan){
                        $('#error-file').removeClass('d-none')
                        $('#error-penghasilan').addClass('d-none')
                    }
                    else {
                        $('#error-penghasilan').removeClass('d-none').html('Kategori belum di pilih.')
                        $('#error-file').removeClass('d-none')
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
                        $('#hasil-filter').addClass('d-none');
                        $('#btn-simpan').addClass('d-none');
                    }
            }

            function showTable(sheet_data) {
                var no = 0;
                var grandTotalNominal = 0;
                var id_tunjangan = $('#penghasilan').val()
                var date = new Date();
                var hari_ini = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();

                var dataNip = [];
                var dataNominal = [];
                var nipDataRequest = [];

                var checkNip = [];
                var checkNipTunjangan = [];
                var namaTunjangan = [];

                var hasError = false;
                var hasNip = false;
                var hasTunjangan = false;
                var hasSuccess = false;

                $.each(sheet_data,function(key, value) {
                    if (sheet_data[key].hasOwnProperty('Nominal') && sheet_data[key].hasOwnProperty('NIP')) {

                        dataNip.push({ nip: value['NIP'], row: key + 1 });
                        dataNominal.push(value['Nominal'])
                    }
                })

                $.ajax({
                    type: "GET",
                    url: `{{ url('penghasilan/get-karyawan-by-entitas') }}`,
                    data: {
                        nip: JSON.stringify(dataNip),
                        tanggal: hari_ini,
                        id_tunjangan: id_tunjangan,
                    },
                    beforeSend: function () {
                        $('#loading-message').html(`
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    Loading Data...
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                        `);
                    },
                    success: function(res){
                        var new_body_tr = ``;
                        var message = ``;
                        var tittleMessage = ``;
                        var headerMessage = `harap cek kembali pada file excel yang di upload.`;
                        $.each(res,function(key,value) {
                                nipDataRequest.push(value.nip);
                                no++;
                                if (value.cek_nip == false) {
                                    checkNip.push(value.nip);
                                    hasError = true;
                                    hasNip = true;
                                    hasTunjangan = false;
                                } else if (value.cek_tunjangan == true) {
                                    checkNipTunjangan.push(value.nip);
                                    namaTunjangan.push(value.tunjangan.nama_tunjangan);
                                    hasError = true;
                                    hasTunjangan = true;
                                    hasNip = false;
                                }
                                grandTotalNominal += parseInt(dataNominal[key])
                                new_body_tr += `
                                     <tr class="${value.cek_nip == false || value.cek_tunjangan == true ? 'table-danger' : ''}">
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
                                            ${formatRupiah(dataNominal[key].toString())}
                                        </td>
                                    </tr>
                                `;

                            })
                            if (hasError == true) {
                                if (hasNip == true) {
                                    message += `${checkNip}`
                                    tittleMessage += `Tidak ditemukan`
                                }
                                if (hasTunjangan == true) {
                                    message += `${checkNipTunjangan}`
                                    tittleMessage += `Sudah terdaftar di tunjangan ${namaTunjangan[0]}`
                                }
                                $('#alert-massage').html(`
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Data tidak valid.</strong> Nip : ${message} <br>
                                        ${tittleMessage}, <strong>${headerMessage}</strong>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                `)
                                $('#btn-simpan').addClass('d-none');
                                $('#hasil-filter').removeClass('d-none');
                                $('#table_item tbody').append(new_body_tr);
                                $('#grand').html(`
                                    <p id="total-data" class="font-weight-bold">Total Data : ${dataNip.length}</p>
                                    <p id="grand-total" class="font-weight-bold">Grand Total : ${
                                        formatRupiah(grandTotalNominal.toString())
                                    }</p>
                                `)
                            }
                            else {
                                $('#alert-massage').html(`
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Data valid.</strong>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                `)
                                $('.nominal-input').val(dataNominal)
                                $('.nip-input').val(nipDataRequest);
                                $('.tunjangan-input').val(id_tunjangan);

                                $('#table_item tbody').append(new_body_tr);
                                $('#btn-simpan').removeClass('d-none');
                                $('#hasil-filter').removeClass('d-none');
                                $('#grand').html(`
                                    <p id="total-data" class="font-weight-bold">Total Data : ${dataNip.length}</p>
                                    <p id="grand-total" class="font-weight-bold">Grand Total : ${
                                        formatRupiah(grandTotalNominal.toString())
                                    }</p>
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
                $("#loadingModal").modal({
                    keyboard: false
                });
                $("#loadingModal").modal("show");
            })
            function alertWarning(message) {
                Swal.fire({
                    tittle: 'Warning!',
                    html: message,
                    icon: 'warning',
                    iconColor: '#DC3545',
                    confirmButtonText: 'Ya',
                    confirmButtonColor: '#DC3545'
                }).then((result) => {
                    return result.isConfirmed;
                })
            }

        });
    </script>
@endpush
