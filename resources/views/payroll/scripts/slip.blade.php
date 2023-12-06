@push('script')
    <script>
        var selected_kantor = $('#kantor').val()
        if (selected_kantor == '0' || selected_kantor == 'pusat') {
            // Hide cabang
            $('.cabang-input').addClass('d-none')
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
                            var option = new Option(item.text, item.id, false, false);
                            arrOption.push(option)
                        })
                        $('#divisi').append(arrOption)
                        $('#divisi').select2({})
                    }
                })
            }
        })
        
        // Divisi onchange
        $('#divisi').on('change', function() {
            const selected = $(this).val()
            
            // Show sub divisi input
            $('.sub-divisi-input').removeClass('d-none')

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
                url: `{{ url('/api/select2/sub-divisi') }}/${selected}`,
                success: function(response) {
                    var data = response.results

                    $.each(data, function(i, item) {
                        var option = new Option(item.text, item.id, false, false);
                        arrOption.push(option)
                    })
                    $('#sub_divisi').append(arrOption)
                    $('#sub_divisi').select2({})
                }
            })
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

        function loadBagian(kd_entitas) {
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
                        var option = new Option(item.text, item.id, false, false);
                        arrOption.push(option)
                    })
                    $('#bagian').append(arrOption)
                    $('#bagian').select2({})
                }
            })
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
            if (page_url.includes('kategori')) {
                btn_pagination[i].href += `&kategori=${$('#kategori').val()}`
            }
            if (page_url.includes('cabang')) {
                btn_pagination[i].href += `&cabang=${$('#cabang').val()}`
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