<div class="modal-layout hidden" id="filter-modal" tabindex="-1" aria-hidden="true">
    <div class="modal modal-sm">
        <div class="modal-head">
            <div class="heading">
                <h2>Filter</h2>
            </div>
            <button data-modal-dismiss="filter-modal"  class="modal-close"><i class="ti ti-x"></i></button>
        </div>
        <form action="" method="GET">
            <div class="modal-body">
                <div class="input-box">
                    <label for="selectfield">Cabang</label>
                    <select name="cabang" id="cabang_req" class="form-input pt-2" required>
                        <option value="">-- Pilih Cabang --</option>
                        @foreach ($cabang as $item)
                            <option value="{{ $item->kd_cabang }}" {{ $item->kd_cabang == Request('cabang') ?
                                'selected' : '' }}>{{ $item->nama_cabang }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer to-right">
                <button data-modal-dismiss="filter-modal" class="btn btn-light" type="button">Batal</button>
                <button class="btn btn-primary" id="filter">Filter</button>
            </div>
        </form>
    </div>
</div>
