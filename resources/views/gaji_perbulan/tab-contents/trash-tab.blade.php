<div class="layout-component">
    <div class="shorty-table">
        <label for="">Show</label>
        <select  name="page_length_sampah" id="page_length_sampah" class="page_length">
        <option value="10"
        @isset($_GET['page_length']) {{ $_GET['page_length'] == 10 ? 'selected' : '' }} @endisset>
        10</option>
    <option value="20"
        @isset($_GET['page_length_sampah']) {{ $_GET['page_length_sampah'] == 20 ? 'selected' : '' }} @endisset>
        20</option>
    <option value="50"
        @isset($_GET['page_length_sampah']) {{ $_GET['page_length_sampah'] == 50 ? 'selected' : '' }} @endisset>
        50</option>
    <option value="100"
        @isset($_GET['page_length_sampah']) {{ $_GET['page_length_sampah'] == 100 ? 'selected' : '' }} @endisset>
        100</option>
        </select>
        <label for="">entries</label>
    </div>
    <div class="input-search">
        <i class="ti ti-search"></i>
        <input type="search" class="q-sampah" placeholder="Search" name="q_sampah" id="q" value="{{ isset($_GET['q_sampah']) ? $_GET['q_sampah'] : '' }}">
    </div>
</div>
@php
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $page_length = isset($_GET['page_length_sampah']) ? $_GET['page_length_sampah'] : 10;
    $start = $page == 1 ? 1 : ($page * $page_length - $page_length) + 1;
    $end = $page == 1 ? $page_length : ($start + $page_length) - 1;
