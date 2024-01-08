@push('script')
    <script src="{{asset('vendor/printpage/printpage.min.js')}}"></script>
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
        $('.btn-download-pdf').on('click',function() {
            let id  = $(this).data('id');
            console.log(id);
            let url = "{{ url('') }}"
            let downloadUrl = `${url}/cetak-penghasilan/${id}`;
            console.log(downloadUrl);
            // href="{{ route('cetak.penghasilanPerBulan',$item->id) }}"
            $(this).attr('href',downloadUrl)

        })
        $(document).ready(function() {
            $('#download').printPage();
        })
    </script>
    <script>
        $('#proses-modal #form').on('submit', function() {
            $('.loader-wrapper').removeAttr('style')
        })
        $('#uploadFile').on('click',function() {
            let batch_id = $(this).data('batch_id');
            let target = $(this).data('target');

            $(`${target} #id`).val(batch_id);
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
                    console.log(json);
                    // call your function here
                }
            });
        }

        $(".btn-proses").on('click', function(e){
            loadDataPenghasilan()
            $('#proses-modal').modal('show');
        })

        $('#proses-modal .close').on('click', function () {
            $('#proses-modal').modal('hide');
            $('#proses-modal #tanggal').val('')
            $('#proses-modal #total_karyawan').empty()
            $('#proses-modal #total_bruto').empty()
            $('#proses-modal #total_potongan').empty()
            $('#proses-modal #total_netto').empty()
        })

        function loadDataPenghasilan() {
            $('.loader-wrapper').removeAttr('style')

            $.ajax({
                url: `{{ route('gaji_perbulan.get_data_penghasilan_json') }}`,
                method: 'GET',
                success: function(response) {
                    if (response.status == 'success') {
                        var data = response.data
                        $('#proses-modal #total_karyawan').html(data.total_karyawan)
                        $('#proses-modal #total_bruto').html(`Rp ${formatRupiah(data.bruto.toString())}`)
                        $('#proses-modal #total_potongan').html(`Rp ${formatRupiah(data.potongan.toString())}`)
                        $('#proses-modal #total_netto').html(`Rp ${formatRupiah(data.netto.toString())}`)
                        $('.loader-wrapper').attr('style', 'display: none;')
                    }
                    else {
                        console.log(response)
                    }
                },
                error: function(e) {
                    console.log(e)
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
                        data: "total_penghasilan",
                        render:function(data , type , row){
                            return `Rp ${formatRupiah(data.toString())}`;
                        },
                    },
                    {
                        data: "total_penghasilan_baru",
                        render:function(data , type , row){
                            return `Rp ${formatRupiah(data.toString())}`;
                        },
                    },
                    {
                        data: "total_potongan",
                        render:function(data , type , row){
                            return `Rp ${formatRupiah(data.toString())}`;
                        },
                    },
                    {
                        data: "total_potongan_baru",
                        render:function(data , type , row){
                            return `Rp ${formatRupiah(data.toString())}`;
                        },
                    },
                ]
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
                serverSide: true,
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
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "gaji.tj_keluarga",
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "gaji.tj_telepon",
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "gaji.tj_jabatan",
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "gaji.tj_ti",
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "gaji.tj_perumahan",
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "gaji.tj_pelaksana",
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "gaji.tj_kemahalan",
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "gaji.tj_kesejahteraan",
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "gaji.gj_penyesuaian",
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "gaji.total_gaji",
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: 'perhitungan_pph21.pph_pasal_21.pph_harus_dibayar',
                        defaultContent: 0,
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data.toString().replaceAll('-', ''));

                            return '(' + number + ')'
                        }
                    },
                ],
            })
            $("#rincian-modal").modal("show")
        })

        $(".btn-payroll").on("click", function(){
            var batch_id = $(this).data("batch_id")
            var table = $("#table-payroll").DataTable({
                processing: true,
                serverSide: true,
                ajax: `{{ route('get-rincian-payroll') }}?batch_id=${batch_id}`,
                columns: [
                    {
                        data: 'counter',
                    },
                    {
                        data: 'nama_karyawan'
                    },
                    {
                        data: 'gaji.total_gaji',
                        defaultContent: 0,
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: 'no_rekening',
                        defaultContent: '-',
                    },
                    {
                        data: "potongan.dpp",
                        defaultContent: 0,
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "bpjs_tk",
                        defaultContent: 0,
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "potonganGaji.kredit_koperasi",
                        defaultContent: 0,
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "potonganGaji.iuran_koperasi",
                        defaultContent: 0,
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "potonganGaji.kredit_pegawai",
                        defaultContent: 0,
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "potonganGaji.iuran_ik",
                        defaultContent: 0,
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "total_potongan",
                        defaultContent: 0,
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                    {
                        data: "total_yg_diterima",
                        defaultContent: 0,
                        render:function(data, type, row){
                            var number = DataTable.render
                                .number('.', '.', 0, '')
                                .display(data);

                            return number
                        }
                    },
                ],
            })
            $("#payroll-modal").modal("show")
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
    </script>
@endpush
