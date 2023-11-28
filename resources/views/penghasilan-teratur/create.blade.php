@extends('layouts.template')
@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
@endpush
@section('content')

    <div class="card-header">
        <div class="card-title">
            <h5 class="card-title">Import Penghasilan</h5>
            <p class="card-title"><a href="">Dashboard </a> > Penghasilan Teratur</p>
        </div>
    </div>

    <div class="card-body">
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
                    <p class="text-danger d-none" id="error-penghasilan"></p>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="form-group">
                    <label for="">File</label>
                    <div class="custom-file">
                        <input type="file" name="upload_excel" class="custom-file-input" id="file-penghasilan" accept=".xlsx, .xls" onchange="updateFileName()">
                        <label class="custom-file-label overflow-hidden" for="file-penghasilan" id="file-label">Choose Excel file...</label>
                    </div>
                    <p class="text-danger d-none" id="error-file">File belum di pilih.</p>
                </div>
            </div>
            <div class="col-lg-2 mt-2">
                <button class="btn btn-primary" id="filter">Tampilkan</button>
            </div>
        </div>

        <form action="{{route('penghasilan.import-penghasilan-teratur.store')}}" method="POST">
        @csrf
            <div class="row d-none" id="hasil-filter">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            {{-- <p id="span_info_save"></p>
                            <p id="span_total_data"></p> --}}
                        </div>
                        <div class="card-body">
                            <table class="table" id="table_item">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nip</th>
                                        <th>Nama Karyawan</th>
                                        <th>Nominal</th>
                                        <th>Aksi</th>
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
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary d-none" id="btn-simpan">Simpan</button>
                            </div>
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

        $('#penghasilan').on('change', function(){
            var value = $("#penghasilan").val()
            var hari_ini = new Date();
            // var tanggal = hari_ini.getDate();
            var tanggal = 5;
            var message = '';
            var nmbr = 0;

            // 11 = tranport, 12 = pulsa, 13 = vitamin, 14 = uang makan

            if (value == 11 || value == 14) {
                if (tanggal == 25) {
                    var message = "success"
                } else {
                    if (value == 12) {
                        var message = 'Uang pulsa hanya bisa di pilih tanggal 1 sampai 10'
                        nmbr++
                    } else if (value == 13) {
                        var message = 'Uang vitamin hanya bisa di pilih tanggal 1 sampai 5'
                        nmbr++
                    }
                    // alertWarning(message)
                    $('#error-penghasilan').removeClass('d-none').html(message)
                    $("#penghasilan").val("")
                }
            } else if (value == 12) {
                if (tanggal >= 1 && tanggal <= 10){
                    var message = "success"
                } else {
                    if (value == 11) {
                        var message = 'Uang transport hanya bisa di pilih saat tanggal 25'
                        nmbr++
                    } else if (value == 14) {
                        var message = 'Uang makan hanya bisa di pilih saat tanggal 25'
                        nmbr++
                    }

                    if (tanggal > 5 && value == 13) {
                        var message = 'Uang vitamin hanya bisa di pilih tanggal 1 sampai 5'
                        nmbr++
                    }

                    // alertWarning(message)
                    $('#error-penghasilan').removeClass('d-none').html(message)
                    $("#penghasilan").val("")
                }
            } else if(value == 13){
                if (tanggal >= 1 && tanggal <= 5 ) {
                    if (value == 12 || value == 13) {
                        var message = "success"
                    } else {
                        if (value == 11) {
                            var message = 'Uang transport hanya bisa di pilih saat tanggal 25'
                            nmbr++
                        } else if (value == 14) {
                            var message = 'Uang makan hanya bisa di pilih saat tanggal 25'
                            nmbr++
                        }
                        // alertWarning(message)
                        $('#error-penghasilan').removeClass('d-none').html(message)
                        $("#penghasilan").val("")
                    }
                }
            }

            if (nmbr > 0){
                $('#error-penghasilan').removeClass('d-none').html(message)
                $("#penghasilan").val("")
            }
        })

        $('#filter').on('click', function(e) {
            console.log('askdaskdjasldjldj');
            var penghasilan = $('#penghasilan').val();
            var filePenghasilan = $('#file-penghasilan').val();

            if (penghasilan && filePenghasilan) {
                importExcel();
            } else {
                if (penghasilan == "" && filePenghasilan) {
                    $('#error-penghasilan').removeClass('d-none').html('Kategori belum di pilih.')
                }
                else if (!filePenghasilan && penghasilan){
                    $('#error-file').removeClass('d-none')
                }
                else {
                    $('#error-penghasilan').removeClass('d-none').html('Kategori belum di pilih.')
                    $('#error-file').removeClass('d-none')
                }
            }
        });

        $('#btn-simpan').on('click', function(){

        })

        $('.btn-plus').on('click', function(e) {
            var number = $("#t_body").children().length + 1
            var new_tr = `
            <tr>
                <td><span id="number[]">${number}</span></td>
                <td>
                    <input type="text" name="nip[]" id="nip[]" class="form-control">
                </td>
                <td>
                    <input type="text" name="nama[]" id="nama[]" class="form-control">
                </td>
                <td>
                    <input type="text" name="nominal[]" id="nominal[]" class="form-control only-number">
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-warning btn-edit">
                        edit
                    </button>
                    <button type="button" class="btn btn-sm btn-icon btn-round btn-danger btn-minus">
                        -
                    </button>
                </td>
            </tr>
            `;
            $('#t_body').append(new_tr);
        })

        $("#table_item").on('click', '.btn-minus', function () {
            $(this).closest('tr').remove();
        })

        $("#table_item").on('click', '.btn-edit', function () {
            $(this).closest('tr').removeAttr("readonly");
        })

        function formatNumber(number) {
            number = number.toString();
            var pattern = /(-?\d+)(\d{3})/;
            while (pattern.test(number))
                number = number.replace(pattern, "$1.$2");
            return number;
        }

        function showToTable(data){
            var penghasilan = $('#penghasilan').val();
            var total_data = data.length;
            // $('#span_info_save').html('Informasi! Geser layar ke bawah untuk menyimpan data.')
            // $('#span_total_data').html(`Total data : ${total_data}`)
            $('#btn-simpan').removeClass('d-none')
            $('#hasil-filter').removeClass('d-none')

            for (let i = 0; i < data.length; i++) {
                var row = data[i]
                var nominal = formatNumber(row[3].v);
                var new_tr = `
                    <tr>
                        <td><span id="number[]">${(i+1)}</span></td>
                        <td>
                            <input type="hidden" name="number[]" id="number[]" class="form-control" value="${(i+1)}">
                            <input type="hidden" name="penghasilan[]" id="penghasilan[]" class="form-control" value="${penghasilan}">
                            <input type="text" name="nip[]" id="nip[]" class="form-control" readonly value="${row[1].v}">
                        </td>
                        <td>
                            <input type="text" name="nama[]" id="nama[]" class="form-control" readonly value="${row[1].v}">
                        </td>
                        <td>
                            <input type="text" name="nominal[]" id="nominal[]" class="form-control only-number" readonly value="${nominal}">
                        </td>
                        <td class="m-2">
                            <button type="button" class="btn btn-sm btn-warning btn-edit">
                                edit
                            </button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-icon btn-round btn-danger btn-minus">
                                -
                            </button>
                        </td>
                    </tr>
                `;
                $('#t_body').append(new_tr);
            }
        }

        function importExcel() {
            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;
            /*Checks whether the file is a valid excel file*/
            if (regex.test($("#file-penghasilan").val().toLowerCase())) {
                var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/
                if ($("#file-penghasilan").val().toLowerCase().indexOf(".xlsx") > 0) {
                    xlsxflag = true;
                }
                /*Checks whether the browser supports HTML5*/
                if (typeof (FileReader) != "undefined") {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var data = e.target.result;
                        /*Converts the excel data in to object*/
                        if (xlsxflag) {
                            var workbook = XLSX.read(data, { type: 'binary' });
                        }
                        /*Gets all the sheetnames of excel in to a variable*/
                        var sheet_name_list = workbook.SheetNames;
                        var cnt = 0; /*This is used for restricting the script to consider only first sheet of excel*/
                        sheet_name_list.forEach(function (y) { /*Iterate through all sheets*/
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
                            var cell_range_letter = ['A', 'B', 'C', 'D', 'E']

                            var arr_data = [];

                            for (var i = 1; i <= cell_to_number; i++) {
                                var arr_row = [];
                                for (var j = 0; j < cell_range_letter.length; j++) {
                                    var index = `${cell_range_letter[j]}${i}`
                                    arr_row.push(excel[index])
                                }
                                arr_data.push(arr_row)
                            }

                            // Show excel data to html table
                            showToTable(arr_data)
                        });
                    }
                    if (xlsxflag) {/*If excel file is .xlsx extension than creates a Array Buffer from excel*/
                        reader.readAsArrayBuffer($("#file-penghasilan")[0].files[0]);
                    }
                    else {
                        reader.readAsBinaryString($("#file-penghasilan")[0].files[0]);
                    }
                }
                else {
                    alert("Maaf! Browser Anda tidak mendukung HTML5!");
                }
            }
            else {
                alert("Unggah file Excel yang valid!");
            }
        }

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
    </script>
@endpush
