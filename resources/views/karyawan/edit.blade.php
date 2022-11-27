@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Data Karyawan</h5>
            <p class="card-title"><a href="/">Dashboard</a> / <a href="/">Karyawan</a> / <a href="/data_karyawan">Data Karyawan</a> / Tambah</p>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('karyawan.update', $data->nip) }}" method="POST" enctype="multipart/form-data" name="karyawan" class="form-group">
            @csrf
            @method('PUT')
            <div class="row m-0">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">NIP</label>
                        <input type="text" class="form-control" name="nip" id="" value="{{ $data->nip }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">NIK</label>
                        <input type="text" class="form-control" name="nik" id="" value="{{ $data->nik }}">
                    </div>
                </div>   
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Nama Karyawan</label>
                        <input type="text" class="form-control" name="nama" id="" value="{{ $data->nama_karyawan }}">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Alamat KTP</label>
                        <textarea name="alamat_ktp" id="" class="form-control">{{ $data->alamat_ktp }}</textarea>
                    </div>
                </div>    
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Alamat sekarang</label>
                        <textarea name="alamat_sek" id="" class="form-control">{{ $data->alamat_sek }}</textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Tempat Lahir</label>
                        <input type="text" class="form-control" name="tmp_lahir" id="" value="{{ $data->tmp_lahir }}">
                    </div>
                </div> 
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Tanggal Lahir</label>
                        <input type="date" class="form-control" name="tgl_lahir" id="" value="{{ $data->tgl_lahir }}">
                    </div>
                </div> 
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Agama</label>
                        <select name="agama" id="" class="form-control">
                            <option value="">--- Pilih ---</option>
                            @foreach ($agama as $item)
                                <option value="{{ $item->kd_agama }}" {{ ($data->kd_agama == $item->kd_agama) ? 'selected' : '' }}>{{ $item->agama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div> 
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Kewarganegaraan</label>
                        <input type="text" name="kewarganegaraan" id="" class="form-control" value="{{ $data->kewarganegaraan }}">
                    </div>
                </div> 
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Jenis Kelamin</label>
                        <select name="jk" id="" class="form-control">
                            <option value="">--- Pilih ---</option>
                            <option value="Laki-laki" {{ ($data->jk == 'Laki-laki') ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ ($data->jk == 'Perempuan') ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div> 
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Status pernikahan</label>
                        <select name="status pernikahan" id="status" class="form-control">
                            <option value="">--- Pilih ---</option>
                            <option value="Kawin" {{ ($data->status == 'Kawin') ? 'selected' : '' }}>Kawin</option>
                            <option value="Belum Kawin" {{ ($data->status == 'Belum Kawin') ? 'selected' : '' }}>Belum Kawin</option>
                            <option value="Janda" {{ ($data->status == 'Janda') ? 'selected' : '' }}>Janda</option>
                            <option value="Duda" {{ ($data->status == 'Duda') ? 'selected' : '' }}>Duda</option>
                        </select>
                    </div>
                </div> 
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Pasangan</label>
                        <select name="is" id="" class="form-control">
                            <option value="">--- Pilih ---</option>
                            @foreach ($is as $item)
                                <option value="{{ $item->id }}"  {{ ($data->id_is == $item->id) ? 'selected' : '' }}>{{ $item->is_nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div> 
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">KPJ</label>
                        <input type="text" class="form-control" name="kpj" value="{{ $data->kpj }}">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">JKN</label>
                        <input type="text" class="form-control" name="jkn" value="{{ $data->jkn }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Gaji Pokok</label>
                        <input type="text" class="form-control" name="gj_pokok" value="{{ $data->gj_pokok }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Gaji Penyesuaian</label>
                        <input type="text" class="form-control" name="gj_penyesuaian" value="{{ $data->gj_penyesuaian }}">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Status Karyawan</label>
                        <select name="status_karyawan" id="" class="form-control">
                            <option value="">--- Pilih ---</option>
                            <option value="Tetap" {{ ($data->status_karyawan == 'Tetap') ? 'selected' : ''}}>Tetap</option>
                            <option value="IKJP" {{ ($data->status_karyawan == 'IKJP') ? 'selected' : ''}}>IKJP</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">SK Pengangkatan</label>
                        <input type="text" class="form-control" name="skangkat" value="{{ $data->skangkat }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Tanggal Pengangkatan</label>
                        <input type="date" class="form-control" name="tanggal_pengangkat" value="{{ $data->tanggal_pengangkat }}">
                    </div>
                </div>
            </div>   
            <div class="row m-3">
                <button type="submit" class="btn btn-success">edit</button>
            </div>
        </form>
    </div>
@endsection

@section('custom_script')
    <script>
    </script>
@endsection