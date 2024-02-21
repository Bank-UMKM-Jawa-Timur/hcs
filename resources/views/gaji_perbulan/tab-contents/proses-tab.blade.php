<div class="layout-component">
    <div class="shorty-table">
        <label for="">Show</label>
    <select name="page_length_proses" id="page_length_proses" class="page_length page_length_proses">
        <option value="10"
        @isset($_GET['page_length_proses']) {{ $_GET['page_length_proses'] == 10 ? 'selected' : '' }} @endisset>
        10</option>
    <option value="20"
        @isset($_GET['page_length_proses']) {{ $_GET['page_length_proses'] == 20 ? 'selected' : '' }} @endisset>
        20</option>
    <option value="50"
        @isset($_GET['page_length_proses']) {{ $_GET['page_length_proses'] == 50 ? 'selected' : '' }} @endisset>
        50</option>
    <option value="100"
        @isset($_GET['page_length_proses']) {{ $_GET['page_length_proses'] == 100 ? 'selected' : '' }} @endisset>
        100</option>
        </select>
        <label for="">entries</label>
    </div>
    <div class="input-search">
        <i class="ti ti-search"></i>
        <input type="search" class="q-proses" placeholder="Search" name="q_proses" id="q"
            value="{{ isset($_GET['q_proses']) ? $_GET['q_proses'] : '' }}">
    </div>
</div>
<table class="tables-stripped">
    @php
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
        $start = $page == 1 ? 1 : ($page * $page_length - $page_length) + 1;
        $end = $page == 1 ? $page_length : ($start + $page_length) - 1;
    @endphp
    <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Kategori</th>
            @if (auth()->user()->hasRole('admin'))
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
    @forelse ($proses_list as $item)
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
            <td class="text-center">{{ $item->is_pegawai ? 'Pegawai' : 'Lainnya' }}</td>
            @if (auth()->user()->hasRole('admin'))
                <td class="text-center">{{ $item->kantor }}</td>
            @endif
            <td class="text-center">{{ $item->tahun }}</td>
            <td class="text-center">{{ $months[$item->bulan] }}</td>
            <td class="text-center">{{date('d-m-Y', strtotime($item->tanggal_input))}}</td>
            <td class="text-center border-none flex gap-2 justify-center">
                @can('penghasilan - proses penghasilan - rincian')
                    <a href="#" data-modal-toggle="modal" data-modal-id="rincian-modal" class="btn btn-warning btn-rincian"
                        data-batch_id="{{$item->id}}" data-month_name="{{$months[$item->bulan]}}" data-year="{{$item->tahun}}" data-is_trash="false">Rincian</a>
                @endcan
                @can('penghasilan - proses penghasilan - payroll')
                    <a href="#" class="btn btn-success btn-payroll"
                    data-modal-toggle="modal" data-modal-id="payroll-modal"
                        data-batch_id="{{$item->id}}" data-month_name="{{$months[$item->bulan]}}" data-year="{{$item->tahun}}" data-is_trash="false">Payroll</a>
                @endcan
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
            <td class="text-center border-none  justify-center flex">
                @if($item->status == 'proses')
                    @if($item->total_penyesuaian > 0)
                        @can('penghasilan - proses penghasilan - proses')
                            <a href="#" class="btn btn-warning btn-perbarui"
                                data-modal-id="penyesuaian-modal" data-modal-toggle="penyesuaian-modal"
                                data-batch_id="{{$item->id}}">Perbarui</a>
                        @endcan
                    @else
                        @can('penghasilan - proses penghasilan - proses')
                            @if ($item->tanggal_cetak != null && $item->tanggal_upload == null)
                                @can('penghasilan - proses penghasilan - lampiran gaji - upload')
                                    @if ($item->file == null)
                                        <a class="btn btn-primary" data-modal-id="modalUploadfile" data-modal-toggle="modal" href="#" id="uploadFile"  data-toggle="modal" data-target="#modalUploadfile" data-batch_id="{{ $item->id }}">
                                            <i class="ti ti-circle-check"></i>Finalisasi
                                        </a>
                                    @endif
                                @endcan
                            @endif
                        @endcan
                    @endif
                @else
                    -
                @endif
                @if (auth()->user()->hasRole('admin'))
                    <a href="#" class="btn btn-danger d-flex justify-center btn-delete"
                        data-batch_id="{{$item->id}}"
                        data-kantor="{{$item->kantor}}"
                        data-bulan="{{$item->bulan}}"
                        data-tahun="{{$item->tahun}}"
                        >Hapus</a>
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="{{ auth()->user()->hasRole('admin') ? 12 : 11 }}" class="text-center">Belum ada penghasilan yang diproses.</td>
        </tr>
    @endforelse
    </tbody>
    <tfoot>
        <th class="text-center" colspan="{{ auth()->user()->hasRole('admin') ? 7 : 6 }}">Total</th>
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
        Showing {{$start}} to {{$end}} of {{$proses_list->total()}} entries
    </div>
    <div>
        @if ($proses_list instanceof \Illuminate\Pagination\LengthAwarePaginator)
        {{ $proses_list->links('pagination::tailwind') }}
        @endif
    </div>
</div>