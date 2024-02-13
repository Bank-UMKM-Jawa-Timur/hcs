<div class="modal-layout  hidden" tabindex="-1" id="proses-modal">
    <form id="form" action="{{route('gaji_perbulan.store')}}" method="post">
        @csrf
        <input type="hidden" name="tahun_terakhir" id="tahun_terakhir" value="0">
        <input type="hidden" name="bulan_terakhir" id="bulan_terakhir" value="0">
        <input type="hidden" name="is_pegawai" id="is_pegawai" value="true">
        <div class="modal modal-sm">
            <div class="modal-content">
                <div class="modal-head">
                    <div class="heading">
                        <h2 class="modal-title">Proses Penggajian Bulanan</h2>
                    </div>
                    <button data-modal-dismiss="proses-modal"  type="button" class="modal-close"><i class="ti ti-x"></i></button>
                </div>
                <div class="modal-body">
                    <p class="text-red-500" id="note">Catatan! Proses penggajian untuk pegawai.</p>
                    <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mt-2">
                        <div class="col-md-6">
                            <div class="flex gap-5">
                                <label>Total Karyawan : </label>
                                <b id="total_karyawan"></b>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="flex gap-5">
                                <label>Total Bruto : </label>
                                <b id="total_bruto"></b>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="flex gap-5">
                                <label>Total Potongan : </label>
                                <b id="total_potongan"></b>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="flex gap-5">
                                <label>Total Netto : </label>
                                <b id="total_netto"></b>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="">
                            <div class="input-box">
                                <label for="tanggal">Tanggal Penghasilan</label>
                                <input type="date" name="tanggal" id="tanggal"
                                    class="form-input" required>
                                @error('tanggal')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer to-right">
                    <button type="submit" class="btn btn-primary" id="btn-proses-penghasilan">Proses</button>
                </div>
            </div>
        </div>
    </form>
</div>