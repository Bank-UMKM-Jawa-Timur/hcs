@php
    $earliest = 2024;
    $tahunSaatIni = date('Y');
    $awal = $tahunSaatIni - 5;
    $akhir = $tahunSaatIni + 5;
    $tahunInput = Request('tahun');
    $bulanArr = [
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
<div class="modal-layout hidden" id="filter-modal" tabindex="-1" aria-hidden="true">
    <div class="modal w-1/3">
        <div class="modal-head">
            <div class="heading">
                <h2>Filter</h2>
            </div>
            <button type="button" data-modal-hide="filter-modal"  class="modal-close"><i class="ti ti-x"></i></button>
        </div>
        <form action="" method="GET">
            <div class="modal-body">
                <div class="input-box mb-3">
                    <label for="selectfield">Cabang</label>
                    <select name="cabang" id="cabang_req" class="form-input pt-2" required>
                        <option value="">-- Pilih Cabang --</option>
                        @foreach ($cabang as $item)
                            <option value="{{ $item->kd_cabang }}" {{ $item->kd_cabang == Request('cabang') ?
                                'selected' : '' }}>{{ $item->nama_cabang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-box mb-3">
                    <label for="select_tahun">Tahun</label>
                    <select name="tahun" id="select_tahun" class="form-input pt-2" required>
                        <option value="">-- Pilih Tahun --</option>
                        @for ($tahunInput = $earliest; $tahunInput <= $akhir; $tahunInput++)
                            <option {{ Request('tahun') == $tahunInput ? 'selected' : '' }} value="{{ $tahunInput }}">
                                {{ $tahunInput }}</option>
                        @endfor
                    </select>
                </div>
                <div class="input-box mb-3">
                    <label for="select_bulan">Bulan</label>
                    <select name="bulan" id="select_bulan" class="form-input pt-2" required>
                        <option value="">-- Pilih Bulan --</option>
                        @foreach ($bulanArr as $key => $item)
                            <option value="{{ $key + 1 }}" {{ $key + 1 == Request('bulan') ?
                                'selected' : '' }}>{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer to-right">
                <button data-modal-dismiss="modal" data-modal-hide="filter-modal" class="btn btn-light" type="button">Batal</button>
                <button class="btn btn-primary" id="filter">Filter</button>
            </div>
        </form>
    </div>
</div>
