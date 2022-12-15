@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Laporan DPP</h5>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('get-dpp') }}" method="post">
            @csrf
            <div class="row m-0">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Kategori</label>
                        <select name="kategori" class="form-control" id="kategori">
                            <option value="">--- Pilih Kategori ---</option>
                            <option value="1">Rekap Keseluruhan</option>
                            <option value="2">Rekap Kantor / Cabang</option>
                        </select>
                    </div>
                </div>
                <div id="kantor_col">
                </div>
                <div id="cabang_col">
                </div>
                <div class="col-md-4 mt-3">
                    <button class="btn btn-info" type="submit">Tampilkan</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset('style/assets/js/table2excel.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script> 
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script> 
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script> 
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script> 
    <script>
        // document.getElementById('btn_export').addEventListener('click', function(){
        //     var table2excel = new Table2Excel();
        //     table2excel.export(document.querySelectorAll('#table_export'));
        // });

        $("#table_export").DataTable({
            dom : "Bfrtip",
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Bank UMKM Jawa Timur',
                    text:'Excel' 
                }
            ]
        });
        

        $("#clear").click(function(e){
            $("#row-baru").empty()
        })

        $("#kategori").change(function(e){
            var value = $(this).val();
            $("#kantor_col").empty();
            console.log(value);
            if(value == 2){
                $("#kantor_col").addClass("col-md-4");
                $("#kantor_col").append(`
                <div class="form-group">
                        <label for="">Kantor</label>
                        <select name="kantor" class="form-control" id="kantor">
                            <option value="">--- Pilih Kantor ---</option>
                            <option value="Pusat">Pusat</option>
                            <option value="Cabang">Cabang</option>
                        </select>
                    </div>
                `)
                
        
                $("#kantor").change(function(e){
                    var value = $(this).val();
                    if(value == 'Cabang'){
                        $.ajax({
                            type: "GET",
                            url: '/getcabang',
                            datatype: 'JSON',
                            success: function(res){
                                $('#cabang_col').addClass("col-md-4");
                                $("#cabang_col").empty();
                                $("#cabang_col").append(`
                                        <div class="form-group">
                                            <label for="Cabang">Cabang</label>
                                            <select name="cabang" id="cabang" class="form-control">
                                                <option value="">--- Pilih Cabang ---</option>
                                            </select>
                                        </div>`
                                );

                                $("#kantor_row3").hide()
                                $.each(res[0], function(i, item){
                                    $('#cabang').append('<option value="'+item.kd_cabang+'">'+item.kd_cabang + ' - ' +item.nama_cabang+'</option>')
                                })
                            }
                        })
                    }else {
                        $("#cabang_col").removeClass("col-md-4");
                        $("#cabang_col").empty();
                    }
                })
            }
        })

        function formatRupiah(angka, prefix){
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);
 
            // tambahkan titik jika yang di input sudah menjadi angka satuan ribuan
            if(ribuan){
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
 
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }
    </script>
@endsection