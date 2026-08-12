@extends('layouts.app')

@section('title', 'Direktori Alat - Email Extractor')

@section('content')
<!-- Main Content -->
<main class="grow flex flex-col items-center w-full">
    <!-- Hero Section OmniTools -->
    <section class="py-16 md:py-24 px-4 text-center max-w-4xl mx-auto flex flex-col items-center">
        <div class="bg-primary-fixed text-primary-container px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">construction</span> All-in-One Workspace
        </div>
        <h1 class="text-4xl md:text-6xl font-bold text-on-surface mb-6">
            Selamat Datang di <span class="text-primary">OmniTools</span>
        </h1>
        <p class="text-lg text-on-surface-variant max-w-2xl">
            Pusat perangkat kerja pintar untuk mendukung produktivitas perusahaan. Temukan berbagai alat andalan yang dirancang khusus  untuk menghemat waktu dan menyederhanakan tugas harianmu.
        </p>
    </section>

    <!-- Tools Grid Section -->
    <section class="w-full max-w-7xl mx-auto px-4 md:px-10 py-16 md:py-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <!-- Card 1: Email Extractor Jurnal -->
            <div class="bg-surface-container-lowest rounded-xl p-8 shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col h-full border border-surface-variant relative overflow-hidden group">
                <div class="w-12 h-12 bg-primary-fixed rounded-lg flex items-center justify-center mb-6 text-primary group-hover:scale-110 transition-transform duration-300">
                    <span class="material-symbols-outlined text-2xl" data-icon="article">article</span>
                </div>
                <h3 class="text-xl font-bold text-on-surface mb-3">Email Extractor Jurnal</h3>
                <p class="text-sm text-on-surface-variant mb-8 grow">
                    Ekstrak alamat email secara otomatis dari puluhan hingga ribuan artikel jurnal akademik, paper penelitian, dan publikasi ilmiah dalam berbagai format.
                </p>
                <a href="{{ route('email-extractor') }}" class="w-full bg-primary text-on-primary text-center font-bold py-3 px-4 rounded-lg hover:bg-primary-container transition-colors duration-200">
                    Gunakan Tool
                </a>
            </div>

            <!-- Card 2: AI Email Writer -->
            <div class="bg-surface-container-lowest rounded-xl p-8 shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col h-full border border-surface-variant relative overflow-hidden group">
                <div class="w-12 h-12 bg-primary-fixed rounded-lg flex items-center justify-center mb-6 text-primary group-hover:scale-110 transition-transform duration-300">
                    <span class="material-symbols-outlined text-2xl" data-icon="edit_note">edit_note</span>
                </div>
                <h3 class="text-xl font-bold text-on-surface mb-3">AI Email Writer</h3>
                <p class="text-sm text-on-surface-variant mb-8 grow">
                    Hasilkan draf email profesional secara instan dengan bantuan AI. Cukup ketik apa yang ingin Anda sampaikan secara singkat.
                </p>
                <a href="{{ route('ai-email-writer') }}" class="w-full bg-primary text-on-primary text-center font-bold py-3 px-4 rounded-lg hover:bg-primary-container transition-colors duration-200">
                    Gunakan Tool
                </a>
            </div>

            <!-- Card 3: Spam Checker (Arahkan ke route spam-checker) -->
            <div class="bg-surface-container-lowest rounded-xl p-8 shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col h-full border border-surface-variant relative overflow-hidden group">
                <div class="w-12 h-12 bg-secondary-fixed rounded-lg flex items-center justify-center mb-6 text-secondary group-hover:scale-110 transition-transform duration-300">
                    <span class="material-symbols-outlined text-2xl" data-icon="fact_check">fact_check</span>
                </div>
                <h3 class="text-xl font-bold text-on-surface mb-3">Email Spam Checker</h3>
                <p class="text-sm text-on-surface-variant mb-8 grow">
                    Analisis subjek dan isi email Anda untuk mendeteksi kata-kata pemicu spam agar email berhasil mendarat di Inbox target Anda.
                </p>
                <a href="{{ route('spam-checker') }}" class="w-full bg-primary text-on-primary text-center font-bold py-3 px-4 rounded-lg hover:bg-primary-container transition-colors duration-200">
                    Gunakan Tool
                </a>
            </div>

            <!-- Card 4: AI Email Response -->
            <div class="bg-surface-container-lowest rounded-xl p-8 shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col h-full border border-surface-variant relative overflow-hidden group">
                <div class="w-12 h-12 bg-secondary-fixed rounded-lg flex items-center justify-center mb-6 text-secondary group-hover:scale-110 transition-transform duration-300">
                    <span class="material-symbols-outlined text-2xl" data-icon="reply">reply</span>
                </div>
                <h3 class="text-xl font-bold text-on-surface mb-3">AI Email Response</h3>
                <p class="text-sm text-on-surface-variant mb-8 grow">
                    Balas email masuk dengan cepat dan profesional. Tempel email yang diterima, berikan instruksi singkat, dan biarkan AI merangkai jawabannya.
                </p>
                <a href="{{ route('ai-email-response') }}" class="w-full bg-primary text-on-primary text-center font-bold py-3 px-4 rounded-lg hover:bg-primary-container transition-colors duration-200">
                    Gunakan Tool
                </a>
            </div>

            <!-- Card 5: AI PDF Summarizer -->
            <div class="bg-surface-container-lowest rounded-xl p-8 shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col h-full border border-surface-variant relative overflow-hidden group">
                <div class="w-12 h-12 bg-tertiary-fixed rounded-lg flex items-center justify-center mb-6 text-tertiary group-hover:scale-110 transition-transform duration-300">
                    <span class="material-symbols-outlined text-2xl" data-icon="summarize">summarize</span>
                </div>
                <h3 class="text-xl font-bold text-on-surface mb-3">AI PDF Summarizer</h3>
                <p class="text-sm text-on-surface-variant mb-8 grow">
                    Ubah laporan atau dokumen PDF panjang menjadi poin-poin ringkasan yang padat dan mudah dipahami dalam hitungan detik.
                </p>
                <a href="{{ route('ai-pdf-summarizer') }}" class="w-full bg-primary text-on-primary text-center font-bold py-3 px-4 rounded-lg hover:bg-primary-container transition-colors duration-200">
                    Gunakan Tool
                </a>
            </div>

        </div>
    </section>

    <!-- CTA Section -->
    <section class="w-full bg-primary-fixed mt-8 py-20 px-4 md:px-10">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-xl">
                <h2 class="text-2xl md:text-3xl font-bold text-on-primary-fixed mb-4">Butuh solusi kustom?</h2>
                <p class="text-base text-on-primary-fixed-variant">
                    Hubungi tim engineer kami untuk membangun alur kerja ekstraksi data yang disesuaikan khusus untuk kebutuhan enterprise Anda.
                </p>
            </div>
            <button class="bg-primary text-on-primary font-bold py-4 px-8 rounded-lg hover:bg-primary-container transition-colors duration-200 whitespace-nowrap">
                Hubungi Developer
            </button>
        </div>
    </section>
</main>
@endsection