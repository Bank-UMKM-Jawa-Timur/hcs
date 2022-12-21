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
        <form action="" class="form-group" method="POST" enctype="multipart/form-data">
            <div id="accordion">
                <div class="card p-2 ml-3 mr-3 shadow">
                    <div class="card-header" id="headingOne">
                        <h6 class="ml-3" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <a class="text-decoration-none" href="" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Data Karyawan</a>
                        </h6>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="row m-0 pb-3 col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">NIP</label>
                                    <input type="text" class="@error('nip') is-invalid @enderror form-control" name="nip" id="" value="{{ old('nip') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Nama Karyawan</label>
                                    <input type="text" class="@error('nama') is-invalid @enderror form-control" name="nama" id="" value="{{ old('nama') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card p-2 ml-3 mr-3 shadow">
                    <div class="card-header" id="headingTwo">
                        <h6 class="ml-3" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <a class="text-decoration-none" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Penghasilan Teratur</a>
                        </h6>
                    </div>

                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="row m-0 pb-3 col-md-12">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Tunjangan</label>
                                    <select name="" id="" class="form-control">
                                        <option value="-">--- Pilih ---</option>
                                            <option value="">Nama Tunjangan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Nominal</label>
                                    <input type="number" id="" name="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-add">
                                    <i class="bi-plus-lg"></i>
                                </button>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-delete">
                                    <i class="bi-dash-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card p-2 ml-3 mr-3 shadow" id="data_is">
                    <div class="card-header" id="headingThree">
                        <h6 class="ml-3" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                            <a class="text-decoration-none" href="" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">Penghasilan Tidak Teratur</a>
                        </h6>
                    </div>

                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="row m-0 pb-3 col-md-12">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Tunjangan</label>
                                    <select name="" id="" class="form-control">
                                        <option value="-">--- Pilih ---</option>
                                            <option value="">Nama Tunjangan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Nominal</label>
                                    <input type="number" id="" name="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-add">
                                    <i class="bi-plus-lg"></i>
                                </button>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-delete">
                                    <i class="bi-dash-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>         
                </div>

                <div class="card p-2 ml-3 mr-3 shadow">
                    <div class="card-header" id="headingFour">
                        <h6 class="ml-3" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                            <a class="text-decoration-none" href="" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">Bonus</a>
                        </h6>
                    </div>

                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                        <div class="row m-0 pb-3 col-md-12">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Tunjangan</label>
                                    <select name="" id="" class="form-control">
                                        <option value="-">--- Pilih ---</option>
                                            <option value="">Nama Tunjangan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Nominal</label>
                                    <input type="number" id="" name="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-add">
                                    <i class="bi-plus-lg"></i>
                                </button>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-delete">
                                    <i class="bi-dash-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row m-3">
                <button type="submit" id="submit" class="btn btn-info">Simpan</button>
            </div>
        </form>
    </div>
@endsection

@section('custom_script')
    <script>
        var x =1;

        $('#collapseTwo').on('click', "#btn-add", function(){
            $('#collapseTwo').append(`
            <div class="row m-0 pb-3 col-md-12">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Tunjangan</label>
                                    <select name="" id="" class="form-control">
                                        <option value="">--- Pilih ---</option>
                                            <option value="">Data Tunjangan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for=""">Nominal</label>
                                    <input type="number" id="" name="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-add">
                                    <i class="bi-plus-lg"></i>
                                </button>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-delete">
                                    <i class="bi-dash-lg"></i>
                                </button>
                            </div>
                        </div>
            `);
            x++
        });

        $('#collapseTwo').on('click', "#btn-delete", function(){
            if(x > 1){
                $(this).closest('.row').remove();
                x--;
            }
        })

        $('#collapseThree').on('click', "#btn-add", function(){
            $('#collapseThree').append(`
            <div class="row m-0 pb-3 col-md-12">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Tunjangan</label>
                                    <select name="" id="" class="form-control">
                                        <option value="">--- Pilih ---</option>
                                            <option value="">Data Tunjangan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Nominal</label>
                                    <input type="number" id="" name="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-add">
                                    <i class="bi-plus-lg"></i>
                                </button>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-delete">
                                    <i class="bi-dash-lg"></i>
                                </button>
                            </div>
                        </div>
            `);
            x++
        });

        $('#collapseThree').on('click', "#btn-delete", function(){
            if(x > 1){
                $(this).closest('.row').remove();
                x--;
            }
        })

        $('#collapseFour').on('click', "#btn-add", function(){
            $('#collapseFour').append(`
            <div class="row m-0 pb-3 col-md-12">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Tunjangan</label>
                                    <select name="" id="" class="form-control">
                                        <option value="">--- Pilih ---</option>
                                            <option value="">Data Tunjangan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Nominal</label>
                                    <input type="number" id="" name="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-add">
                                    <i class="bi-plus-lg"></i>
                                </button>
                            </div>
                            <div class="col-md-1 mt-3">
                                <button class="btn btn-info" type="button" id="btn-delete">
                                    <i class="bi-dash-lg"></i>
                                </button>
                            </div>
                        </div>
            `);
            x++
        });

        $('#collapseFour').on('click', "#btn-delete", function(){
            if(x > 1){
                $(this).closest('.row').remove();
                x--;
            }
        })
    </script>
@endsection