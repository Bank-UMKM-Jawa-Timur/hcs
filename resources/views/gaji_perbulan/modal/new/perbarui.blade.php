<div class="modal-layout no-backdrop-click hidden" tabindex="-1" id="penyesuaian-modal">
    <div class="modal max-w-[90%]">
        <div class="modal-content">
            <div class="modal-head">
                <div class="heading">
                    <h2 class="modal-title">Perbarui</h2>
                </div>
                <button type="button" class="close" data-moda-dismiss="penyesuaian-modal" aria-label="Close">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-wrapping">
                    <table class="display table-stripped" id="penyesuaian-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th rowspan="2" class="text-center"></th>
                                <th rowspan="2" class="text-center">No</th>
                                <th rowspan="2" class="text-center">NIP</th>
                                <th rowspan="2" class="text-center">Nama</th>
                                <th colspan="2" class="text-center">Total Penghasilan</th>
                                <th colspan="2" class="text-center">Total Potongan</th>
                            </tr>
                            <tr>
                                <th class="text-center">Sebelum</th>
                                <th class="text-center">Sesudah</th>
                                <th class="text-center">Sebelum</th>
                                <th class="text-center">Sesudah</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="total">
                                <th class="text-center" colspan="4">Total</th>
                                <th class="text-right"></th>
                                <th class="text-right"></th>
                                <th class="text-right"></th>
                                <th class="text-right"></th>
                            </tr>
                            <tr class="grandTotal">
                                <th class="text-center" colspan="4">Grand Total</th>
                                <th class="text-right"></th>
                                <th class="text-right"></th>
                                <th class="text-right"></th>
                                <th class="text-right"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer to-right">
                <form id="form" action="{{route('gaji_perbulan.store')}}" method="POST">
                    @csrf
                    <input type="hidden" name="batch_id" id="batch_id">
                    <button type="submit" class="btn btn-primary" id="btn-update">Perbarui</button>
                </form>
            </div>
        </div>
    </div>
</div>