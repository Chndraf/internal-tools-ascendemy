@extends('layouts.app')

@section('title', 'AI Email Response - OmniTools')

@section('content')
<!-- Hero Section -->
<section class="py-16 md:py-24 px-4 max-w-5xl mx-auto text-center flex flex-col items-center">
    <h1 class="text-3xl md:text-5xl font-bold text-on-surface mb-4">
        AI Email Response
    </h1>
    <p class="text-base text-on-surface-variant max-w-2xl">
        Hemat waktu dan bangun hubungan lebih baik dengan balasan email yang cerdas, sopan, dan kontekstual dalam hitungan detik.
    </p>
</section>

<!-- Main Tool Area -->
<section class="pb-20 mx-auto py-16 px-4 md:px-10 bg-surface-container-lowest"> 
    <div class="max-w-7xl w-full mx-auto bg-surface-container-lowest rounded-xl shadow-sm border border-surface-variant overflow-hidden">
        
        <div class="grid grid-cols-1 md:grid-cols-12 divide-y md:divide-y-0 md:divide-x divide-surface-variant">
            
            <div class="p-6 md:p-9 md:col-span-7 flex flex-col h-full gap-6">
                <!-- Input 1: Email Masuk (Dibuat lebih tinggi) -->
                <div class="relative grow flex flex-col">
                    <label class="text-lg font-bold text-on-surface mb-3">Email yang masuk</label>
                    <textarea id="incoming-email" rows="10" class="w-full h-full min-h-62.5 resize-none bg-surface-container-lowest border border-surface-variant focus:border-primary rounded-lg p-4 text-body-md text-on-surface outline-none transition-all shadow-sm" placeholder="Tempelkan isi email yang Anda terima di sini..."></textarea>
                </div>
                
                <!-- Input 2: Konteks Balasan (Dibuat lebih pendek) -->
                <div class="relative flex flex-col">
                    <label class="text-lg font-bold text-on-surface mb-3">Konteks balasan</label>
                    <textarea id="reply-context" rows="4" class="w-full resize-none bg-surface-container-lowest border border-surface-variant focus:border-primary rounded-lg p-4 pb-16 text-body-md text-on-surface outline-none transition-all shadow-sm" placeholder="Contoh: Tolak tawaran meeting dengan sopan karena jadwal padat minggu ini..."></textarea>
                    
                    <!-- Submit Button -->
                    <div class="absolute bottom-4 right-4">
                        <button onclick="generateResponse(event)" id="generate-btn" class="bg-secondary text-on-secondary w-10 h-10 rounded-full flex items-center justify-center hover:bg-secondary-container transition-colors shadow-md group">
                            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column: Output (Disempitkan proporsinya dengan md:col-span-5) -->
            <div class="p-6 md:p-8 md:col-span-5 flex flex-col h-full bg-surface-container-low">
                <div class="flex items-center justify-between mb-4">
                    <label class="text-lg font-bold text-on-surface">Hasil balasan</label>
                    <button onclick="copyResponse()" class="text-on-surface-variant hover:text-primary transition-colors" title="Salin Email">
                        <span class="material-symbols-outlined">content_copy</span>
                    </button>
                </div>
                
                <div class="relative grow rounded-lg border border-transparent">
                    <!-- Loading Indicator -->
                    <div id="loading-indicator" class="hidden absolute inset-0 flex-col items-center justify-center bg-surface-container-low/80 backdrop-blur-sm rounded-lg z-10">
                        <span class="material-symbols-outlined animate-spin text-primary text-4xl mb-2">progress_activity</span>
                        <p class="text-sm font-medium text-on-surface-variant">AI sedang merangkai balasan...</p>
                    </div>

                    <!-- Result Area -->
                    <div id="email-result" class="w-full h-full min-h-75 p-4 text-on-surface whitespace-pre-wrap font-body-md overflow-y-auto">Hasil balasan AI akan muncul di sini...</div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Features / Cara Kerja Section -->
<section class="py-20 px-4 md:px-10 max-w-7xl mx-auto" id="cara-kerja">
    <div class="text-center mb-16">
        <h2 class="text-3xl font-bold text-on-surface mb-4">Cara Kerja AI Email Response</h2>
        <p class="text-base text-on-surface-variant max-w-2xl mx-auto">Tiga langkah mudah untuk menghasilkan draf balasan email profesional dalam hitungan detik.</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Card 1 -->
        <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-surface-container hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-primary-fixed rounded-lg flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-primary" data-icon="input">input</span>
            </div>
            <h3 class="text-xl font-bold text-on-surface mb-3">1. Masukkan Email</h3>
            <p class="text-sm text-on-surface-variant">Tempelkan isi email yang kamu terima ke dalam kotak pertama yang tersedia.</p>
        </div>
        
        <!-- Card 2 -->
        <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-surface-container hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-secondary-fixed rounded-lg flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-secondary" data-icon="format_list_bulleted">format_list_bulleted</span>
            </div>
            <h3 class="text-xl font-bold text-on-surface mb-3">2. Berikan Konteks</h3>
            <p class="text-sm text-on-surface-variant">Jelaskan secara singkat poin-poin yang ingin kamu sampaikan atau arahkan responsnya.</p>
        </div>
        
        <!-- Card 3 -->
        <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-surface-container hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-tertiary-fixed rounded-lg flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-tertiary" data-icon="rocket_launch">rocket_launch</span>
            </div>
            <h3 class="text-xl font-bold text-on-surface mb-3">3. Salin & Kirim</h3>
            <p class="text-sm text-on-surface-variant">Tinjau hasil draf balasan di kolom kanan, salin, dan emailmu siap untuk dikirimkan.</p>
        </div>
    </div>
</section>

<script>
    async function generateResponse(event) {
        event.preventDefault();
        
        const incomingEmail = document.getElementById('incoming-email').value;
        const replyContext = document.getElementById('reply-context').value;
        
        const resultArea = document.getElementById('email-result');
        const loading = document.getElementById('loading-indicator');
        const generateBtn = document.getElementById('generate-btn');

        if (!incomingEmail.trim() || !replyContext.trim()) {
            alert("Harap isi email masuk dan konteks balasan Anda.");
            return;
        }

        // Tampilkan loading, matikan tombol
        loading.classList.remove('hidden');
        resultArea.classList.add('opacity-50');
        generateBtn.disabled = true;

        try {
            const response = await fetch("{{ route('ai-email-response.generate') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    incoming_email: incomingEmail,
                    reply_context: replyContext
                })
            });

            const data = await response.json();

            if (data.success) {
                resultArea.innerText = data.result;
            } else {
                resultArea.innerText = "Maaf, gagal membuat email. " + (data.message || "");
            }
        } catch (error) {
            console.error("Error:", error);
            resultArea.innerText = "Terjadi kesalahan sistem saat menghubungi server.";
        } finally {
            loading.classList.add('hidden');
            resultArea.classList.remove('opacity-50');
            generateBtn.disabled = false;
        }
    }

    function copyResponse() {
        const textToCopy = document.getElementById('email-result').innerText;
        if(textToCopy === "Hasil balasan AI akan muncul di sini..." || textToCopy === "") return;

        navigator.clipboard.writeText(textToCopy).then(() => {
            alert('Balasan email berhasil disalin ke clipboard!');
        }).catch(err => {
            console.error('Gagal menyalin', err);
        });
    }
</script>
@endsection