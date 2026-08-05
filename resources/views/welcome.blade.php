@extends('layouts.app')

@section('title', 'Beranda - Email Extractor Jurnal')

@section('content')
<!-- Hero Section -->
<section class="py-16 md:py-24 px-4 md:px-10 max-w-7xl mx-auto text-center flex flex-col items-center">
    <h1 class="text-3xl md:text-5xl font-headline-xl text-on-surface mb-6 max-w-4xl">
        Temukan Email Jurnal Berdasarkan Negara &amp; Keyword dengan Mudah
    </h1>
    <p class="text-base md:text-lg text-on-surface-variant mb-10 max-w-2xl">
        Ekstrak ribuan alamat email dari database jurnal tervalidasi secara instan. Tingkatkan efisiensi riset dan penjangkauan akademis Anda dengan akurasi tinggi.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
        <button class="bg-primary text-on-primary px-8 py-4 rounded-lg hover:bg-primary-container transition-all shadow-md hover:-translate-y-0.5 w-full sm:w-auto flex items-center justify-center gap-2">
            <span class="material-symbols-outlined" data-icon="search">search</span>
            Cari Email Sekarang
        </button>
        <button class="border-2 border-outline-variant text-primary px-8 py-4 rounded-lg hover:border-primary hover:bg-surface-container-low transition-colors w-full sm:w-auto">
            Pelajari Lebih Lanjut
        </button>
    </div>
</section>

