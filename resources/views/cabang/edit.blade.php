@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title font-weight-bold">Edit Kantor Cabang</h5>
            <p class="card-title"><a href="">Setting </a> > <a href="">Master</a> > <a href="{{ route('cabang.index') }}">Kantor Cabang</a> > <a>Edit</a></p>
        </div>
    </div>
    <div class="card-body ml-3 mr-3">
        <div class="row">
            <div class="col">
                <form action="{{ route('cabang.update', $data->kd_cabang) }}" method="POST" enctype="multipart/form-data" name="cabang" class="form-group">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_profil" value="{{$data?->id_profil ?? null}}">
                    <label for="kode_cabang">
                        Kode Kantor Cabang<span class="text-danger">*</span>
                    </label>
                    <input type="text" class="@error('kode_cabang') is_invalid @enderror form-control" value="{{ old('kode_cabang', $data->kd_cabang) }}" name="kode_cabang" id="kode_cabang" required>
                    @error('kode_cabang')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror

                    <label for="nama_cabang" class="mt-2">
                        Nama Kantor Cabang<span class="text-danger">*</span>
                    </label>
                    <input type="text" class="@error('nama_cabang') is_invalid @enderror form-control" value="{{ old('nama_cabang', $data->nama_cabang) }}" name="nama_cabang" id="nama_cabang" required>
                    @error('nama_cabang')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                    
                    <label for="alamat_cabang" class="mt-2">
                        Alamat Kantor Cabang<span class="text-danger">*</span>
                    </label>
                    <textarea name="alamat_cabang" id="alamat_cabang" class="@error('alamat_cabang') is_invalid @enderror form-control" required>{{ old('alamat_cabang', $data->alamat_cabang ) }}</textarea>
                    @error('alamat_cabang')
                        <div class="mt-2 alert alert-danger">{{ $message }}</div>
                    @enderror

                    <label for="masa_pajak" class="mt-2">Masa Pajak</label>
                        <input type="text" class="@error('masa_pajak') is_invalid @enderror form-control" value="{{ old('masa_pajak', $data->masa_pajak) }}" name="masa_pajak" id="masa_pajak">
                        @error('masa_pajak')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror

                        <label for="tanggal_lapor" class="mt-2">Tanggal Lapor</label>
                        <input type="date" class="@error('tanggal_lapor') is_invalid @enderror form-control" value="{{ old('tanggal_lapor', $data->tanggal_lapor) }}" name="tanggal_lapor" id="tanggal_lapor">
                        @error('tanggal_lapor')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                        
                        <label for="npwp_pemotong" class="mt-2">
                            NPWP Perusahaan Pemotong<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="@error('npwp_pemotong') is_invalid @enderror form-control" value="{{ old('npwp_pemotong', $data->npwp_pemotong) }}" name="npwp_pemotong" id="npwp_pemotong" required>
                        @error('npwp_pemotong')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror

                        <label for="nama_pemotong" class="mt-2">
                            Nama Perusahaan Pemotong<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="@error('nama_pemotong') is_invalid @enderror form-control" value="{{ old('nama_pemotong', $data->nama_pemotong) }}" name="nama_pemotong" id="nama_pemotong" required>
                        @error('nama_pemotong')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror

                        <label for="telp" class="mt-2">Telepon</label>
                        <input type="text" class="@error('telp') is_invalid @enderror form-control" value="{{ old('telp', $data->telp) }}" name="telp" id="telp">
                        @error('telp')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror

                        <label for="email" class="mt-2">
                            Email<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="@error('email') is_invalid @enderror form-control" value="{{ old('email', $data->email) }}" name="email" id="email" required>
                        @error('email')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror

                        <label for="npwp_pemimpin_cabang" class="mt-2">
                            NPWP Pemotong<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="@error('npwp_pemimpin_cabang') is_invalid @enderror form-control" value="{{ old('npwp_pemimpin_cabang', $data->npwp_pemimpin_cabang) }}" name="npwp_pemimpin_cabang" id="npwp_pemimpin_cabang" required>
                        @error('npwp_pemimpin_cabang')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror

                        <label for="nama_pemimpin_cabang" class="mt-2">
                            Nama Pemotong<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="@error('nama_pemimpin_cabang') is_invalid @enderror form-control" value="{{ old('nama_pemimpin_cabang', $data->nama_pemimpin_cabang) }}" name="nama_pemimpin_cabang" id="nama_pemimpin_cabang" required>
                        @error('nama_pemimpin_cabang')
                            <div class="mt-2 alert alert-danger">{{ $message }}</div>
                        @enderror
                        <div class="accordion mt-3" id="accordionExample">
                            <div class="card p-2 shadow">
                              <div class="card-header" id="headingOne">
                                <h6 class="ml-3" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <a class="text-decoration-none" href="" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Penambahan Bruto</a>
                                </h6>
                              </div>
                              <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="row m-0 pb-3 col-md-12">
                                        <div class="col p-2">
                                            <label for="jkk" class="mt-2">
                                                JKK(%)<span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="@error('jkk') is-invalid @enderror only-angka form-control" name="jkk" id="jkk"
                                                value="{{ old('jkk', $data?->penambah->jkk ?? null) }}" onkeyup="hitungTotal()" required maxlength="5">
                                            @error('jkk')
                                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                                            @enderror
                        
                                            <label for="jht" class="mt-2">
                                                JHT(%)<span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="@error('jht') is-invalid @enderror only-angka form-control" name="jht" id="jht" value="{{ old('jht', $data?->penambah->jht ?? null) }}" required maxlength="5">
                                            @error('jht')
                                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                                            @enderror
                        
                                            <label for="jkm" class="mt-2">
                                                JKM(%)<span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="@error('jkm') is-invalid @enderror only-angka form-control" name="jkm" id="jkm"
                                                value="{{ old('jkm', $data?->penambah->jkm ?? null) }}" onkeyup="hitungTotal()" required maxlength="5">
                                            @error('jkm')
                                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                                            @enderror
                        
                                            <label for="kesehatan" class="mt-2">
                                                Kesehatan(%)<span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="@error('kesehatan') is-invalid @enderror only-angka form-control" name="kesehatan" id="kesehatan"
                                                value="{{ old('kesehatan', $data?->penambah->kesehatan ?? null) }}" onkeyup="hitungTotal()" required maxlength="5">
                                            @error('kesehatan')
                                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                                            @enderror
                        
                                            <label for="kesehatan_batas_atas" class="mt-2">
                                                Batas Atas(Rp)<span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="@error('kesehatan_batas_atas') is-invalid @enderror rupiah form-control" name="kesehatan_batas_atas" id="kesehatan_batas_atas" value="{{ old('kesehatan_batas_atas', $data?->penambah->kesehatan_batas_atas == null ? null : number_format($data?->penambah->kesehatan_batas_atas, 0, '.', '.')) }}" required maxlength="10">
                                            @error('kesehatan_batas_atas')
                                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                                            @enderror
                        
                                            <label for="kesehatan_batas_bawah" class="mt-2">
                                                Batas Bawah(Rp)<span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="@error('kesehatan_batas_bawah') is-invalid @enderror rupiah form-control" name="kesehatan_batas_bawah" id="kesehatan_batas_bawah" value="{{ old('kesehatan_batas_bawah', $data?->penambah->kesehatan_batas_bawah == null ? null : number_format($data?->penambah->kesehatan_batas_bawah, 0, '.', '.')) }}" required maxlength="10">
                                            @error('kesehatan_batas_bawah')
                                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                                            @enderror
                        
                                            <label for="jp" class="mt-2">
                                                JP(%)<span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="@error('jp') is-invalid @enderror only-angka form-control" name="jp" id="jp"
                                                value="{{ old('jp', $data?->penambah->jp ?? null) }}" onkeyup="hitungTotal()" required maxlength="5">
                                            @error('jp')
                                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                                            @enderror
                        
                                            <label for="total" class="mt-2">
                                                Total(%)<span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="@error('total') is-invalid @enderror form-control" name="total" id="total"
                                                value="{{ old('total', $data->penambah != null ? floatVal($data?->penambah->jkk) + floatVal($data?->penambah->jht) + floatVal($data?->penambah->jkm) + floatVal($data?->penambah->kesehatan) + floatVal($data?->penambah->jp) : 0) }}" onkeyup="hitungTotal()" readonly>
                                            @error('total')
                                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                              </div>
                            </div>
                            <div class="card p-2 shadow">
                              <div class="card-header" id="headingTwo">
                                <h6 class="ml-3" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <a class="text-decoration-none" href="" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Pengurangan Bruto</a>
                                </h6>
                              </div>
                          
                              <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="row m-0 pb-3 col-md-12">
                                        <div class="col p-2">
                                            <label for="dpp" class="mt-2">
                                                DPP(%)<span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="@error('dpp') is-invalid @enderror only-angka form-control" name="dpp" id="dpp"
                                                value="{{ old('dpp', $data?->pengurang->dpp ?? null) }}" onkeyup="hitungTotal()" required maxlength="5">
                                            @error('dpp')
                                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                                            @enderror

                                            <label for="jp" class="mt-2">
                                                JP(%)<span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="@error('jp') is-invalid @enderror only-angka form-control" name="jp" id="jp" value="{{ old('jp', $data?->pengurang->jp ?? null) }}" required maxlength="5">
                                            @error('jp')
                                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                                            @enderror

                                            <label for="jp_jan_feb" class="mt-2">
                                                Januari - Februari(Rp)<span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="@error('jp_jan_feb') is-invalid @enderror rupiah form-control" name="jp_jan_feb" id="jp_jan_feb" value="{{ old('jp_jan_feb', $data?->pengurang->jp_jan_feb != null ? number_format($data?->pengurang->jp_jan_feb, 0, '.', '.') : null) }}" required maxlength="10">
                                            @error('jp_jan_feb')
                                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                                            @enderror

                                            <label for="jp_mar_des" class="mt-2">
                                                Maret - Desember(Rp)<span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="@error('jp_mar_des') is-invalid @enderror rupiah form-control" name="jp_mar_des" id="jp_mar_des" value="{{ old('jp_mar_des', $data?->pengurang->jp_mar_des != null ? number_format($data?->pengurang->jp_mar_des, 0, '.', '.') : null) }}" required maxlength="10">
                                            @error('jp_mar_des')
                                                <div class="mt-2 alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                              </div>
                            </div>
                        </div>
                   <div class="pt-3 pb-3">
                    <button class="is-btn is-primary">Simpan</button>
                   </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            hitungTotal();
        })
        function hitungTotal() {
            console.log('hitung total')
            const jkk = !$('#jkk').val() ? 0.00 : parseFloat($('#jkk').val())
            const jht = !$('#jht').val() ? 0.00 : parseFloat($('#jht').val())
            const jkm = !$('#jkm').val() ? 0.00 : parseFloat($('#jkm').val())
            const kesehatan = !$('#kesehatan').val() ? 0.00 : parseFloat($('#kesehatan').val())
            const jp = !$('#jp').val() ? 0.00 : parseFloat($('#jp').val())
            const total = jkk + jht + jkm + kesehatan + jp
            $("#total").val(total)
        }

        $('.rupiah').keyup(function(){
            var angka = $(this).val();
            $(this).val(formatRupiah(angka));
        })

        $(document).ready(function() {
                // Selector untuk input dengan kelas "angka-saja"
            var inputElement = $('.only-angka');

            // Tambahkan event listener untuk memfilter input
            inputElement.on('input', function() {
                // Hapus semua karakter non-digit
                var sanitizedValue = $(this).val().replace(/[^\d.]/g, '');
                // Perbarui nilai input dengan angka yang telah difilter
                $(this).val(sanitizedValue);
            });
        });
    </script>
@endpush