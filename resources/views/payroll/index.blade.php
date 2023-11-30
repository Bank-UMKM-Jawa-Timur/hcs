@extends('layouts.template')

@section('content')
    <div class="card-header">
        <h5 class="card-title">Payroll</h5>
        <p class="card-title"><a href="{{route('payroll.index')}}">Payroll</a></p>
    </div>

    <div class="card-body">
        <div class="col">
            <div class="row">
                <div class="table-responsive overflow-hidden content-center">
                    <form id="form" method="get">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Kantor</label>
                                    <select name="kantor" id="kantor"
                                        class="form-control">
                                        <option value="">-- Pilih kantor --</option>
                                        <option value="pusat">Pusat</option>
                                        <option value="cabang">Cabang</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col cabang-input d-none">
                                <div class="form-group">
                                    <label for="">Cabang</label>
                                    <select name="cabang" id="cabang"
                                        class="form-control select2">
                                        <option value="">-- Pilih cabang --</option>
                                        @foreach ($cabang as $item)
                                            <option value="{{$item->kd_cabang}}">{{$item->nama_cabang}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Bulan</label>
                                    <select name="bulan" id="bulan"
                                        class="form-control">
                                        <option value="">-- Pilih bulan --</option>
                                        <option value="1">Januari</option>
                                        <option value="2">Februari</option>
                                        <option value="3">Maret</option>
                                        <option value="4">April</option>
                                        <option value="5">Mei</option>
                                        <option value="6">Juni</option>
                                        <option value="7">Juli</option>
                                        <option value="8">Agustus</option>
                                        <option value="9">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Tahun</label>
                                    <select name="tahun" id="tahun"
                                        class="form-control">
                                        <option value="">-- Pilih tahun --</option>
                                        @php
                                            $sekarang = date('Y');
                                            $awal = $sekarang - 5;
                                            $akhir = $sekarang + 5;
                                        @endphp
                                        @for($i=$awal;$i<=$akhir;$i++)
                                            <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <div>
                                <input type="submit" value="Tampilkan" class="btn btn-primary">
                            </div>
                        </div>
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
                            <input type="search" name="q" id="q" placeholder="Cari disini..."
                              class="form-control p-2" value="{{isset($_GET['q']) ? $_GET['q'] : ''}}">
                          </div>
                        </div>
                        <table class="table whitespace-nowrap" id="table" style="width: 100%">
                            <thead class="text-primary">
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Nama karyawan</th>
                                    <th rowspan="2">Gaji</th>
                                    <th rowspan="2">No Rek</th>
                                    <th colspan="6" class="text-center">Potongan</th>
                                    <th rowspan="2">Total Potongan</th>
                                    <th rowspan="2">Total Yang Diterima</th>
                                </tr>
                                <tr>
                                    <th>JP BPJS TK 1%</th>
                                    <th>DPP 5%</th>
                                    <th>Kredit Koperasi</th>
                                    <th>Iuaran Koperasi</th>
                                    <th>Kredit Pegawai</th>
                                    <th>Iuran IK</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                    $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
                                    $start = $page == 1 ? 1 : ($page * $page_length - $page_length) + 1;
                                    $end = $page == 1 ? $page_length : ($start + $page_length) - 1;
                                    $i = $page == 1 ? 1 : $start;
                                    $footer_total_gaji = 0;
                                    $footer_bpjs_tk = 0;
                                    $footer_dpp = 0;
                                    $footer_kredit_koperasi = 0;
                                    $footer_iuran_koperasi = 0;
                                    $footer_kredit_pegawai = 0;
                                    $footer_iuran_ik = 0;
                                    $footer_total_potongan = 0;
                                    $footer_total_diterima = 0;
                                @endphp
                                @foreach ($data as $item)
                                    @php
                                        $norek = $item->no_rekening ? $item->no_rekening : '-';
                                        $total_gaji = $item->gaji ? number_format($item->gaji->total_gaji, 0, ',', '.') : 0;
                                        $dpp = $item->potongan ? number_format($item->potongan->dpp, 0, ',', '.') : 0;
                                        $bpjs_tk = $item->bpjs_tk ? number_format($item->bpjs_tk, 0, ',', '.') : 0;
                                        $kredit_koperasi = $item->potonganGaji ? number_format($item->potonganGaji->kredit_koperasi, 0, ',', '.') : 0;
                                        $iuran_koperasi = $item->potonganGaji ? number_format($item->potonganGaji->iuran_koperasi, 0, ',', '.') : 0;
                                        $kredit_pegawai = $item->potonganGaji ? number_format($item->potonganGaji->kredit_pegawai, 0, ',', '.') : 0;
                                        $iuran_ik = $item->potongan_gaji ? number_format($item->potongan_gaji->iuran_ik, 0, ',', '.') : 0;
                                        $total_potongan = $item->potonganGaji ? number_format($item->potonganGaji->total_potongan, 0, ',', '.') : 0;
                                        $total_diterima = $item->total_yg_diterima ? number_format($item->total_yg_diterima, 0, ',', '.') : 0;
                                        
                                        // count total
                                        $footer_total_gaji += str_replace('.', '', $total_gaji);
                                        $footer_bpjs_tk += str_replace('.', '', $bpjs_tk);
                                        $footer_dpp += str_replace('.', '', $dpp);
                                        $footer_kredit_koperasi += str_replace('.', '', $kredit_koperasi);
                                        $footer_iuran_koperasi += str_replace('.', '', $iuran_koperasi);
                                        $footer_kredit_pegawai += str_replace('.', '', $kredit_pegawai);
                                        $footer_iuran_ik += str_replace('.', '', $iuran_ik);
                                        $footer_total_potongan += str_replace('.', '', $total_potongan);
                                        $footer_total_diterima += str_replace('.', '', $total_diterima);
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama_karyawan }}</td>
                                        <td class="text-right">{{ $total_gaji }}</td>
                                        <td class="text-center">{{ $norek }}</td>
                                        <td class="text-right">{{ $bpjs_tk }}</td>
                                        <td class="text-right">{{ $dpp }}</td>
                                        <td class="text-right">{{ $kredit_koperasi }}</td>
                                        <td class="text-right">{{ $iuran_koperasi }}</td>
                                        <td class="text-right">{{ $kredit_pegawai }}</td>
                                        <td class="text-right">{{ $iuran_ik }}</td>
                                        <td class="text-right">{{ $total_potongan }}</td>
                                        <td class="text-right">{{ $total_diterima }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-center">Jumlah</th>
                                    <th class="text-right">{{ number_format($footer_total_gaji, 0, ',', '.') }}</th>
                                    <th></th>
                                    <th class="text-right">{{ number_format($footer_bpjs_tk, 0, ',', '.') }}</th>
                                    <th class="text-right">{{ number_format($footer_dpp, 0, ',', '.') }}</th>
                                    <th class="text-right">{{ number_format($footer_kredit_koperasi, 0, ',', '.') }}</th>
                                    <th class="text-right">{{ number_format($footer_iuran_koperasi, 0, ',', '.') }}</th>
                                    <th class="text-right">{{ number_format($footer_kredit_pegawai, 0, ',', '.') }}</th>
                                    <th class="text-right">{{ number_format($footer_iuran_ik, 0, ',', '.') }}</th>
                                    <th class="text-right">{{ number_format($footer_total_potongan, 0, ',', '.') }}</th>
                                    <th class="text-right">{{ number_format($footer_total_diterima, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                        {{--  <div class="d-flex justify-content-between">
                            <div>
                                Showing {{$start}} to {{$end}} of {{$karyawan->total()}} entries
                            </div>
                            <div>
                                @if ($karyawan instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ $karyawan->links('pagination::bootstrap-4') }}
                                @endif
                            </div>
                        </div>  --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $('#kantor').on('change', function() {
            const selected = $(this).val()

            if (selected == 'cabang') {
                $('.cabang-input').removeClass('d-none')
            }
            else {
                $('.cabang-input').addClass('d-none')
            }
        })

        $('#page_length').on('change', function() {
            $('#form').submit()
        })
        // Adjust pagination url
        var btn_pagination = $(`.pagination`).find('a')
        var page_url = window.location.href
        $(`.pagination`).find('a').each(function(i, obj) {
            if (page_url.includes('page_length')) {
                btn_pagination[i].href += `&page_length=${$('#page_length').val()}`
            }
            if (page_url.includes('q')) {
                btn_pagination[i].href += `&q=${$('#q').val()}`
            }
        })
    </script>
@endsection
