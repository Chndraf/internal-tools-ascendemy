@extends('layouts.app')

@section('title', 'Spam Checker - OmniTools')

@section('content')
<!-- Hero Section -->
<section class="py-16 md:py-24 px-4 md:px-10 max-w-7xl mx-auto text-center flex flex-col items-center">
    <h1 class="text-3xl md:text-5xl font-headline-xl text-on-surface mb-6 max-w-4xl">
        Email Spam Checker
    </h1>
    <p class="text-base md:text-lg text-on-surface-variant mb-10 max-w-2xl">
        Pastikan email Anda mendarat di Inbox, bukan folder Spam. Analisis subjek dan isi email Anda untuk mendeteksi kata-kata pemicu spam sebelum dikirim.
    </p>
</section>

<!-- Interactive Demo / Main Tool Area -->
<section class="py-16 px-4 md:px-10 bg-surface-container-lowest">
    <div class="max-w-4xl mx-auto">
        <div class="bg-surface rounded-xl shadow-lg p-6 md:p-10 border border-surface-variant overflow-hidden">
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-surface-variant">
                <h2 class="text-2xl font-headline-md text-on-surface">Cek Skor Spam Email</h2>
            </div>

            <!-- Form Spam Checker -->
            <form id="spam-checker-form" onsubmit="checkSpam(event)">
                <div class="flex flex-col gap-6 mb-8">
                    <!-- Input Subjek -->
                    <div class="flex flex-col gap-2">
                        <label class="text-xs text-on-surface-variant font-bold uppercase tracking-wider">Subjek Email</label>
                        <input id="email-subject" type="text" class="w-full bg-surface-container-low border border-transparent focus:border-primary focus:bg-surface rounded-lg px-4 py-3 text-body-md text-on-surface outline-none transition-all cursor-text" placeholder="Contoh: Penawaran Eksklusif untuk Anda!" required/>
                    </div>

                    <!-- Input Body Email -->
                    <div class="flex flex-col gap-2">
                        <label class="text-xs text-on-surface-variant font-bold uppercase tracking-wider">Isi Email</label>
                        <textarea id="email-body" rows="8" class="w-full bg-surface-container-low border border-transparent focus:border-primary focus:bg-surface rounded-lg px-4 py-3 text-body-md text-on-surface outline-none transition-all cursor-text" placeholder="Ketik atau tempel isi email Anda di sini..." required></textarea>
                    </div>
                </div>
                
                <!-- Check Button -->
                <button type="submit" class="relative overflow-hidden w-full bg-secondary text-on-secondary font-label-md text-label-md py-4 rounded-lg hover:bg-secondary-container transition-all duration-300 shadow-sm flex items-center justify-center gap-2 mb-4">
                    <span class="material-symbols-outlined" data-icon="fact_check">fact_check</span>
                    <span class="tracking-wide">Analisis Email</span>
                </button>
            </form>

            <!-- Global Loading Indicator -->
            <div id="loading-indicator" class="hidden text-center py-6">
                <span class="material-symbols-outlined animate-spin text-primary text-3xl">autorenew</span>
                <p class="text-on-surface-variant mt-2 text-sm">Sedang menganalisis teks...</p>
            </div>

            <!-- Results Area -->
            <div id="results-area" class="hidden bg-surface-container-lowest rounded-lg border border-surface-variant p-6 mt-6">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-on-surface">Hasil Analisis</h3>
                        <p class="text-sm text-on-surface-variant" id="result-message">Skor spam Anda akan muncul di sini.</p>
                    </div>
                    <div class="text-center bg-surface-container p-4 rounded-lg">
                        <span class="block text-3xl font-bold text-primary" id="spam-score">0/100</span>
                        <span class="text-xs text-on-surface-variant uppercase tracking-wider">Skor Keamanan</span>
                    </div>
                </div>

                <div class="border-t border-surface-variant pt-4">
                    <h4 class="text-sm font-bold text-on-surface mb-3">Kata Pemicu Spam yang Ditemukan:</h4>
                    <div id="spam-words-list" class="flex flex-wrap gap-2">
                        <!-- Spam words badges will be injected here -->
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Cara Kerja Section -->
<section class="py-20 px-4 md:px-10 max-w-7xl mx-auto" id="cara-kerja">
    <div class="text-center mb-16">
        <h2 class="text-3xl font-bold text-on-surface mb-4">Cara Kerja Spam Checker</h2>
        <p class="text-base text-on-surface-variant max-w-2xl mx-auto">Tiga langkah mudah untuk memastikan email Anda bersih dari kata-kata pemicu spam sebelum dikirim ke penerima.</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Card 1 -->
        <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-surface-container hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-primary-fixed rounded-lg flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-primary" data-icon="edit_document">edit_document</span>
            </div>
            <h3 class="text-xl font-bold text-on-surface mb-3">1. Masukkan Teks Email</h3>
            <p class="text-sm text-on-surface-variant">Ketik atau tempelkan (paste) subjek dan isi draf email yang ingin Anda periksa ke dalam kolom yang tersedia.</p>
        </div>
        
        <!-- Card 2 -->
        <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-surface-container hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-secondary-fixed rounded-lg flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-secondary" data-icon="troubleshoot">troubleshoot</span>
            </div>
            <h3 class="text-xl font-bold text-on-surface mb-3">2. Analisis Otomatis</h3>
            <p class="text-sm text-on-surface-variant">Klik tombol periksa, dan sistem akan langsung memindai setiap kata yang berpotensi masuk ke folder spam.</p>
        </div>
        
        <!-- Card 3 -->
        <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-surface-container hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-tertiary-fixed rounded-lg flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-tertiary" data-icon="task_alt">task_alt</span>
            </div>
            <h3 class="text-xl font-bold text-on-surface mb-3">3. Evaluasi & Perbaiki</h3>
            <p class="text-sm text-on-surface-variant">Lihat skor akhir email Anda. Hapus atau ganti kata-kata yang ditandai merah agar email lebih aman dan profesional.</p>
        </div>
    </div>
