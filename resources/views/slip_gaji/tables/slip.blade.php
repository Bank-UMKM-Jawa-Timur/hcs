<table class="tables table whitespace-nowrap table-bordered" id="table" style="width: 100%;">
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
            $footer_total_potongan2 = 0;
            $footer_total_diterima = 0;
            $footer_total_gaji_bersih = 0;
            $footer_total_gaji_bersih2 = 0;
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
                        $total_gaji = $item->total_gaji ? number_format($item->total_gaji, 0, ',', '.') : 0;
                        $bpjs_tk = $item->bpjs_tk ? $item->bpjs_tk : 0;
                        $dpp = $item->potongan ? $item->potongan->dpp : 0;
                        $kredit_koperasi = $item->kredit_koperasi ? $item->kredit_koperasi : 0;
                        $iuran_koperasi = $item->iuran_koperasi ? $item->iuran_koperasi : 0;
                        $kredit_pegawai = $item->kredit_pegawai ? $item->kredit_pegawai : 0;
                        $iuran_ik = $item->iuran_ik ? $item->iuran_ik : 0;
                        $total_potongan = $bpjs_tk + $dpp + $kredit_koperasi + $iuran_koperasi + $kredit_pegawai + $iuran_ik;
                        $total_diterima = $item->total_gaji - $total_potongan;

                        // count total
                        $footer_total_gaji += str_replace('.', '', $total_gaji);
                        $footer_total_potongan += str_replace('.', '', $total_potongan);
                        $footer_total_diterima += str_replace('.', '', $total_diterima);
                        $footer_total_gaji_bersih += str_replace('.', '', $total_diterima);

                        $footer_total_potongan2 += $total_potongan;
                        $footer_total_gaji_bersih2 += $total_diterima;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $month_arr[($item->bulan - 1)] }}</td>
                        <td class="text-right">{{ $total_gaji }}</td>
                        <td class="text-right">{{ $total_potongan > 0 ? number_format($total_potongan, 0, ',', '.') : '-' }}</td>
                        <td class="text-right">{{ $total_diterima > 0 ? number_format($total_diterima, 0, ',', '.') : '-' }}</td>
                        <td class="flex justify-center">
                            <button type="button"
                                data-modal-id="exampleModal"
                                data-modal-toggle="exampleModal"
                                data-target-id="slipGaji-{{ $item->id }}"
                                data-nip="{{$data->nip}}"
                                data-nama="{{$data->nama_karyawan}}"
                                data-no_rekening="{{$data->no_rekening}}"
                                data-status_jabatan="{{$data->status_jabatan}}"
                                data-tanggal_pengangkat="{{ $data->tanggal_pengangkat }}"
                                data-tanggal_pengangkat_formated="{{ Carbon::parse($data->tanggal_pengangkat)->translatedFormat('d F Y') }}"
                                data-json="{{ $item }}"
                                data-bulan="{{$item->bulan}}"
                                class="btn btn-primary is-btn btn-sm is-primary p-1 show-data">Rincian</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="12">Data tidak tersedia.</td>
                    </tr>
                @endforelse
            @endif
        @else
            <tr>
                <td class="text-center" colspan="12">Data tidak tersedia.</td>
            </tr>
        @endif
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2" class="text-center">Jumlah</th>
            <th class="text-right">{{ number_format($footer_total_gaji, 0, ',', '.') }}</th>
            <th class="text-right">{{ number_format($footer_total_potongan2, 0, ',', '.') }}</th>
            <th class="text-right">{{ number_format($footer_total_gaji_bersih2, 0, ',', '.') }}</th>
            <th></th>
        </tr>
    </tfoot>
</table>
