@extends('layouts.app')

@section('title', 'Email Extractor Jurnal - OmniTools')

@section('content')
<!-- Hero Section -->
<section class="py-16 md:py-24 px-4 md:px-10 max-w-7xl mx-auto text-center flex flex-col items-center">
    <h1 class="text-3xl md:text-5xl font-headline-xl text-on-surface mb-6 max-w-4xl">
        Temukan Email Jurnal Berdasarkan Negara &amp; Keyword dengan Mudah
    </h1>
    <p class="text-base md:text-lg text-on-surface-variant mb-10 max-w-2xl">
        Ekstrak ratusan alamat email dari database jurnal tervalidasi secara instan. Tingkatkan efisiensi riset dan penjangkauan akademis Anda dengan akurasi tinggi.
    </p>
    {{-- <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
        <a href="#main"><button class="bg-primary text-on-primary px-8 py-4 rounded-lg hover:bg-primary-container transition-all shadow-md hover:-translate-y-0.5 w-full sm:w-auto flex items-center justify-center gap-2">
            <span class="material-symbols-outlined" data-icon="search">search</span>
            Cari Email Sekarang
        </button></a>
        <button class="border-2 border-outline-variant text-primary px-8 py-4 rounded-lg hover:border-primary hover:bg-surface-container-low transition-colors w-full sm:w-auto">
            Pelajari Lebih Lanjut
        </button>
    </div> --}}
</section>

