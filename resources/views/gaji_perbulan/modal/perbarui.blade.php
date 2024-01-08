<div class="modal" tabindex="-1" id="penyesuaian-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Perbarui</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="display" id="penyesuaian-table" style="width: 100%;">
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
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <form id="form" action="{{route('gaji_perbulan.store')}}" method="POST">
                    @csrf
                    <input type="hidden" name="batch_id" id="batch_id">
                    <button type="submit" class="is-btn is-primary" id="btn-update">Perbarui</button>
                </form>
            </div>
        </div>
    </div>
</div>