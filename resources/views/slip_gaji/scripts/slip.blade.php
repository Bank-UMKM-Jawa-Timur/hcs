@push('script')
    <script src="{{asset('vendor/printpage/printpage.min.js')}}"></script>
    <script>
        var nipKaryawan = "";
        var namaKaryawan = '';
        loadKaryawan()

        function loadKaryawan() {
            const selected = "{{\Request::get('nip')}}"
            const name = "{{\Request::get('nama_karyawan')}}"
            const kantor = $('#kantor').val()
            const cabang = "{{$cabang}}"
            const divisi = $('#divisi').val()
            const sub_divisi = $('#sub_divisi').val()
            const bagian = $('#bagian').val()

            nipKaryawan = selected;

            $('#nip').empty()
            $('#nip_per_cabang').select2()
            // Load karyawan options
            $('#nip').select2({
                ajax: {
                    url: '{{ route('api.select2.karyawan.jabatan') }}',
                    data: function (params) {
                        var query = {
                            search: params.term,
                            kantor:kantor,
                            cabang:cabang,
                            page: params.page
                        }
                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                    },
                    success: function (response) {

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

        $.ajax({
            type: "GET",
            url: "/get-name-karyawan/" + nipKaryawan,
            success: function (response) {
                namaKaryawan = response.data[0].nama_karyawan;
                $('#select2-nip-container').html(nipKaryawan + ' - ' +namaKaryawan)
            },
            error: function (error) {

            }
        });

        function convertToTerbilang(number) {
            var bilangan = [
                '',
                'Satu',
                'Dua',
                'Tiga',
                'Empat',
                'Lima',
                'Enam',
                'Tujuh',
                'Delapan',
                'Sembilan',
                'Sepuluh',
                'Sebelas'
            ];

            var terbilang = '';

            if (number < 12) {
                terbilang = bilangan[number];
            } else if (number < 20) {
                terbilang = convertToTerbilang(number - 10) + ' Belas';
            } else if (number < 100) {
                terbilang = convertToTerbilang(Math.floor(number / 10)) + ' Puluh ' + convertToTerbilang(number % 10);
            } else if (number < 200) {
                terbilang = ' Seratus ' + convertToTerbilang(number - 100);
            } else if (number < 1000) {
                terbilang = convertToTerbilang(Math.floor(number / 100)) + ' Ratus ' + convertToTerbilang(number % 100);
            } else if (number < 2000) {
                terbilang = ' Seribu ' + convertToTerbilang(number - 1000);
            } else if (number < 1000000) {
                terbilang = convertToTerbilang(Math.floor(number / 1000)) + ' Ribu ' + convertToTerbilang(number % 1000);
            } else if (number < 1000000000) {
                terbilang = convertToTerbilang(Math.floor(number / 1000000)) + ' Juta ' + convertToTerbilang(number % 1000000);
            } else if (number < 1000000000000) {
                terbilang = convertToTerbilang(Math.floor(number / 1000000000)) + ' Miliar ' + convertToTerbilang(number % 1000000000);
            } else if (number < 1000000000000000) {
                terbilang = convertToTerbilang(Math.floor(number / 1000000000000)) + ' Triliun ' + convertToTerbilang(number % 1000000000000);
            }

            return terbilang ;
        }

        function generatePendapatanItem(data) {
            var tableTunjangan = ``;
            // Gaji Pokok
            if (data.gj_pokok > 0) {
                tableTunjangan += `
                    <tr style="border:1px solid #e3e3e3">
                        <td class="px-3">Gaji Pokok</td>
                        <td class="text-right px-3">Rp ${formatRupiahPayroll(data.gj_pokok)}</td>
                    </tr>
                `
            }
            // T. Keluarga
            if (data.tj_keluarga > 0) {
                tableTunjangan += `
                    <tr style="border:1px solid #e3e3e3">
                        <td class="px-3">Tj. Keluarga</td>
                        <td class="text-right px-3">Rp ${formatRupiahPayroll(data.tj_keluarga)}</td>
                    </tr>
                `
            }
            // Jabatan
            if (data.tj_jabatan > 0) {
                tableTunjangan += `
                    <tr style="border:1px solid #e3e3e3">
                        <td class="px-3">Tj. Jabatan</td>
                        <td class="text-right px-3">Rp ${formatRupiahPayroll(data.tj_jabatan)}</td>
                    </tr>
                `
            }
            // Gaji Penyesuaian
            if (data.gj_penyesuaian > 0) {
                tableTunjangan += `
                    <tr style="border:1px solid #e3e3e3">
                        <td class="px-3">Penyesuaian</td>
                        <td class="text-right px-3">Rp ${formatRupiahPayroll(data.gj_penyesuaian)}</td>
                    </tr>
                `
            }
            // T. Perumahan
            if (data.tj_perumahan > 0) {
                tableTunjangan += `
                    <tr style="border:1px solid #e3e3e3">
                        <td class="px-3">Tj. Perumahan</td>
                        <td class="text-right px-3">Rp ${formatRupiahPayroll(data.tj_perumahan)}</td>
                    </tr>
                `
            }
            // T. Telp, Listrik & Air
            if (data.tj_telepon > 0) {
                tableTunjangan += `
                    <tr style="border:1px solid #e3e3e3">
                        <td class="px-3">Tj. Telepon, Listrik & Air</td>
                        <td class="text-right px-3">Rp ${formatRupiahPayroll(data.tj_telepon)}</td>
                    </tr>
                `
            }
            // T. Pelaksana
            if (data.tj_pelaksana > 0) {
                tableTunjangan += `
                    <tr style="border:1px solid #e3e3e3">
                        <td class="px-3">Tj. Pelaksana</td>
                        <td class="text-right px-3">Rp ${formatRupiahPayroll(data.tj_pelaksana)}</td>
                    </tr>
                `
            }
            // T. Kemahalan
            if (data.tj_kemahalan > 0) {
                tableTunjangan += `
                    <tr style="border:1px solid #e3e3e3">
                        <td class="px-3">Tj. Kemahalan</td>
                        <td class="text-right px-3">Rp ${formatRupiahPayroll(data.tj_kemahalan)}</td>
                    </tr>
                `
            }
            // T. Kesejahteraan
            if (data.tj_kesejahteraan > 0) {
                tableTunjangan += `
                    <tr style="border:1px solid #e3e3e3">
                        <td class="px-3">Tj. Kesejahteraan</td>
                        <td class="text-right px-3">Rp ${formatRupiahPayroll(data.tj_kesejahteraan)}</td>
                    </tr>
                `
            }
            var tj_khusus = 0;
            // T. Multilevel
            if (data.tj_ti > 0) {
                tj_khusus += data.tj_ti
            }
            if (data.tj_multilevel > 0) {
                tj_khusus += data.tj_multilevel
            }
            if (data.tj_fungsional > 0) {
                tj_khusus += data.tj_fungsional
            }
            if (tj_khusus > 0) {
                tableTunjangan += `
                    <tr style="border:1px solid #e3e3e3">
                        <td class="px-3">Tj. Khusus</td>
                        <td class="text-right px-3">Rp ${formatRupiahPayroll(tj_khusus)}</td>
                    </tr>
                `
            }
            return tableTunjangan;
        }

        function lamaBekerja(date) {
            var dateFormat = /^\d{4}-\d{2}-\d{2}$/;

            if (!date.match(dateFormat)) {
                console.error("Please use the format 'Y-m-d'.");
                return null;
            }

            var dateParts = date.split("-");
            var mulaiKerja = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);

            var now = new Date();

            var differenceInMilliseconds = now - mulaiKerja;

            var years = Math.floor(differenceInMilliseconds / (365.25 * 24 * 60 * 60 * 1000));
            var months = Math.floor((differenceInMilliseconds % (365.25 * 24 * 60 * 60 * 1000)) / (30.44 * 24 * 60 * 60 * 1000));

            return {
                tahun: years,
                bulan: months,
            };
        }

        $('.show-data').on('click',function(e) {
            const targetId = $(this).data("target-id");
            const nip = $(this).data('nip');
            const tahun = "{{\Request::get('tahun')}}";
            const nama = $(this).data("nama");
            const norek = $(this).data("no_rekening");
            const jabatan = $(this).data("status_jabatan");
            const tanggalPengangkat = $(this).data("tanggal_pengangkat") ? $(this).data("tanggal_pengangkat") : '-';
            const tanggalPengangkatFormated = $(this).data("tanggal_pengangkat_formated") ? $(this).data("tanggal_pengangkat_formated") : '-';
            const result = lamaBekerja(tanggalPengangkat);
            const data = $(this).data('json');
            const bulan = data.bulan;
            const bulanName = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
            const download_url = `{{route('slip.cetak_slip')}}?nip=${nip}&bulan=${bulan}&tahun=${tahun}`

            $('#print-gaji').attr('href', download_url);
            $('#request_month').val(bulan);

            $('.periode').html(`Periode ${tahun} ${bulanName[bulan - 1]}`)

            $('#download-gaji').on('click',function(e) {
                $.ajax({
                    type: "GET",
                    url: `{{ route('slip.cetak_slip') }}`,
                    data: {
                        request_nip: nip,
                        request_month: bulan,
                        request_year: tahun,
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(response){
                        var blob = new Blob([response]);
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = "slip-gaji.pdf";
                        link.click();
                    },
                });
            })

            $('#table-tunjangan > tbody').empty();
            $('#table-tunjangan-total ').empty();

            $('#table-potongan > tbody').empty()
            $("#table-total-potongan").empty()

            $("#table-total-diterima thead").empty();

            $('#data-nip').html(`${nip}`)
            $('#nama').html(`${nama}`)
            $('#no_rekening').html(`${norek ? norek : '-'}`)
            // $('#data-jabatan').html(`${jabatan}`)
            $('#tanggal-bergabung').html(`${tanggalPengangkatFormated}`)

            if (result !== null) {
                const { tahun, bulan } = result;
                const settDate = `${tahun} tahun, ${bulan} bulan`;
                $('#lama-kerja').html(settDate);
            } else {
                $('#lama-kerja').html("-");
            }

            var nominal = 0;
            // Tunjangan
            var tableTunjangan = generatePendapatanItem(data);

            $("#table-tunjangan tbody").append(tableTunjangan);

            var tableTotalTunjanganTeratur = `
                <tr>
                    <th class="px-3">TOTAL PENDAPATAN</th>
                    <th class="text-right px-3">Rp ${formatRupiahPayroll(data.total_gaji)}</th>
                </tr>
            `
            $("#table-tunjangan-total").append(tableTotalTunjanganTeratur);
            // END TUNJANGAN TERATUR
            // POTONGAN
            var kredit_koperasi = data.kredit_koperasi ? data.kredit_koperasi : 0;
            var iuran_koperasi = data.iuran_koperasi ? data.iuran_koperasi : 0;
            var kredit_pegawai = data.kredit_pegawai ? data.kredit_pegawai : 0;
            var iuran_ik = data.iuran_ik ? data.iuran_ik : 0;
            var bpjs_tk = data.bpjs_tk ? data.bpjs_tk : 0;
            var potongan_dpp = data.potongan.dpp ? data.potongan.dpp : 0;
            var total_potongan = parseInt(data.bpjs_tk) + parseInt(data.potongan.dpp) + parseInt(kredit_koperasi) + parseInt(iuran_koperasi) + parseInt(kredit_pegawai) + parseInt(iuran_ik);
            var total_diterima = parseInt(data.total_gaji) - total_potongan;
            var potongan = ``;

            if (bpjs_tk > 0 ) {
                potongan += `
                <tr style="border:1px solid #e3e3e3">
                    <td class="px-3">JP BPJS TK 1%</td>
                    <td id="gaji_pokok" class="text-right px-3">Rp ${formatRupiahPayroll(parseInt(data.bpjs_tk))}</td>
                </tr>`
            }
            if (potongan_dpp > 0) {
                potongan += `
                <tr style="border:1px solid #e3e3e3">
                    <td class="px-3">DPP 5%</td>
                    <td id="gaji_pokok" class="text-right px-3">Rp ${formatRupiahPayroll(data.potongan.dpp)}</td>
                </tr>`
            }
            if (kredit_koperasi > 0) {
                potongan += `
                <tr style="border:1px solid #e3e3e3">
                    <td class="px-3">KREDIT KOPERASI</td>
                    <td id="gaji_pokok" class="text-right px-3">Rp ${formatRupiahPayroll(parseInt(data.kredit_koperasi))}</td>
                </tr>`
            }
            if (iuran_koperasi > 0) {
                potongan += `<tr style="border:1px solid #e3e3e3">
                    <td class="px-3">IUARAN KOPERASI	</td>
                    <td id="gaji_pokok" class="text-right px-3">Rp ${formatRupiahPayroll(parseInt(data.iuran_koperasi))}</td>
                </tr>`
            }
            if (kredit_pegawai > 0) {
                potongan += `
                <tr style="border:1px solid #e3e3e3">
                    <td class="px-3">KREDIT PEGAWAI	</td>
                    <td id="gaji_pokok" class="text-right px-3">Rp ${formatRupiahPayroll(parseInt(data.kredit_pegawai))}</td>
                </tr>`
            }
            if (iuran_ik > 0) {
                potongan += `
                <tr style="border:1px solid #e3e3e3">
                    <td class="px-3">IURAN IK</td>
                    <td id="gaji_pokok" class="text-right px-3">Rp ${formatRupiahPayroll(parseInt(data.iuran_ik))}</td>
                </tr>`
            }
            $('#table-potongan tbody').append(potongan);
            var tableTotalPotongan = `
                <tr>
                    <th class="px-3">TOTAL POTONGAN</th>
                    <th class="text-right px-3">Rp ${formatRupiahPayroll(total_potongan.toString())}</th>
                </tr>
            `
            $("#table-total-potongan").append(tableTotalPotongan);
            // END POTONGAN
            var totalGaji = total_diterima > 0 ? formatRupiahPayroll(total_diterima.toString()) : '-';
            var terbilang = total_diterima > 0 ? total_diterima : '-';
            var tableTotalDiterima = `
                <tr class="bg-primary text-center text-white p-1 rounded">
                    <th colspan="2" width="40%">Total Gaji Yang Diterima <i>(Take Home Pay)</i></th>
                </tr>
                <tr>
                    <th width="40%">Jumlah</th>
                    <th class="text-right px-3">Rp ${totalGaji}</th>
                </tr
                <tr>
                    <th width="40%">Terbilang</th>
                    <th>${convertToTerbilang(terbilang) + " Rupiah"}</th>
                </tr
            `
            $("#table-total-diterima thead").append(tableTotalDiterima);
        })

        $(document).ready(function() {
            $('#print-gaji').printPage();
        })
    </script>
@endpush
