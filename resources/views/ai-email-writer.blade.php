@extends('layouts.app')

@section('title', 'AI Email Writer - Tulis Email Profesional dalam Detik')

@section('content')
<!-- Hero Section -->
<section class="py-12 md:py-16 px-4 max-w-5xl mx-auto text-center flex flex-col items-center">
    <div class="bg-secondary-fixed text-secondary-container px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest mb-6 flex items-center gap-2">
        <span class="material-symbols-outlined text-sm">magic_button</span> AI Powered
    </div>
    <h1 class="text-3xl md:text-5xl font-bold text-on-surface mb-4">
        AI Email Writer
    </h1>
    <p class="text-base text-on-surface-variant max-w-2xl">
        Biarkan AI menulis email untuk Anda. Hilangkan stres dan susun email profesional hanya dalam hitungan detik.
    </p>
</section>

<!-- Main Tool Area -->
<section class="pb-20 mx-auto py-16 px-4 md:px-10 bg-surface-container-lowest"> 
    <!-- max-w-7xl dan w-full mx-auto untuk melebarkan keseluruhan container -->
    <div class="max-w-7xl w-full mx-auto bg-surface-container-lowest rounded-xl shadow-sm border border-surface-variant overflow-hidden">
        
        <!-- Menggunakan grid-cols-12 untuk rasio pembagian ruang yang kustom -->
        <div class="grid grid-cols-1 md:grid-cols-12 divide-y md:divide-y-0 md:divide-x divide-surface-variant">
            
            <!-- Left Column: Input (Dilebarkan dengan md:col-span-7) -->
            <div class="p-6 md:p-9 md:col-span-7 flex flex-col h-full">
                <label class="text-lg font-bold text-on-surface mb-4">Beri tahu kami tentang email Anda</label>
                <div class="relative grow flex flex-col">
                    <textarea id="prompt-input" rows="12" class="w-full h-full min-h-75 resize-none bg-surface-container-lowest border border-surface-variant focus:border-primary rounded-lg p-4 text-body-md text-on-surface outline-none transition-all" placeholder="Contoh: apakah saya bisa meminta cuti untuk 3 hari kedepan..."></textarea>
                    
                    <!-- Submit Button positioned at bottom right inside textarea -->
                    <div class="absolute bottom-4 right-4">
                        <button onclick="generateEmail(event)" id="generate-btn" class="bg-secondary text-on-secondary w-10 h-10 rounded-full flex items-center justify-center hover:bg-secondary-container transition-colors shadow-md group">
                            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column: Output (Disempitkan proporsinya dengan md:col-span-5) -->
            <div class="p-6 md:p-8 md:col-span-5 flex flex-col h-full bg-surface-container-low">
                <div class="flex items-center justify-between mb-4">
                    <label class="text-lg font-bold text-on-surface">Hasil email</label>
                    <button onclick="copyGeneratedEmail()" class="text-on-surface-variant hover:text-primary transition-colors" title="Salin Email">
                        <span class="material-symbols-outlined">content_copy</span>
                    </button>
                </div>
                
                <div class="relative grow rounded-lg border border-transparent">
                    <!-- Loading Indicator -->
                    <div id="loading-indicator" class="hidden absolute inset-0 flex-col items-center justify-center bg-surface-container-low/80 backdrop-blur-sm rounded-lg z-10">
                        <span class="material-symbols-outlined animate-spin text-primary text-4xl mb-2">progress_activity</span>
                        <p class="text-sm font-medium text-on-surface-variant">AI sedang menulis...</p>
                    </div>

                    <!-- Result Area -->
                    <div id="email-result" class="w-full h-full min-h-75 p-4 text-on-surface whitespace-pre-wrap font-body-md overflow-y-auto">Hasil tulisan AI akan muncul di sini...</div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Features / Cara Kerja Section -->
<section class="py-20 px-4 md:px-10 max-w-7xl mx-auto" id="cara-kerja">
    <div class="text-center mb-16">
        <h2 class="text-3xl font-bold text-on-surface mb-4">Cara Kerja AI Email Writer</h2>
        <p class="text-base text-on-surface-variant max-w-2xl mx-auto">Tiga langkah mudah untuk menghasilkan draf email profesional dalam hitungan detik tanpa perlu pusing merangkai kata.</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Card 1 -->
        <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-surface-container hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-primary-fixed rounded-lg flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-primary" data-icon="edit_note">edit_note</span>
            </div>
            <h3 class="text-xl font-bold text-on-surface mb-3">1. Masukkan Instruksi</h3>
            <p class="text-sm text-on-surface-variant">Ketik tujuan atau topik email yang ingin kamu buat secara singkat dan padat di kolom sebelah kiri.</p>
        </div>
        
        <!-- Card 2 -->
        <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-surface-container hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-secondary-fixed rounded-lg flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-secondary" data-icon="auto_awesome">auto_awesome</span>
            </div>
            <h3 class="text-xl font-bold text-on-surface mb-3">2. Biarkan AI Bekerja</h3>
            <p class="text-sm text-on-surface-variant">Klik tombol proses dan AI kami akan langsung menyusun teks email yang rapi, sopan, dan terstruktur.</p>
        </div>
        
        <!-- Card 3 -->
        <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-surface-container hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-tertiary-fixed rounded-lg flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-tertiary" data-icon="content_copy">content_copy</span>
            </div>
            <h3 class="text-xl font-bold text-on-surface mb-3">3. Salin & Kirim</h3>
            <p class="text-sm text-on-surface-variant">Tinjau hasil draf email di kolom kanan, klik tombol ikon salin, dan emailmu siap untuk dikirimkan.</p>
        </div>
    </div>
</section>

<script>
    async function generateEmail(event) {
        event.preventDefault();
        
        const promptInput = document.getElementById('prompt-input').value;
        const resultArea = document.getElementById('email-result');
        const loading = document.getElementById('loading-indicator');
        const generateBtn = document.getElementById('generate-btn');

        if (!promptInput.trim()) {
            alert("Harap beritahu kami email apa yang ingin Anda tulis.");
            return;
        }

        // Tampilkan loading, matikan tombol
        loading.classList.remove('hidden');
        resultArea.classList.add('opacity-50');
        generateBtn.disabled = true;

        try {
            const response = await fetch("{{ route('ai-email-writer.generate') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ prompt: promptInput })
            });

            const data = await response.json();

            if (data.success) {
                resultArea.innerText = data.result;
            } else {
                resultArea.innerText = "Maaf, gagal membuat email. " + (data.message || "");
            }
        } catch (error) {
            console.error("Error:", error);
            resultArea.innerText = "Terjadi kesalahan jaringan saat menghubungi server.";
        } finally {
            // Sembunyikan loading, aktifkan tombol
            loading.classList.add('hidden');
            resultArea.classList.remove('opacity-50');
            generateBtn.disabled = false;
        }
    }

    function copyGeneratedEmail() {
        const textToCopy = document.getElementById('email-result').innerText;
        if(textToCopy === "Hasil tulisan AI akan muncul di sini..." || textToCopy === "") return;

        navigator.clipboard.writeText(textToCopy).then(() => {
            alert('Email berhasil disalin ke clipboard!');
        }).catch(err => {
            console.error('Gagal menyalin', err);
        });
    }
</script>
@endsection