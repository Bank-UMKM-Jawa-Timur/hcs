@include('penghasilan-teratur.modal.loading')
@extends('layouts.template')
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
@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
@section('content')
    <div class="card-header">
        <div class="card-title">
            <h5 class="card-title">Import Potongan</h5>
            <p class="card-title"><a href="/">Dashboard </a> > <a href="{{ route('potongan.index') }}">Potongan</a> >
                Import Potongan</p>
        </div>
    </div>

    <div class="card-body">
        <a class="btn is-btn is-primary" href="{{ route('template-excel-potongan') }}" download>Download Template Excel</a>
        <div class="mt-2 mb-2" id="alert-content">
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="">File</label>
                    <div class="custom-file">
                        <input type="file" name="upload_excel" class="custom-file-input form-control" id="file-csv"
                            accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                            onchange="updateFileName()">
                        <label class="custom-file-label overflow-hidden" for="validatedCustomFile" id="file-label"
                            style="padding: 10px 4px 30px 5px">Choose file...</label>
                    </div>
                    <p class="text-danger d-none mt-2" id="error-file">File belum di pilih.</p>
                </div>
            </div>
            <div class="col-lg-2 align-items-center mt-3">
                <button type="button" class="btn is-btn is-primary filter-potongan-import" id="filter-potongan-import">Tampilkan</button>
            </div>
        </div>
        <div class="teks mt-4">
            <div class="col-md-4 align-self-center mt-4" id="grand"></div>
        </div>

        <form action="{{ route('import-potongan-post') }}" method="POST">
            @csrf
            <input type="hidden" name="nip" class="form-control nip-input" value="" readonly>
            <input type="hidden" name="kredit_koperasi" class="form-control kredit-koperasi-input" value="" readonly>
            <input type="hidden" name="iuran_koperasi" class="form-control iuran-koperasi-input" value="" readonly>
            <input type="hidden" name="kredit_pegawai" class="form-control kredit-pegawai-input" value="" readonly>
            <input type="hidden" name="iuran_ik" class="form-control iuran_ik-input" value="" readonly>
            <div class="d-flex justify-content-start">
                <button type="submit" class="btn is-btn is-primary d-none" id="btn-simpan">Simpan</button>
            </div>
            <div class="col" id="loading-message"></div>
            <div class="row d-none" id="hasil-import">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table" id="table_item">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nip</th>
                                        <th>Nama Karyawan</th>
                                        <th>Kredit Koprasi</th>
                                        <th>Iuran Koprasi</th>
                                        <th>Kredit Pegawai</th>
                                        <th>Iuran IK</th>
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
    <script>
        function updateFileName() {
            var input = document.getElementById('file-csv');
            var label = document.getElementById('file-label');
            var fileName = input.files[0].name;
            label.innerHTML = fileName;
        }
    </script>
@endsection


