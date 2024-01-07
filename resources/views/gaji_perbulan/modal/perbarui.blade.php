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
                <table class="display" id="penyesuaian-table">
                    <thead>
                        <tr>
                            <th rowspan="2"></th>
                            <th rowspan="2">No</th>
                            <th rowspan="2">NIP</th>
                            <th rowspan="2">Nama</th>
                            <th colspan="2">Total Penghasilan</th>
                            <th colspan="2">Total Potongan</th>
                        </tr>
                        <tr>
                            <th class="text-right">Sebelum</th>
                            <th class="text-right">Sesudah</th>
                            <th class="text-right">Sebelum</th>
                            <th class="text-right">Sesudah</th>
                        </tr>
                    </thead>
                </table>
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