<!-- Interactive Demo / Main Tool Area -->
<section id="main" class="py-16 px-4 md:px-10 bg-surface-container-lowest">
    <div class="max-w-4xl mx-auto">
        <div class="bg-surface rounded-xl shadow-lg p-6 md:p-10 border border-surface-variant overflow-hidden">
            <div class="flex items-center justify-between mb-4 pb-4 border-b border-surface-variant">
                <h2 class="text-2xl font-headline-md text-on-surface">Mulai Ekstraksi</h2>
            </div>
            
            <!-- Tab Switcher -->
            <div class="flex justify-center mb-8">
                <div class="inline-flex items-center bg-surface-container-low border border-surface-variant rounded-full p-1 shadow-sm relative">
                    <!-- Background Slider -->
                    <div id="tab-bg" class="absolute left-1 top-1 bottom-1 w-[calc(50%-4px)] bg-primary rounded-full transition-all duration-300 ease-in-out shadow-sm"></div>
                    
                    <button onclick="switchTab('keyword')" id="btn-keyword" class="relative z-10 px-8 py-1.5 rounded-full text-on-primary font-label-md text-label-md transition-colors duration-300 w-32 text-center">
                        Keyword
                    </button>
                    <button onclick="switchTab('url')" id="btn-url" class="relative z-10 px-8 py-1.5 rounded-full text-on-surface-variant font-label-md text-label-mdtransition-colors duration-300 w-32 text-center">
                        Url
                    </button>
                </div>
            </div>

            <!-- Forms Container (Slider) -->
            <div class="relative w-full overflow-hidden">
                <div id="form-slider" class="flex w-full transition-transform duration-500 ease-in-out transform translate-x-0">
                    
                    <!-- FORM 1: KEYWORD (Width 100% dari container parent) -->
                    <div class="w-full shrink-0 px-1">
                        <form id="form-keyword" onsubmit="extractData(event, 'keyword')">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <!-- Dropdown Country -->
                                <div class="flex flex-col gap-2">
                                    <label class="text-xs text-on-surface-variant font-bold uppercase tracking-wider">Pilih Negara</label>
                                    <div class="relative">
                                        <select id="country-select" class="w-full appearance-none bg-surface-container-low border border-transparent focus:border-primary focus:bg-surface rounded-lg px-4 py-3 text-on-surface outline-none transition-all pr-10 cursor-pointer">
                                            <option value="all">Semua Negara</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none" data-icon="expand_more">expand_more</span>
                                    </div>
                                </div>
                                <!-- Dropdown Keyword -->
                                <div class="flex flex-col gap-2">
                                    <label class="text-xs text-on-surface-variant font-bold uppercase tracking-wider">Pilih Keyword Jurnal</label>
                                    <div class="relative">
                                        <select id="keyword-select" class="w-full appearance-none bg-surface-container-low border border-transparent focus:border-primary focus:bg-surface rounded-lg px-4 py-3 text-on-surface outline-none transition-all pr-10 cursor-pointer">
                                            <option value="all">Semua Keyword</option>
                                            @foreach($keywords as $keyword)
                                                <option value="{{ $keyword->id }}">{{ $keyword->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none" data-icon="expand_more">expand_more</span>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="w-full bg-secondary text-on-secondary py-4 rounded-lg hover:bg-secondary-container transition-colors shadow-sm flex items-center justify-center gap-2 mb-4">
                                <span class="material-symbols-outlined" data-icon="manage_search">manage_search</span>
                                Extract Emails
                            </button>
                        </form>
                    </div>

                    <!-- FORM 2: URL -->
                    <div class="w-full shrink-0 px-1">
                        <form id="form-url" onsubmit="extractData(event, 'url')">
                            <div class="flex flex-col gap-2 mb-8">
                                <label class="font-label-sm text-label-sm text-on-surface-variant font-bold uppercase tracking-wider">URL ENDPOINT</label>
                                <div class="relative">
                                    <input id="url-input" class="w-full bg-surface-container-low border border-transparent focus:border-primary focus:bg-surface rounded-lg px-4 py-3 text-body-md text-on-surface outline-none transition-all cursor-text" placeholder="https://example.com/journal..." type="url" required/>
                                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none" data-icon="link">link</span>
                                </div>
                            </div>
                            <!-- Styled Button from User -->
                            <button type="submit" class="relative overflow-hidden w-full bg-secondary text-on-secondary font-label-md text-label-md py-4 rounded-lg hover:bg-secondary-container transition-all duration-300 shadow-[0_8px_20px_-4px_rgba(75,65,225,0.4)] hover:shadow-[0_12px_24px_-4px_rgba(75,65,225,0.5)] hover:-translate-y-0.5 flex items-center justify-center gap-2 mb-4 group">
                                <div class="absolute inset-0 w-full h-full bg-linear-to-r from-transparent via-white/10 to-transparent -translate-x-full animate-shimmer pointer-events-none"></div>
                                <span class="material-symbols-outlined transition-transform duration-300 group-hover:scale-110" data-icon="manage_search">manage_search</span>
                                <span class="tracking-wide">Extract Emails</span>
                            </button>
                        </form>
                    </div>

                </div>
            </div>

            <!-- Global Loading Indicator -->
            <div id="loading-indicator" class="hidden text-center py-6">
                <span class="material-symbols-outlined animate-spin text-primary text-3xl">autorenew</span>
                <p class="text-on-surface-variant mt-2 text-sm">Sedang mengekstrak data...</p>
            </div>

            <!-- Global Results Area -->
            <div id="results-area" class="hidden bg-surface-container-lowest rounded-lg border border-surface-variant p-4 mt-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-label-md text-label-md text-on-surface" id="results-title">Hasil Ekstraksi</h3>
                    <button onclick="exportToCSV()" class="text-secondary hover:text-secondary-fixed-dim font-label-sm text-label-sm flex items-center gap-1 transition-colors cursor-pointer hover:text-primary">
                        <span class="material-symbols-outlined text-[18px]" data-icon="download">download</span>
                        Export to CSV
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-surface-variant text-on-surface-variant text-xs">
                                <th class="py-2 px-3 font-medium">Email</th>
                                <th class="py-2 px-3 font-medium">Judul</th>
                                <th class="py-2 px-3 font-medium">Negara</th>
                                <th class="py-2 px-3 font-medium">Keyword</th>
                                <th class="py-2 px-3 font-medium text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="results-tbody" class="text-sm text-on-surface">
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Features Section -->
<section id="cara-kerja" class="py-20 px-4 md:px-10 max-w-7xl mx-auto" id="fitur">
    <div class="text-center mb-16">
        <h2 class="text-3xl font-headline-lg text-on-surface mb-4">Cara Kerja</h2>
        <p class="text-base text-on-surface-variant max-w-2xl mx-auto">Dapatkan ribuan email jurnal tervalidasi hanya dalam tiga langkah mudah.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-surface p-8 rounded-xl shadow-sm border border-surface-container hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-primary-fixed rounded-lg flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-primary" data-icon="filter_list">filter_list</span>
            </div>
            <h3 class="text-xl font-headline-md text-on-surface mb-3">1. Pilih Metode</h3>
            <p class="text-sm text-on-surface-variant">Anda dapat memilih menggunakan keyword dari database yang kami sediakan atau menggunakan URL yang ada anda dari internet</p>
        </div>
        <div class="bg-surface p-8 rounded-xl shadow-sm border border-surface-container hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-secondary-fixed rounded-lg flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-secondary" data-icon="auto_fix_high">auto_fix_high</span>
            </div>
            <h3 class="text-xl font-headline-md text-on-surface mb-3">2. Auto-Extract</h3>
            <p class="text-sm text-on-surface-variant">Sistem kami secara otomatis memfilter dan mengekstrak email dengan kriteria yang anda butuhkan.</p>
        </div>
        <div class="bg-surface p-8 rounded-xl shadow-sm border border-surface-container hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-tertiary-fixed rounded-lg flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-tertiary" data-icon="download">download</span>
            </div>
            <h3 class="text-xl font-headline-md text-on-surface mb-3">3. Download </h3>
            <p class="text-sm text-on-surface-variant">Simpan hasil ekstraksi dalam format CSV yang siap digunakan untuk riset atau kebutuhan lain anda.</p>
        </div>
    </div>
</section>

<!-- CTA Section -->
{{-- <section class="bg-primary py-20 px-4 md:px-10 text-center relative overflow-hidden">
    <div class="max-w-3xl mx-auto relative z-10">
        <h2 class="text-3xl font-headline-lg text-on-primary mb-6">
            Mulai kumpulkan email jurnal Anda sekarang
        </h2>
        <p class="text-lg text-primary-fixed-dim mb-10">
            Bergabunglah dengan ribuan peneliti yang telah menghemat ratusan jam pencarian manual.
        </p>
        <button class="bg-surface text-primary px-8 py-4 rounded-lg hover:bg-surface-container-low transition-colors shadow-lg">
            Buat Akun Gratis
        </button>
    </div>
</section> --}}

<script>
    let currentTab = 'keyword';
    let extractedEmails = [];

    // Switch Tab Function
    function switchTab(tab) {
        currentTab = tab;
        const formSlider = document.getElementById('form-slider');
        const tabBg = document.getElementById('tab-bg');
        const btnKeyword = document.getElementById('btn-keyword');
        const btnUrl = document.getElementById('btn-url');

        if (tab === 'keyword') {
            formSlider.style.transform = 'translateX(0)';
            tabBg.style.transform = 'translateX(0)';
            btnKeyword.classList.remove('text-on-surface-variant');
            btnKeyword.classList.add('text-on-primary');
            btnUrl.classList.remove('text-on-primary');
            btnUrl.classList.add('text-on-surface-variant');
        } else {
            formSlider.style.transform = 'translateX(-100%)';
            tabBg.style.transform = 'translateX(100%)';
            btnUrl.classList.remove('text-on-surface-variant');
            btnUrl.classList.add('text-on-primary');
            btnKeyword.classList.remove('text-on-primary');
            btnKeyword.classList.add('text-on-surface-variant');
        }

        // Hide results when switching tabs
        document.getElementById('results-area').classList.add('hidden');
    }

    // Extract Data Function
    async function extractData(event, type) {
        event.preventDefault();

        const loadingIndicator = document.getElementById('loading-indicator');
        const resultsArea = document.getElementById('results-area');
        const resultsTbody = document.getElementById('results-tbody');

        // Show loading
        loadingIndicator.classList.remove('hidden');
        resultsArea.classList.add('hidden');
        resultsTbody.innerHTML = '';

        try {
            let url = '{{ route("email-extractor") }}';
            let formData = new FormData();

            if (type === 'keyword') {
                const country = document.getElementById('country-select').value;
                const keyword = document.getElementById('keyword-select').value;

                formData.append('type', 'keyword');
                formData.append('country', country);
                formData.append('keyword', keyword);
            } else {
                const urlInput = document.getElementById('url-input').value;
                formData.append('type', 'url');
                formData.append('url_endpoint', urlInput);
            }

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const data = await response.json();

            // Hide loading
            loadingIndicator.classList.add('hidden');

            // Process results
            if (type === 'keyword') {
                // Pagination response from database with full data
                extractedEmails = data.data;
            } else {
                // Array response from URL scraping (only emails)
                extractedEmails = (data.data || []).map(email => ({
                    email: email,
                    title: '-',
                    country: { name: '-' },
                    keyword: { name: '-' }
                }));
            }

            if (extractedEmails.length > 0) {
                displayResults(extractedEmails, type);
            } else {
                resultsTbody.innerHTML = '<tr><td colspan="5" class="text-center py-6 text-on-surface-variant">Tidak ada email ditemukan</td></tr>';
                resultsArea.classList.remove('hidden');
            }

        } catch (error) {
            console.error('Error:', error);
            loadingIndicator.classList.add('hidden');
            resultsTbody.innerHTML = '<tr><td colspan="5" class="text-center py-6 text-error">Terjadi kesalahan: ' + error.message + '</td></tr>';
            resultsArea.classList.remove('hidden');
        }
    }

    // Display Results Function
    function displayResults(data, type) {
        const resultsTbody = document.getElementById('results-tbody');
        const resultsArea = document.getElementById('results-area');
        const resultsTitle = document.getElementById('results-title');

        resultsTbody.innerHTML = '';
        resultsTitle.textContent = `Hasil Ekstraksi (${data.length} data)`;

        data.forEach((item, index) => {
            const row = document.createElement('tr');
            row.className = 'border-b border-surface-variant hover:bg-surface-container-low transition-colors';
            
            const email = item.email || '-';
            const title = item.title || '-';
            const country = item.country ? item.country.name : '-';
            const keyword = item.keyword ? item.keyword.name : '-';
            
            row.innerHTML = `
                <td class="py-3 px-3">${email}</td>
                <td class="py-3 px-3">${title}</td>
                <td class="py-3 px-3">${country}</td>
                <td class="py-3 px-3">${keyword}</td>
                <td class="py-3 px-3 text-center">
                    <button onclick="copyEmail('${email}')" class="text-secondary hover:text-primary transition-colors" title="Copy Email">
                        <span class="material-symbols-outlined text-[20px]" data-icon="content_copy">content_copy</span>
                    </button>
                </td>
            `;
            resultsTbody.appendChild(row);
        });

        resultsArea.classList.remove('hidden');
    }

    // Copy Email Function
    function copyEmail(email) {
        navigator.clipboard.writeText(email).then(() => {
            alert('Email berhasil disalin: ' + email);
        }).catch(err => {
            console.error('Gagal menyalin:', err);
        });
    }

    // Export to CSV Function
    function exportToCSV() {
        if (extractedEmails.length === 0) {
            alert('Tidak ada data untuk diekspor');
            return;
        }

        let csvContent = "data:text/csv;charset=utf-8,";
        csvContent += "Email,Judul,Negara,Keyword\n";
        
        extractedEmails.forEach(item => {
            const email = (item.email || '-').replace(/"/g, '""');
            const title = (item.title || '-').replace(/"/g, '""');
            const country = item.country ? item.country.name.replace(/"/g, '""') : '-';
            const keyword = item.keyword ? item.keyword.name.replace(/"/g, '""') : '-';
            
            csvContent += `"${email}","${title}","${country}","${keyword}"\n`;
        });

        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "extracted_emails_" + Date.now() + ".csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>

@endsection