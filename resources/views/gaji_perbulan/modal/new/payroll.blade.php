<div class="modal-layout no-backdrop-click hidden" tabindex="-1" id="payroll-modal">
    <div class="modal" style="max-width: 90%;">
        <div class="modal-content" style="
        width: 100%;
        margin: 0;
        padding: 0;">
            <div class="modal-head">
                <div class="heading">
                    <h2 class="modal-title">Payroll</h2>
                </div>
                <button type="button" class="close" data-modal-dismiss="payroll-modal" aria-label="Close">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <div class="modal-body ">
                <div class="pb-4 flex">
                    <a href="" download class="btn btn-outline-excel mb-3 btn-download-payroll" data-batch="" type=""><iconify-icon icon="file-icons:microsoft-excel"></iconify-icon> Download Excel</a>
                    @can('penghasilan - proses penghasilan - lampiran gaji - cetak')
                        <a class="btn btn-outline-pdf ml-2 mb-3 btn-download-pdf " id="download" href="" data-id="">Cetak</a>
                    @endcan
                </div>
                <div class="table-wrapping">
                    <table class="tables-stripped " id="table-payroll" style="width: 100%;">
                        <thead class="text-primary text-center" style="border:1px solid #e3e3e3 !important">
                            <tr>
                                <th rowspan="2" class="text-center">No</th>
                                <th rowspan="2" class="text-center">Nama karyawan</th>
                                <th rowspan="2" class="text-center">Gaji</th>
                                <th rowspan="2" class="text-center">No Rek</th>
                                <th colspan="6" class="text-center mx-auto" align="center">Potongan</th>
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
                            <tr class="grandtotalPayroll">
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
</div>
