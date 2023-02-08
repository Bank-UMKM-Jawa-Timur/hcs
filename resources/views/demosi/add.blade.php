@extends('layouts.template')
@include('vendor.select2')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Tambah Demosi</h5>
            <p class="card-title"><a href="">Manajemen Karyawan</a> > <a href="">Pergerakan Karir</a> > <a href="/demosi">Demosi</a> > Tambah</p>
        </div>
    </div>

    <div class="card-body ml-3 mr-3">
        <form action="{{ route('demosi.store') }}" method="POST" enctype="multipart/form-data" name="divis" class="form-group">
            @csrf
            <input type="hidden" name="kd_entity" value="">
            <input type="hidden" name="kd_bagian_lama">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="">Karyawan:</label>
                        <select name="nip" id="nip" class="form-control"></select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="">Status Jabatan</label>
                        <input type="text" id="status_jabatan" class="form-control" disabled>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="">Pangkat dan Golongan Sekarang</label>
                        <input type="text" id="panggol" class="form-control" disabled>
                        <input type="hidden" id="panggol_lama" name="panggol_lama" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <input type="hidden" id="bagian_lama" name="bagian_lama">
                <input type="hidden" id="status_jabatan_lama" name="status_jabatan_lama">
                <div class="" id="">

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Jabatan Sekarang</label>
                        <input type="text" class="form-control" disabled name="" id="jabatan_lama">
                        <input type="hidden" id="id_jabatan_lama" name="id_jabatan_lama">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Kantor Sekarang</label>
                        <input type="text" class="form-control" disabled name="" id="kantor_lama">
                    </div>
                </div>
            </div>
            <hr>
            <div class="row align-content-center">
                <div class="col-lg-12">
                    <h6>Pembaruan NIP</h6>
                </div>
                <div class="col-md-6 form-group">
                    <label for="NIP Baru">
                        NIP Baru
                    </label>
                    <input type="text" class="form-control" name="nip_baru" id="nip_baru">
                </div>
            </div>
            <hr>
            <div class="row align-content-center">
                <div class="col-lg-12">
                    <h6>Pembaruan Data Jabatan</h6>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Status Jabatan</label>
                        <select name="status_jabatan" id="" class="@error('status_jabatan') is-invalid @enderror form-control">
                            <option value="">--- Pilih ---</option>
                            <option value="Definitif">Definitif</option>
                            <option value="Penjabat">Penjabat</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Pangkat dan Golongan</label>
                        <select name="panggol" id="" class="@error('status_jabatan') is-invalid @enderror form-control">
                            <option value="">--- Pilih ---</option>
                            @foreach ($panggol as $item)
                                <option value="{{ $item->golongan }}">{{ $item->golongan }} - {{ $item->pangkat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row align-content-center">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Jabatan Baru</label>
                        <select name="id_jabatan_baru" id="jabatan_baru" class="@error('id_jabatan_baru') @enderror form-control">
                            <option value="-">--- Pilih ---</option>
                            @foreach ($jabatan as $item)
                                <option {{ old('id_jabatan_baru') == $item->kd_jabatan ? 'selected' : '-' }} value="{{ $item->kd_jabatan }}">{{ $item->kd_jabatan }} - {{ $item->nama_jabatan }}</option>
                            @endforeach
                        </select>
                        @error('id_jabatan_baru')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="kantor">Kantor</label>
                        <select name="kantor" id="kantor" class="@error('kantor') @enderror form-control" disabled>
                            <option value="-">--- Pilih Kantor ---</option>
                            <option @selected(old('kantor') == '1') value="1">Kantor Pusat</option>
                            <option @selected(old('kantor') == '2') value="2">Kantor Cabang</option>
                        </select>
                        @error('kantor')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div id="divisions-section"></div>
                <div id="subdivs-section"></div>
                <div id="bagians-section"></div>
                <div id="branches-section"></div>
            </div>
            <hr>
            <div class="row align-content-center">
                <div class="col-lg-12">
                    <h6>Pembaruan Data Gaji</h6>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="gj_pokok">Gaji Pokok</label>
                        <input class="form-control" type="text" name="gj_pokok" id="gj_pokok" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="gj_penyesuaian">Gaji Penyesuaian</label>
                        <input class="form-control" type="text" name="gj_penyesuaian" id="gj_penyesuaian" disabled>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row align-content-center">
                <div class="col-lg-12">
                    <h6>Pembaruan Data Tunjangan</h6>
                </div>
                <div class="col-lg-12" id="tj">
                    <div class="row pb-3">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="is">Tunjangan</label>
                                <select name="tunjangan[]" id="row_tunjangan" class="form-control">
                                    <option value="">--- Pilih ---</option>
                                    @foreach ($tunjangan as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_tunjangan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="id_tk[]" id="id_tk" value="">
                        <input type="hidden" name="nominal_lama[]" id="nominal_lama" value="0">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="is_nama">Nominal</label>
                                <input type="text" id="nominal" name="nominal_tunjangan[]" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-1 mt-3">
                            <button class="btn btn-info" type="button" id="btn-add">
                                <i class="bi-plus-lg"></i>
                            </button>
                        </div>
                        <div class="col-md-1 mt-3">
                            <button class="btn btn-info" type="button" id="btn-delete">
                                <i class="bi-dash-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row align-content-center justify-content-center">
                <div class="col-lg-12">
                    <h6>Pengesahan</h6>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Tanggal Pengesahan</label>
                        <input type="date" class="form-control" name="@error('tanggal_pengesahan') @enderror tanggal_pengesahan" id="" value="{{ old('tanggal_pengesahan') }}">
                        @error('tanggal_pengesahan')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class= "col-md-6">
                    <div class="form-group">
                        <label for="">Surat Keputusan</label>
                        <input type="text" class="@error('bukti_sk') @enderror form-control" name="bukti_sk" id="inputGroupFile01" value="{{ old('bukti_sk') }}">
                        @error('bukti_sk')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Keterangan Jabatan</label>
                        <textarea name="ket_jabatan" class="form-control" id="ket_jabatan"></textarea>
                    </div>
                </div>
            </div>
                <button type="submit" class="btn btn-info">Simpan</button>
            </div>
        </form>
    </div>
@endsection

@push('script')
<script>
// $(document).ready(function() {
//     var table = $('#table').DataTable({
//         'autoWidth': false,
//         'dom': 'Rlfrtip',
//         'colReorder': {
//             'allowReorder': false
//         }
//     });
// })

$('#nip').select2({
    ajax: {
        url: '{{ route('api.select2.karyawan') }}'
    },
    templateResult: function(data) {
        if(data.loading) return data.text;
        return $(`
            <span>${data.nama}<br><span class="text-secondary">${data.id} - ${data.jabatan}</span></span>
        `);
    }
});
</script>
@endpush


@section('custom_script')
    <script>
        var x = 0;
        // Give divisi option's value
        function fillDivision(data) {
            const section = $('#divisions-section');

            section.empty();
            section.addClass('col-md-4');
            section.append(`
                <div class="form-group">
                    <label for="divisi">Divisi</label>
                    <select name="kd_divisi" id="divisi" class="form-control">
                        <option value="">--- Pilih divisi ---</option>
                    </select>
                </div>`
            );

            $.each(data, function(i, item){
                $('#divisi').append('<option value="'+item.kd_divisi+'">'+item.nama_divisi+'</option>')
            });

            $('#divisi').change(function(e) {
                $.ajax({
                    url: `/getsubdivisi?divisiID=${this.value}`,
                    dataType: 'JSON',
                    success: (res) => fillSubDivision(res)
                });
            });
        }

        // Give subdivisi option's value
        function fillSubDivision(data) {
            const hides = ['PBO', 'PIMDIV'];
            const skips = ['PSD'];

            const section = $('#subdivs-section');
            const jabVal = $('#jabatan_baru').val();
            // If empmty, then skip the append element
            section.empty();
            $('#bagians-section').empty();
            if(data.length < 1 || hides.includes(jabVal)) return;

            section.addClass('col-md-4');
            section.append(`
                <div class="form-group">
                    <label for="sub_divisi">Sub divisi</label>
                    <select name="kd_subdiv" id="sub_divisi" class="form-control">
                        <option value="">--- Pilih sub divisi ---</option>
                    </select>
                </div>`
            );

            $('#sub_divisi').empty();
            $('#sub_divisi').append('<option value="">--- Pilih sub divisi ---</option>')
            $.each(data, function(i, item){
                $('#sub_divisi').append('<option value="'+item.kd_subdiv+'">'+item.nama_subdivisi+'</option>')
            })

            if(skips.includes(jabVal)) return;
            $('#sub_divisi').change(function(e) {
                $.ajax({
                    url: `/getbagian?kd_entitas=${this.value}`,
                    dataType: 'JSON',
                    success: (res) => fillBagian(res)
                });
            });
        }

        // Give bagian option's value
        function fillBagian(data) {
            const hides = ['PBO', 'PBP', 'PC'];
            const section = $('#bagians-section');
            const jabVal = $('#jabatan_baru').val();

            section.empty();
            if(data.length < 1 || hides.includes(jabVal)) return;

            section.addClass('col-md-4');
            section.append(`
                <div class="form-group">
                    <label for="kd_bagian">Bagian</label>
                    <select name="kd_bagian" id="kd_bagian" class="form-control">
                        <option value="">--- Pilih ---</option>
                    </select>
                </div>`
            );

            $('#kd_bagian').empty();
            $('#kd_bagian').append('<option value="">--- Pilih ---</option>')
            $.each(data, function(i, item){
                console.log(item);
                $('#kd_bagian').append('<option value="'+item.kd_bagian+'">'+item.nama_bagian+'</option>')
            })
        }

        // Give branch option's value
        function fillBranches(data) {
            $("#divisions-section").empty();
            $("#subdivs-section").empty();

            $('#divisions-section').addClass('col-md-4');
            $("#divisions-section").append(`
                    <div class="form-group">
                        <label for="kd_cabang">Cabang</label>
                        <select name="kd_cabang" id="cabang" class="form-control">
                            <option value="">--- Pilih Cabang ---</option>
                        </select>
                    </div>`
            );
            $.each(data, function(i, item){
                $('#cabang').append('<option value="'+item.kd_cabang+'">'+item.nama_cabang+'</option>')
            })
        }

        function setTunjangan(index, value){
            $("#tunjangan"+index).val(value)
        }

        $('#nip').change(function(e) {
            const nip = $(this).val();

            $.ajax({
                url: '/getdatakaryawan',
                data: {nip},
                dataType: 'JSON',
                success: (data) => {
                    if(!data.success) return;

                    $('input[name=kd_entity]').val(data.karyawan.kd_entitas);
                    $('#jabatan_lama').val(data.karyawan.jabatan.nama_jabatan || '');
                    $('#kantor_lama').val(data.karyawan.kd_entitas);
                    $('#id_jabatan_lama').val(data.karyawan.jabatan.kd_jabatan);
                    $("#status_jabatan").val(data.karyawan.status_jabatan)
                    $("#bagian_lama").val(data.karyawan.kd_bagian)
                    $("#panggol").val(data.karyawan.kd_panggol + " - " + data.karyawan.pangkat)
                    $("#panggol_lama").val(data.karyawan.kd_panggol)
                    $("#status_jabatan_lama").val(data.karyawan.status_jabatan)
                }
            });

            $.ajax({
                url: '{{ route('getDataGjPromosi') }}?nip='+nip,
                dataType: "json",
                type: "Get",
                success: function(res){
                    x = res.data_tj.length
                    $("#gj_pokok").removeAttr("disabled")
                    $("#gj_penyesuaian").removeAttr("disabled")
                    $("#gj_pokok").val(formatRupiah(res.data_gj.gj_pokok.toString()))
                    $("#gj_penyesuaian").val(formatRupiah(res.data_gj.gj_penyesuaian.toString()))
                    $("#tj").empty();
                    $.each(res.data_tj, function(i, item){
                        var idTj = item.id_tunjangan
                        $("#tj").append(`
                        <div class="row" id="row_tunjangan">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="is">Tunjangan</label>
                                        <select name="tunjangan[]" id="tunjangan`+i+`" class="form-control">
                                            <option value="">--- Pilih ---</option>
                                            @foreach ($tunjangan as $item)
                                                <option value="{{ $item->id }}" {{ ($item->id == `+ idTj +`) ? 'selected' : '' }}>{{ $item->nama_tunjangan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="id_tk[]" id="id_tk" value="`+item.id+`">
                                <input type="hidden" name="nominal_lama[]" id="nominal_lama" value="`+item.nominal+`">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="is_nama">Nominal</label>
                                        <input type="text" id="nominal" name="nominal_tunjangan[]" class="form-control" value="`+formatRupiah(item.nominal.toString())+`">
                                    </div>
                                </div>
                                <div class="col-md-1 mt-3">
                                    <button class="btn btn-info" type="button" id="btn-add">
                                        <i class="bi-plus-lg"></i>
                                    </button>
                                </div>
                                <div class="col-md-1 mt-3">
                                    <button class="btn btn-info" type="button" id="btn-delete">
                                        <i class="bi-dash-lg"></i>
                                    </button>
                                </div>
                            </div>
                        `);
                        setTunjangan(i, item.id_tunjangan)
                    })
                }
            })
        });
        $('#tj').on('click', "#btn-add", function(){
            $('#tj').append(`
            <div class="row pb-3">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="is">Tunjangan</label>
                                    <select name="tunjangan[]" id="row_tunjangan" class="form-control">
                                        <option value="">--- Pilih ---</option>
                                        @foreach ($tunjangan as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama_tunjangan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="id_tk[]" id="id_tk" value="">
                            <input type="hidden" name="nominal_lama[]" id="nominal_lama" value="0">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="is_nama">Nominal</label>
                                    <input type="text" id="nominal" name="nominal_tunjangan[]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-add">
                                    <i class="bi-plus-lg"></i>
                                </button>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-delete">
                                    <i class="bi-dash-lg"></i>
                                </button>
                            </div>
                        </div>
            `);
            x++
        });
        $('#tj').on('click', "#btn-delete", function(){
            var row = $(this).closest('.row')
            var value = row.children('#id_tk').val()
            console.log(value);
            if(x > 1){
                if(value != null){
                    $.ajax({
                        type: "GET",
                        url: "/deleteEditTunjangan?id_tk="+value,
                        datatype: "json",
                        success: function(res){
                            if(res == "sukses"){
                                row.remove()
                                x--;
                            }
                        }
                    })
                    $(this).closest('.row').remove()
                    x--;
                } else{
                    $(this).closest('.row').remove()
                    x--;
                }
            }
        })

        $("#gj_pokok").keyup(function(){
            var value = $(this).val()
            $(this).val(formatRupiah(value))
        })
        $("#gj_penyesuaian").keyup(function(){
            var value = $(this).val()
            $(this).val(formatRupiah(value))
        })
        $("#tj").on("keyup", "#nominal", function(){
            var value = $(this).val()
            $(this).val(formatRupiah(value))
        })

        $('#kantor').change(function(e) {
            const office = $(this).val();
            $('#bagians-section').empty();

            if(office == 1) {
                $.ajax({
                    url: '/getdivisi',
                    dataType: 'JSON',
                    success: (res) => fillDivision(res)
                });
            }

            if(office == 2) {
                $.ajax({
                    url: '/getcabang',
                    dataType: 'JSON',
                    success: (res) => {
                        fillBranches(res[0]);
                        fillBagian(res[1]);
                    }
                })
            }
        });

        $('#jabatan_baru').change(function(e) {
            const kantor = $('#kantor');
            const value = $(this).val();

            kantor.attr('disabled', false);

            if(value == 'PIMDIV') {
                kantor.val("1").change();
                kantor.attr('disabled', true);
            }

            if(value == "PSD") {
                kantor.val("1").change();
                kantor.attr("disabled", true);
            }

            if(value == "PC" || value == "PBP") {
                kantor.val("2").change();
                kantor.attr("disabled", true);
            }
        });
    </script>
@endsection
