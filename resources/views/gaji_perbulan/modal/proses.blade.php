<div class="modal" tabindex="-1" id="proses-modal">
    <form id="form" action="{{route('gaji_perbulan.store')}}" method="post">
        @csrf
        <input type="hidden" name="tahun_terakhir" id="tahun_terakhir" value="0">
        <input type="hidden" name="bulan_terakhir" id="bulan_terakhir" value="0">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Proses Penghasilan Bulanan</h5>
                    <button type="button" class="close" data-modal-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Total Karyawan : </label>
                                <b id="total_karyawan"></b>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Total Bruto : </label>
                                <b id="total_bruto"></b>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Total Potongan : </label>
                                <b id="total_potongan"></b>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Total Netto : </label>
                                <b id="total_netto"></b>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="tanggal">Tanggal Penghasilan</label>
                                <input type="date" name="tanggal" id="tanggal"
                                    class="form-control" required>
                                @error('tanggal')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="is-btn is-primary" id="btn-proses-penghasilan">Proses</button>
                </div>
            </div>
        </div>
    </form>
</div>