@extends('layouts.template')

@php
    $request = isset($request) ? $request : null;
@endphp

@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="card-title">
                <h5 class="card-title">Pengklasifikasian Data</h5>
                <p class="card-title"><a href="/">Dashboard</a> > <a href="/karyawan">Karyawan</a> > <a href="">Pengklasifikasian Data</a></p>
            </div>
        </div>
    </div>

    <div class="card-body ml-3 mr-3">
        <form action="" method="post">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Kategori {{ old('kategori') }}</label>
                        <select name="kategori" class="form-control" id="kategori">
                            <option value="-">--- Pilih Kategori ---</option>
                            <option @selected($request?->kategori == 1) value="1">Rekap Divisi</option>
                            <option @selected($request?->kategori == 2) value="2">Rekap Sub Divisi</option>
                            <option @selected($request?->kategori == 3) value="3">Rekap Bagian</option>
                            <option @selected($request?->kategori == 4) value="4">Rekap Kantor</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div id="kantor_col" class="col-md-4">
                </div>
                
                <div id="cabang_col" class="col-md-4">
                </div>
    
                <div id="divisi_col" class="col-md-4">
                </div>
    
                <div id="subDivisi_col" class="col-md-4">
                </div>
    
                <div id="bagian_col" class="col-md-4">
                </div>

                <div class="col-md-12">
                    <button class="btn btn-info" type="submit">Tampilkan</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('custom_script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script>
        $('#kategori').change(function(e) {
            const value = $(this).val();

            if (value == 1) {
                generateDivision();

                $('#divisi').removeAttr("disabled", "disabled");
                $("#divisi_col").show();

                $('#subDivisi').attr("disabled", "disabled");
                $("#subDivisi_col").hide();

                $('#bagian').attr("disabled", "disabled");
                $("#bagian_col").hide();

                $('#kantor').attr("disabled", "disabled");
                $("#kantor_col").hide();

                $('#cabang').attr("disabled", "disabled");
                $("#cabang_col").hide();
            } else if (value == 2) {
                generateDivision();

                $('#divisi').removeAttr("disabled", "disabled");
                $("#divisi_col").show();

                $('#subDivisi').removeAttr("disabled", "disabled");
                $("#subDivisi_col").show();

                $('#bagian').attr("disabled", "disabled");
                $("#bagian_col").hide();

                $('#kantor').attr("disabled", "disabled");
                $("#kantor_col").hide();

                $('#cabang').attr("disabled", "disabled");
                $("#cabang_col").hide();
            } else if (value == 3) {
                generateDivision();

                $('#divisi').removeAttr("disabled", "disabled");
                $("#divisi_col").show();

                $('#subDivisi').removeAttr("disabled", "disabled");
                $("#subDivisi_col").show();

                $('#bagian').removeAttr("disabled", "disabled");
                $("#bagian_col").show();

                $('#kantor').attr("disabled", "disabled");
                $("#kantor_col").hide();

                $('#cabang').attr("disabled", "disabled");
                $("#cabang_col").hide();
            } else if (value == 4) {
                generateOffice();

                $('#kantor').removeAttr("disabled", "disabled");
                $("#kantor_col").show();

                $('#cabang').removeAttr("disabled", "disabled");
                $("#cabang_col").show();

                $('#divisi').attr("disabled", "disabled");
                $("#divisi_col").hide();

                $('#subDivisi').attr("disabled", "disabled");
                $("#subDivisi_col").hide();

                $('#bagian').attr("disabled", "disabled");
                $("#bagian_col").hide();
            } else {
                $('#divisi').attr("disabled", "disabled");
                $("#divisi_col").hide();

                $('#subDivisi').attr("disabled", "disabled");
                $("#subDivisi_col").hide();

                $('#bagian').attr("disabled", "disabled");
                $("#bagian_col").hide();

                $('#kantor').attr("disabled", "disabled");
                $("#kantor_col").hide();

                $('#cabang').attr("disabled", "disabled");
                $("#cabang_col").hide();
            }
        });

        function generateDivision() {
            const division = '{{ $request?->divisi }}';
            $.ajax({
                type: 'GET',
                url: '/getdivisi',
                dataType: 'JSON',
                success: (res) => {
                    $('#divisi_col').empty();
                    $('#divisi_col').append(`
                        <div class="form-group">
                            <label for="divisi">Divisi</label>
                            <select name="divisi" id="divisi" class="form-control">
                                <option value="">--- Pilih Divisi ---</option>
                            </select>
                        </div>
                    `);

                    $.each(res, (i, item) => {
                        const kd_divisi = item.kd_divisi;
                        $('#divisi').append(`<option ${division == kd_divisi ? 'selected' : ''} value="${kd_divisi}">${item.kd_divisi} - ${item.nama_divisi}</option>`);
                    });

                    $('#subDivisi_col').empty();

                    $('#subDivisi_col').append(`
                        <div class="form-group">
                            <label for="subDivisi">Sub Divisi</label>
                            <select name="subDivisi" id="subDivisi" class="form-control">
                                <option value="">--- Pilih Sub Divisi ---</option>
                            </select>
                        </div>
                    `);

                    $('#divisi').change(function(e) {
                        var divisi = $(this).val();
        
                        if (divisi) {
                            const subDivision = '{{ $request?->subDivisi }}';
        
                            $.ajax({
                                type: 'GET',
                                url: '/getsubdivisi?divisiID='+divisi,
                                dataType: 'JSON',
                                success: (res) => {
                                    $('#subDivisi').empty();
                                    $('#subDivisi').append('<option value="">--- Pilih Sub Divisi ---</option>');
        
                                    $.each(res, (i, item) => {
                                        const kd_subDivisi = item.kd_subdiv;
                                        $('#subDivisi').append(`<option ${subDivision == kd_subDivisi ? 'selected' : ''} value="${kd_subDivisi}">${item.kd_subdiv} - ${item.nama_subdivisi}</option>`);
                                    });

                                    $('#bagian_col').empty();

                                    $('#bagian_col').append(`
                                        <div class="form-group">
                                            <label for="bagian">Bagian</label>
                                            <select name="bagian" id="bagian" class="form-control">
                                                <option value="">--- Pilih Bagian ---</option>
                                            </select>
                                        </div>
                                    `);

                                    $("#subDivisi").change(function(){
                                        const bagian = '{{ $request?->bagian}}';
                                        $.ajax({
                                            type: "GET",
                                            url: "/getbagian?kd_entitas="+$(this).val(),
                                            datatype: "JSON",
                                            success: function(res){
                                                $('#bagian').empty();
                                                $('#bagian').append('<option value="">--- Pilih Bagian ---</option>');

                                                $.each(res, (i, item) => {
                                                    const kd_bagian = item.kd_bagian;
                                                    $('#bagian').append(`<option ${bagian == kd_bagian ? 'selected' : ''} value="${kd_bagian}">${item.kd_bagian} - ${item.nama_bagian}</option>`);
                                                });
                                            }
                                        })
                                    });
                                }
                            });
                        }
                    });
                }

            });
        }

        function generateOffice() {
            const office = '{{ $request?->kantor }}';
            $('#kantor_col').append(`
                <div class="form-group">
                    <label for="kantor">Kantor</label>
                    <select name="kantor" class="form-control" id="kantor">
                        <option value="-">--- Pilih Kantor ---</option>
                        <option ${ office == "Pusat" ? 'selected' : '' } value="Pusat">Pusat</option>
                        <option ${ office == "Cabang" ? 'selected' : '' } value="Cabang">Cabang</option>
                    </select>
                </div>
            `);

            $('#kantor').change(function(e) {
                $('#cabang_col').empty();
                if($(this).val() != "Cabang") return;
                generateSubOffice();
            });

            function generateSubOffice() {
                $('#cabang_col').empty();
                const subOffice = '{{ $request?->cabang }}';

                $.ajax({
                    type: 'GET',
                    url: '/getcabang',
                    dataType: 'JSON',
                    success: (res) => {
                        $('#cabang_col').append(`
                            <div class="form-group">
                                <label for="cabang">Cabang</label>
                                <select name="cabang" id="cabang" class="form-control">
                                    <option value="">--- Pilih Cabang ---</option>
                                </select>
                            </div>
                        `);

                        $.each(res[0], (i, item) => {
                            const kd_cabang = item.kd_cabang;
                            $('#cabang').append(`<option ${subOffice == kd_cabang ? 'selected' : ''} value="${kd_cabang}">${item.kd_cabang} - ${item.nama_cabang}</option>`);
                        });
                    }
                });
            }
        }
    </script>
@endsection