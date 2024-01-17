@include('vendor.select2')
@csrf

<div class="col">
    <div class="row">
        <div class="form-group col-md-6">
            <label for="nip">Karyawan</label>
            <select name="nip" id="nip" class="form-control @error('nip') is-invalid @enderror" @disabled($ro ?? null)></select>
            @error('nip')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col-md-6">
            <label for=""></label>
            <input type="text" id="jb-entity" class="form-control disabled mt-1" readonly>
        </div>
    </div>
</div>

<div class="col">
    <div class="row" id="jabatan-wrapper">
        <div class="form-group col-md-6">
            <label for="tanggal-mulai">Tanggal Mulai PJS</label>
            <input type="date" name="tanggal_mulai" id="tanggal-mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror">
            @error('tanggal_mulai')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col-md-6">
            <label for="jabatan">Jabatan</label>
            <select name="kd_jabatan" id="jabatan" class="form-control @error('kd_jabatan') is-invalid @enderror">
                <option value="">-- Pilih Jabatan --</option>
            </select>
            @error('kd_jabatan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="col">
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="no_sk">No SK</label>
            <input type="text" name="no_sk" id="no_sk" class="form-control @error('no_sk') is-invalid @enderror">
            @error('no_sk')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col-md-6">
            <label for="">SK PJS</label>
            <div class="custom-file">
                <input type="file" class="custom-file-input @error('file_sk') is-invalid @enderror" name="file_sk" id="validatedCustomFile" accept="application/pdf">
                <label class="custom-file-label overflow-hidden" for="validatedCustomFile">Choose file(.pdf) ...</label>
                @error('file_sk')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-12 text-right">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </div>
</div>



@push('script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $("#validatedCustomFile").on('change', function(e){
        var ext = this.value.match(/\.([^\.]+)$/)[1];
        if(ext != 'pdf'){
            Swal.fire({
                title: 'Terjadi Kesalahan.',
                text: 'File harus PDF',
                icon: 'error'
            })
        }
    })
    
    document.querySelector('.custom-file-input').addEventListener('change', function (e) {
            var name = document.getElementById("validatedCustomFile").files[0].name;
            var ext = name.match(/\.([^\.]+)$/)[1];
            var nextSibling = e.target.nextElementSibling
            if(ext == 'pdf'){
                nextSibling.innerText = name
            } else {
                nextSibling.innerText = ''
                $("#validatedCustomFile").val('Choose File(.pdf) ...')
            }
        });
        const posArray = JSON.parse('@php echo json_encode($jabatan) @endphp');

        const nipSelect = $('#nip').select2({
            ajax: {
                url: '{{ route('api.select2.karyawan.pjs') }}',
                data: function(params) {
                    return {
                        search: params.term || '',
                        page: params.page || 1
                    }
                },
                cache: true,
            },
            templateResult: function(data) {
                if(data.loading) return data.text;
                return $(`
                    <span>${data.nama}<br><span class="text-secondary">${data.id} - ${data.jabatan}</span></span>
                `);
            }
        });

        $('#nip').on('select2:select', function() {
            const nip = $(this).val();
            let jabatan = '';

            $.ajax({
                url: `{{ route('api.karyawan') }}?nip=${nip}`,
                dataType: 'JSON',
                success(res) {
                    const entitas = res.data.entitas;
                    const bagian = res.data.bagian?.nama_bagian || '';
                    jabatan = res.data?.jabatan?.nama_jabatan || '';

                    managePositions(res.data.jabatan);

                    if(Object.hasOwn(entitas, 'subDiv')) {
                        $('#jb-entity').val(`${jabatan} ${bagian} ${entitas.subDiv.nama_subdivisi}`);
                        return;
                    }

                    if(Object.hasOwn(entitas, 'div')) {
                        $('#jb-entity').val(`${jabatan} ${bagian} ${entitas.div.nama_divisi}`);
                        return;
                    }

                    if(Object.hasOwn(entitas, 'cab')) {
                        $('#jb-entity').val(`${jabatan} ${bagian} ${entitas.cab.nama_cabang}`);
                        return;
                    }

                    $('#jb-entity').val(`${jabatan} ${bagian}`);
                }
            });
        });

        $('#jabatan').change(function() {
            const value = $(this).val();
            generateKantor();

            if(value == 'PBP' || value == 'PC') {
                $('#kantor').val('cabang').trigger('change').attr('disabled', true);
            }

            if(value == 'PIMDIV' || value == 'PSD') {
                $('#kantor').val('pusat').trigger('change').attr('disabled', true);
            }
        });

        function managePositions(jabatan) {
            let skippedPos = [];

            if(jabatan.kd_jabatan == 'PSD')
                skippedPos = ['PEN', 'PBP', 'PBO'];

            if(jabatan.kd_jabatan == 'PIMDIV')
                skippedPos = ['PEN', 'PSD', 'PBP', 'PBO'];

            generateJabatan(
                posArray.filter((data) => !skippedPos.includes(data.kd_jabatan))
            );
        }

        function generateJabatan(jabatan)
        {
            $('#jabatan').html('<option>-- Pilih Jabatan--</option>');

            jabatan.map(function(data) {
                $('#jabatan').append(`<option value="${data.kd_jabatan}">${data.nama_jabatan}</option>`);
            });
        }

        function generateKantor()
        {
            $('#kantor').parent().remove();
            $('#cabang').parent().remove();
            $('#divisi').parent().remove();

            $('#jabatan-wrapper').append(`
                <div class="form-group col-md-4">
                    <label for="kantor">Kantor</label>
                    <select class="form-control" name="kantor" id="kantor">
                        <option>-- Pilih Kantor --</option>
                        <option value="pusat">Pusat</option>
                        <option value="cabang">Cabang</option>
                    </select>
                </div>
            `);

            $('#kantor').change(function() {
                const value = $(this).val();
                const rmEls = ['#cabang', '#divisi', '#bagian', '#subdiv'];

                rmEls.map((selector) => $(selector).parent().remove());

                if(value == 'pusat') {
                    generateDivisi();
                    return;
                }

                generateCabang();
            });
        }

        function generateDivisi() {
            let divisiOption = '';

            $.ajax({
                url: "{{ route('get_divisi') }}",
                dataType: 'JSON',
                success: (res) => {
                    $.each(res, (i, item) => {
                        divisiOption += `
                            <option value="${item.kd_divisi}">${item.nama_divisi}</option>
                        `;
                    });

                    $('#jabatan-wrapper').append(`
                        <div class="form-group col-md-4">
                            <label for="divisi">Divisi</label>
                            <select class="form-control" name="kd_divisi" id="divisi">
                                <option>-- Pilih Divisi --</option>
                                ${divisiOption}
                            </select>
                        </div>
                    `);

                    $('#divisi').change(function() {
                        generateSubDivisi($(this).val());
                        generateBagian(null, $(this).val());
                    });
                }
            })
        }

        function generateCabang() {
            let cabangOption = '';

            $.ajax({
                url: "{{ route('get_cabang') }}",
                dateType: 'JSON',
                success: (res) => {
                    $.each(res[0], (i, item) => {
                        cabangOption += `
                            <option value="${item.kd_cabang}">${item.nama_cabang}</option>
                        `;
                    });

                    $('#jabatan-wrapper').append(`
                        <div class="form-group col-md-4">
                            <label for="cabang">Cabang</label>
                            <select class="form-control" name="kd_cabang" id="cabang">
                                <option>-- Pilih Cabang --</option>
                                ${cabangOption}
                            </select>
                        </div>
                    `);

                    generateBagian(res[1]);
                }
            });
        }

        function generateSubDivisi(divisi) {
            const generateSkipper = ['PBO', 'PIMDIV'];
            let subDivOption = '';

            if(generateSkipper.includes($('#jabatan').val())) return;
            $('#subdiv').parent().remove();
            $('#bagian').parent().remove();

            $.ajax({
                url: `{{ route('get_subdivisi') }}?divisiID=${divisi}`,
                dataType: 'JSON',
                success: (res) => {
                    if(res.length < 1) return;

                    $.each(res, (i, item) => {
                        subDivOption += `
                            <option value="${item.kd_subdiv}">${item.nama_subdivisi}</option>
                        `;
                    });

                    $('#jabatan-wrapper').append(`
                        <div class="form-group col-md-4">
                            <label for="subdiv">Sub Divisi</label>
                            <select class="form-control" name="kd_subdiv" id="subdiv">
                                <option>-- Pilih Sub Divisi --</option>
                                ${subDivOption}
                            </select>
                        </div>
                    `);

                    $('#subdiv').change(function() {
                        generateBagian(null, $(this).val());
                    });
                }
            });
        }

        function generateBagian(data, entitas = null) {
            const generateSkipper = ['NST', 'PBO', 'PBP', 'PC', 'PSD'];
            let bagianOption = '';

            if(generateSkipper.includes($('#jabatan').val())) return;
            $('#bagian').parent().remove();

            function generateBagianElement(options) {
                $('#jabatan-wrapper').append(`
                    <div class="form-group col-md-4">
                        <label for="bagian">Bagian</label>
                        <select class="form-control" name="kd_bagian" id="bagian">
                            <option>-- Pilih Bagian --</option>
                            ${options}
                        </select>
                    </div>
                `);
            }

            if(data != null) {
                $.each(data, (i, item) => {
                    bagianOption += `
                        <option value="${item.kd_bagian}">${item.nama_bagian}</option>
                    `;
                });

                generateBagianElement(bagianOption);
                return;
            }

            $.ajax({
                url: `{{ route('getBagian') }}?kd_entitas=${entitas}`,
                dataType: 'JSON',
                success: (res) => {
                    if(res.length < 1) return;

                    $.each(res, (i, item) => {
                        bagianOption += `
                            <option value="${item.kd_bagian}">${item.nama_bagian}</option>
                        `;
                    });

                    generateBagianElement(bagianOption);
                }
            });
        }
    </script>
@endpush