@endphp
<table class="tables-stripped">
    <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Kategori</th>
            @if (!auth()->user()->hasRole('cabang'))
                <th rowspan="2">Kantor</th>
            @endif
            <th rowspan="2">Tahun</th>
            <th rowspan="2">Bulan</th>
            <th rowspan="2">Tanggal</th>
            <th rowspan="2">File</th>
            <th colspan="6">Total</th>
            <th rowspan="2">Aksi</th>
        </tr>
        <tr>
            <th>Bruto</th>
            <th>Potongan</th>
            <th>Netto</th>
            <th>PPh Bentukan</th>
            <th>Pajak Insentif</th>
            <th>PPh21<br>(PPh Bentukan - Pajak Insentif)</th>
        </tr>
    </thead>
    <tbody>
    @forelse ($sampah as $item)
        @php
            $total_bruto += $item->bruto;
            $total_potongan += $item->total_potongan;
            $total_netto += $item->netto;
            $total_pph += $item->total_pph;
            $total_pajak_insentif += $item->total_pajak_insentif;
            $total_hasil_pph += $item->hasil_pph;
        @endphp
        <tr>
            <td class="text-center">{{ $i++ }}</td>
            <td class="text-center">{{ $item->nama_divisi ? $item->nama_divisi : 'Pegawai' }}</td>
            @if (!auth()->user()->hasRole('cabang'))
                <td class="text-center">{{ $item->kantor }}</td>
            @endif
            <td class="text-center">{{ $item->tahun }}</td>
            <td class="text-center">{{ $months[$item->bulan] }}</td>
            <td class="text-center">{{date('d-m-Y', strtotime($item->tanggal_input))}}</td>
            <td class="text-center flex justify-center gap-5">
                <a href="#" data-modal-target="rincian-modal" data-modal-toggle="rincian-modal" class="btn btn-warning btn-rincian"
                    data-batch_id="{{$item->id}}" data-month_name="{{$months[$item->bulan]}}" data-year="{{$item->tahun}}">Rincian</a>
                <a href="#" data-modal-target="payroll-modal" data-modal-toggle="payroll-modal" class="btn btn-success btn-payroll"
                    data-batch_id="{{$item->id}}" data-month_name="{{$months[$item->bulan]}}" data-year="{{$item->tahun}}">Payroll</a>
            </td>
            @if ($item->bruto == 0)
                <td class="text-center">-</td>
            @else
                <td class="text-right">
                    Rp {{number_format($item->bruto, 0, ',', '.')}}
                </td>
            @endif
            @if ($item->total_potongan == 0)
                <td class="text-center">-</td>
            @else
                <td class="text-right">
                    Rp {{number_format($item->total_potongan, 0, ',', '.')}}
                </td>
            @endif
            @if ($item->netto < 0)
                <td class="text-right">
                    Rp ({{number_format(str_replace('-', '', $item->netto), 0, ',', '.')}})
                </td>
            @elseif ($item->netto == 0)
                <td class="text-center">-</td>
            @else
                <td class="text-right">
                    Rp {{number_format($item->netto, 0, ',', '.')}}
                </td>
            @endif
            {{--  pph bentukan  --}}
            @if ($item->total_pph < 0)
                <td class="text-right">
                    Rp ({{number_format(str_replace('-', '', $item->total_pph), 0, ',', '.')}})
                </td>
            @elseif ($item->total_pph == 0)
                <td class="text-center">-</td>
            @else
                <td class="text-right">
                    Rp {{number_format($item->total_pph, 0, ',', '.')}}
                </td>
            @endif
            {{-- pajak insentif --}}
            @if ($item->total_pajak_insentif < 0)
                <td class="text-right">
                    Rp ({{number_format(str_replace('-', '', $item->total_pajak_insentif), 0, ',', '.')}})
                </td>
            @elseif ($item->total_pajak_insentif == 0)
                <td class="text-center">-</td>
            @else
                <td class="text-right">
                    Rp {{number_format($item->total_pajak_insentif, 0, ',', '.')}}
                </td>
            @endif
            {{-- pph21 (bentukan - insentif) --}}
            @if ($item->hasil_pph < 0)
                <td class="text-right">
                    Rp ({{number_format(str_replace('-', '', $item->hasil_pph), 0, ',', '.')}})
                </td>
            @elseif ($item->hasil_pph == 0)
                <td class="text-center">-</td>
            @else
                <td class="text-right">
                    Rp {{number_format($item->hasil_pph, 0, ',', '.')}}
                </td>
            @endif
            @if (auth()->user()->hasRole('admin'))
                <td>
                    <a href="#" class="btn btn-primary d-flex justify-center btn-restore"
                       data-modal-toggle="restore-modal"
                       data-modal-target="restore-modal"
                        data-batch_id="{{$item->id}}"
                        data-kantor="{{$item->kantor}}"
                        data-bulan="{{$item->bulan}}"
                        data-tahun="{{$item->tahun}}"
                        >Kembalikan</a>
                </td>
            @endif
        </tr>
    @empty
        <tr>
            <td colspan="{{ !auth()->user()->hasRole('cabang') ? 14 : 13 }}" class="text-center">Belum ada penghasilan yang telah dihapus.</td>
        </tr>
    @endforelse
    </tbody>
    <tfoot>
        <th class="text-center" colspan="{{ !auth()->user()->hasRole('cabang') ? 7 : 6 }}">Total</th>
        @if ($total_bruto > 0)
            <th class="text-right">
                RP {{number_format($total_bruto, 0, ',', '.')}}
            </th>
        @else
            <th class="text-center">-</th>
        @endif
        @if ($total_potongan > 0)
            <th class="text-right">
                RP {{number_format($total_potongan, 0, ',', '.')}}
            </th>
        @else
            <th class="text-center">-</th>
        @endif
        @if ($total_netto > 0)
            <th class="text-right">
                RP {{number_format($total_netto, 0, ',', '.')}}
            </th>
        @else
            <th class="text-center">-</th>
        @endif
        @if ($total_pph > 0)
            <th class="text-right">
                RP {{number_format($total_pph, 0, ',', '.')}}
            </th>
        @else
            <th class="text-center">-</th>
        @endif
        @if ($total_pajak_insentif > 0)
            <th class="text-right">
                RP {{number_format($total_pajak_insentif, 0, ',', '.')}}
            </th>
        @else
            <th class="text-center">-</th>
        @endif
        @if ($total_hasil_pph > 0)
            <th class="text-right">
                RP {{number_format($total_hasil_pph, 0, ',', '.')}}
            </th>
        @else
            <th class="text-center">-</th>
        @endif
        <th></th>
    </tfoot>
</table>
<div class="table-footer">
    <div class="showing">
    Showing {{$start}} to {{$end}} of {{$sampah->total()}} entries
    </div>
    <div>
    @if ($sampah instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $sampah->links('pagination::tailwind') }}
    @endif
</div>
</div>
