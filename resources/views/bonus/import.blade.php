@extends('layouts.template')
@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <style>
        .hidden{
            display: none;
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
            $('.btn-import').on('click',function(element) {
                $('#table_item tbody').empty();
                $('#table-data').removeClass('hidden');
                $('#button-simpan').removeClass('hidden');

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
                                var cell_range_letter = ['A', 'B', 'C']

                                var arr_data = [];

                                for (var i = 2; i <= cell_to_number; i++) {
                                    var arr_row = [];
                                    for (var j = 0; j < cell_range_letter.length; j++) {
                                        var index = `${cell_range_letter[j]}${i}`
                                        arr_row.push(excel[index].v)
                                    }
                                    arr_data.push(arr_row)
                                }

                                // Tampil data di table
                                console.log(arr_data);
                                // jquery untuk cek api
                                showTable(arr_data);


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
            function showTable(arr_data) {
                var total_data = arr_data.length;
                // Function to handle each row
                function handleRow(index) {
                    var row = arr_data[index];
                    if (row[0] != null) {
                        // get karyawan
                        $.ajax({
                            type: "GET",
                            url: `{{ route('api.get.karyawan') }}`,
                            data: {
                                nip: row[0]
                            },
                            success: function (res) {
                                var nama = res != 'null' ? res : '-';
                                if (res != 'null') {
                                    console.log('agaag');
                                    $('#table_item tbody tr:eq(' + index + ') span').removeClass('hidden');
                                } else {
                                    $('#table_item tbody tr:eq(' + index + ') span').addClass('hidden')

                                }

                                createTableRow(row, nama,index);
                            },
                            complete: function () {
                                // Continue processing the next row after the AJAX request is complete
                                if (index < total_data - 1) {
                                    handleRow(index + 1);
                                }
                            }
                        });
                    }
                }
                // Start processing rows
                handleRow(0);
            }

            function createTableRow(row, nama,index) {
                var new_body_tr = `
                    <tr>
                        <td>
                            <input type="text" name="nip[]" class="typeahead form-control nip-input" value="${row[0]}" readonly>
                            <small class="hidden" id="alert">Data NIP tidak ditemukan</small>
                        </td>
                        <td>
                            <input type="text" name="nama[]" class="form-control nama-input" value="${nama}" readonly>
                        </td>
                        <td>
                            <input type="text" name="kategori[]" class="form-control kategori-input" value="${row[1]}" readonly>
                        </td>
                        <td>
                            <input type="text" name="nominal[]" class="form-control nominal-input" value="${row[2]}" readonly>
                        </td>
                        <td>
                            <button type="button" class="btn btn-warning edit-button" data-index="${index}">Edit</button>
                        </td>
                    </tr>
                `;
                $('#table_item tbody').append(new_body_tr);
            }
            // Event handler for edit button
            $('#table_item tbody').on('click', '.edit-button', function () {
                var index = $(this).data('index');
                var rowInputs = $('#table_item tbody tr:eq(' + index + ') input');
                var namaInputs = $('#table_item tbody tr:eq(' + index + ') input.nama-input');
                $('#table_item tbody tr:eq(' + index + ') input.nip-input').autocomplete({
                    source: function( request, response ) {
                    $.ajax({
                        url: `{{ route('api.get.autocomplete') }}`,
                        type: 'GET',
                        dataType: "json",
                        data: {
                            search: request.term
                        },
                        success: function( data ) {
                         response( data );
                        }
                    });
                    },
                    select: function (event, ui) {
                        $('#table_item tbody tr:eq(' + index + ') input.nip-input').val(ui.item.value);
                        namaInputs.val(ui.item.nama)
                        return false;
                    }
                });
                rowInputs.prop('readonly', !rowInputs.prop('readonly'));
            });

        })
    </script>
@endpush
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Import Bonus</h5>
            <p class="card-title"><a href="">Dashboard</a> > <a href="{{ route('bonus.index') }}">Bonus</a> >Import</p>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('bonus.store') }}" enctype="multipart/form-data" method="POST" class="form-group mt-4">
            @csrf
        <div class="row">
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
                    <div class="form-row">
                        <div class="col">
                            <label for="">Kategori</label>
                            <select name="kategori_bonus" id="kategori" class="form-control">
                                <option value="jaspro">Jaspro DanKes</option>
                                <option value="thr">Import THR </option>
                                <option value="penghasilan-lainnya">Import Penghasilan Lainnya</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="">Data Excel</label>
                            <div class="custom-file col-md-12">
                                <input type="file" name="upload_csv" class="custom-file-input" id="upload_csv"  accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                <label class="custom-file-label overflow-hidden" for="validatedCustomFile">Choose file...</label>
                            </div>
                        </div>
                        <div class="col align-items-center mt-2">
                            <button type="button" class="btn btn-info btn-import">Import</button>
                            <a href="{{ asset('template_penghasilan_lainnya.xlsx') }}" download>Download Template Excel</a>
                        </div>
                    </div>
            </div>
            <div class="col-md-12 my-5 hidden" id="table-data">
                <div class="table-responsive overflow-hidden content-center">
                    <table class="table whitespace-nowrap table-bondered" id="table_item" style="width: 100%">
                      <thead class="text-primary">
                        <th>
                            NIP
                        </th>
                        <th>
                            Nama
                        </th>
                        <th>
                            Kategori
                        </th>
                        <th>
                            Nominal
                        </th>
                        <th>
                            Aksi
                        </th>
                      </thead>
                      <tbody>

                      </tbody>

                    </table>
                </div>
            </div>
            <div class="col-md-12 " ">
                <div class="d-flex justify-content-end hidden">
                    <button type="submit" class="btn btn-info hidden" id="button-simpan">Simpan</button>
                </div>
            </div>

        </div>
    </form>
    </div>
@endsection
