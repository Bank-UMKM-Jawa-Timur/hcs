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
        <a class="btn btn-primary" href="{{ route('template-excel-potongan') }}" download>Template Excel</a>
        <div class="row">
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="">Bulan</label>
                    <select name="bulan" id="bulan" class="form-control">
                        <option value="">Pilih Bulan</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option {{ Request()->bulan == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}
                                value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">
                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                        @endfor
                    </select>
                    <p class="text-danger d-none mt-2" id="error-bulan"></p>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="">Tahun</label>
                    <select name="tahun" id="tahun" class="form-control">
                        <option value="">Pilih Tahun</option>
                        @php
                            $tahunSaatIni = date('Y');
                            $awal = $tahunSaatIni - 5;
                            $akhir = $tahunSaatIni + 5;
                        @endphp

                        @for ($tahun = $awal; $tahun <= $akhir; $tahun++)
                            <option {{ Request()->tahun == $tahun ? 'selected' : '' }} value="{{ $tahun }}">
                                {{ $tahun }}</option>
                        @endfor
                    </select>
                    <p class="text-danger d-none mt-2" id="error-tahun"></p>
                </div>
            </div>
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
            <div class="col-lg-2 mt-2">
                <button type="button" class="btn btn-primary filter-potongan-import" id="filter-potongan-import">Tampilkan</button>
            </div>
        </div>
        <div class="teks mt-4">
            <div class="col-md-4 align-self-center mt-4" id="grand"></div>
        </div>

        <form action="{{ route('import-potongan-post') }}" method="POST">
            @csrf
            <input type="hidden" name="nip" class="form-control nip-input" value="" readonly>
            <input type="hidden" name="bulan" class="form-control bulan-input" value="" readonly>
            <input type="hidden" name="tahun" class="form-control tahun-input" value="" readonly>
            <input type="hidden" name="kredit_koperasi" class="form-control kredit-koperasi-input" value="" readonly>
            <input type="hidden" name="iuran_koperasi" class="form-control iuran-koperasi-input" value="" readonly>
            <input type="hidden" name="kredit_pegawai" class="form-control kredit-pegawai-input" value="" readonly>
            <input type="hidden" name="iuran_ik" class="form-control iuran_ik-input" value="" readonly>
            <div class="d-flex justify-content-start">
                <button type="submit" class="btn btn-primary d-none" id="btn-simpan">Simpan</button>
            </div>
            <div class="col-md-10" id="loading-message"></div>
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
            var bulan = $('#bulan').val();
            var tahun = $('#tahun').val();
            var fileCsv = $('#file-csv').val();

            if (bulan && tahun && fileCsv) {
                importExcel();
                $('#table_item tbody').empty();
                $('#error-bulan').addClass('d-none')
                $('#error-tahun').addClass('d-none')
                $('#error-file').addClass('d-none')
            } else {
                if (bulan == "" && tahun && fileCsv) {
                    $('#error-bulan').removeClass('d-none').html('Bulan belum di pilih.')
                    $('#error-tahun').addClass('d-none')
                    $('#error-file').addClass('d-none')
                }
                else if (tahun == "" && bulan && fileCsv){
                    $('#error-tahun').removeClass('d-none').html('Tahun belum di pilih.')
                    $('#error-bulan').addClass('d-none')
                    $('#error-file').addClass('d-none')
                }
                else if (tahun && bulan && !fileCsv){
                    $('#error-tahun').addClass('d-none')
                    $('#error-bulan').addClass('d-none')
                    $('#error-file').removeClass('d-none')
                }
                else {
                    $('#error-bulan').removeClass('d-none').html('Bulan belum di pilih.')
                    $('#error-tahun').removeClass('d-none').html('Tahun belum di pilih.')
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

        function showTable(sheet_data) {
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

            $.ajax({
                type: "GET",
                url: `{{ url('/get-karyawan-by-nip') }}`,
                data: {
                    nip: JSON.stringify(dataNip),
                },
                beforeSend: function() {
                    $('#loading-message').html(`
                        <div class="d-flex align-items-center">
                            <strong>Loading...</strong>
                            <div class="spinner-border ml-auto" role="status" aria-hidden="true"></div>
                        </div>
                    `);
                },
                success: function(res) {
                    var bulan = $('#bulan').val();
                    var tahun = $('#tahun').val();
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
                        grandKreditKoprasi += parseInt(dataKreditKoprasi[key])
                        grandIuranKoprasi += parseInt(dataIuranKoprasi[key])
                        grandKreditPegawai += parseInt(dataKreditPegawai[key])
                        grandIuranIk += parseInt(dataIuranIk[key])
                        new_body_tr += `
                                <tr>
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
                    if (hasError == true) {
                        var message = ``;
                        if (hasNip == true) {
                            message += `NIP : ${checkNip} tidak di temukan.`
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Data tidak valid!',
                            text: message
                        });
                        $('#btn-simpan').addClass('d-none');
                        $('#hasil-import').addClass('d-none');
                    } else {
                        Swal.fire({
                            icon: 'success',
                            text: 'Data valid.'
                        });
                        $('.nip-input').val(nipDataRequest);
                        $('.kredit-koperasi-input').val(dataKreditKoprasi);
                        $('.iuran-koperasi-input').val(dataIuranKoprasi);
                        $('.kredit-pegawai-input').val(dataKreditPegawai);
                        $('.iuran_ik-input').val(dataIuranIk);
                        $('.bulan-input').val(bulan);
                        $('.tahun-input').val(tahun);

                        $('#table_item tbody').append(new_body_tr);
                        $('#btn-simpan').removeClass('d-none');
                        $('#hasil-import').removeClass('d-none');
                        $('#grand').html(`
                                <p id="total-data" class="font-weight-bold">Total Data : ${dataNip.length}</p>
                                <p id="grand-total" class="font-weight-bold">Grand Kredit Koprasi : ${
                                    new Intl.NumberFormat("id-ID", {
                                    style: "currency",
                                    currency: "IDR"
                                    }).format(grandKreditKoprasi)
                                }</p>
                                <p id="grand-total" class="font-weight-bold">Grand Iuran Koprasi : ${
                                    new Intl.NumberFormat("id-ID", {
                                    style: "currency",
                                    currency: "IDR"
                                    }).format(grandIuranKoprasi)
                                }</p>
                                <p id="grand-total" class="font-weight-bold">Grand Kredit Pegawai : ${
                                    new Intl.NumberFormat("id-ID", {
                                    style: "currency",
                                    currency: "IDR"
                                    }).format(grandKreditPegawai)
                                }</p>
                                <p id="grand-total" class="font-weight-bold">Grand Iuran IK : ${
                                    new Intl.NumberFormat("id-ID", {
                                    style: "currency",
                                    currency: "IDR"
                                    }).format(grandIuranIk)
                                }</p>
                            `)
                    }
                },
                complete: function() {
                    $('#loading-message').empty();
                }
            })
            // Start processing rows
            // handleRow(0);
        }

        function formatNumber(number) {
            number = number.toString();
            var pattern = /(-?\d+)(\d{3})/;
            while (pattern.test(number))
                number = number.replace(pattern, "$1.$2");
            return number;
        }
    </script>
@endpush
