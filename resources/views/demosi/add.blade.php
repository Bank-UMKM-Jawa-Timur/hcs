@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Tambah Demosi</h5>
            <p class="card-title"><a href="/">Dashboard</a> > <a href="/demosi">Demosi</a> > Tambah</p>
        </div>
    </div>

    <div class="card-body ml-3 mr-3">
        <form action="{{ route('demosi.store') }}" method="POST" enctype="multipart/form-data" name="divisi" class="form-group">
            @csrf
            <input type="hidden" name="kd_entity" value="">
            <div class="row">
                <div class="col-lg-12">
                    <h6>Karyawan</h6>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">NIP</label>
                        <input type="text" name="nip" id="karyawan" class="@error('nip') is-invalid @enderror form-control" value="{{ old('nip') }}">
                        @error('nip')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_karyawan">Nama Karyawan</label>
                        <input type="text" name="nama" id="nama_karyawan" disabled class="form-control">
                    </div>
                </div>
                <div class="" id="">

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Jabatan Lama</label>
                        <input type="text" class="form-control" disabled name="" id="jabatan_lama">
                        <input type="hidden" id="id_jabatan_lama" name="id_jabatan_lama">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Kantor Lama</label>
                        <input type="text" class="form-control" disabled name="" id="kantor_lama">
                    </div>
                </div>
            </div>
            <hr>
            <div class="row align-content-center">
                <div class="col-lg-12">
                    <h6>Pembaruan Data</h6>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Jabatan Baru</label>
                        <select name="id_jabatan_baru" id="jabatan_baru" class="@error('id_jabatan_baru') @enderror form-control">
                            <option value="-">--- Pilih ---</option>
                            @foreach ($data_jabatan as $item)
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
            <div class="row align-content-center justify-content-center">
                <div class="col-lg-12">
                    <h6>Pengesahan</h6>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Tanggal Pengesahan</label>
                        <input type="date" class="@error('tanggal_pengesahan') @enderror form-control" name="tanggal_pengesahan" id="" value="{{ old('tanggal_pengesahan') }}">
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
                <div class= "col-md-12">
                    <div class="form-floating form-group">
                        <label for="">Keterangan Jabatan</label>
                        <textarea class="@error('keterangan') @enderror form-control" name="keterangan" placeholder="Keterangan" id="floatingTextarea">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
                <button type="submit" class="btn btn-info">Simpan</button>
            </div>
        </form>
    </div>
@endsection

@section('custom_script')
<script>
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

    $('#karyawan').change(function(e) {
        const nip = $(this).val();

        $.ajax({
            url: '/getdatakaryawan',
            data: {nip},
            dataType: 'JSON',
            success: (data) => {
                if(!data.success) return;

                $('input[name=kd_entity]').val(data.karyawan.kd_entitas);
                $('#nama_karyawan').val(data.karyawan.nama_karyawan);
                $('#jabatan_lama').val(data.karyawan.jabatan.nama_jabatan || '');
                $('#kantor_lama').val(data.karyawan.kd_entitas);
                $('#id_jabatan_lama').val(data.karyawan.jabatan.kd_jabatan);
            }
        });
    });

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