<!-- Interactive Demo / Main Tool Area -->
<section class="py-16 px-4 md:px-10 bg-surface-container-lowest">
    <div class="max-w-4xl mx-auto">
        <div class="bg-surface rounded-xl shadow-lg p-6 md:p-10 border border-surface-variant">
            <div class="flex items-center justify-between mb-4 pb-4 border-b border-surface-variant">
                <h2 class="text-2xl font-headline-md text-on-surface">Mulai Ekstraksi</h2>
                {{-- <span class="bg-tertiary-fixed text-on-tertiary-fixed text-xs px-3 py-1 rounded-full">Mode Demo</span> --}}
            </div>
            
            <div class="flex justify-center mb-8">
                <div class="inline-flex items-center bg-surface-container-low border border-surface-variant rounded-full p-1 shadow-sm">
                    <button class="px-6 py-1.5 rounded-full bg-primary text-on-primary font-label-md text-label-md shadow-sm transition-all duration-200">
                        Keyword
                    </button>
                    <button class="px-6 py-1.5 rounded-full bg-transparent text-on-surface-variant font-label-md text-label-md hover:text-primary hover:bg-surface-container transition-all duration-200">
                        Url
                    </button>
                </div>
            </div>

            <!-- Form Controls -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Dropdown 1: Country -->
                <div class="flex flex-col gap-2">
                    <label class="text-xs text-on-surface-variant font-bold uppercase tracking-wider">Pilih Negara</label>
                    <div class="relative">
                        <select class="w-full appearance-none bg-surface-container-low border border-transparent focus:border-primary focus:bg-surface rounded-lg px-4 py-3 text-on-surface outline-none transition-all pr-10 cursor-pointer">
                            <option disabled="" selected="" value="">Pilih Negara Tujuan...</option>
                            <option value="id">Indonesia</option>
                            <option value="us">United States</option>
                            <option value="uk">United Kingdom</option>
                            <option value="au">Australia</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none" data-icon="expand_more">expand_more</span>
                    </div>
                </div>
                
                <!-- Dropdown 2: Keyword -->
                <div class="flex flex-col gap-2">
                    <label class="text-xs text-on-surface-variant font-bold uppercase tracking-wider">Pilih Keyword Jurnal</label>
                    <div class="relative">
                        <select class="w-full appearance-none bg-surface-container-low border border-transparent focus:border-primary focus:bg-surface rounded-lg px-4 py-3 text-on-surface outline-none transition-all pr-10 cursor-pointer">
                            <option disabled="" selected="" value="">Pilih Bidang Studi...</option>
                            <option value="med">Kedokteran &amp; Kesehatan</option>
                            <option value="tech">Teknologi &amp; Ilmu Komputer</option>
                            <option value="edu">Pendidikan</option>
                            <option value="soc">Ilmu Sosial</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none" data-icon="expand_more">expand_more</span>
                    </div>
                </div>
            </div>
            
            <!-- Extract Button -->
            <button class="w-full bg-secondary text-on-secondary py-4 rounded-lg hover:bg-secondary-container transition-colors shadow-sm flex items-center justify-center gap-2 mb-10">
                <span class="material-symbols-outlined" data-icon="manage_search">manage_search</span>
                Extract Emails
            </button>
            
            <!-- Mock Results Area -->
            <div class="bg-surface-container-lowest rounded-lg border border-surface-variant p-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm text-on-surface">Hasil Ekstraksi (Sample)</h3>
                    <button class="text-secondary text-xs flex items-center gap-1 transition-colors">
                        <span class="material-symbols-outlined text-[18px]" data-icon="download">download</span>
                        Export to CSV
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-surface-variant text-on-surface-variant text-xs">
                                <th class="py-2 px-3 font-medium">Email</th>
                                <th class="py-2 px-3 font-medium">Jurnal</th>
                                <th class="py-2 px-3 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-on-surface">
                            <tr class="border-b border-surface-variant hover:bg-surface-container-low transition-colors">
                                <td class="py-3 px-3">editor@medjournal.ac.id</td>
                                <td class="py-3 px-3">Jurnal Kedokteran Tropis</td>
                                <td class="py-3 px-3"><span class="bg-tertiary-fixed text-on-tertiary-fixed text-[10px] px-2 py-1 rounded-full uppercase tracking-wider font-bold">Verified</span></td>
                            </tr>
                            <tr class="border-b border-surface-variant hover:bg-surface-container-low transition-colors">
                                <td class="py-3 px-3">contact@techreview.org</td>
                                <td class="py-3 px-3">Indonesian Tech Review</td>
                                <td class="py-3 px-3"><span class="bg-tertiary-fixed text-on-tertiary-fixed text-[10px] px-2 py-1 rounded-full uppercase tracking-wider font-bold">Verified</span></td>
                            </tr>
                            <tr class="border-b border-surface-variant hover:bg-surface-container-low transition-colors">
                                <td class="py-3 px-3">info@eduscience.id</td>
                                <td class="py-3 px-3">Jurnal Ilmu Pendidikan</td>
                                <td class="py-3 px-3"><span class="bg-tertiary-fixed text-on-tertiary-fixed text-[10px] px-2 py-1 rounded-full uppercase tracking-wider font-bold">Verified</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 px-4 md:px-10 max-w-7xl mx-auto" id="fitur">
    <div class="text-center mb-16">
        <h2 class="text-3xl font-headline-lg text-on-surface mb-4">Fitur Unggulan</h2>
        <p class="text-base text-on-surface-variant max-w-2xl mx-auto">Dibangun khusus untuk memenuhi kebutuhan riset akademis dengan presisi tinggi dan efisiensi maksimal.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-surface p-8 rounded-xl shadow-sm border border-surface-container hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-primary-fixed rounded-lg flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-primary" data-icon="filter_alt">filter_alt</span>
            </div>
            <h3 class="text-xl font-headline-md text-on-surface mb-3">Filter Spesifik</h3>
            <p class="text-sm text-on-surface-variant">Pencarian tertarget berdasarkan negara asal dan kata kunci spesifik disiplin ilmu jurnal target Anda.</p>
        </div>
        <div class="bg-surface p-8 rounded-xl shadow-sm border border-surface-container hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-secondary-fixed rounded-lg flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-secondary" data-icon="ios_share">ios_share</span>
            </div>
            <h3 class="text-xl font-headline-md text-on-surface mb-3">Export Anywhere</h3>
            <p class="text-sm text-on-surface-variant">Unduh hasil ekstraksi Anda dengan mudah dalam format CSV yang kompatibel dengan berbagai software spreadsheet.</p>
        </div>
        <div class="bg-surface p-8 rounded-xl shadow-sm border border-surface-container hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-tertiary-fixed rounded-lg flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-tertiary" data-icon="verified_user">verified_user</span>
            </div>
            <h3 class="text-xl font-headline-md text-on-surface mb-3">Database Tervalidasi</h3>
            <p class="text-sm text-on-surface-variant">Sistem otomatis memvalidasi email untuk memastikan rasio pantulan (bounce rate) yang rendah.</p>
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
@endsection