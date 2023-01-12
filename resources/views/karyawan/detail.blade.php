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
            @php
                $no = 1;
                function rupiah($angka){
                    $hasil_rupiah = number_format($angka, 0, ",", ".");
                    return $hasil_rupiah;
                }
                $tj = DB::table('tunjangan_karyawan')
                    ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
                    ->where('nip', $data->nip)
                    ->get();
            @endphp
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
                    @if (isset($agama->agama) != null)
                        <input type="text" disabled class="form-control" value="{{ $agama->agama }}">                        
                    @else
                        <input type="text" disabled class="form-control" value="-">
                    @endif
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
                    @if (isset($data->alamat_sek) != null || $data->alamat_sek != '')
                        <input type="text" disabled class="form-control" value="{{ $data->alamat_sek }}">
                    @else
                        <input type="text" disabled class="form-control" value="-">
                    @endif
                </div>
            </div>
            <hr>
            <div class="row m-0 ">
                <div class="col-lg-12"> 
                    <h6>No Rekening & NPWP</h6>
                </div>
            </div> 
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Nomor Rekening</label>
                <div class="col-sm-10">
                    @if (isset($data->no_rekening) != null || $data->no_rekening != '')
                        <input type="text" disabled class="form-control" value="{{ $data->no_rekening }}">
                    @else
                        <input type="text" disabled class="form-control" value="-">
                    @endif
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">NPWP</label>
                <div class="col-sm-10">
                    @if (isset($data->npwp) != null || $data->npwp != '')
                        <input type="text" disabled class="form-control" value="{{ $data->npwp }}">
                    @else
                        <input type="text" disabled class="form-control" value="-">
                    @endif
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
                    @if (isset($ent))
                        <input type="text" disabled class="form-control" value="{{ $ent->nama }}">
                    @else
                        <input type="text" disabled class="form-control" value="-">
                    @endif
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Jabatan</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $data->nama_jabatan }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Pangkat</label>
                <div class="col-sm-10">
                    @if (isset($panggol->pangkat) != null) 
                        <input type="text" disabled class="form-control" value="{{ $panggol->pangkat }}">
                    @else
                        <input type="text" disabled class="form-control" value="-">
                    @endif
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Golongan</label>
                <div class="col-sm-10">
                    @if (isset($panggol->golongan) != null) 
                        <input type="text" disabled class="form-control" value="{{ $panggol->golongan }}">
                    @else
                        <input type="text" disabled class="form-control" value="-">
                    @endif
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Bagian</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ ($bagian != null) ? $bagian->nama_bagian : '-' }}">
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
                <label class="col-sm-2 mt-2">Keterangan Jabatan</label>
                <div class="col-sm-10">
                    @if (isset($data->ket_jabatan) != null)
                        <input type="text" disabled class="form-control" value="{{ $data->ket_jabatan }}">
                    @else
                        <input type="text" disabled class="form-control" value="-">
                    @endif
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Tanggal Mulai</label>
                <div class="col-sm-10">
                    @if (isset($data->tgl_mulai) != null)
                        <input type="text" disabled class="form-control" value="{{ date('d F Y', strtotime($data->tgl_mulai)) }}">
                    @else
                        <input type="text" disabled class="form-control" value="-">
                    @endif
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">SK Pengangkatan</label>
                <div class="col-sm-10">
                    @if (isset($data->skangkat) != null)
                        <input type="text" disabled class="form-control" value="{{ $data->skangkat}}">
                    @else
                        <input type="text" disabled class="form-control" value="-">
                    @endif
                </div>
            </div>
            @php
                use Carbon\Carbon;
                $mulaKerja = Carbon::create($data->tgl_mulai);
                $waktuSekarang = Carbon::now();

                $tahunMulai = Carbon::parse($mulaKerja)->year;
                $bulanMulai = Carbon::parse($mulaKerja)->month;
                $hariMulai = Carbon::parse($mulaKerja)->day;

                $tahunSekarang = Carbon::parse($waktuSekarang)->year;
                $bulanSekarang = Carbon::parse($waktuSekarang)->month;
                $hariSekarang = Carbon::parse($waktuSekarang)->day;

                $masaKerjaThn = $tahunSekarang - $tahunMulai;
                $masaKerjaBln = $bulanSekarang - $bulanMulai;
                $masaKerjaHr = $hariSekarang - $hariMulai;

            @endphp
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Masa Kerja</label>
                <div class="col-sm-10">
                    @if (isset($data->tgl_mulai) != null)
                        <input type="text" disabled class="form-control" value="{{ $masaKerjaThn }} Tahun | {{ $masaKerjaBln }} Bulan | {{ $masaKerjaHr }} Hari">
                    @else
                        <input type="text" disabled class="form-control" value="-">
                    @endif
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
                    @if (isset($data->kpj) != null)
                        <input type="text" disabled class="form-control" value="{{ $data->kpj }}">
                    @else
                        <input type="text" disabled class="form-control" value="-">
                    @endif
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-0">Jaminan Kesehatan Nasional (JKN)</label>
                <div class="col-sm-10">
                    @if (isset($data->jkn) != null)
                        <input type="text" disabled class="form-control" value="{{ $data->jkn }}">
                    @else
                        <input type="text" disabled class="form-control" value="-">
                    @endif
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Gaji Pokok</label>
                <div class="col-sm-10">
                    @if (isset($data->gj_pokok) != null)
                        <input type="text" disabled class="form-control" value="{{ rupiah($data->gj_pokok) }}">
                    @else
                        <input type="text" disabled class="form-control" value="-">
                    @endif
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Gaji Penyesuaian</label>
                <div class="col-sm-10">
                    @if (isset($data->gj_penyesuaian) != null)
                        <input type="text" disabled class="form-control" value="{{ rupiah($data->gj_penyesuaian) }}">
                    @else
                        <input type="text" disabled class="form-control" value="-">
                    @endif
                </div>
            </div>
            <br>
                @if (isset($tj))
                    @foreach ($tj as $item)
                        <div class="row m-0 mt-2">
                            <label class="col-sm-2 mt-2">Tunjangan {{ $no++ }}</label>
                            <div class="col-sm-5">
                                <input type="text" disabled class="form-control" value="{{ $item->nama_tunjangan }}">
                            </div>
                            <div class="col-sm-5">
                                <input type="text" disabled class="form-control" value="Rp. {{ rupiah($item->nominal) }}">
                            </div>
                        </div>
                    @endforeach
                @endif
            <br>

            @if (isset($suis))    
                <hr>
                <div class="row m-0 ">
                    <div class="col-lg-12">
                        <h6>Data Pasangan</h6>
                    </div>
                </div> 
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-0">Status Pasangan</label>
                    <div class="col-sm-10">
                        @if (isset($suis) != null)
                            <input type="text" disabled class="form-control" value="{{ $suis->enum }}">
                        @else
                            <input type="text" disabled class="form-control" value="-">
                        @endif
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-0">Nama</label>
                    <div class="col-sm-10">
                        @if (isset($suis) != null)
                            <input type="text" disabled class="form-control" value="{{ $suis->is_nama }}">
                        @else
                            <input type="text" disabled class="form-control" value="-">
                        @endif
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-0">Tanggal Lahir</label>
                    <div class="col-sm-10">
                        @if (isset($suis) != null)
                            <input type="text" disabled class="form-control" value="{{ $suis->is_tgl_lahir }}">
                        @else
                            <input type="text" disabled class="form-control" value="-">
                        @endif
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-0">Alamat</label>
                    <div class="col-sm-10">
                        @if (isset($suis) != null)
                            <input type="text" disabled class="form-control" value="{{ $suis->is_alamat }}">
                        @else
                            <input type="text" disabled class="form-control" value="-">
                        @endif
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-0">Pekerjaan</label>
                    <div class="col-sm-10">
                        @if (isset($suis) != null)
                            <input type="text" disabled class="form-control" value="{{ $suis->is_pekerjaan }}">
                        @else
                            <input type="text" disabled class="form-control" value="-">
                        @endif
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-0">Jumlah Anak</label>
                    <div class="col-sm-10">
                        @if (isset($suis) != null)
                            <input type="text" disabled class="form-control" value="{{ $suis->is_jml_anak }}">
                        @else
                            <input type="text" disabled class="form-control" value="-">
                        @endif
                    </div>
                </div>
            @endif

            <div class="row m-3">
                <a href="/karyawan">
                    <button type="button" class="btn btn-info">Kembali</button>
                </a>
            </div>
        </form>
    </div>
@endsection