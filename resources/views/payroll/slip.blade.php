@extends('layouts.template')
@include('vendor.select2')
@include('payroll.scripts.slip')
@push('style')
    <style>
        table th {
            border: 1px solid #e3e3e3 !important;
        }
    </style>
@endpush

@section('content')
    <div class="card-header">
        <h5 class="card-title">Payroll</h5>
        <p class="card-title">Payroll > <a href="{{route('payroll.slip')}}">Slip Gaji</a></p>
    </div>

    <div class="card-body">
        <div class="col">
            <div class="row">
                <div class="table-responsive overflow-hidden content-center">
                    <form id="form" method="get">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Kantor<span class="text-danger">*</span></label>
                                    <select name="kantor" id="kantor"
                                        class="form-control">
                                        <option value="0">-- Pilih kantor --</option>
                                        <option value="pusat" @if(\Request::get('kantor') == 'pusat') selected @endif
                                            {{old('kantor') == 'pusat' ? 'selected' : ''}}>Pusat</option>
                                        <option value="cabang" @if(\Request::get('kantor') != '' && \Request::get('kantor') != 'pusat') selected @endif
                                            {{old('kantor') == 'cabang' ? 'selected' : ''}}>Cabang</option>
                                    </select>
                                    @error('kantor')
                                        <small class="text-danger">{{ucfirst($message)}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col cabang-input @if(\Request::get('kantor') == 'pusat' || \Request::get('kantor') == '0')d-none @endif">
                                <div class="form-group">
                                    <label for="">Cabang<span class="text-danger">*</span></label>
                                    <select name="cabang" id="cabang"
                                        class="form-control select2">
                                        <option value="0">-- Semua Cabang --</option>
                                        @foreach ($cabang as $item)
                                            <option value="{{$item->kd_cabang}}" @if(\Request::get('cabang') == $item->kd_cabang) selected @endif>{{$item->nama_cabang}}</option>
                                        @endforeach
                                    </select>
                                    @error('cabang')
                                        <small class="text-danger">{{ucfirst($message)}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col divisi-input @if(\Request::get('kantor') != 'pusat')d-none @endif">
                                <div class="form-group">
                                    <label for="">Divisi<span class="text-danger">*</span></label>
                                    <select name="divisi" id="divisi"
                                        class="form-control select2">
                                    </select>
                                    @error('divisi')
                                        <small class="text-danger">{{ucfirst($message)}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col sub-divisi-input @if(\Request::get('kantor') != 'pusat')d-none @endif">
                                <div class="form-group">
                                    <label for="">Sub Divisi<span class="text-danger">*</span></label>
                                    <select name="sub_divisi" id="sub_divisi"
                                        class="form-control select2">
                                        <option value="0">-- Semua Sub Divisi --</option>
                                    </select>
                                    @error('sub_divisi')
                                        <small class="text-danger">{{ucfirst($message)}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Bagian<span class="text-danger">*</span></label>
                                    <select name="bagian" id="bagian"
                                        class="form-control select2">
                                        <option value="0">-- Semua Bagian --</option>
                                    </select>
                                    @error('bagian')
                                        <small class="text-danger">{{ucfirst($message)}}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Karyawan<span class="text-danger">*</span></label>
                                    <select name="nip" id="nip"
                                        class="form-control select2">
                                        <option value="0">-- Pilih Semua Karyawan --</option>
                                    </select>
                                    @error('nip')
                                        <small class="text-danger">{{ucfirst($message)}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Bulan<span class="text-danger">*</span></label>
                                    <select name="bulan" id="bulan"
                                        class="form-control">
                                        <option value="0">-- Pilih bulan --</option>
                                        <option value="1" @if(\Request::get('bulan') == '1') selected @endif>Januari</option>
                                        <option value="2" @if(\Request::get('bulan') == '2') selected @endif>Februari</option>
                                        <option value="3" @if(\Request::get('bulan') == '3') selected @endif>Maret</option>
                                        <option value="4" @if(\Request::get('bulan') == '4') selected @endif>April</option>
                                        <option value="5" @if(\Request::get('bulan') == '5') selected @endif>Mei</option>
                                        <option value="6" @if(\Request::get('bulan') == '6') selected @endif>Juni</option>
                                        <option value="7" @if(\Request::get('bulan') == '7') selected @endif>Juli</option>
                                        <option value="8" @if(\Request::get('bulan') == '8') selected @endif>Agustus</option>
                                        <option value="9" @if(\Request::get('bulan') == '9') selected @endif>September</option>
                                        <option value="10" @if(\Request::get('bulan') == '10') selected @endif>Oktober</option>
                                        <option value="11" @if(\Request::get('bulan') == '11') selected @endif>November</option>
                                        <option value="12" @if(\Request::get('bulan') == '12') selected @endif>Desember</option>
                                    </select>
                                    @error('bulan')
                                        <small class="text-danger">{{ucfirst($message)}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Tahun<span class="text-danger">*</span></label>
                                    <select name="tahun" id="tahun"
                                        class="form-control">
                                        <option value="0">-- Pilih tahun --</option>
                                        @php
                                            $sekarang = date('Y');
                                            $awal = $sekarang - 5;
                                            $akhir = $sekarang + 5;
                                        @endphp
                                        @for($i=$awal;$i<=$akhir;$i++)
                                            <option value="{{$i}}" @if(\Request::get('tahun') == $i) selected @endif>{{$i}}</option>
                                        @endfor
                                    </select>
                                    @error('tahun')
                                        <small class="text-danger">{{ucfirst($message)}}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            @if (\Request::has('kantor') && !empty($data))
                                <div class="mr-2">
                                    <a href="{{ route('payroll.pdf') }}" target="_blank"  class="btn btn-warning">Cetak PDF</a>
                                </div>
                            @endif
                            <div>
                                <input type="submit" value="Tampilkan" class="btn btn-primary">
                            </div>
                        </div>
                        @if (\Request::has('kantor'))
                            <div class="d-flex justify-content-between mb-4">
                                <div class="p-2 mt-4">
                                <label for="page_length" class="mr-3 text-sm text-neutral-400">show</label>
                                <select name="page_length" id="page_length"
                                    class="border px-4 py-2 cursor-pointer rounded appearance-none text-center">
                                    <option value="10"
                                        @isset($_GET['page_length']) {{ $_GET['page_length'] == 10 ? 'selected' : '' }} @endisset>
                                        10</option>
                                    <option value="20"
                                        @isset($_GET['page_length']) {{ $_GET['page_length'] == 20 ? 'selected' : '' }} @endisset>
                                        20</option>
                                    <option value="50"
                                        @isset($_GET['page_length']) {{ $_GET['page_length'] == 50 ? 'selected' : '' }} @endisset>
                                        50</option>
                                    <option value="100"
                                        @isset($_GET['page_length']) {{ $_GET['page_length'] == 100 ? 'selected' : '' }} @endisset>
                                        100</option>
                                </select>
                                <label for="" class="ml-3 text-sm text-neutral-400">entries</label>
                                </div>
                                <div class="p-2">
                                <label for="q">Cari</label>
                                <input type="search" name="q" id="q" placeholder="Cari nama karyawan disini..."
                                    class="form-control p-2" value="{{isset($_GET['q']) ? $_GET['q'] : ''}}"
                                    style="width: 300px;">
                                </div>
                            </div>
                            @php
                                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
                                $start = $page == 1 ? 1 : ($page * $page_length - $page_length) + 1;
                                $end = $page == 1 ? $page_length : ($start + $page_length) - 1;
                            @endphp
                            <div class="table-responsive">
                                @include('payroll.tables.slip', ['data' => $data])
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>
                                    Showing {{$start}} to {{$end}} of {{$data->total()}} entries
                                </div>
                                <div>
                                    {{--  @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    {{ $data->links('pagination::bootstrap-4') }}
                                    @endif  --}}
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection