@push('style')
    <style>
        .select2 {
            height: 40px;
        }
    </style>
@endpush
<div class="modal fade" id="modal-cetak-vitamin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Filter Download Vitamin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('penghasilan.cetak-vitamin') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="from-group">
                                <label for="" class="form-label">Bulan</label>
                                <select name="bulan" id="bulan" class="select2 form-control" required>
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
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group" id="periode">
                                <label for="" class="form-label">Periode</label>
                                @php
                                    $earliest = 2024;
                                    $tahunSaatIni = date('Y');
                                    $tahunAwal = $tahunSaatIni - 5;
                                    $tahunAkhir = $tahunSaatIni + 5;
                                @endphp
                                <select name="tahun" class="select2 form-control" required>
                                    <option value="">Pilih Tahun</option>

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
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Download Excel</button>
                </div>
            </form>
        </div>
    </div>
</div>
