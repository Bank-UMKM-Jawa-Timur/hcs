@push('extraScript')
    <script src="{{asset('vendor/printpage/printpage.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('style/assets/js/table2excel.js') }}"></script>
    <script src="{{asset('vendor/datatables/dataTables.button.min.js')}}"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.1/js/dataTables.fixedColumns.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script>
        $('#page_length_proses').on('change', function() {
            $('#form-filter').submit()
        })
        $('#page_length_final').on('change', function() {
            $('#form-filter').submit()
        })

        $('#form-filter').on('submit', function() {
            $('.loader-wrapper').css('display: none;')
            $('.loader-wrapper').addClass('d-block')
            $(".loader-wrapper").fadeOut("slow");
        })

        $('.nav-tabs button').on('click', function() {
            $('#tab').val($(this).data('tab'))
            refreshPagination();

        })

        refreshPagination();

        // Adjust pagination url
        function updateQueryStringParameter(uri, key, value) {
            var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
            var separator = uri.indexOf('?') !== -1 ? "&" : "?";
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + "=" + value + '$2');
            }
            else {
                return uri + separator + key + "=" + value;
            }
        }
        //  function refreshPagination() {
        //     var btn_pagination = $(`.pagination`).find('a')
        //     var page_url = window.location.href
        //     $(`#myTabContent .active .pagination`).find('a').each(function(i, obj) {
        //         var have_arial = $(this).attr('aria-label')
        //         if (page_url.includes('tab')) {
        //             var url = updateQueryStringParameter(page_url, 'tab', $('#tab').val())
        //             const urlParams = new URLSearchParams(url);
        //             btn_pagination[i].href += url;
        //         }
        //         else {
        //             btn_pagination[i].href += `&tab=${$('#tab').val()}`
        //         }
        //         if (page_url.includes('page_length')) {
        //             var url = updateQueryStringParameter(page_url, 'page_length', $('#page_length').val())
        //             btn_pagination[i].href = url;
        //         }
        //         else {
        //             btn_pagination[i].href += `&page_length=${$('#page_length').val()}`
        //         }
        //         if (page_url.includes('q')) {
        //             var url = updateQueryStringParameter(page_url, 'q', $('#q').val())
        //             btn_pagination[i].href = url;
        //         }
        //         else {
        //             btn_pagination[i].href += `&q=${$('#q').val()}`
        //         }
        //     })
        // }

        function refreshPagination() {
            var btn_pagination = $("#myTabContent .active .pagination").find("a");
            var page_url = window.location.href;

            // Your custom query parameter and its value
            let tab = $('#tab').val();
            var customParam = "";
            customParam += "&tab=" + $('#tab').val();
            if (tab == 'proses') {
                customParam += "&page_length=" + $('#page_length_proses').val();
            } else {
                customParam += "&page_length=" + $('#page_length_final').val();

            }
            customParam += "&q=" + $('#q').val();

            btn_pagination.each(function (i, obj) {
                // Clone the original href to avoid modifying the original link
                var href = $(this).attr("href");

                // Check if the href already contains a question mark
                var separator = href.includes("?") ? "&" : "?";

                // Append the custom query parameter and its value
                var updatedHref = href + separator + customParam;
                updatedHref = updatedHref.replaceAll('&&', '&')

                // Update the href attribute of the pagination link
                $(this).attr("href", updatedHref);
            });
        }

        $(document).ready(function() {
            $('.btn-download-pdf').printPage({
                parent: window
            });
        })
        $('#proses-modal #form').on('submit', function() {
            $('.loader-wrapper').removeAttr('style')
        })
        $('#uploadFile').on('click',function() {
            // console.log('masuk');
            let batch_id = $(this).data('batch_id');
            let target = $(this).data('target');
            $(`${target} #id`).val(batch_id);
            $(`${target} #cetak_lampiran_gaji`).data('id', batch_id);
        })
        document.querySelector('.custom-file-input').addEventListener('change', function (e) {
            var name = document.getElementById("upload_csv").files[0].name;
            var nextSibling = e.target.nextElementSibling
            nextSibling.innerText = name
        });

        $('.btn-show').on('click', function() {
            $('#penghasilan-kantor-modal').modal('show')
            loadPenghasilanKantor()
        })

        $('#penghasilan-kantor-modal .close').on('click', function () {
            $('#penghasilan-kantor-modal').modal('hide')
            $("#penghasilan-kantor-modal #penghasilan-kantor-table").dataTable().fnDestroy();
        })

        function loadPenghasilanKantor() {
            var table = $('#penghasilan-kantor-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{route('gaji_perbulan.penghasilan_kantor')}}",
                columns: [
                    {
                        data: "counter",
                    },
                    {
                        data: "nama_cabang"
                    },
                    {
                        data: "penghasilan.januari"
                    },
                    {
                        data: "penghasilan.februari"
                    },
                    {
                        data: "penghasilan.maret"
                    },
                    {
                        data: "penghasilan.april"
                    },
                    {
                        data: "penghasilan.mei"
                    },
                    {
                        data: "penghasilan.juni"
                    },
                    {
                        data: "penghasilan.juli"
                    },
                    {
                        data: "penghasilan.agustus"
                    },
                    {
                        data: "penghasilan.september"
                    },
                    {
                        data: "penghasilan.oktober"
                    },
                    {
                        data: "penghasilan.november"
                    },
                    {
                        data: "penghasilan.desember"
                    }
                ],
                initComplete:function( settings, json){
                    // call your function here
                }
            });
        }

        $(".btn-proses").on('click', function(e){
            loadDataPenghasilan()
            // $('#proses-modal').modal('show');
        })

        $('#proses-modal .close').on('click', function () {
            // $('#proses-modal').modal('hide');
            $('#proses-modal #tanggal').val('')
            $('#proses-modal #total_karyawan').empty()
            $('#proses-modal #total_bruto').empty()
            $('#proses-modal #total_potongan').empty()
            $('#proses-modal #total_netto').empty()
        })

        $('#proses-modal #tanggal').on('change', function () {
            var tanggal = $(this).val()
            // Create a Date object from the date string
            var dateObject = new Date(tanggal);

            // Get the month (0-indexed, so January is 0, February is 1, and so on)
            const month = dateObject.getMonth() + 1;
            const year = dateObject.getFullYear();

            // Get month & year penghasilan terakhir
            if (year.toString().length == 4) {
                const last_month = $('#proses-modal #bulan_terakhir').val()
                const last_year = $('#proses-modal #tahun_terakhir').val()
                const dif_month = parseInt(month) - parseInt(last_month);
                if (dif_month > 1) {
                    Swal.fire({
                        title: 'Peringatan',
                        text: 'Tanggal penghasilan hanya diperbolehkan H+1 dari bulan penghasilan terakhir',
                        icon: 'warning',
                        iconColor: '#da271f',
                        confirmButtonText: 'Oke',
                        confirmButtonColor: "#da271f",
                    })
                    $(this).val('')
                }
                else {
                    if (((year == last_year) && (month == last_month)) || (year == last_year) && (month < last_month)) {
                        // Clear tanggal
                        Swal.fire({
                            title: 'Peringatan',
                            text: 'Harap pilih tanggal setelah tanggal penghasilan terakhir',
                            icon: 'warning',
                            iconColor: '#da271f',
                            confirmButtonText: 'Oke',
                            confirmButtonColor: "#da271f",
                        })
                        $(this).val('')
                    }
                    else if (year < last_year) {
                        Swal.fire({
                            title: 'Peringatan',
                            text: 'Harap pilih tanggal setelah tanggal penghasilan terakhir',
                            icon: 'warning',
                            iconColor: '#da271f',
                            confirmButtonText: 'Oke',
                            confirmButtonColor: "#da271f",
                        })
                        $(this).val('')
                    }
                }
            }
        })

        function loadDataPenghasilan() {
            $('.loader-wrapper').removeAttr('style')

            $.ajax({
                url: `{{ route('gaji_perbulan.get_data_penghasilan_json') }}`,
                method: 'GET',
                success: function(response) {
                    if (response.status == 'success') {
                        var data = response.data
                        $('#proses-modal #tahun_terakhir').val(data.penghasilan_tahun_terakhir)
                        $('#proses-modal #bulan_terakhir').val(data.penghasilan_bulan_terakhir)
                        $('#proses-modal #total_karyawan').html(data.total_karyawan)
                        $('#proses-modal #total_bruto').html(`Rp ${formatRupiah(data.bruto.toString())}`)
                        $('#proses-modal #total_potongan').html(`Rp ${formatRupiah(data.potongan.toString())}`)
                        $('#proses-modal #total_netto').html(`Rp ${formatRupiah(data.netto.toString())}`)
                        $('.loader-wrapper').attr('style', 'display: none;')
                    }
                    else {
                    }
                },
                error: function(e) {
                    Swal.fire({
                        title: 'Error',
                        text: e,
                        icon: 'error',
                        iconColor: '#da271f',
                        confirmButtonText: 'Ya',
                        confirmButtonColor: "#da271f",
                    })
                }
            })
        }

        $("#tahun").change(function(e){
            var tahun = $(this).val();
            $('#bulan option').removeAttr('disabled');

            $.ajax({
                url: "{{ route('getBulan') }}?tahun="+tahun,
                type: "get",
                datatype: "json",
                success: function(res){
                    $.each(res, function(i, v){
                        $('#bulan option[value="'+v.bulan+'"]').prop('disabled', true);
                    })
                }
            })
        })

        $('#btn-submit').on('click', function(e) {
            const bulan = $('#bulan').val()
            const tahun = $('#tahun').val()
            const tanggal = $('#tanggal').val()

            if (bulan != 0 && tahun != 0 && tanggal)
                $('.loader-wrapper').removeAttr('style')
        })

        function showDetail(data) {
            let elements = ``;
            $.each(data, function(i, item) {
                var keyNames = Object.keys(item);
                var values = Object.values(item);
                let title = 'undifined';
                let old_val = 0;
                let new_val = 0;
                title = keyNames[0].replaceAll('tj', 'Tj. ')
                title = title.replaceAll('_', ' ')
                title = title.ucwords()
                old_val = formatRupiah(values[0].toString(), 0)
                new_val = formatRupiah(values[1].toString(), 0)
                const item_element = `
                    <div class="col-md-3">
                        <dt>${title}</dt>
                        <dd>${old_val} -> ${new_val}</dd>
                    </div>
                `
                elements += (item_element)
            })

            let row_element = `
                <div class="row">
                    ${elements}
                </div>
            `;
            return row_element;
        }
        let table = null
        $('.btn-perbarui').on('click', function() {
            const batch_id = $(this).data('batch_id');
            table = $('#penyesuaian-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: `{{ route('gaji_perbulan.penyesuian_json') }}?batch_id=${batch_id}`,
                columns: [
                    {
                        className: 'dt-control',
                        orderable: false,
                        data: null,
                        defaultContent: ''
                    },
                    {
                        data: "counter"
                    },
                    {"data": "nip"},
                    {"data": "nama_karyawan"},
                    {
                        className: 'text-right',
                        data: "total_penghasilan",
                        render:function(data , type , row){
                            return `Rp ${formatRupiah(data.toString())}`;
                        },
                    },
                    {
                        className: 'text-right',
                        data: "total_penghasilan_baru",
                        render:function(data , type , row){
                            return `Rp ${formatRupiah(data.toString())}`;
                        },
                    },
                    {
                        className: 'text-right',
                        data: "total_potongan",
                        render:function(data , type , row){
                            return `Rp ${formatRupiah(data.toString())}`;
                        },
                    },
                    {
                        className: 'text-right',
                        data: "total_potongan_baru",
                        render:function(data , type , row){
                            return `Rp ${formatRupiah(data.toString())}`;
                        },
                    },
                ],
                footerCallback: function( row, data, start, end, display ) {
                    var api = this.api(),data;
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };
                    var grandTotalPenghasilanSebelum = data[0].grandtotal.bruto_lama

                    var totalPenghasilanSebelum = api
                        .column(4, {page: "current"})
                        .data()
                        .reduce(function (a, b) {
                            return Math.round(a) + Math.round(b);
                        }, 0);

                    var grandTotalPenghasilanSesudah = data[0].grandtotal.bruto_baru

                    var totalPenghasilanSesudah = api
                        .column(5, {page: "current"})
                        .data()
                        .reduce(function (a, b) {
                            return Math.round(a) + Math.round(b);
                        }, 0);

                    var grandTotalPotonganSebelum = data[0].grandtotal.potongan_lama

                    var totalPotonganSebelum = api
                        .column(6, {page: "current"})
                        .data()
                        .reduce(function (a, b) {
                            return Math.round(a) + Math.round(b);
                        }, 0);

                    var grandTotalPotonganSesudah = data[0].grandtotal.potongan_baru

                    var totalPotonganSesudah = api
                        .column(7, {page: "current"})
                        .data()
                        .reduce(function (a, b) {
                            return Math.round(a) + Math.round(b);
                        }, 0);

                    $( api.column( 0 ).footer('.total') ).html('Total');
                    $( api.column( 4 ).footer('.total') ).html( 'Rp ' + totalPenghasilanSebelum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $( api.column( 5 ).footer('.total') ).html( 'Rp ' + totalPenghasilanSesudah.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $( api.column( 6 ).footer('.total') ).html( 'Rp ' + totalPotonganSebelum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $( api.column( 7 ).footer('.total') ).html( 'Rp ' + totalPotonganSesudah.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));

                    $('tfoot tr.grandtotal').html(`
                        <th colspan="4" class="text-center">Grand Total</th>
                        <th class="text-right">Rp ${grandTotalPenghasilanSebelum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</th>
                        <th class="text-right">Rp ${grandTotalPenghasilanSesudah.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</th>
                        <th class="text-right">Rp ${grandTotalPotonganSebelum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</th>
                        <th class="text-right">Rp ${grandTotalPotonganSesudah.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</th>
                    `);
                }
            });

            // Add event listener for opening and closing details
            table.on('click', 'td.dt-control', function (e) {
                let tr = e.target.closest('tr');
                let row = table.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                }
                else {
                    // Open this row
                    row.child(showDetail(row.data().penyesuaian)).show();
                }
            });
            $('#penyesuaian-modal #batch_id').val(batch_id);
            $('#penyesuaian-modal').modal('show');
        })

        $('#penyesuaian-modal .close').on('click', function () {
            $('#penyesuaian-modal').modal('hide');
            $("#penyesuaian-modal #penyesuaian-table").dataTable().fnDestroy();
        })

        $('.btn-final').on('click', function() {
            const token = generateCsrfToken()
            const batch_id = $(this).data('batch_id')
            $('#form-final #token').val(token)
            $('#form-final #batch_id').val(batch_id)

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Anda yakin akan memproses data ini?',
                icon: 'question',
                iconColor: '#da271f',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                showCancelButton: true,
                confirmButtonColor: "#da271f",
                cancelButtonColor: "#fccf71",
                inputValidator: (value) => {
                    if (!value) {
                        return "You need to write something!";
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $('.loader-wrapper').removeAttr('style')
                    $('#form-final').submit()
                }
            })
        })

        $('#penyesuaian-modal #btn-update').on('click', function(e) {
            e.preventDefault();
            $('.loader-wrapper').removeAttr('style')
            $('#penyesuaian-modal #form').submit()
        })

        $(".btn-rincian").on("click", function(){
            var batch_id = $(this).data("batch_id")
            var iteration = 1;
            var table = $("#table-rincian").DataTable({
                processing: true,
                serverSide: false,
                orderCellsTop: true,
                paging:true,
                ajax: `{{ route('get-rincian-payroll') }}?batch_id=${batch_id}`,
                columns: [
                    {
                        data: 'counter',
                    },
                    {
                        data: 'nama_karyawan'
                    },
                    {
                        data: 'gaji.gj_pokok',
                        class: 'text-right',
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: "gaji.tj_keluarga",
                        class: 'text-right',
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: "gaji.tj_telepon",
                        class: 'text-right',
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: "gaji.tj_jabatan",
                        class: 'text-right',
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: "gaji.tj_ti",
                        class: 'text-right',
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: "gaji.tj_perumahan",
                        class: 'text-right',
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: "gaji.tj_pelaksana",
                        class: 'text-right',
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: "gaji.tj_kemahalan",
                        class: 'text-right',
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: "gaji.tj_kesejahteraan",
                        class: 'text-right',
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: "gaji.tj_teller",
                        class: 'text-right',
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: "gaji.gj_penyesuaian",
                        class: 'text-right',
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: "gaji.total_gaji",
                        class: 'text-right',
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: 'pph_dilunasi_bulan_ini',
                        class: 'text-right',
                        defaultContent: 0,
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                ],
                footerCallback: function ( row, data, start, end, display ) {
                    var api = this.api(),data;
                    var test = this.api();
                        // converting to interger to find total
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };
                     // Calculate and display grand totals in the second footer
                    var grandTotalGajiPokok = api
                        .column(2)
                        .data()
                        .reduce(function (a, b) {
                            return Math.round(a) + Math.round(b);
                        }, 0);
                    // computing column Total gaji pokok
                    var totalGajiPokok = api
                        .column(2, { page: "current" })
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                        }, 0 );
                    // computing column Total gaji pokok
                    var grandTotalGajiKeluarga = api
                        .column(3)
                        .data()
                        .reduce(function (a, b) {
                            return Math.round(a) + Math.round(b);
                        }, 0);
                    var totalGajiKeluarga = api
                        .column( 3, { page: "current" })
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );

                    var grandTotalGajiListrik = api
                        .column( 4 )
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalGajiListrik = api
                        .column( 4,{page:"current"} )
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var grandTotalGajiJabatan = api
                        .column( 5 )
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalGajiJabatan = api
                        .column( 5, {page:"current"})
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var grandTotalGajiKhusus = api
                        .column( 6 )
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalGajiKhusus = api
                        .column( 6, {page:"current"})
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var grandTotalGajiPerumahan = api
                        .column( 7)
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalGajiPerumahan = api
                        .column( 7, {page:"current"})
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var grandTotalGajiPelaksana = api
                        .column( 8 )
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalGajiPelaksana = api
                        .column( 8 ,{page:"current"})
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var grandTotalGajiKemahalan = api
                        .column( 9 )
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalGajiKemahalan = api
                        .column( 9 ,{page:"current"})
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var grandTotalGajiKesejahteraan = api
                        .column( 10 )
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalGajiKesejahteraan = api
                        .column( 10 ,{page:"current"} )
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var grandTotalGajiTeller = api
                        .column( 11 )
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalGajiTeller = api
                        .column( 11 ,{page:"current"} )
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var grandTotalGajiPenyesuian = api
                        .column( 12 )
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalGajiPenyesuian = api
                        .column( 12 ,{page:"current"})
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var grandTotalGajiTotal = api
                        .column( 13 )
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalGajiTotal = api
                        .column( 13 ,{page:"current"})
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var grandTotalGajiPPH = api
                    .column( 14 )
                    .data()
                    .reduce( function (a, b) {
                        return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalGajiPPH = api
                    .column( 14 ,{page:"current"})
                    .data()
                    .reduce( function (a, b) {
                        return Math.round(a) + Math.round(b);
                    }, 0 );
                    // $( api.column( 0 ).footer('.total') ).html('Total');
                    var displayTotalGajiPokok = formatRupiahExcel(totalGajiPokok)
                    // $( api.column( 2 ).footer('.total') ).html(displayTotalGajiPokok);
                    var displayTotalGajiKeluarga = formatRupiahExcel(totalGajiKeluarga)
                    // $( api.column( 3 ).footer('.total') ).html(displayTotalGajiKeluarga);
                    var displayTotalGajiListrik = formatRupiahExcel(totalGajiListrik)
                    // $( api.column( 4 ).footer('.total') ).html(displayTotalGajiListrik);
                    var displayTotalGajiJabatan = formatRupiahExcel(totalGajiJabatan)
                    // $( api.column( 5 ).footer('.total') ).html(displayTotalGajiJabatan);
                    var displayTotalGajiKhusus = formatRupiahExcel(totalGajiKhusus)
                    // $( api.column( 6 ).footer('.total') ).html(displayTotalGajiKhusus);
                    var displayTotalGajiPerumahan = formatRupiahExcel(totalGajiPerumahan)
                    // $( api.column( 7 ).footer('.total') ).html(displayTotalGajiPerumahan);
                    var displayTotalGajiPelaksana = formatRupiahExcel(totalGajiPelaksana)
                    // $( api.column( 8 ).footer('.total') ).html(displayTotalGajiPelaksana);
                    var displayTotalGajiKemahalan = formatRupiahExcel(totalGajiKemahalan)
                    // $( api.column( 9 ).footer('.total') ).html(displayTotalGajiKemahalan);
                    var displayTotalGajiKesejahteraan = formatRupiahExcel(totalGajiKesejahteraan)
                    // $( api.column( 10 ).footer('.total') ).html(displayTotalGajiKesejahteraan);
                    var displayTotalGajiTeller = formatRupiahExcel(totalGajiTeller)
                    // $( api.column( 11 ).footer('.total') ).html(displayTotalGajiTeller);
                    var displayTotalGajiPenyesuian = formatRupiahExcel(totalGajiPenyesuian)
                    // $( api.column( 12 ).footer('.total') ).html(displayTotalGajiPenyesuian);
                    var displayTotalGajiTotal = formatRupiahExcel(totalGajiTotal)
                    // $( api.column( 13 ).footer('.total') ).html(displayTotalGajiTotal);
                    var displayTotalGajiPPH = formatRupiahExcel(totalGajiPPH)
                    // $( api.column( 14 ).footer('.total') ).html(displayTotalGajiPPH);
                    console.log(`pph: ${totalGajiPPH}`);
                    $('tfoot tr.total').html(`
                        <th colspan="2" class="text-center">Total</th>
                        <th class="text-right">${displayTotalGajiPokok}</th>
                        <th class="text-right">${displayTotalGajiKeluarga}</th>
                        <th class="text-right">${displayTotalGajiListrik}</th>
                        <th class="text-right">${displayTotalGajiJabatan}</th>
                        <th class="text-right">${displayTotalGajiKhusus}</th>
                        <th class="text-right">${displayTotalGajiPerumahan}</th>
                        <th class="text-right">${displayTotalGajiPelaksana}</th>
                        <th class="text-right">${displayTotalGajiKemahalan}</th>
                        <th class="text-right">${displayTotalGajiKesejahteraan}</th>
                        <th class="text-right">${displayTotalGajiTeller}</th>
                        <th class="text-right">${displayTotalGajiPenyesuian}</th>
                        <th class="text-right">${displayTotalGajiTotal}</th>
                        <th class="text-right">${displayTotalGajiPPH}</th>
                    `);

                    grandTotalGajiPokok = Math.round(grandTotalGajiPokok)
                    var displayGrandTotalGajiPokok = formatRupiahExcel(grandTotalGajiPokok)
                    grandTotalGajiKeluarga = Math.round(grandTotalGajiKeluarga)
                    var displayGrandTotalGajiKeluarga = formatRupiahExcel(grandTotalGajiKeluarga)
                    grandTotalGajiListrik = Math.round(grandTotalGajiListrik)
                    var displayGrandTotalGajiListrik = formatRupiahExcel(grandTotalGajiListrik)
                    grandTotalGajiJabatan = Math.round(grandTotalGajiJabatan)
                    var displayGrandTotalGajiJabatan = formatRupiahExcel(grandTotalGajiJabatan)
                    grandTotalGajiKhusus = Math.round(grandTotalGajiKhusus)
                    var displayGrandTotalGajiKhusus = formatRupiahExcel(grandTotalGajiKhusus)
                    grandTotalGajiPerumahan = Math.round(grandTotalGajiPerumahan)
                    var displayGrandTotalGajiPerumahan = formatRupiahExcel(grandTotalGajiPerumahan)
                    grandTotalGajiPelaksana = Math.round(grandTotalGajiPelaksana)
                    var displayGrandTotalGajiPelaksana = formatRupiahExcel(grandTotalGajiPelaksana)
                    grandTotalGajiKemahalan = Math.round(grandTotalGajiKemahalan)
                    var displayGrandTotalGajiKemahalan = formatRupiahExcel(grandTotalGajiKemahalan)
                    grandTotalGajiKesejahteraan = Math.round(grandTotalGajiKesejahteraan)
                    var displayGrandTotalGajiKesejahteraan = formatRupiahExcel(grandTotalGajiKesejahteraan)
                    grandTotalGajiTeller = Math.round(grandTotalGajiTeller)
                    var displayGrandTotalGajiTeller = formatRupiahExcel(grandTotalGajiTeller)
                    grandTotalGajiPenyesuian = Math.round(grandTotalGajiPenyesuian)
                    var displayGrandTotalGajiPenyesuian = formatRupiahExcel(grandTotalGajiPenyesuian)
                    grandTotalGajiTotal = Math.round(grandTotalGajiTotal)
                    var displayGrandTotalGajiTotal = formatRupiahExcel(grandTotalGajiTotal)
                    grandTotalGajiPPH = Math.round(grandTotalGajiPPH)
                    var displayGrandTotalGajiPPH = formatRupiahExcel(grandTotalGajiPPH)

                    $('tfoot tr.grandtotal').html(`
                        <th colspan="2" class="text-center">Grand Total</th>
                        <th class="text-right">${displayGrandTotalGajiPokok}</th>
                        <th class="text-right">${displayGrandTotalGajiKeluarga}</th>
                        <th class="text-right">${displayGrandTotalGajiListrik}</th>
                        <th class="text-right">${displayGrandTotalGajiJabatan}</th>
                        <th class="text-right">${displayGrandTotalGajiKhusus}</th>
                        <th class="text-right">${displayGrandTotalGajiPerumahan}</th>
                        <th class="text-right">${displayGrandTotalGajiPelaksana}</th>
                        <th class="text-right">${displayGrandTotalGajiKemahalan}</th>
                        <th class="text-right">${displayGrandTotalGajiKesejahteraan}</th>
                        <th class="text-right">${displayGrandTotalGajiTeller}</th>
                        <th class="text-right">${displayGrandTotalGajiPenyesuian}</th>
                        <th class="text-right">${displayGrandTotalGajiTotal}</th>
                        <th class="text-right">${displayGrandTotalGajiPPH}</th>
                    `);
                },

            })
            // $("#rincian-modal").modal("show")
            $("#rincian-modal .btn-download-rincian").data('batch', batch_id)
        })


        function formatrupiah(angka, prefix) {
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
            return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
        }
        $(".btn-payroll").on("click", function(){
            var batch_id = $(this).data("batch_id")
            var table = $("#table-payroll").DataTable({
                ajax: `{{ route('get-rincian-payroll') }}?batch_id=${batch_id}`,
                processing: true,
                serverSide: false,
                orderCellsTop: true,
                paging:true,
                columns: [
                    {
                        data: 'counter',
                    },
                    {
                        data: 'nama_karyawan'
                    },
                    {
                        data: 'gaji.total_gaji',
                        class: 'text-right',
                        defaultContent: 0,
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: 'no_rekening',
                        defaultContent: '-',
                    },
                    {
                        data: "bpjs_tk",
                        class: 'text-right',
                        defaultContent: 0,
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: "potongan.dpp",
                        class: 'text-right',
                        defaultContent: 0,
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },

                    {
                        data: "gaji.kredit_koperasi",
                        class: 'text-right',
                        defaultContent: 0,
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: "gaji.iuran_koperasi",
                        class: 'text-right',
                        defaultContent: 0,
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: "gaji.kredit_pegawai",
                        class: 'text-right',
                        defaultContent: 0,
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: "gaji.iuran_ik",
                        class: 'text-right',
                        defaultContent: 0,
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: "total_potongan",
                        class: 'text-right',
                        defaultContent: 0,
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: "total_yg_diterima",
                        class: 'text-right',
                        defaultContent: 0,
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                ],
                footerCallback: function ( row, data, start, end, display ) {
                    var api = this.api(),data;
                        // converting to interger to find total
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };
                     // Calculate and display grand totals in the second footer
                    var grandTotalGajiPokok = api
                        .column(2)
                        .data()
                        .reduce(function (a, b) {
                            return Math.round(a) + Math.round(b);
                        }, 0);
                    // computing column Total gaji pokok
                    var totalGajiPokok = api
                        .column(2, { page: "current" })
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                        }, 0 );
                    // computing column Total gaji pokok
                    var grandTotalDPP = api
                        .column(4)
                        .data()
                        .reduce(function (a, b) {
                            return Math.round(a) + Math.round(b);
                        }, 0);
                    var totalGajiDPP = api
                        .column( 4, { page: "current" })
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );

                    var grandTotalBPJS = api
                        .column( 5 )
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalGajiBPJS = api
                        .column(5,{page:"current"} )
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var grandTotalGajiKredit = api
                        .column(6)
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalGajiKredit = api
                        .column( 6, {page:"current"})
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var grandTotalGajiKoperasi = api
                        .column( 7 )
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalGajiKoperasi = api
                        .column( 7, {page:"current"})
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var grandTotalGajiPegawai = api
                        .column(8)
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalGajiPegawai = api
                        .column(8, {page:"current"})
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var grandTotalGajiIuran = api
                        .column(9)
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalGajiIuran = api
                        .column(9 ,{page:"current"})
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var grandTotalGajiPotongan = api
                        .column(10 )
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalGajiPotongan = api
                        .column(10 ,{page:"current"})
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var grandTotalGajiDiterima = api
                        .column( 11 )
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalGajiDiterima = api
                    .column( 11 ,{page:"current"} )
                    .data()
                    .reduce( function (a, b) {
                        return Math.round(a) + Math.round(b);
                    }, 0 );

                    $( api.column( 0 ).footer('.total') ).html('Total');
                    $( api.column( 2 ).footer('.total') ).html(formatRupiahExcel(Math.round(totalGajiPokok)));
                    $( api.column( 3 ).footer('.total') ).html();
                    $( api.column( 4 ).footer('.total') ).html(formatRupiahExcel(Math.round(totalGajiDPP)));
                    $( api.column( 5 ).footer('.total') ).html(formatRupiahExcel(Math.round(totalGajiBPJS)));
                    $( api.column( 6 ).footer('.total') ).html(formatRupiahExcel(Math.round(totalGajiKredit)));
                    $( api.column( 7 ).footer('.total') ).html(formatRupiahExcel(Math.round(totalGajiKoperasi)));
                    $( api.column( 8 ).footer('.total') ).html(formatRupiahExcel(Math.round(totalGajiPegawai)));
                    $( api.column( 9 ).footer('.total') ).html( formatRupiahExcel(Math.round(totalGajiIuran)));
                    $( api.column( 10 ).footer('.total') ).html( formatRupiahExcel(Math.round(totalGajiPotongan)));
                    $( api.column( 11 ).footer('.total') ).html( formatRupiahExcel(Math.round(totalGajiDiterima)));

                    $('tfoot tr.grandtotalPayroll').html(`
                        <th colspan="2" class="text-center">Grand Total</th>
                        <th class="text-right">${formatRupiahExcel(Math.round(grandTotalGajiPokok))}</th>
                        <th class="text-center"></th>
                        <th class="text-right">${formatRupiahExcel(Math.round(grandTotalDPP))}</th>
                        <th class="text-right">${formatRupiahExcel(Math.round(grandTotalBPJS))}</th>
                        <th class="text-right">${formatRupiahExcel(Math.round(grandTotalGajiKredit))}</th>
                        <th class="text-right">${formatRupiahExcel(Math.round(grandTotalGajiKoperasi))}</th>
                        <th class="text-right">${formatRupiahExcel(Math.round(grandTotalGajiPegawai))}</th>
                        <th class="text-right">${formatRupiahExcel(Math.round(grandTotalGajiIuran))}</th>
                        <th class="text-right">${formatRupiahExcel(Math.round(grandTotalGajiPotongan))}</th>
                        <th class="text-right">${formatRupiahExcel(Math.round(grandTotalGajiDiterima))}</th>

                    `);
                },

            })
            // $("#payroll-modal").modal("show")
            $("#payroll-modal .btn-download-payroll").data('batch', batch_id)
        })

        $('#payroll-modal').on('hidden.bs.modal', function () {
            $('#payroll-modal').modal('hide')
            $("#payroll-modal #table-payroll").dataTable().fnDestroy();
        })

        $('#rincian-modal').on('hidden.bs.modal', function () {
            $('#rincian-modal').modal('hide')
            $("#rincian-modal #table-rincian").dataTable().fnDestroy();
        })

        $('#payroll-modal .close').on('click', function () {
            $('#payroll-modal').modal('hide')
            $("#payroll-modal #table-payroll").dataTable().fnDestroy();
        })

        $('#rincian-modal .close').on('click', function () {
            $('#rincian-modal').modal('hide')
            $("#rincian-modal #table-rincian").dataTable().fnDestroy();
        })

        $("#payroll-modal .btn-download-payroll").on('click', function(){
            var tipe = 'payroll';
            var batch_id = $(this).data('batch');
            $(this).attr('href', `{{ route('proses-gaji-download-rincian') }}?batch_id=${batch_id}&tipe=${tipe}`)
        })

        $("#rincian-modal .btn-download-rincian").on('click', function(){
            var tipe = 'rincian';
            var batch_id = $(this).data('batch');
            $(this).attr('href', `{{ route('proses-gaji-download-rincian') }}?batch_id=${batch_id}&tipe=${tipe}`)
        })

        $('.btn-lampiran-gaji').on("click", function () {
            let id  = $(this).data('id');
            let url = "{{ url('') }}"
            let downloadUrl = `${url}/cetak-penghasilan/${id}`;
            $('#download').attr('href', downloadUrl);
            $('#download').data('id', id);
            var table = $("#table-lampiran-gaji").DataTable({
                processing: true,
                serverSide: false,
                orderCellsTop: true,
                paging:true,
                ajax: `{{ url('get-lampiran-gaji/${id}') }}`,
                columns: [
                    {
                        data: 'counter',
                    },
                    {
                        data: 'nama_karyawan'
                    },
                    {
                        data: 'gaji.total_gaji',
                        class: 'text-right',
                        defaultContent: 0,
                        render: function (data, type, row) {
                            var number = $.fn.dataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number;
                        }
                    },
                    {
                        data: 'no_rekening',
                        defaultContent: '-',
                        class: 'text-center',
                    },
                    {
                        data: "bpjs_tk",
                        defaultContent: 0,
                        class: 'text-right',
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: "potongan.dpp",
                        defaultContent: 0,
                        class: 'text-right',
                        render: function (data, type, row) {
                            var number = $.fn.dataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number;
                        }
                    },
                    {
                        data: "potongan_gaji.kredit_koperasi",
                        defaultContent: 0,
                        class: 'text-right',
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: "potongan_gaji.iuran_koperasi",
                        defaultContent: 0,
                        class: 'text-right',
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: "potongan_gaji.kredit_pegawai",
                        defaultContent: 0,
                        class: 'text-right',
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: "potongan_gaji.iuran_ik",
                        defaultContent: 0,
                        class: 'text-right',
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: "total_potongan",
                        defaultContent: 0,
                        class: 'text-right',
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                    {
                        data: "total_yg_diterima",
                        defaultContent: 0,
                        class: 'text-right',
                        render:function(data, type, row){
                            return formatRupiahExcel(data)
                        }
                    },
                ],
                footerCallback: function ( row, data, start, end, display ) {
                    var api = this.api(),data;
                        // converting to interger to find total
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };
                     // Calculate and display grand totals in the second footer
                    var grandTotalGajiPokok = api
                        .column(2)
                        .data()
                        .reduce(function (a, b) {
                            return Math.round(a) + Math.round(b);
                        }, 0);
                    // computing column Total gaji pokok
                    var totalGajiPokok = api
                        .column(2, { page: "current" })
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                        }, 0 );
                    // computing column bpjs
                    var grandTotalBPJS = api
                        .column( 4 )
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalBPJS = api
                        .column(4,{page:"current"} )
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    // computing column dpp
                    var grandTotalDPP = api
                        .column(5)
                        .data()
                        .reduce(function (a, b) {
                            return Math.round(a) + Math.round(b);
                        }, 0);
                    var totalDPP = api
                        .column( 5, { page: "current" })
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var grandTotalKreditKoperasi = api
                        .column(6)
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalKredit = api
                        .column( 6, {page:"current"})
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var grandTotalIuran= api
                        .column( 7 )
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalIuran = api
                        .column( 7, {page:"current"})
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var grandTotalKreditPegawai = api
                        .column(8)
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalKreditPegawai = api
                        .column(8, {page:"current"})
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var grandTotalIuranIk = api
                        .column(9)
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalIuranIk = api
                        .column(9 ,{page:"current"})
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var grandTotalPotongan = api
                        .column(10)
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalPotongan = api
                        .column(10 ,{page:"current"})
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var grandTotalDiterima = api
                        .column(11 )
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );
                    var totalDiterima = api
                        .column(11 ,{page:"current"})
                        .data()
                        .reduce( function (a, b) {
                            return Math.round(a) + Math.round(b);
                    }, 0 );

                    $( api.column( 0 ).footer('.total') ).html('Total');
                    $( api.column( 2 ).footer('.total') ).html(  Math.round(totalGajiPokok));
                    $( api.column( 3 ).footer('.total') ).html(`-`);
                    $( api.column( 4 ).footer('.total') ).html(  Math.round(totalBPJS).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $( api.column( 5 ).footer('.total') ).html(  Math.round(totalDPP).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $( api.column( 6 ).footer('.total') ).html(  Math.round(totalKredit).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $( api.column( 7 ).footer('.total') ).html(  Math.round(totalIuran).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $( api.column( 8 ).footer('.total') ).html(  Math.round(totalKreditPegawai).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $( api.column( 9 ).footer('.total') ).html( Math.round(totalIuranIk).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $( api.column( 10 ).footer('.total') ).html( Math.round(totalPotongan).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $( api.column( 11 ).footer('.total') ).html( Math.round(totalDiterima).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));

                    $('tfoot tr.grandtotalGaji').html(`
                        <th colspan="2" class="text-center">Grand Total</th>
                        <th class="text-right">${Math.round(grandTotalGajiPokok).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</th>
                        <th class="text-center">-</th>
                        <th class="text-right">${Math.round(grandTotalBPJS).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</th>
                        <th class="text-right">${Math.round(grandTotalDPP).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</th>
                        <th class="text-right">${Math.round(grandTotalKreditKoperasi).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</th>
                        <th class="text-right">${Math.round(grandTotalIuran).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</th>
                        <th class="text-right">${Math.round(grandTotalKreditPegawai).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</th>
                        <th class="text-right">${Math.round(grandTotalIuranIk).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</th>
                        <th class="text-right">${Math.round(grandTotalPotongan).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</th>
                        <th class="text-right">${Math.round(grandTotalDiterima).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</th>

                    `);
                },

            })
            // $("#lampiran-gaji-modal").modal("show")
            $("#lampiran-gaji-modal .btn-download-lampiran-gaji").data('batch', batch_id)
        });

        $('#lampiran-gaji-modal').on('hidden.bs.modal', function () {
            $('#lampiran-gaji-modal').modal('hide')
            $("#lampiran-gaji-modal #table-lampiran-gaji").dataTable().fnDestroy();
        })

        $('#lampiran-gaji-modal .close').on("click", function(){
            $("#lampiran-gaji-modal").modal("hide")
            $("#lampiran-gaji-modal #table-lampiran-gaji").dataTable().fnDestroy();
        });
    </script>
@endpush
