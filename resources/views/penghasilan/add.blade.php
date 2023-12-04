@extends('layouts.template')
@include('vendor.select2')
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
    <script>
        $(document).ready(function() {
            var kategori;
            var totalDataInput = 0;
            var grandTotalNominal = 0;
            $("#kategori").select2();

            $("#col-kategori-spd").hide()

            function updateFileName() {
            }
            
            $("input[type=file]").on('change', function(){
                var input = document.getElementById('upload_csv');
                var label = document.getElementById('file-label');
                var fileName = input.files[0].name;
                label.innerHTML = fileName;
            })

            $("#kategori").on('change', function(){
                var value = $(this).val();
                kategori = value.toLowerCase()
                if(value == 'pengganti biaya kesehatan'){
                    $('#btnDownloadTemplate').attr('href', "{{ asset('template_penghasilan_tidak_teratur-pengganti_biaya_kesehatan.xlsx') }}");
                } else if(value == 'uang duka'){
                    $('#btnDownloadTemplate').attr('href', "{{ asset('template_penghasilan_tidak_teratur-uang_duka.xlsx') }}");
                } else{
                    $('#btnDownloadTemplate').attr('href', "{{ asset('template_penghasilan_tidak_teratur.xlsx') }}");
                }
            })

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

            $('.btn-import').on('click',function(element) {
                var kategori = $("#kategori").val();
                if(kategori == ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan!',
                        text: `Harap pilih kategori terlebih dahulu.`
                    });
                    $('#table_item tbody').empty();
                    $('#table-data').addClass('hidden');
                    $('#button-simpan').addClass('hidden');
                } else{
                    var keterangan = 'Keterangan';
                    if(kategori == 'uang duka' || kategori == 'pengganti biaya kesehatan'){
                        keterangan = kategori == 'uang duka' ? 'Yang Meninggal' : 'Keterangan';
                        $("#keterangan").html(keterangan);
                        $("#keterangan").removeClass('hidden');
                    } else{
                        $("#keterangan").addClass('hidden')
                    }
                    
                    $('#table_item tbody').empty();
    
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
                                    var cell_range_letter = kategori == 'uang duka' || kategori == 'pengganti biaya kesehatan' ? ['A', 'B', 'C'] : ['A', 'B']
    
                                    var arr_data = [];
                                    var nip = [];
                                    console.log(excel);
    
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
    
                                    // Tampil data di table
                                    console.log(arr_data);
                                    // jquery untuk cek api
                                    showTable(arr_data, nip);
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
                }
            })
            function showTable(arr_data, nip) {
                $.ajax({
                    type: "GET",
                    url: `{{ route('api.get.karyawan') }}`,
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
                                var index = searchArray(arr_data, v.nip);
                                var row = arr_data[index];
                                createTableRow(row, v.nama_karyawan, i);
                                $('#table-data').removeClass('hidden');
                                $('#button-simpan').removeClass('hidden');
                            })
                        }
                    }
                })
                // Start processing rows
                // handleRow(0);
            }

            function createTableRow(row, nama,index) {
                console.log(row);
                var keterangan = kategori == 'uang duka' || kategori == 'pengganti biaya kesehatan' ? `
                        <td>
                            ${row[2]}
                            <input type="hidden" name="keterangan[]" class="form-control keterangan-input" value="${row[2]}" readonly>
                        </td>;` : ``
                var new_body_tr = `
                    <tr>
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
                        ${keterangan}
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
            <h5 class="card-title">Import Penghasilan Tidak Teratur</h5>
            <p class="card-title"><a href="">Dashboard</a> > <a href="">Penghasilan Tidak Teratur</a> >Import</p>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('pajak_penghasilan.store') }}" enctype="multipart/form-data" method="POST" class="form-group mt-4">
            @csrf
        <div class="row">
            <div class="col">
                <a href="{{ asset('template_penghasilan_tidak_teratur.xlsx') }}" class="btn btn-primary" id="btnDownloadTemplate" download>Download Template Excel</a>
            </div>
        </div>
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
                    <div class="row">
                        <div class="col">
                            <label for="">Kategori</label>
                            <select name="kategori" id="kategori" class="form-control">
                                <option value="">-- Pilih Kategori --</option>
                                @forelse ($data as $item)
                                    <option value="{{ strtolower($item->nama_tunjangan) }}">{{ $item->nama_tunjangan }}</option>
                                @empty
                                    
                                @endforelse
                            </select>
                        </div>
                        <div class="col">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal">
                        </div>
                        <div class="col">
                            <label for="">Data Excel</label>
                            <div class="custom-file">
                                <input type="file" name="upload_excel" class="custom-file-input" id="upload_csv" accept=".xlsx, .xls">
                                <label class="custom-file-label overflow-hidden" for="file-penghasilan" id="file-label">Choose Excel file...</label>
                            </div>
                        </div>
                        <div class="col align-items-center mt-2">
                            <button type="button" class="btn btn-info btn-import">Import</button>
                        </div>
                    </div>
            </div>

            <div class="col-md-12 mt-5">
                <div class="d-flex justify-content-start hidden">
                    <button type="submit" class="btn btn-info hidden" id="button-simpan">Simpan</button>
                </div>
            </div>
            <div class="col-md-12 hidden" id="table-data">
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
                            Nominal
                        </th>
                        <th class="hidden" id="keterangan">
                            Keterangan
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