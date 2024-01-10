<div class="modal" tabindex="-1" id="lampiran-gaji-modal">
    <div class="modal-dialog modal-xl" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lampiran Gaji</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="">
            </div>
            <div class="modal-body table-responsive">
                @can('penghasilan - proses penghasilan - lampiran gaji - cetak')
                    <br>
                        <a class="is-btn is-primary mb-3 btn-download-pdf " id="download" href="" data-id="">Cetak</a>
                    <br>
                    <br>
                @endcan
                <table class="table whitespace-nowrap table-bordered" id="table-lampiran-gaji" style="width: 100%;">
                    <thead class="text-primary" style="border:1px solid #e3e3e3 !important">
                        <tr>
                            <th rowspan="2" class="text-center">No</th>
                            <th rowspan="2" class="text-center">Nama karyawan</th>
                            <th rowspan="2" class="text-center">Gaji</th>
                            <th rowspan="2" class="text-center">No Rek</th>
                            <th colspan="6" class="text-center">Potongan</th>
                            <th rowspan="2" class="text-center">Total Potongan</th>
                            <th rowspan="2" class="text-center">Total Yang Diterima</th>
                        </tr>
                        <tr>
                            <th class="text-center">JP BPJS TK 1%</th>
                            <th class="text-center">DPP 5%</th>
                            <th class="text-center">Kredit Koperasi</th>
                            <th class="text-center">Iuaran Koperasi</th>
                            <th class="text-center">Kredit Pegawai</th>
                            <th class="text-center">Iuran IK</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot >
                        <tr class="total">
                            <th colspan="2" class="text-center">Total</th>
                            <th class="text-right"></th>
                            <th></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                        </tr>
                        <tr class="grandtotalGaji">
                            <th colspan="2" class="text-center"></th>
                            <th class="text-right"></th>
                            <th></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
