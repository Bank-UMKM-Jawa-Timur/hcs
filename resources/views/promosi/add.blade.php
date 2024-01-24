@extends('layouts.app-template')
@include('vendor.select2')
@section('content')
{{-- <div class="card-header">
    <div class="card-header">
        <h5 class="card-title font-weight-bold">Tambah Promosi</h5>
        <p class="card-title"><a href="">Manajemen Karyawan</a> > <a href="">Pergerakan Karir</a> > <a
                href="{{ route('promosi.index') }}">Promosi</a> > Tambah</p>
    </div>
</div> --}}
<div class="head mt-5">
    <div class="flex gap-5 justify-between items-center">
        <div class="heading">
            <h2 class="text-2xl font-bold tracking-tighter">Tambah Promosi</h2>
            <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">Manajemen Karyawan</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="#" class="text-sm text-gray-500 font-bold">Pergerakan Karir</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('promosi.index') }}" class="text-sm text-gray-500 font-bold">Promosi</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <p class="text-sm text-gray-500 font-bold">Tambah</p>
            </div>
        </div>
    </div>
</div>

<div class="body-pages">
    <div class="table-wrapping">
        <form action="{{ route('promosi.store') }}" method="POST" enctype="multipart/form-data" name="divis"
            class="form-group">
            @csrf
            <input type="hidden" name="kd_entity" value="">
            <div class="grid lg:grid-cols-3 grid-cols-1 gap-5 mt-5">
                <div class="input-box">
                    <label for="">Karyawan:</label>
                    <select name="nip" id="nip" class="form-input"></select>
                </div>
                <div class="input-box">
                    <label for="">Status Jabatan</label>
                    <input type="text" id="status_jabatan" placeholder="Status Jabatan" class="form-input-disabled" disabled>
                </div>
                <div class="input-box">
                    <label for="">Pangkat dan Golongan Sekarang</label>
                    <input type="text" id="panggol" class="form-input-disabled" placeholder="Pangkat dan Golongan Sekarang" disabled>
                    <input type="hidden" id="panggol_lama" name="panggol_lama" class="form-input">
                </div>
            </div>
            <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mt-5">
                <input type="hidden" id="bagian_lama" name="bagian_lama">
                <input type="hidden" id="status_jabatan_lama" name="status_jabatan_lama">
                <div class="input-box">
                    <label for="">Jabatan Sekarang</label>
                    <input type="text" class="form-input-disabled" placeholder="Jabatan Sekarang" disabled name="" id="jabatan_lama">
                    <input type="hidden" id="id_jabatan_lama" name="id_jabatan_lama">
                </div>
                <div class="input-box">
                    <label for="">Kantor Sekarang</label>
                    <input type="text" class="form-input-disabled" disabled name="" placeholder="Kantor Sekarang" id="kantor_lama">
                </div>
                <div class="" id="">
                </div>
            </div>
            <hr>
            <h6 class="mt-5 font-bold text-lg">Pembaruan NIP</h6>
            <div class="row grid lg:grid-cols-2 grid-cols-1 gap-5 mt-5">
                <div class="input-box">
                    <label for="NIP Baru">
                        NIP Baru
                    </label>
                    <input type="text" class="form-input" name="nip_baru" placeholder="Pembaruan NIP" id="nip_baru">
                </div>
            </div>
            <hr>
            <h6 class="mt-5 text-lg font-bold">Pembaruan Data Jabatan</h6>
            <div class="row grid lg:grid-cols-2 grid-cols-1 gap-5 mt-5">
                <div class="input-box">
                    <label for="">Status Jabatan</label>
                    <select name="status_jabatan" id=""
                        class="@error('status_jabatan') is-invalid @enderror form-input">
                        <option value="">--- Pilih ---</option>
                        <option value="Definitif">Definitif</option>
                        <option value="Penjabat">Penjabat</option>
                    </select>
                </div>
                <div class="input-box">
                    <label for="">Pangkat dan Golongan</label>
                    <select name="panggol" id="" class="@error('status_jabatan') is-invalid @enderror form-input">
                        <option value="">--- Pilih ---</option>
                        @foreach ($panggol as $item)
                        <option value="{{ $item->golongan }}">{{ $item->golongan }} - {{ $item->pangkat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row grid lg:grid-cols-2 grid-cols-1 gap-5 mt-5">
                <div class="input-box">
                    <label for="">Jabatan Baru</label>
                    <select name="id_jabatan_baru" id="jabatan_baru"
                        class="@error('id_jabatan_baru') @enderror form-input">
                        <option value="-">--- Pilih ---</option>
                        @foreach ($jabatan as $item)
                        <option {{ old('id_jabatan_baru')==$item->kd_jabatan ? 'selected' : '-' }} value="{{
                            $item->kd_jabatan }}">{{ $item->kd_jabatan }} - {{ $item->nama_jabatan }}</option>
                        @endforeach
                    </select>
                    @error('id_jabatan_baru')
                    <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="input-box">
                    <label for="kantor">Kantor</label>
                    <select name="kantor" id="kantor" class="@error('kantor') @enderror form-input" disabled>
                        <option value="-">--- Pilih Kantor ---</option>
                        <option @selected(old('kantor')=='1' ) value="1">Kantor Pusat</option>
                        <option @selected(old('kantor')=='2' ) value="2">Kantor Cabang</option>
                    </select>
                    @error('kantor')
                    <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="input-box" id="divisions-section"></div>
                <div class="input-box" id="subdivs-section"></div>
                <div class="input-box" id="bagians-section"></div>
                <div class="input-box" id="branches-section"></div>
            </div>
            <hr>
            <h6 class="mt-5 font-bold text-lg">Pembaruan Data Gaji</h6>
            <div class="row grid lg:grid-cols-2 grid-cols-1 gap-5 mt-5">
                <div class="input-box">
                    <label for="gj_pokok">Gaji Pokok</label>
                    <input class="form-input" type="text" placeholder="Gaji Pokok" name="gj_pokok" id="gj_pokok" disabled>
                </div>
                <div class="input-box">
                    <label for="gj_penyesuaian">Gaji Penyesuaian</label>
                    <input class="form-input" type="text" placeholder="Gaji Penyesuian" name="gj_penyesuaian" id="gj_penyesuaian" disabled>
                </div>
            </div>
            <hr>
            <h6 class="mt-5 font-bold text-lg">Pembaruan Data Tunjangan</h6>
            <div class="mt-5" id="tj">
                <div class="row grid lg:grid-cols-3 grid-cols-1 gap-5 mt-5">
                    <div class="input-box">
                        <label for="is">Tunjangan</label>
                        <select name="tunjangan[]" id="row_tunjangan" class="form-input">
                            <option value="">--- Pilih ---</option>
                            @foreach ($tunjangan as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_tunjangan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-box">
                        <label for="is_nama">Nominal</label>
                        <input type="text" id="nominal" name="nominal_tunjangan[]" class="form-input">
                    </div>
                    <div class="input-box">
                        <div class="flex mt-8 gap-10">
                            <button class="btn btn-primary" type="button" id="btn-add">
                                <i class="ti ti-plus"></i>
                            </button>
                            <button class="btn btn-primary-light" type="button" id="btn-delete">
                                <i class="ti ti-minus"></i>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="id_tk[]" id="id_tk" value="">
                    <input type="hidden" name="nominal_lama[]" id="nominal_lama" value="0">
                </div>
            </div>
            <hr>
            <h6 class="mt-5 font-bold text-lg">Pengesahan</h6>
            <div class="row grid lg:grid-cols-2 grid-cols-1 gap-5 mt-5">
                <div class="input-box">
                    <label for="">Tanggal Pengesahan</label>
                    <input type="date" class="form-input"
                        name="@error('tanggal_pengesahan') @enderror tanggal_pengesahan" id=""
                        value="{{ old('tanggal_pengesahan') }}">
                    @error('tanggal_pengesahan')
                    <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="input-box">
                    <label for="">Surat Keputusan</label>
                    <input type="text" class="@error('bukti_sk') @enderror form-input" name="bukti_sk"
                        id="inputGroupFile01" value="{{ old('bukti_sk') }}" placeholder="Surat Keputusan">
                    @error('bukti_sk')
                    <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="input-box">
                    <label for="file_sk">Dokumen SK <span class="text-red-500 text-sm">*(Pdf)</span></label>
                    <div class="custom-file col-md-12">
                        <input type="file" name="file_sk" class="form-input" id="validatedCustomFile" accept=".pdf">
                        {{-- <label class="custom-file-label overflow-hidden" for="validatedCustomFile">Choose
                            file(.pdf)
                            ...</label> --}}
                    </div>
                    @error('file_sk')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="input-box">
                <label for="">Keterangan Jabatan</label>
                <textarea name="ket_jabatan" class="form-input" id="ket_jabatan" placeholder="Keterangan Jabatan"></textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-5">Simpan</button>
        </form>
    </div>
</div>
@endsection

@push('script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $("#validatedCustomFile").on('change', function(e){
        var ext = this.value.match(/\.([^\.]+)$/)[1];
        if(ext != 'pdf'){
            Swal.fire({
                title: 'Terjadi Kesalahan.',
                text: 'File harus PDF',
                icon: 'error'
            })
        }
    })

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
    placeholder: "Pilih Karyawan",
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
                    url: `{{ route('get_subdivisi') }}?divisiID=${this.value}`,
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
                    <label for="sub_divisi">Sub divisi</label>
                    <select name="kd_subdiv" id="sub_divisi" class="form-input">
                        <option value="">--- Pilih sub divisi ---</option>
                    </select>`
            );

            $('#sub_divisi').empty();
            $('#sub_divisi').append('<option value="">--- Pilih sub divisi ---</option>')
            $.each(data, function(i, item){
                $('#sub_divisi').append('<option value="'+item.kd_subdiv+'">'+item.nama_subdivisi+'</option>')
            })

            if(skips.includes(jabVal)) return;
            $('#sub_divisi').change(function(e) {
                $.ajax({
                    url: `{{ route('getBagian') }}?kd_entitas=${this.value}`,
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
                    <label for="kd_bagian">Bagian</label>
                    <select name="kd_bagian" id="kd_bagian" class="form-input">
                        <option value="">--- Pilih ---</option>
                    </select>`
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
                        <label for="kd_cabang">Cabang</label>
                        <select name="kd_cabang" id="cabang" class="form-input">
                            <option value="">--- Pilih Cabang ---</option>
                        </select>`
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
                url: "{{ route('getDataKaryawan') }}",
                data: {nip},
                dataType: 'JSON',
                success: (data) => {
                    if(!data.success) return;
                    var jabatan = ""
                    var bag = ""
                    if(data.karyawan.entitas.type == 1){
                        $('#kantor_lama').val("Pusat");
                        if(data.karyawan.entitas.subDiv != null){
                            jabatan = " " + data.karyawan.entitas.subDiv.nama_subdivisi
                        }
                        if(data.karyawan.entitas.div != null && data.karyawan.entitas.subDiv == null){
                            jabatan = " " + data.karyawan.entitas.div.nama_divisi
                        }
                    } else if(data.karyawan.entitas.type == 2){
                        jabatan =  " " + data.karyawan.entitas.cab.nama_cabang
                        $('#kantor_lama').val(data.karyawan.kd_entitas + " - Cab. " + data.karyawan.entitas.cab.nama_cabang);
                    }

                    if(data.karyawan.bagian != null){
                        jabatan = ""
                        bag = " Bagian " + data.karyawan.bagian.nama_bagian
                    } else {
                        bag = ""
                    }

                    $('input[name=kd_entity]').val(data.karyawan.kd_entitas);
                    $('#jabatan_lama').val(data.karyawan.jabatan.nama_jabatan + jabatan + bag || '');
                    $('#id_jabatan_lama').val(data.karyawan.jabatan.kd_jabatan);
                    $("#status_jabatan").val(data.karyawan.status_jabatan.length > 0 ? data.karyawan.status_jabatan : '-')
                    $("#bagian_lama").val(data.karyawan.kd_bagian)
                    $("#panggol").val(data.karyawan.panggol != null ? data.karyawan.panggol.golongan + " - " + data.karyawan.panggol.pangkat : '-')
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
                    if(res.data_tj.length > 0){
                        $.each(res.data_tj, function(i, item){
                            var idTj = item.id_tunjangan
                            $("#tj").append(`
                                <div class="row grid lg:grid-cols-3 grid-cols-1 gap-5 mt-5">
                                    <div class="input-box">
                                        <label for="is">Tunjangan</label>
                                        <select name="tunjangan[]" iid="tunjangan`+i+`" class="form-input">
                                            <option value="">--- Pilih ---</option>
                                            @foreach ($tunjangan as $item)
                                            <option value="{{ $item->id }}" ${ idTj === {{ $item->id }} ? 'selected' : '' }>{{ $item->nama_tunjangan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="input-box">
                                        <label for="is_nama">Nominal</label>
                                        <input type="text" id="nominal" name="nominal_tunjangan[]" class="form-input" value="`+formatRupiah(item.nominal.toString())+`">
                                    </div>
                                    <div class="input-box">
                                        <div class="flex mt-8 gap-10">
                                            <button class="btn btn-primary" type="button" id="btn-add">
                                                <i class="ti ti-plus"></i>
                                            </button>
                                            <button class="btn btn-primary-light" type="button" id="btn-delete">
                                                <i class="ti ti-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id_tk[]" id="id_tk" value="`+item.id+`">
                                    <input type="hidden" name="nominal_lama[]" id="nominal_lama" value="`+item.nominal+`">
                                </div>
                            `);
                            setTunjangan(i, item.id_tunjangan)
                        })
                    } else{
                        $('#tj').append(`
                        <div class="row grid lg:grid-cols-3 grid-cols-1 gap-5 mt-5">
                            <div class="input-box">
                                <label for="is">Tunjangan</label>
                                <select name="tunjangan[]" id="row_tunjangan" class="form-input">
                                    <option value="">--- Pilih ---</option>
                                    @foreach ($tunjangan as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_tunjangan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-box">
                                <label for="is_nama">Nominal</label>
                                <input type="text" id="nominal" name="nominal_tunjangan[]" class="form-input">
                            </div>
                            <div class="input-box">
                                <div class="flex mt-8 gap-10">
                                    <button class="btn btn-primary" type="button" id="btn-add">
                                        <i class="ti ti-plus"></i>
                                    </button>
                                    <button class="btn btn-primary-light" type="button" id="btn-delete">
                                        <i class="ti ti-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" name="id_tk[]" id="id_tk" value="">
                            <input type="hidden" name="nominal_lama[]" id="nominal_lama" value="0">
                        </div>
                        `);
                    }
                }
            })
        });
        $('#tj').on('click', "#btn-add", function(){
            $('#tj').append(`
                <div class="row grid lg:grid-cols-3 grid-cols-1 gap-5 mt-5">
                    <div class="input-box">
                        <label for="is">Tunjangan</label>
                        <select name="tunjangan[]" id="row_tunjangan" class="form-input">
                            <option value="">--- Pilih ---</option>
                            @foreach ($tunjangan as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_tunjangan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-box">
                        <label for="is_nama">Nominal</label>
                        <input type="text" id="nominal" name="nominal_tunjangan[]" class="form-input">
                    </div>
                    <div class="input-box">
                        <div class="flex mt-8 gap-10">
                            <button class="btn btn-primary" type="button" id="btn-add">
                                <i class="ti ti-plus"></i>
                            </button>
                            <button class="btn btn-primary-light" type="button" id="btn-delete">
                                <i class="ti ti-minus"></i>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="id_tk[]" id="id_tk" value="">
                    <input type="hidden" name="nominal_lama[]" id="nominal_lama" value="0">
                </div>
            `);
            x++
        });
        $('#tj').on('click', "#btn-delete", function(){
            var row = $(this).closest('.grid')
            var value = row.children('#id_tk').val()
            console.log(value);
            if(x > 1){
                if(value != null){
                    $.ajax({
                        type: "GET",
                        url: "{{ route('deleteEditTunjangan') }}?id_tk="+value,
                        datatype: "json",
                        success: function(res){
                            if(res == "sukses"){
                                row.remove()
                                x--;
                            }
                        }
                    })
                    $(this).closest('.grid').remove()
                    x--;
                } else{
                    $(this).closest('.grid').remove()
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
                    url: "{{ route('get_divisi') }}",
                    dataType: 'JSON',
                    success: (res) => fillDivision(res)
                });
            }

            if(office == 2) {
                $.ajax({
                    url: "{{ route('get_cabang') }}",
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

            if(value == "DIRUT" || value == "DIRHAN" || value == "DIRPEM" || value == "DIRUMK") {
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
