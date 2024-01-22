@push('style')
    <style>
        .select2 {
            height: 40px;
        }
    </style>
@endpush
<div class="modal-layout hidden" id="modal-cetak-vitamin" tabindex="-1" aria-hidden="true">
    <div class="modal modal-sm">
        <div class="modal-head">
            <div class="heading">
                <h2>Filter Download Vitamin</h2>
            </div>
            <button data-modal-dismiss="modal-cetak-vitamin"  class="modal-close"><i class="ti ti-x"></i></button>
        </div>
        <form action="{{ route('penghasilan.cetak-vitamin') }}" method="POST">
            <div class="modal-body">
                <div class="modal-body">
                    @csrf
                    <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-5">
                        <div class="input-box">
                            <label for="selectfield">Bulan</label>
                            <select name="bulan" class="form-input" id="bulan" required>
                                <option value="">Pilih Bulan</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option
                                    {{ Request()->bulan == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}
                                    value="{{ str_pad($i, 1, '0', STR_PAD_LEFT) }}">
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                @endfor
                            </select>
                            @error('bulan')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="input-box">
                            <label for="selectfield">Periode</label>
                            @php
                                $earliest = 2024;
                                $tahunSaatIni = date('Y');
                                $tahunAwal = $tahunSaatIni - 5;
                                $tahunAkhir = $tahunSaatIni + 5;
                            @endphp
                            <select name="tahun" class="form-input" id="tahun" required>
                                <option value="">Pilih Bulan</option>
                                @for ($tahun = $earliest; $tahun <= $tahunAkhir; $tahun++)
                                    <option {{ Request()->tahun == $tahun ? 'selected' : '' }} value="{{ $tahun }}">
                                        {{ $tahun }}</option>
                                @endfor
                            </select>
                            @error('periode')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer to-right">
                <button type="submit" class="btn btn-primary">Download Excel</button>
            </div>
        </form>
    </div>
</div>