@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Detail Data Karyawan</h5>
            <p class="card-title"><a href="/">Dashboard</a> > <a href="/karyawan">Data Karyawan</a> > Detail</p>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('karyawan.show', $data->nip) }}" method="POST" enctype="multipart/form-data" name="karyawan" class="form-group">
            @csrf
            @method('PUT')
            <div class="row m-0 ">
                <div class="col-lg-12">
                    <h6>Biodata Diri Karyawan</h6>
                </div>
            </div> 
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">NIP</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $data->nip }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">NIK</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $data->nik }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Nama Karyawan</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $data->nama_karyawan }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Tempat, Tanggal Lahir</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $data->tmp_lahir }}, {{ date('d F Y', strtotime($data->tgl_lahir)) }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Agama</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $data->agama }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Jenis Kelamin</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $data->jk }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Status Pernikahan</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control"  value="{{ $data->status }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Kewarganegaraan</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control"  value="{{ $data->kewarganegaraan }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Alamat KTP</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $data->alamat_ktp }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Alamat Sekarang</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $data->alamat_sek }}">
                </div>
            </div>
            <hr>
            <div class="row m-0 ">
                <div class="col-lg-12">
                    <h6>Data Karyawan</h6>
                </div>
            </div> 
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Kantor</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $data->nama_cabang }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Jabatan</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $data->nama_jabatan }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Bagian</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $data->nama_bagian }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Status Karyawan</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $data->status_karyawan}}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Status Jabatan</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $data->status_jabatan}}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">SK Pengangkatan</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $data->skangkat}}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Tanggal Pengangkatan</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $data->tanggal_pengangkat}}">
                </div>
            </div>
            <hr>
            <div class="row m-0 ">
                <div class="col-lg-12">
                    <h6>Data Tunjangan Karyawan</h6>
                </div>
            </div> 
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-0">Kartu Peserta Jamsostek (KPJ)</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $data->kpj }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-0">Jaminan Kesehatan Nasional (JKN)</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $data->jkn }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-0">Gaji Pokok</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $data->gj_pokok }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-0">Gaji Penyesuaian</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $data->gj_penyesuaian }}">
                </div>
            </div>
            <br>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-0">Status Pasangan</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-0">Nama</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-0">Tanggal Lahir</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-0">Alamat</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-0">Pekerjaan</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-0">Jumlah Anak</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control">
                </div>
            </div>
            <div class="row m-3">
                <a href="/karyawan">
                    <button type="button" class="btn btn-info">Kembali</button>
                </a>
            </div>
        </form>
    </div>
@endsection