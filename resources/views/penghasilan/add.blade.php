@extends('layouts.template')

@section('content')
    <div class="card-header">
        <div class="card-header">
            <div class="card-title">
                <h5 class="card-title">Penghasilan Tidak Teratur</h5>
                <p class="card-title"><a href="/">Dashboard </a> > <a href="/penghasilan">Penghasilan Tidak Teratur </a> > Tambah</p>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col m-3">
                <form action="" class="form-group" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-12">
                            <h6>Tunjangan 1</h6>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="tUangMkn">T. Uang Makan</label>
                                <input type="number" name="tUangMkn" id="tUangMkn" class="@error('tUangMkn') is-invalid @enderror form-control" value="{{ old('tUangMkn') }}">
                            </div>
                        </div>
                        
                        <div class="col-lg-3">
                            <div class="form_group">
                                <label for="tUangPlsa">T. Uang Pulsa</label>
                                <input type="number" name="tUangPlsa" id="tUangPlsa" class="@error('tUangPlsa') is-invalid @enderror form-control" value="{{ old('tUangPlsa') }}">
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="tUangVit">T. Uang Vitamin</label>
                                <input type="number" name="tUangVit" id="tUangVit" class="@error('tUangVit') is-invalid @enderror form-control" value="{{ old('tUangVit') }}">
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="tUangTrans">T. Uang Transport</label>
                                <input type="number" name="tUangTrans" id="tUangTrans" class="@error('tUangTrans') is-invalid @enderror form-control" value="{{ old('tUangTrans') }}">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12">
                            <h6>Tunjangan 2</h6>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="tUangLmbr">T. Uang Lembur</label>
                                <input type="number" name="tUangLmbr" id="tUangLmbr" class="@error('tUangLmbr') is-invalid @enderror form-control" value="{{ old('tUangLmbr') }}">
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="tPenggantiKshtn">Pengganti Biaya Kesehatan</label>
                                <input type="number" name="tPenggantiKshtn" id="tPenggantiKshtn" class="@error('tPenggantiKshtn') is-invalid @enderror form-control" value="{{ old('tPenggantiKshtn') }}">
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="tUangDuka">Uang Duka</label>
                                <input type="number" name="tUangDuka" id="tUangDuka" class="@error('tUangDuka') is-invalid @enderror form-control" value="{{ old('tUangDuka') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="tSPD">SPD</label>
                                <input type="number" name="tSPD" id="tSPD" class="@error('tSPD') is-invalid @enderror form-control" value="{{ old('tSPD') }}">
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="tSPDPenddkn">SPD Pendidikan</label>
                                <input type="number" name="tSPDPenddkn" id="tSPDPenddkn" class="@error('tSPDPenddkn') is-invalid @enderror form-control" value="{{ old('tSPDPenddkn') }}">
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="tSPDTgs">SPD Pindah Tugas</label>
                                <input type="number" name="tSPDTgs" id="tSPDTgs" class="@error('tSPDTgs') is-invalid @enderror form-control" value="{{ old('tSPDTgs') }}">
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-info" value="submit" type="submit">Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection