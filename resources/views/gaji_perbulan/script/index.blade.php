@section('custom_script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
            var iteration = 1;
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
                        data: null,
                        render:function(data , type , row){
                            return iteration++;
                        },
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
    </script>
@endsection