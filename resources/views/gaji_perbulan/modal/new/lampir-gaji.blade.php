<div class="modal-layout hidden" tabindex="-1" id="lampiran-gaji-modal">
    <div class="modal modal-xl" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-head">
                <div class="heading">
                    <h2 class="modal-title">Lampiran Gaji</h2>
                </div> 
                <button type="button" class="close" data-modal-dismiss="modal" data-modal-hide="lampiran-gaji-modal" aria-label="Close">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <div class="">
            </div>
            <div class="modal-body">
                @can('penghasilan - proses penghasilan - lampiran gaji - cetak')
                    <br>
                        <div class="flex">
                            <a class="btn btn-outline-pdf mb-3 btn-download-pdf " id="download" href="" data-id="">Cetak</a>
                        </div>
                    <br>
                    <br>
                @endcan
            <div class="table-wrapping">
                <table class="tables-stripped " id="table-lampiran-gaji" style="width: 100%;">
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
                        <tr class="total_lampiran_gaji">
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
</div>
