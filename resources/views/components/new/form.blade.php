@extends('layouts.app-template')
@section('content')

<div class="head">
    <div class="heading">
        <h2>Form</h2>
        <div class="breadcrumb">
            <a href="#" class="text-sm text-gray-500">Component</a>
            <i class="ti ti-circle-filled text-theme-primary"></i>
            <a href="#" class="text-sm text-gray-500">Form</a>
        </div>
    </div>
</div>
<div class="body-pages">
    <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
        <div class="card">
            <div class="input-box">
                <label for="textfield">Text field</label>
                <input type="text" id="textfield" class="form-input">
            </div>
        </div>
        <div class="card">
            <div class="input-box">
                <label for="datefield">Date field</label>
                <input type="date" id="datefield" class="form-input">
            </div>
        </div>
        <div class="card">
            <div class="input-box">
                <label for="numberfield">Number field</label>
                <input type="number" id="numberfield" class="form-input">
            </div>
        </div>
        <div class="card">
            <div class="input-box">
                <label for="selectfield">Select field</label>
                <select name="" class="form-input" id="">
                    <option value="">-- Pilih --</option>
                </select>
            </div>
        </div>
        <div class="card">
            <div class="input-box">
                <label for="uploadfile">Upload field</label>
                <div class="input-group">
                    <input type="file" id="uploadfile" class="form-upload">
                    <button class="upload-group-icon">
                        <label for="uploadfile">
                            <i class="ti ti-upload"></i>
                        </label>
                    </button>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="input-box">
                <label for="grouped-text-field">Grouped Text field</label>
                <div class="input-group">
                    <input type="text" id="grouped-text-field" class="form-input-grouped">
                    <div class="group-icon">
                       %
                    </div>
                </div>
            </div>
        </div>
        <div class="card col-span-3">
            <div class="input-box">
                <label for="">Textarea field</label>
                <textarea name="" id="" class="form-input" cols="30" rows="10"></textarea>
            </div>
        </div>
        <div class="card col-span-3">
            <div class="input-box">
                <label for="id_label_single">
                    Select 2 Tailwind
                </label>
                <select class="select-2 select-2-tailwind">
                    <option selected>-- Pilih Nama --</option>
                    <option value="">Arsyad Arthan Nurrohim</option>
                    <option value="">Mohammad Sahrullah</option>
                    <option value="">Muhammad Khalil Zhillullah</option>
                    <option value="">Muhammad Rhomaedi</option>
                    <option value="">Tampan Oktaviand</option>
                    <option value="">Maulana Malik Ibrahim</option>
                    <option value="">Rifjan Jundila</option>
                </select>
            </div>
        </div>
    </div>
</div>
@endsection

@push('extraScript')
<script>
    $(".select-2").select2({});
</script>
@endpush