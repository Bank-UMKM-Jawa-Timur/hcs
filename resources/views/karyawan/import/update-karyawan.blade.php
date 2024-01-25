@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title font-weight-bold">Import Data Karyawan</h5>
            <p class="card-title"><a href="">Manajemen Karyawan</a> > <a href="/karyawan">Karyawan</a> > Import</p>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
                <form action="" enctype="multipart/form-data" method="POST" class="form-group">
                    @csrf
                    <div class="row">
                        <div class="container ml-3">
                            <label for="">Data Excel</label>
                            <div class="custom-file col-md-12">
                                <input type="file" name="upload" class="custom-file-input" id="validatedCustomFile">
                                <label class="custom-file-label overflow-hidden" for="validatedCustomFile">Choose file...</label>
                            </div>  
                        </div>
                        <div class="container ml-3">
                            <button type="button" class="is-btn is-primary" id="filter">Import</button>
                        </div>
                    </div>
                </form>
            </div>

        <form action="{{ route('import-data-karyawan') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <input type="hidden" name="nip" value="" id="nipReq">
                <input type="hidden" name="norek" value="" id="norekReq">
                <input type="hidden" name="npwp" value="" id="npwpReq">
                <input type="hidden" name="ptkp" value="" id="ptkpReq">
                <input type="hidden" name="pendidikan" value="" id="pendidikanReq">
                <input type="hidden" name="jurusan" value="" id="jurusanReq">
                <input type="hidden" name="alamat_ktp" value="" id="alamat_ktpReq">
                <input type="hidden" name="alamat_dom" value="" id="alamat_domReq">
                <button>Update</button>
                <div class="body-pages pt-0 row hidden col-md-12" id="hasil-filter">
                    <div class="table-wrappingt table-responsive" >
                        <table class="table table-bordered" id="table_item" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIP</th>
                                    <th>Nama Karyawan</th>
                                    <th>No Rekening</th>
                                    <th>NPWP</th>
                                    <th>PTKP</th>
                                    <th>Pendidikan</th>
                                    <th>Jurusan</th>
                                    <th>Alamat KTP</th>
                                    <th>Alamat Dom</th>
                                </tr>
                            </thead>
                            <tbody id="t_body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>

            <div class="col-md-12 justify-content-center">
                
                
                @if ($errors->any())
                    <div class="table-responsive justify-content-center container">
                        <table class="table">
                            <tbody>
                                @foreach ($errors->all() as $item)
                                <tr class="justify-content-center">
                                    <td>
                                        {{ $item }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@section('custom_script')
    <script>
        document.querySelector('.custom-file-input').addEventListener('change', function (e) {
            var name = document.getElementById("validatedCustomFile").files[0].name;
            var nextSibling = e.target.nextElementSibling
            nextSibling.innerText = name
        });
    </script>
@endsection

@push('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            var grandTotalNominal = 0;
            var penghasilan = '';
            $('#filter').on('click', function(e) {
                console.log('test1234124');
                $('#card-alert').removeClass('hidden');
                var file = $('#validatedCustomFile').val();
                console.log("penghasilan");

                if (file) {
                    importExcel();
                    $('#table_item tbody').empty();
                    $('#error-penghasilan').addClass('hidden')
                    $('#error-file').addClass('hidden')
                    $('#error-bulan').addClass('hidden')
                } else {
                    if (file == ""){
                        $('#error-file').removeClass('hidden')
                        $('#error-penghasilan').addClass('hidden')
                        $('#error-bulan').addClass('hidden')
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
                    var test = $("#validatedCustomFile").val();
                    var sheet_data = [];
                    if (regex.test($("#validatedCustomFile").val().toLowerCase())) {
                        var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/
                        if ($('#validatedCustomFile').val().toLowerCase().indexOf(".xlsx") > 0) {
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
                                reader.readAsArrayBuffer($("#validatedCustomFile")[0].files[0]);
                            }
                            else {
                                reader.readAsBinaryString($("#validatedCustomFile")[0].files[0]);
                            }
                        } else {
                            console.log('tidak support');
                        }
                    } else {
                        alert("Unggah file Excel yang valid!");
                        $('#table_item tbody').empty();
                        $('#hasil-filter').addClass('hidden');
                        $('#cover-btn-simpan').addClass('hidden');
                    }
            }

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

            function showTable(sheet_data) {
                var no = 0;
                var grandTotalNominal = 0;
                var id_tunjangan = $('#penghasilan-kat').val()
                var bulanReq = $('#bulan').val()
                var date = new Date();
                var hari_ini = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
                var tahun_ini = date.getFullYear();

                var dataNip = [];
                var dataNominal = [];
                var no_rek = [];
                var nipDataRequest = [];

                var checkNip = [];
                var rowEmpty = [];
                var checkFinal = [];
                var NoCheckFinal = false;
                var noEmpty = 1;
                var checkNipTunjangan = [];
                var namaTunjangan = [];

                var hasError = false;
                var hasNip = false;
                var hasTunjangan = false;
                var hasSuccess = false;

                var nipReq = [];
                var norekReq = [];
                var npwpReq = [];
                var ptkpReq = [];
                var pendidikanReq = [];
                var jurusanReq = [];
                var alamat_ktpReq = [];
                var alamat_domReq = [];

                $.each(sheet_data,function(key, value) {
                    if (sheet_data[key].hasOwnProperty('nip')) {
                        // console.log(value['Nominal'].replace(/[ ,.Rprp]/g, ""));
                        dataNip.push({ 
                            row: key + 1,
                            nip: value['nip'],
                            norek: value['norek'],
                            npwp: value['npwp'],
                            ptkp: value['ptkp'],
                            pendidikan: value['pendidikan'],
                            jurusan: value['jurusan'],
                            alamat_ktp: value['alamat_ktp'],
                            alamat_dom: value['alamat_dom'],
                        });
                    }
                })
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: `{{ route('get-data-import-karyawan') }}`,
                    data: {
                        import: JSON.stringify(dataNip),
                    },
                    beforeSend: function () {
                        $('#loading-message').html(`
                            <div class="bg-orange-100 border-t-4 border-orange-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert">
                                <div class="flex justify-between">
                                    <p class="font-bold">Loading...</p>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" id="loading-alert">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                </div>
                            </div>
                        `);
                    },
                    success: function(res){
                        console.log(res);
                        var new_body_tr = ``;
                        var new_body_tr_success = ``;
                        var message = ``;
                        var tittleMessage = ``;
                        var headerMessage = `harap cek kembali pada file excel yang di upload.`;
                        $.each(res,function(key,value) {
                            if (value.status == 1) {
                                checkFinal.push(value.nip);
                                // hasError = true
                            } else {
                                if (value.cek_nip == false) {
                                    checkNip.push(value.nip + " baris " + noEmpty++);
                                    hasError = true;
                                    hasNip = true;
                                    hasTunjangan = false;
                                }
                                if (value.cek_nip == false) {
                                    hasError = true;
                                    hasNip = true;
                                    hasTunjangan = false;
                                    no++;
                                    new_body_tr += `
                                        <tr class="table-danger">
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
                                                ${value.norek != null ? value.norek : '-'}
                                            </td>
                                            <td>
                                                ${value.npwp}
                                            </td>
                                            <td>
                                                ${value.ptkp}
                                            </td>
                                            <td>
                                                ${value.pendidikan}
                                            </td>
                                            <td>
                                                ${value.jurusan}
                                            </td>
                                            <td>
                                                ${value.alamat_ktp}
                                            </td>
                                            <td>
                                                ${value.alamat_dom}
                                            </td>
                                        </tr>
                                    `;
                                }
                            }
                            // if (value.cek_nip) {
                            //     if (nipDuplicate) {
                            //         checkNip.push("Duplikasi " + value.nip + " baris " + noEmpty++);
                            //         hasError = true;
                            //         hasNip = true;
                            //         nipDataRequest.push(value.nip);
                            //         no++;
                            //         new_body_tr += `
                            //             <tr class="table-danger">
                            //                 <td>
                            //                     ${no}
                            //                 </td>
                            //                 <td>
                            //                     ${value.nip}
                            //                 </td>
                            //                 <td>
                            //                     ${value.nama_karyawan}
                            //                 </td>
                            //                 <td>
                            //                     ${value.norek != null ? value.norek : '-'}
                            //                 </td>
                            //                 <td>
                            //                     ${value.npwp}
                            //                 </td>
                            //                 <td>
                            //                     ${value.ptkp}
                            //                 </td>
                            //                 <td>
                            //                     ${value.pendidikan}
                            //                 </td>
                            //                 <td>
                            //                     ${value.jurusan}
                            //                 </td>
                            //                 <td>
                            //                     ${value.alamat_ktp}
                            //                 </td>
                            //                 <td>
                            //                     ${value.alamat_dom}
                            //                 </td>
                            //             </tr>
                            //         `;
                            //     }
                            // }
                        })

                        $.each(res,function(key,value) {
                            if (value.cek_nip == false) {
                                // checkNip.push(value.nip);
                                // hasError = true;
                                // hasNip = true;
                                // hasTunjangan = false;
                            } else if (value.cek_tunjangan == true) {
                                // checkNipTunjangan.push(value.nip);
                                // namaTunjangan.push(value.tunjangan.nama_tunjangan);
                                // hasError = true;
                                // hasTunjangan = true;
                                // hasNip = false;
                            }
                            if (value.cek_nip == false) {
                                no++;
                                new_body_tr += `
                                        <tr class="table-danger">
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
                                                ${value.norek != null ? value.norek : '-'}
                                            </td>
                                            <td>
                                                ${value.npwp}
                                            </td>
                                            <td>
                                                ${value.ptkp}
                                            </td>
                                            <td>
                                                ${value.pendidikan}
                                            </td>
                                            <td>
                                                ${value.jurusan}
                                            </td>
                                            <td>
                                                ${value.alamat_ktp}
                                            </td>
                                            <td>
                                                ${value.alamat_dom}
                                            </td>
                                        </tr>
                                    `;
                            }else{
                                nipReq.push(value.nip)
                                norekReq.push(value.norek)
                                npwpReq.push(value.npwp)
                                ptkpReq.push(value.ptkp)
                                pendidikanReq.push(value.pendidikan)
                                jurusanReq.push(value.jurusan)
                                alamat_ktpReq.push(value.alamat_ktp)
                                alamat_domReq.push(value.alamat_dom)
                                no++;
                                new_body_tr += `
                                    <tr class="">
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
                                                ${value.norek != null ? value.norek : '-'}
                                            </td>
                                            <td>
                                                ${value.npwp}
                                            </td>
                                            <td>
                                                ${value.ptkp}
                                            </td>
                                            <td>
                                                ${value.pendidikan}
                                            </td>
                                            <td>
                                                ${value.jurusan}
                                            </td>
                                            <td>
                                                ${value.alamat_ktp}
                                            </td>
                                            <td>
                                                ${value.alamat_dom}
                                            </td>
                                    </tr>
                                `;
                            }
                        })
                        if (hasError == true) {
                            if (hasNip == true) {
                                message += `${checkNip}`
                                tittleMessage += `Tidak ditemukan`
                            }
                            $('#alert-massage').html(`
                                <div id="alert-border-2" class="flex items-center p-4 mb-4 text-red-800 border-t-4 border-red-300 bg-red-50">
                                    <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                    </svg>
                                    <div class="ms-3 text-sm font-medium">
                                    <strong>Data tidak valid.</strong> Nip : ${message} <br>
                                    ${tittleMessage}, <strong>${headerMessage}</strong>
                                    </div>
                                    <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8"  data-dismiss-target="#alert-border-2" aria-label="Close">
                                    <span class="sr-only">Dismiss</span>
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                    </button>
                                </div>
                            `)
                            $('#cover-btn-simpan').addClass('hidden');
                            $('#hasil-filter').removeClass('hidden');
                            $('#table_item tbody').append(new_body_tr);
                        }else{
                            $('#alert-massage').html(`
                                <div id="alert-border-3" class="flex items-center p-4 mb-4 text-green-800 border-t-4 border-green-300 bg-green-50" role="alert">
                                    <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                    </svg>
                                    <div class="ms-3 text-sm font-medium">
                                        Data Valid.
                                    </div>
                                    <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8"  data-dismiss-target="#alert-border-3" aria-label="Close">
                                    <span class="sr-only">Dismiss</span>
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                    </button>
                                </div>
                            `)
                            $('#nipReq').val(nipReq);
                            $('#norekReq').val(norekReq);
                            $('#npwpReq').val(npwpReq);
                            $('#ptkpReq').val(ptkpReq);
                            $('#pendidikanReq').val(pendidikanReq);
                            $('#jurusanReq').val(jurusanReq);
                            $('#alamat_ktpReq').val(alamat_ktpReq);
                            $('#alamat_domReq').val(alamat_domReq);

                            $('#table_item tbody').append(new_body_tr);
                            $('#cover-btn-simpan').removeClass('hidden');
                            $('#btn-simpan').removeClass('hidden');
                            $('#hasil-filter').removeClass('hidden');
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
                // setTimeout(() => {
                //     $('.preloader').addClass('hidden')
                // }, 3000);

                $("#loadingModal").modal({
                    keyboard: false
                });
                $("#loadingModal").modal("show");
            })

            function getMonth(bulan) {
                bulans = parseInt(bulan);
                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

                return months[bulans - 1];
            }

            function alertDanger(message) {
                // Display an alert with danger style
                $('#alert-massage').removeClass('hidden');

                $('#alert-massage').html(`
                    <div id="alert-border-2" class="flex items-center p-4 mb-4 text-red-800 border-t-4 border-red-300 bg-red-50">
                        <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <div class="ms-3 text-sm font-medium">
                            ${message}
                        </div>
                        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8"  data-dismiss-target="#alert-border-2" aria-label="Close">
                        <span class="sr-only">Dismiss</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        </button>
                    </div>
                `);
            }
            function alertSuccess(message) {
                // Display an alert with danger style
                $('#alert-massage').removeClass('hidden');

                $('#alert-massage').html(`
                    <div id="alert-border-3" class="flex items-center p-4 mb-4 text-green-800 border-t-4 border-green-300 bg-green-50" role="alert">
                        <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <div class="ms-3 text-sm font-medium">
                            ${message}
                        </div>
                        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8"  data-dismiss-target="#alert-border-3" aria-label="Close">
                        <span class="sr-only">Dismiss</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        </button>
                    </div>
                `);
            }

        });
    </script>
@endpush