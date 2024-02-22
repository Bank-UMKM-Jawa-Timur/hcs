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
                    <div class="input-box jabatan-box">
                        <label>Jabatan</label>
                        <select name="jabatan" id="jabatan" class="form-input">
                            <option value="">Pegawai</option>
                            <option value="DIR">Direksi</option>
                            <option value="KOM">Komisaris</option>
                            <option value="STAD">Staf Ahli</option>
                        </select>
                    </div>
                    <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 my-2">
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
                        <div></div>
                        <div class="col-md-6">
                            <div class="flex gap-5">
                                <label>Total Netto : </label>
                                <b id="total_netto"></b>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="my-2">
                        <b>Potongan</b>
                        <div class="grid grid-cols-2 gap-4 mt-2">
                            <div class="grid grid-cols-3">
                                <div><label>Kredit Koperasi</label></div>
                                <div style="width: 10px;">:</div>
                                <div style="width: 200px;"><b id="total_potongan_kredit_koperasi"></b></div>
                            </div>
                            <div class="grid grid-cols-3">
                                <div><label>Iuran Koperasi</label></div>
                                <div style="width: 10px;">:</div>
                                <div style="width: 200px;"><b id="total_potongan_iuran_koperasi"></b></div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-2">
                            <div class="grid grid-cols-3">
                                <div><label>Kredit Pegawai</label></div>
                                <div style="width: 10px;">:</div>
                                <div style="width: 200px;"><b id="total_potongan_kredit_pegawai"></b></div>
                            </div>
                            <div class="grid grid-cols-3">
                                <div><label>Iuran IK</label></div>
                                <div style="width: 10px;">:</div>
                                <div style="width: 200px;"><b id="total_potongan_iuran_ik"></b></div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-2">
                            <div class="grid grid-cols-3">
                                <div><label>DPP</label></div>
                                <div style="width: 10px;">:</div>
                                <div style="width: 200px;"><b id="total_potongan_dpp"></b></div>
                            </div>
                            <div class="grid grid-cols-3">
                                <div><label>BPJS TK</label></div>
                                <div style="width: 10px;">:</div>
                                <div style="width: 200px;"><b id="total_potongan_bpjs_tk"></b></div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-2">
                            <div class="grid grid-cols-3">
                                <div></div>
                                <div style="width: 10px;"></div>
                                <div></div>
                            </div>
                            <div class="grid grid-cols-3">
                                <div><label>Total Potongan</label></div>
                                <div style="width: 10px;">:</div>
                                <div style="width: 200px;"><b id="total_potongan"></b></div>
                            </div>
                        </div>
                    </div>
                    <hr>
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