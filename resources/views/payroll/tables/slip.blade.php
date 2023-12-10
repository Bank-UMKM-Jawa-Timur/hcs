<table class="table whitespace-nowrap table-bordered" id="table" style="width: 100%;">
    <thead class="text-primary" style="border:1px solid #e3e3e3 !important">
        <tr>
            <th class="text-center">No</th>
            <th class="text-center">Bulan</th>
            <th class="text-center">Gaji</th>
            <th class="text-center">Total Potongan</th>
            <th class="text-center">Gaji Bersih</th>
            <th class="text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @php
            $footer_total_gaji = 0;
            $footer_total_potongan = 0;
            $footer_total_diterima = 0;
            $footer_total_gaji_bersih = 0;
        @endphp
        @if ($data)
            @php
                $month_arr = [
                    'Januari',
                    'Februari',
                    'Maret',
                    'April',
                    'Mei',
                    'Juni',
                    'Juli',
                    'Agustus',
                    'September',
                    'Oktober',
                    'November',
                    'Desember'
                ];
            @endphp
            @if ($data->allGajiByKaryawan)
                @forelse ($data->allGajiByKaryawan as $item)
                    @php
                        $total_gaji = $item->gaji ? number_format($item->gaji, 0, ',', '.') : 0;
                        $bpjs_tk = $item->bpjs_tk ? $item->bpjs_tk : 0;
                        $dpp = $item->potongan ? $item->potongan->dpp : 0;
                        $kredit_koperasi = $item->kredit_koperasi ? $item->kredit_koperasi : 0;
                        $iuran_koperasi = $item->iuran_koperasi ? $item->iuran_koperasi : 0;
                        $kredit_pegawai = $item->kredit_pegawai ? $item->kredit_pegawai : 0;
                        $iuran_ik = $item->iuran_ik ? $item->iuran_ik : 0;
                        $total_potongan = $bpjs_tk + $dpp + $kredit_koperasi + $iuran_koperasi + $kredit_pegawai + $iuran_ik;
                        $total_diterima = $item->gaji - $total_potongan;

                        // count total
                        $footer_total_gaji += str_replace('.', '', $total_gaji);
                        $footer_total_potongan += str_replace('.', '', $total_potongan);
                        $footer_total_diterima += str_replace('.', '', $total_diterima);
                        $footer_total_gaji_bersih += str_replace('.', '', $total_diterima);
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $month_arr[($item->bulan - 1)] }}</td>
                        <td class="text-right">{{ $total_gaji }}</td>
                        <td class="text-right">{{ $total_potongan > 0 ? number_format($total_potongan, 0, ',', '.') : '-' }}</td>
                        <td class="text-right">{{ $total_diterima > 0 ? number_format($total_diterima, 0, ',', '.') : '-' }}</td>
                        <td class="text-center">
                            <button type="button"
                            data-toggle="modal"
                            data-target="#exampleModal"
                            data-target-id="slipGaji-{{ $item->id }}"
                            data-nip="{{$data->nip}}"
                            data-nama="{{$data->nama_karyawan}}"
                            data-no_rekening="{{$data->no_rekening}}"
                            data-json="{{ $item }}"
                            class="is-btn btn-sm is-primary p-1 show-data">Slip</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="12">Data tidak tersedia.</td>
                    </tr>
                @endforelse
            @endif
        @endif
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" class="text-center">Jumlah</th>
            <th class="text-right">{{ number_format($footer_total_gaji, 0, ',', '.') }}</th>
            <th class="text-right">{{ number_format($footer_total_potongan, 0, ',', '.') }}</th>
            <th class="text-right">{{ number_format($footer_total_gaji_bersih, 0, ',', '.') }}</th>
            <th></th>
        </tr>
    </tfoot>
</table>
