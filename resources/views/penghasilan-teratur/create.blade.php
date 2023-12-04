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
            <p class="card-title"><a href="">Dashboard </a> > <a href="{{route('penghasilan.import-penghasilan-teratur.index')}}">Penghasilan Teratur</a> > Import Penghasilan Teratur</p>
        </div>
    </div>

    <div class="card-body">
        <a class="btn btn-primary" href="{{ route('penghasilan.template-excel') }}" download>Template Excel</a>
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

        <form action="{{route('penghasilan.import-penghasilan-teratur.store')}}" method="POST">
        @csrf
        <div class="d-flex justify-content-start">
            <button type="submit" class="btn btn-primary d-none" id="btn-simpan">Simpan</button>
        </div>
            <div class="row d-none" id="hasil-filter">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <p id="span_total_data"></p>
                            <p id="span_total_nominal"></p>
                        </div>
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

            var $row = null;

            $("#table_item").on('click', '.btn-edit', function () {
                var $row = $(this).closest('tr');
                var index = $(this).data('index');
                var namaInput = $row.find('input.nama');
                var rowInputs = $row.find('input').not('.nama');

                $row.removeClass('hidden');

                rowInputs.prop('readonly', false);

                namaInput.prop('readonly', true);

                $('#table_item tbody tr:eq(' + index + ') input.nip').autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: `{{ route('api.get.autocomplete') }}`,
                            type: 'GET',
                            dataType: "json",
                            data: {
                                search: request.term
                            },
                            success: function(data) {
                                // console.log(data);
                                response(data);
                            }
                        });
                    },
                    select: function(event, ui) {
                        // rowInputs.filter('.nip').val(ui.item.value);
                        // namaInput.val(ui.item.nama);
                        $('#table_item tbody tr:eq(' + index + ') input.nip').val(ui.item.value);
                        $('#table_item tbody tr:eq(' + index + ') input.nama').val(ui.item.nama);
                        // $('#table_item tbody tr:eq(' + index + ')').find('small').remove();
                        // namaInputs.val(ui.item.nama)
                        return false;
                    }
                });
            });

            $("#table_item").on('click', '.btn-minus', function () {
                $(this).closest('tr').remove();
            })

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

            function searchForArray(haystack, needle){
                var i, j, current;
                for(i = 0; i < haystack.length; ++i){
                    if(needle.length === haystack[i].length){
                    current = haystack[i];
                    for(j = 0; j < needle.length && needle[j] === current[j]; ++j);
                        if(j === needle.length){
                            haystack.splice(i, 1);
                        }
                    }
                }
            }

            function searchArray(arr_data, data){
                for(var i = 0; i < arr_data.length; i++){
                    for(var j = 0; j < arr_data[i].length; j++){
                        if(data == arr_data[i][j]){
                            return i;
                        }
                    }
                }
                return -1;
            }

            function searchArrayDiff(arr_data, res){
                var arrayExcel = [];
                var arrayRes = [];
                var diffArray = []
                for(var i = 0; i < arr_data.length; i++){
                    arrayExcel.push(arr_data[i][0]);
                }
                for(var i = 0; i < res.length; i++){
                    arrayRes.push(res[i].nip);
                }
                const difference = arrayExcel.reduce((result, element) => {
                    if (arrayRes.indexOf(element) === -1) {
                        result.push(element);
                    }
                    return result;
                }, []);

                Swal.fire({
                    icon: 'error',
                    title: 'Data tidak valid!',
                    text: `NIP ${difference.toString().replaceAll(',', ', ')} tidak dapat ditemukan.`
                });
            }

            function importExcel() {
                    var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;
                    var test = $("#file-penghasilan").val();
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
                                    var nip = [];

                                    for (var i = 2; i <= cell_to_number; i++) {
                                        var arr_row = [];
                                        for (var j = 0; j < cell_range_letter.length; j++) {
                                            var index = `${cell_range_letter[j]}${i}`
                                            if(typeof excel[`B${i}`] === "undefined" || excel[`B${i}`] == null){
                                            }else{
                                                arr_row.push(excel[index].v)
                                                arr_data.push(arr_row)
                                            }
                                        }
                                    }

                                    for(var i = 0; i < arr_data.length; i++){
                                        searchForArray(arr_data, arr_data[i]);
                                    }
                                    for(var i = 0; i < arr_data.length; i++){
                                        nip.push(arr_data[i][0]);
                                    }
                                    // jquery untuk cek api
                                    showTable(arr_data, nip);
                                })
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

            function showTable(arr_data, nip) {
                var no = 0;
                $.ajax({
                    type: "GET",
                    url: `{{ url('penghasilan/get-karyawan-by-entitas') }}`,
                    data: {
                        nip: nip
                    },
                    success: function(res){
                        if(res.length != nip.length){
                            searchArrayDiff(arr_data, res);
                        } else{
                            Swal.fire({
                                icon: 'success',
                                text: 'Data valid.'
                            });

                            $.each(res, function(i, v){
                                no++;

                                // console.log(v.length);
                                var index = searchArray(arr_data, v.nip);
                                var row = arr_data[index];
                                createTableRow(row, v.nama_karyawan, i, no);
                                $('#hasil-filter').removeClass('d-none');
                                $('#btn-simpan').removeClass('d-none');
                            })
                        }
                    }
                })
                // Start processing rows
                // handleRow(0);
            }

            function createTableRow(row, nama,index, no) {
                $('#span_total_data').html('Total data : <b>' + no + '</b>');
                var new_body_tr = `
                    <tr>
                        <td>
                            ${no}
                        </td>
                        <td>
                            ${row[0]}
                            <input type="hidden" name="nip[]" class="typeahead form-control nip-input" value="${row[0]}" readonly>
                        </td>
                        <td>
                            ${nama}
                            <input type="hidden" name="nama[]" class="form-control nama-input" value="${nama}" readonly>
                        </td>
                        <td>
                            ${formatRupiah(row[1].toString())}
                            <input type="hidden" name="nominal[]" class="form-control nominal-input" value="${row[1]}" readonly>
                        </td>
                    </tr>
                `;
                $('#t_body').append(new_body_tr);
            }

            function showToTable(data) {
                var penghasilan = $('#penghasilan').val();
                var total_data = data.length;
                var date = new Date();
                var hari_ini = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
                var id_tunjangan = $('#penghasilan').val()
                var grandNominal = 0

                $('#btn-simpan').removeClass('d-none');
                $('#hasil-filter').removeClass('d-none');
                $('#span_total_data').html('Total data : <b>' + total_data + '</b>');

                for (let i = 0; i < data.length; i++) {
                    (function (index) {
                        var row = data[index];
                        var errorElement = $(`tbody #error-karyawan-${index}`);
                        // console.log(errorElement);

                        $.ajax({
                            url: "{{ url('penghasilan/get-karyawan-by-entitas') }}",
                            type: "GET",
                            data: {
                                nip: row[0].v,
                                tanggal: hari_ini,
                                id_tunjangan: id_tunjangan,
                            },
                            accept: "Application/json",
                            success: function (response) {
                                // console.log(response);
                                var nominal = formatNumber(row[1].v);
                                grandNominal += parseFloat(row[1].v);
                                $('#span_total_nominal').html('Grand nominal : <b>' + formatNumber(grandNominal) + '</b>');
                                var employeeData = response.data;
                                var tunjanganExists = response.tunjangan;
                                var nama = employeeData && employeeData.nama_karyawan ? employeeData.nama_karyawan : 'Karyawan tidak ditemukan.';
                                var validation_msg = "";
                                if (tunjanganExists) {
                                    var nama_tunjangan = tunjanganExists.nama_tunjangan;
                                    var msg_tunjangan = `${nama} sudah ada di tunjangan ${nama_tunjangan}.`;
                                    validation_msg = `${msg_tunjangan} Silahkan edit atau hapus data ini.`;
                                    // errorElement.removeClass('d-none');
                                    errorElement.html(validation_msg);
                                    // console.log(`Validasi tunjangan berhasil untuk indeks ${index}`);
                                } else if (nama === 'Karyawan tidak ditemukan.') {
                                    validation_msg = 'Nip tidak di temukan. Silahkan edit atau hapus data ini.'
                                    // errorElement.removeClass('d-none');
                                    // console.log(`Validasi nama berhasi l untuk indeks ${index}`);
                                    errorElement.html(validation_msg);
                                } else {
                                    validation_msg = ""
                                    errorElement.html(validation_msg);
                                }

                                var new_tr = `
                                    <tr>
                                        <td><span id="number[]">${(index + 1)}</span></td>
                                        <td>
                                            <input type="hidden" name="number[]" class="form-control" value="${index + 1}">
                                            <input type="hidden" name="penghasilan[]" class="form-control" value="${penghasilan}">
                                            <input type="hidden" name="nip[]" class="form-control nip" readonly value="${row[0].v}">
                                            ${row[0].v}
                                            <small class="text-danger" data-error="${index}" id="error-karyawan-${index}">${validation_msg}</small>
                                        </td>
                                        <td>
                                            <input type="hidden" name="nama[]" class="form-control nama" readonly value="${nama}">
                                            ${nama}
                                        </td>
                                        <td>
                                            <input type="hidden" name="nominal[]" class="form-control only-number nominal" readonly value="${nominal}">
                                            ${nominal}
                                        </td>
                                    </tr>
                                `;

                                $('#table_item tbody').append(new_tr);
                            },
                            error: function (response) {
                                console.log(response);
                            }
                        });
                    })(i);
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

        });
    </script>
@endpush
