@extends('layouts.app')

@section('title', 'AI Broadcast Generator - OmniTools')

@section('content')
<!-- Hero Section -->
<section class="py-16 md:py-24 px-4 max-w-5xl mx-auto text-center flex flex-col items-center">
    <h1 class="text-3xl md:text-5xl font-bold text-on-surface mb-4">
        AI Broadcast Generator
    </h1>
    <p class="text-base text-on-surface-variant max-w-2xl">
        Ubah inti pengumuman menjadi pesan broadcast WhatsApp atau email yang luwes, natural, dan siap dikirim ke banyak orang.
    </p>
</section>

<!-- Main Tool Area -->
<section class="pb-20 mx-auto py-16 px-4 md:px-10 bg-surface-container-lowest"> 
    <div class="max-w-7xl w-full mx-auto bg-surface-container-lowest rounded-xl shadow-sm border border-surface-variant overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-12 divide-y md:divide-y-0 md:divide-x divide-surface-variant">
            
            <!-- Left Column: Inputs -->
            <div class="p-6 md:p-9 md:col-span-7 flex flex-col h-full gap-5">
                <div class="flex flex-col">
                    <label class="text-base font-bold text-on-surface mb-2">Platform & Target Audiens</label>
                    <input type="text" id="audience" class="w-full bg-surface-container-lowest border border-surface-variant focus:border-primary rounded-lg p-3 text-body-md text-on-surface outline-none transition-all shadow-sm" placeholder="Contoh: Grup WA Karyawan, Peserta Try Out, dll">
                </div>

                <div class="flex flex-col">
                    <label class="text-base font-bold text-on-surface mb-2">Gaya Bahasa</label>
                    <select id="tone" class="w-full bg-surface-container-lowest border border-surface-variant focus:border-primary rounded-lg p-3 text-body-md text-on-surface outline-none transition-all shadow-sm cursor-pointer">
                        <option value="Sopan, hangat, seperti bicara dengan teman">Sopan & Bersahabat (Kasual)</option>
                        <option value="Profesional, formal, dan jelas">Formal & Profesional</option>
                        <option value="Antusias, semangat, dan memotivasi">Antusias & Semangat</option>
                        <option value="Tegas, langsung pada intinya (to the point)">Singkat & Padat</option>
                    </select>
                </div>

                <div class="relative grow flex flex-col mt-2">
                    <label class="text-base font-bold text-on-surface mb-2">Topik atau Isi Pengumuman</label>
                    <textarea id="topic" rows="6" class="w-full h-full min-h-45 resize-none bg-surface-container-lowest border border-surface-variant focus:border-primary rounded-lg p-4 pb-16 text-body-md text-on-surface outline-none transition-all shadow-sm" placeholder="Contoh: Mengingatkan panitia untuk hadir rapat koordinasi besok jam 3 sore di ruang meeting utama..."></textarea>
                    
                    <!-- Submit Button -->
                    <div class="absolute bottom-4 right-4">
                        <button onclick="generateBroadcast(event)" id="generate-btn" class="bg-secondary text-on-secondary w-10 h-10 rounded-full flex items-center justify-center hover:bg-secondary-container transition-colors shadow-md group">
                            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column: Output -->
            <div class="p-6 md:p-8 md:col-span-5 flex flex-col h-full bg-surface-container-low">
                <div class="flex items-center justify-between mb-4">
                    <label class="text-lg font-bold text-on-surface">Hasil Pesan</label>
                    <button onclick="copyBroadcast()" class="text-on-surface-variant hover:text-primary transition-colors" title="Salin Pesan">
                        <span class="material-symbols-outlined">content_copy</span>
                    </button>
                </div>
                
                <div class="relative grow rounded-lg border border-transparent">
                    <!-- Loading -->
                    <div id="loading-indicator" class="hidden absolute inset-0 flex-col items-center justify-center bg-surface-container-low/80 backdrop-blur-sm rounded-lg z-10">
                        <span class="material-symbols-outlined animate-spin text-primary text-4xl mb-2">progress_activity</span>
                        <p class="text-sm font-medium text-on-surface-variant">Menyusun kalimat...</p>
                    </div>

                    <!-- Result -->
                    <div id="broadcast-result" class="w-full h-full min-h-75 p-4 text-on-surface whitespace-pre-wrap font-body-md overflow-y-auto">Teks broadcast akan muncul di sini...</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Cara Kerja Section -->
<section class="py-20 px-4 md:px-10 max-w-7xl mx-auto" id="cara-kerja">
    <div class="text-center mb-16">
        <h2 class="text-3xl font-bold text-on-surface mb-4">Cara Kerja Pembuat Broadcast</h2>
        <p class="text-base text-on-surface-variant max-w-2xl mx-auto">Tiga tahapan ringkas untuk mengubah instruksi pendek menjadi pesan massal yang komprehensif.</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-surface-container hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-primary-fixed rounded-lg flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-primary" data-icon="groups">groups</span>
            </div>
            <h3 class="text-xl font-bold text-on-surface mb-3">1. Tentukan Target</h3>
            <p class="text-sm text-on-surface-variant">Pilih gaya bahasa yang sesuai dan beritahu siapa yang akan membaca pesan Anda.</p>
        </div>
        <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-surface-container hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-secondary-fixed rounded-lg flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-secondary" data-icon="edit_note">edit_note</span>
            </div>
            <h3 class="text-xl font-bold text-on-surface mb-3">2. Ketik Poin Utama</h3>
            <p class="text-sm text-on-surface-variant">Masukkan informasi mentah yang ingin disampaikan (waktu, tempat, instruksi khusus).</p>
        </div>
        <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-surface-container hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-tertiary-fixed rounded-lg flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-tertiary" data-icon="send_to_mobile">send_to_mobile</span>
            </div>
            <h3 class="text-xl font-bold text-on-surface mb-3">3. Salin & Sebarkan</h3>
            <p class="text-sm text-on-surface-variant">AI akan merangkai kata-katanya. Anda tinggal menyalin dan mengirimkannya via WA atau Email.</p>
        </div>
    </div>
</section>

<script>
    async function generateBroadcast(event) {
        event.preventDefault();
        
        const topic = document.getElementById('topic').value;
        const audience = document.getElementById('audience').value;
        const tone = document.getElementById('tone').value;
        
        const resultArea = document.getElementById('broadcast-result');
        const loading = document.getElementById('loading-indicator');
        const generateBtn = document.getElementById('generate-btn');

        if (!topic.trim() || !audience.trim()) {
            alert("Harap lengkapi target audiens dan topik pengumuman.");
            return;
        }

        loading.classList.remove('hidden');
        resultArea.classList.add('opacity-50');
        generateBtn.disabled = true;

        try {
            const response = await fetch("{{ route('ai-broadcast.generate') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ topic, audience, tone })
            });

            const data = await response.json();

            if (data.success) {
                resultArea.innerText = data.result;
            } else {
                resultArea.innerText = "Gagal membuat pesan. " + (data.message || "");
            }
        } catch (error) {
            resultArea.innerText = "Terjadi kesalahan koneksi ke server.";
        } finally {
            loading.classList.add('hidden');
            resultArea.classList.remove('opacity-50');
            generateBtn.disabled = false;
        }
    }

    function copyBroadcast() {
        const textToCopy = document.getElementById('broadcast-result').innerText;
        if(!textToCopy || textToCopy.includes("akan muncul di sini")) return;

        navigator.clipboard.writeText(textToCopy).then(() => {
            alert('Pesan broadcast berhasil disalin!');
        });
    }
</script>
@endsection