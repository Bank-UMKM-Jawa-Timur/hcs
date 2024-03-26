<div class="modal-layout p-5 no-backdrop-click hidden" tabindex="-1" id="penyesuaian-modal">
    <div class="modal w-full" >
        <div class="modal-content py-3">
            <div class="modal-head">
                <div class="heading">
                    <h2 class="modal-title">Perbarui</h2>
                </div>
                <button data-modal-hide="penyesuaian-modal" type="button" class="close" aria-label="Close">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-wrapping">
                    <table class="display table-stripped" id="penyesuaian-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th rowspan="2" style="text-align: center;"></th>
                                <th rowspan="2" style="text-align: center;">No</th>
                                <th rowspan="2" style="text-align: center;">NIP</th>
                                <th rowspan="2" style="text-align: center;">Nama</th>
                                <th colspan="2" style="text-align: center;">Total Penghasilan</th>
                                <th colspan="2" style="text-align: center;">Total Potongan</th>
                            </tr>
                            <tr>
                                <th style="text-align: center;">Sebelum</th>
                                <th style="text-align: center;">Sesudah</th>
                                <th style="text-align: center;">Sebelum</th>
                                <th style="text-align: center;">Sesudah</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="total_pembaruan">
                                <th style="text-align: center;" colspan="4">Total</th>
                                <th style="text-align: right;"></th>
                                <th style="text-align: right;"></th>
                                <th style="text-align: right;"></th>
                                <th style="text-align: right;"></th>
                            </tr>
                            <tr class="grandTotalPembaruan">
                                <th style="text-align: center;" colspan="4">Grand Total</th>
                                <th style="text-align: right;"></th>
                                <th style="text-align: right;"></th>
                                <th style="text-align: right;"></th>
                                <th style="text-align: right;"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer to-right">
                <form id="form" action="{{route('gaji_perbulan.store')}}" method="POST">
                    @csrf
                    <input type="hidden" name="batch_id" id="batch_id">
                    <input type="hidden" name="is_pegawai" id="is_pegawai">
                    <button type="submit" class="btn btn-primary" id="btn-update">Perbarui</button>
                </form>
            </div>
        </div>
    </div>
</div>