</section>

<!-- Script AJAX Placeholder (bisa dipindah ke app.js nanti) -->
<script>
    async function checkSpam(event) {
        event.preventDefault();
        
        const loading = document.getElementById('loading-indicator');
        const resultsArea = document.getElementById('results-area');
        
        resultsArea.classList.add('hidden');
        loading.classList.remove('hidden');

        const subject = document.getElementById('email-subject').value;
        const body = document.getElementById('email-body').value;

        try {
            const response = await fetch("{{ route('spam-checker.analyze') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                },
                body: JSON.stringify({ subject: subject, body: body })
            });

            const result = await response.json();
            const scoreElement = document.getElementById('spam-score');

            // 1. Hapus warna sebelumnya agar tidak bertumpuk
            scoreElement.classList.remove('text-primary', 'text-green-600', 'text-error');

            // 2. Tentukan warna berdasarkan skor
            if (result.score >= 80) {
                scoreElement.classList.add('text-green-600'); // Hijau untuk Aman
            } else if (result.score >= 50) {
                scoreElement.classList.add('text-primary'); // Oranye (Primary) untuk Hati-hati
            } else {
                scoreElement.classList.add('text-error'); // Merah untuk Bahaya
            }

            // 3. Tampilkan Skor
            scoreElement.innerText = `${result.score}/100`;
            
            const spamWordsContainer = document.getElementById('spam-words-list');
            spamWordsContainer.innerHTML = '';
            
            if (result.spam_words.length > 0) {
                result.spam_words.forEach(word => {
                    spamWordsContainer.innerHTML += `<span class="bg-error-container text-error px-3 py-1 rounded-full text-xs">${word}</span>`;
                });
                document.getElementById('result-message').innerText = "Email Anda berisiko masuk folder Spam. Perbaiki kata-kata di bawah ini.";
            } else {
                spamWordsContainer.innerHTML = `<span class="text-sm text-on-surface-variant">Bagus! Tidak ditemukan indikasi spam yang mencurigakan.</span>`;
                document.getElementById('result-message').innerText = "Email Anda terlihat aman dan profesional.";
            }

        } catch (error) {
            console.error("Error:", error);
            alert("Terjadi kesalahan saat menganalisis email.");
        }

        loading.classList.add('hidden');
        resultsArea.classList.remove('hidden');
    }
</script>
@endsection