@push('script')
    <script>

        $('#filter-potongan-import').on('click', function() {
            var fileCsv = $('#file-csv').val();

            if (fileCsv) {
                importExcel();
                $('#table_item tbody').empty();
                $('#error-file').addClass('d-none')
            } else {
                if (!fileCsv){
                    $('#error-file').removeClass('d-none')
                }
                else {
                    $('#error-file').removeClass('d-none')
                }
            }
        });

        function importExcel(){
            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;
            var test = $("#file-csv").val();
            var sheet_data = [];
            if (regex.test($("#file-csv").val().toLowerCase())) {
                var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/
                if ($('#file-csv').val().toLowerCase().indexOf(".xlsx") > 0) {
                    xlsxflag = true;
                }
                // check browser support html5
                if (typeof(FileReader) != 'undefined') {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var data = e.target.result;
                        // convert the excel data in to object
                        if (xlsxflag) {
                            var workbook = XLSX.read(data, {
                                type: 'binary'
                            });
                        }
                        // all element sheetnames of excel
                        var sheet_name_list = workbook.SheetNames;
                        if (typeof(sheet_name_list) != 'undefined') {
                            sheet_data = XLSX.utils.sheet_to_json(workbook.Sheets[sheet_name_list[
                                0]], {
                                header: 5
                            });
                            showTable(sheet_data);
                        }
                    }

                    reader.onerror = function(ex) {
                        console.log(ex);
                    };
                    if (xlsxflag) {
                        /*If excel file is .xlsx extension than creates a Array Buffer from excel*/
                        reader.readAsArrayBuffer($("#file-csv")[0].files[0]);
                    } else {
                        reader.readAsBinaryString($("#file-csv")[0].files[0]);
                    }
                } else {
                    console.log('tidak support');
                }
            } else {
                alert("Unggah file Excel yang valid!");
                $('#table_item tbody').empty();
                $('#hasil-import').addClass('d-none');
                $('#btn-simpan').addClass('d-none');
            }
        }

        function alertSuccess(message) {
            let msg = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="close" data-dismiss="alert" aria-label="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>`;
            return msg;

        }

        function alertDanger(message) {
            let msg = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="close" data-dismiss="alert" aria-label="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>`;
            return msg;
        }

        function showTable(sheet_data) {
            $('#alert-content').empty();
            var no = 0;
            var grandKreditKoprasi = 0;
            var grandIuranKoprasi = 0;
            var grandKreditPegawai = 0;
            var grandIuranIk = 0;

            var dataNip = [];
            var dataKreditKoprasi = [];
            var dataIuranKoprasi = [];
            var dataKreditPegawai = [];
            var dataIuranIk = [];

            var nipDataRequest = [];

            var checkNip = [];
            var namaTunjangan = [];

            var hasError = false;
            var hasNip = false;
            var hasTunjangan = false;
            var hasSuccess = false;

            $.each(sheet_data, function(key, value) {
                if (sheet_data[key].hasOwnProperty('Kredit Koprasi') &&
                    sheet_data[key].hasOwnProperty('NIP') &&
                    sheet_data[key].hasOwnProperty('Iuran Koprasi') &&
                    sheet_data[key].hasOwnProperty('Kredit Pegawai') &&
                    sheet_data[key].hasOwnProperty('Iuran IK')) {
                    dataNip.push({
                        nip: value['NIP'],
                        row: key + 1
                    });
                    dataKreditKoprasi.push(value['Kredit Koprasi']);
                    dataIuranKoprasi.push(value['Iuran Koprasi']);
                    dataKreditPegawai.push(value['Kredit Pegawai']);
                    dataIuranIk.push(value['Iuran IK']);
                }
            })

            var dataExcel = true;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: `{{ url('/get-karyawan-by-nip') }}`,
                data: {
                    nip: JSON.stringify(dataNip),
                },
                beforeSend: function() {
                    $('#loading-message').html(`
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            Loading Data...
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    `);
                },
                success: function(res) {
                    var new_body_tr = ``
                    $.each(res, function(key, value) {
                        nipDataRequest.push(value.nip);
                        no++;
                        if (value.cek_nip == false) {
                            checkNip.push(value.nip);
                            hasError = true;
                            hasNip = true;
                        } else {
                            hasError = false;
                            hasNip = false;
                        }
                        if (hasNip == true) {
                            dataExcel = false;
                        }
                        grandKreditKoprasi += parseInt(dataKreditKoprasi[key])
                        grandIuranKoprasi += parseInt(dataIuranKoprasi[key])
                        grandKreditPegawai += parseInt(dataKreditPegawai[key])
                        grandIuranIk += parseInt(dataIuranIk[key])
                        new_body_tr += `
                                <tr class="${hasNip == true ? 'table-danger' : ''}">
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
                                        ${formatRupiah(dataKreditKoprasi[key].toString())}
                                    </td>
                                    <td>
                                        ${formatRupiah(dataIuranKoprasi[key].toString())}
                                    </td>
                                    <td>
                                        ${formatRupiah(dataKreditPegawai[key].toString())}
                                    </td>
                                    <td>
                                        ${formatRupiah(dataIuranIk[key].toString())}
                                    </td>
                                </tr>
                            `;

                    })

                    if (hasNip == true) {
                        $('.nip-input').val(nipDataRequest);
                        $('.kredit-koperasi-input').val(dataKreditKoprasi);
                        $('.iuran-koperasi-input').val(dataIuranKoprasi);
                        $('.kredit-pegawai-input').val(dataKreditPegawai);
                        $('.iuran_ik-input').val(dataIuranIk);

                        $('#table_item tbody').append(new_body_tr);
                        $('#hasil-import').removeClass('d-none');
                    }else{
                        $('.nip-input').val(nipDataRequest);
                        $('.kredit-koperasi-input').val(dataKreditKoprasi);
                        $('.iuran-koperasi-input').val(dataIuranKoprasi);
                        $('.kredit-pegawai-input').val(dataKreditPegawai);
                        $('.iuran_ik-input').val(dataIuranIk);

                        $('#table_item tbody').append(new_body_tr);
                        $('#hasil-import').removeClass('d-none');
                        $('#grand').html(`
                                <p id="total-data" class="font-weight-bold">Total Data : ${dataNip.length}</p>
                                <p id="grand-total" class="font-weight-bold">Grand Kredit Koprasi : ${
                                    new Intl.NumberFormat("id-ID", {
                                    style: "currency",
                                    currency: "IDR"
                                    }).format(grandKreditKoprasi).replace(/(\.|,)00$/g, '')
                                }</p>
                                <p id="grand-total" class="font-weight-bold">Grand Iuran Koprasi : ${
                                    new Intl.NumberFormat("id-ID", {
                                    style: "currency",
                                    currency: "IDR"
                                    }).format(grandIuranKoprasi).replace(/(\.|,)00$/g, '')
                                }</p>
                                <p id="grand-total" class="font-weight-bold">Grand Kredit Pegawai : ${
                                    new Intl.NumberFormat("id-ID", {
                                    style: "currency",
                                    currency: "IDR"
                                    }).format(grandKreditPegawai).replace(/(\.|,)00$/g, '')
                                }</p>
                                <p id="grand-total" class="font-weight-bold">Grand Iuran IK : ${
                                    new Intl.NumberFormat("id-ID", {
                                    style: "currency",
                                    currency: "IDR"
                                    }).format(grandIuranIk).replace(/(\.|,)00$/g, '')
                                }</p>
                            `)
                    }

                    if (dataExcel == true) {
                        console.log("TRUE");
                        let alrtSucces = alertSuccess("Data Valid.");
                        $('#alert-content').append(alrtSucces);
                        $('#btn-simpan').addClass('hidden');
                        $('#btn-simpan').removeClass('d-none');
                        $('#btn-simpan').removeClass('hidden');
                    }else{
                        console.log("FALSE");
                        let message = ``;
                        message += `NIP : ${checkNip} tidak di temukan, harap cek kembali pada file excel yang di upload`;
                        $('#alert-content').append(alertDanger(message));
                        $('#btn-simpan').addClass('hidden');
                    }

                },
                complete: function() {
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

        function formatNumber(number) {
            number = number.toString();
            var pattern = /(-?\d+)(\d{3})/;
            while (pattern.test(number))
                number = number.replace(pattern, "$1.$2");
            return number;
        }
    </script>
@endpush
