<div class="layout-component">
    <div class="shorty-table">
        <label for="">Show</label>
        <select  name="page_length_final" id="page_length_final" class="page_length page_length_final">
        <option value="10"
        @isset($_GET['page_length_final']) {{ $_GET['page_length_final'] == 10 ? 'selected' : '' }} @endisset>
        10</option>
    <option value="20"
        @isset($_GET['page_length_final']) {{ $_GET['page_length_final'] == 20 ? 'selected' : '' }} @endisset>
        20</option>
    <option value="50"
        @isset($_GET['page_length_final']) {{ $_GET['page_length_final'] == 50 ? 'selected' : '' }} @endisset>
        50</option>
    <option value="100"
        @isset($_GET['page_length_final']) {{ $_GET['page_length_final'] == 100 ? 'selected' : '' }} @endisset>
        100</option>
        </select>
        <label for="">entries</label>
    </div>
    <div class="input-search">
        <i class="ti ti-search"></i>
        <input type="search" class="q-final" placeholder="Search" name="q_final" id="q_final" value="{{ isset($_GET['q_final']) ? $_GET['q_final'] : '' }}">
    </div>
</div>
@php
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
    $start = $page == 1 ? 1 : ($page * $page_length - $page_length) + 1;
    $end = $page == 1 ? $page_length : ($start + $page_length) - 1;
    $i = $page == 1 ? 1 : $start;
@endphp
<table class="tables-stripped" id="table_lampiran_gaji">
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
        @forelse ($final_list as $item)
            @php
            $total_bruto += $item->bruto;
            $total_potongan += $item->total_potongan;
            $total_netto += $item->netto;
            $total_pph += $item->total_pph;
            $total_pajak_insentif += $item->total_pajak_insentif;
            $total_hasil_pph += $item->hasil_pph;
            @endphp
            <tr>
                <td class="text-center">{{$i++}}</td>
                <td class="text-center">{{ $item->nama_divisi ? $item->nama_divisi : 'Pegawai' }}</td>
                @if (!auth()->user()->hasRole('cabang'))
                    <td class="text-center">{{ $item->kantor }}</td>
                @endif
                <td class="text-center">{{ $item->tahun }}</td>
                <td class="text-center">{{ $months[$item->bulan] }}</td>
                <td class="text-center">{{date('d-m-Y', strtotime($item->tanggal_input))}}</td>
                <td class="text-center flex justify-center gap-5">
                    <a href="#" data-modal-id="rincian-modal" data-modal-toggle="modal" class="btn btn-warning btn-rincian"
                        data-batch_id="{{$item->id}}" data-month_name="{{$months[$item->bulan]}}" data-year="{{$item->tahun}}">Rincian</a>
                    <a href="#" data-modal-id="payroll-modal" data-modal-toggle="modal" class="btn btn-success btn-payroll"
                        data-batch_id="{{$item->id}}" data-month_name="{{$months[$item->bulan]}}" data-year="{{$item->tahun}}">Payroll</a>
                </td>
                {{-- bruto --}}
                @if ($item->bruto == 0)
                    <td class="text-center">-</td>
                @else
                    <td class="text-right">
                        Rp {{number_format($item->bruto, 0, ',', '.')}}
                    </td>
                @endif
                {{-- potongan --}}
                @if ($item->total_potongan == 0)
                    <td class="text-center">-</td>
                @else
                    <td class="text-right">
                        Rp {{number_format($item->total_potongan, 0, ',', '.')}}
                    </td>
                @endif
                {{-- netto --}}
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
                {{-- pph bentukan --}}
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
                {{-- aksi --}}
                <td>
                    @if (auth()->user()->hasRole('admin'))
                        <a href="#" class="btn btn-danger d-flex justify-center btn-delete"
                           data-modal-toggle="delete-modal"
                           data-modal-target="delete-modal"
                            data-batch_id="{{$item->id}}"
                            data-kantor="{{$item->kantor}}"
                            data-bulan="{{$item->bulan}}"
                            data-tahun="{{$item->tahun}}">
                            Hapus
                        </a>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ !auth()->user()->hasRole('cabang') ? 14 : 13 }}" class="text-center">Belum ada penghasilan yang telah difinal.</td>
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
        Showing {{$start}} to {{$end}} of {{$final_list->total()}} entries
        </div>
        <div>
        @if ($final_list instanceof \Illuminate\Pagination\LengthAwarePaginator)
        {{ $final_list->links('pagination::tailwind') }}
        @endif
    </div>
</div>
