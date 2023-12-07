@push('script')
    <script>
        /**
        * new Option(id, text, false, checked)
        **/
        var selected_kantor = $('#kantor').val()
        if (selected_kantor == '0' || selected_kantor == 'pusat') {
            // Hide cabang
            $('.cabang-input').addClass('d-none')
            $('.divisi-input').removeClass('d-none')
            $('.sub-divisi-input').removeClass('d-none')

            // Load divisi options
            loadDivisi()

            // Load sub divisi options
            const kode_div = "{{\Request::get('divisi')}}"
            loadSubDivisi(kode_div)

            // Load bagian
            const selected_cabang = "{{\Request::get('cabang')}}"
            const selected_kd_subdiv = "{{\Request::get('sub_divisi')}}"
            let kd_entitas = '';
            if (selected_cabang != '0' && selected_kd_subdiv == '0') {
                kd_entitas = selected_cabang;
            }
            if (selected_cabang == '0' && selected_kd_subdiv != '0') {
                kd_entitas = selected_kd_subdiv;
            }
            loadBagian(kd_entitas)
        }
        else {
            // Show cabang
            $('.cabang-input').removeClass('d-none')
        }

        $('#kantor').on('change', function() {
            const selected = $(this).val()

            if (selected == 'cabang') {
                $('.cabang-input').removeClass('d-none')
                $('.divisi-input').addClass('d-none')
                $('.sub-divisi-input').addClass('d-none')
            }
            else {
                $('.cabang-input').addClass('d-none')
                $('.divisi-input').removeClass('d-none')
                $('.sub-divisi-input').removeClass('d-none')

                // Load divisi options
                loadDivisi()
            }
        })
        
        // Divisi onchange
        $('#divisi').on('change', function() {
            const selected = $(this).val()
            
            // Show sub divisi input
            $('.sub-divisi-input').removeClass('d-none')

            loadSubDivisi(selected)
        })

        // Divisi onchange
        $('#sub_divisi').on('change', function() {
            const selected = $(this).val()
            
            loadBagian(selected)
        })

        // Cabang onchange
        $('#cabang').on('change', function() {
            const selected = $(this).val()
            
            loadBagian(selected)
        })

        function loadDivisi() {
            const selected = "{{\Request::get('divisi')}}"
            $('#divisi').empty()
            var item = {
                id: 0,
                text: '-- Semua Divisi --'
            };
            var newOption = new Option(item.text, item.id, false, false);
            var arrOption =[]
            arrOption.push(newOption)

            $.ajax({
                url: '{{ route('api.select2.divisi') }}',
                success: function(response) {
                    var data = response.results
                    $.each(data, function(i, item) {
                        var option = new Option(item.text, item.id, false, selected == item.kode);
                        arrOption.push(option)
                    })
                    $('#divisi').append(arrOption)
                    $('#divisi').select2({})
                }
            })
        }

        function loadSubDivisi(kode_div) {
            const selected = "{{\Request::get('sub_divisi')}}"
            // Load divisi options
            $('#sub_divisi').empty()
            var item = {
                id: 0,
                text: '-- Semua Sub Divisi --'
            };
            var newOption = new Option(item.text, item.id, false, false);
            var arrOption =[]
            arrOption.push(newOption)

            $.ajax({
                url: `{{ url('/api/select2/sub-divisi') }}/${kode_div}`,
                success: function(response) {
                    var data = response.results

                    $.each(data, function(i, item) {
                        var option = new Option(item.text, item.id, false, selected == item.kode);
                        arrOption.push(option)
                    })
                    $('#sub_divisi').append(arrOption)
                    $('#sub_divisi').select2({})
                }
            })
        }

        function loadBagian(kd_entitas) {
            const selected = "{{\Request::get('bagian')}}"
            const is_cabang = $('#kantor').val() == 'cabang'

            // Show bagian input
            $('.bagian-input').removeClass('d-none')

            // Reset option
            $('#bagian').empty()
            var item = {
                id: 0,
                text: '-- Semua Bagian --'
            };
            var newOption = new Option(item.text, item.id, false, false);
            var arrOption =[]
            arrOption.push(newOption)

            // Load bagian options
            $.ajax({
                url: `{{ url('/api/select2/bagian') }}`,
                data: {
                    'kd_entitas': kd_entitas,
                    'is_cabang': is_cabang
                },
                success: function(response) {
                    var data = response.results

                    $.each(data, function(i, item) {
                        var option = new Option(item.text, item.id, false, selected == item.kode);
                        arrOption.push(option)
                    })
                    $('#bagian').append(arrOption)
                    $('#bagian').select2({})
                }
            })
        }

        function loadKaryawan() {
            const selected = "{{\Request::get('nip')}}"
            $('#nip').empty()
            var item = {
                id: 0,
                text: '-- Semua Karyawan --'
            };
            var newOption = new Option(item.text, item.id, false, false);
            var arrOption =[]
            arrOption.push(newOption)

            // Load karyawan options
            $('#nip').select2({
                ajax: {
                    url: '{{ route('api.select2.karyawan') }}'
                },
                templateResult: function(data) {
                    if(data.loading) return data.text;
                    return $(`
                        <span>${data.nama}<br><span class="text-secondary">${data.id} - ${data.jabatan}</span></span>
                    `);
                }
            });
        }

        const formatRupiahPayroll = (angka) => {
            let reverse = angka.toString().split('').reverse().join('');
            let ribuan = reverse.match(/\d{1,3}/g);
            ribuan = ribuan.join('.').split('').reverse().join('');
            return `${ribuan}`;
        }

        $('#page_length').on('change', function() {
            $('#form').submit()
        })

        $('#form').on('submit', function() {
            $('.loader-wrapper').css('display: none;')
            $('.loader-wrapper').addClass('d-block')
            $(".loader-wrapper").fadeOut("slow");
        })

        // Adjust pagination url
        var btn_pagination = $(`.pagination`).find('a')
        var page_url = window.location.href
        $(`.pagination`).find('a').each(function(i, obj) {
            if (page_url.includes('kantor')) {
                btn_pagination[i].href += `&kantor=${$('#kantor').val()}`
            }
            if (page_url.includes('cabang')) {
                btn_pagination[i].href += `&cabang=${$('#cabang').val()}`
            }
            if (page_url.includes('divisi')) {
                btn_pagination[i].href += `&divisi=${$('#divisi').val()}`
            }
            if (page_url.includes('sub_divisi')) {
                btn_pagination[i].href += `&sub_divisi=${$('#sub_divisi').val()}`
            }
            if (page_url.includes('bagian')) {
                btn_pagination[i].href += `&bagian=${$('#bagian').val()}`
            }
            if (page_url.includes('nip')) {
                btn_pagination[i].href += `&nip=${$('#nip').val()}`
            }
            if (page_url.includes('bulan')) {
                btn_pagination[i].href += `&bulan=${$('#bulan').val()}`
            }
            if (page_url.includes('tahun')) {
                btn_pagination[i].href += `&tahun=${$('#tahun').val()}`
            }
            if (page_url.includes('page_length')) {
                btn_pagination[i].href += `&page_length=${$('#page_length').val()}`
            }
            if (page_url.includes('q')) {
                btn_pagination[i].href += `&q=${$('#q').val()}`
            }
        })
    </script>
@endpush