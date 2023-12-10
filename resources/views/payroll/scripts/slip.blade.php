@push('script')
    <script>
        loadKaryawan()

        function loadKaryawan() {
            const selected = "{{\Request::get('nip')}}"
            const kantor = $('#kantor').val()
            const cabang = $('#cabang').val()
            const divisi = $('#divisi').val()
            const sub_divisi = $('#sub_divisi').val()
            const bagian = $('#bagian').val()

            $('#nip').empty()
            // Load karyawan options
            $('#nip').select2({
                ajax: {
                    url: '{{ route('api.select2.karyawan.jabatan') }}',
                    data: function (params) {
                        var query = {
                            search: params.term,
                            kantor:kantor,
                            page: params.page
                        }

                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                    }
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
