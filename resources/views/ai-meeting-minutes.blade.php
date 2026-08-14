@extends('layouts.app')

@section('title', 'AI Meeting Minutes - OmniTools')

@section('content')
<!-- Hero Section -->
<section class="py-16 md:py-24 px-4 max-w-5xl mx-auto text-center flex flex-col items-center">
    <h1 class="text-3xl md:text-5xl font-bold text-on-surface mb-4">
        AI Meeting Minutes
    </h1>
    <p class="text-base text-on-surface-variant max-w-2xl">
        Ubah catatan acak, poin-poin singkat, atau ketikan cepat saat rapat menjadi notulen profesional yang terstruktur secara instan.
    </p>
</section>

<!-- Main Tool Area -->
<section class="pb-20 mx-auto py-16 px-4 md:px-10 bg-surface-container-lowest"> 
    <div class="max-w-7xl w-full mx-auto bg-surface-container-lowest rounded-xl shadow-sm border border-surface-variant overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-12 divide-y md:divide-y-0 md:divide-x divide-surface-variant">
            
            <!-- Left Column: Inputs -->
            <div class="p-6 md:p-9 md:col-span-7 flex flex-col h-full gap-5">
                <div class="flex flex-col">
                    <label class="text-base font-bold text-on-surface mb-2">Topik / Agenda Rapat</label>
                    <input type="text" id="agenda" class="w-full bg-surface-container-lowest border border-surface-variant focus:border-primary rounded-lg p-3 text-body-md text-on-surface outline-none transition-all shadow-sm" placeholder="Contoh: Evaluasi Kinerja Tim Q3 2026">
                </div>

                <div class="relative grow flex flex-col mt-2">
                    <label class="text-base font-bold text-on-surface mb-2">Catatan Mentah (Raw Notes)</label>
                    <textarea id="raw-notes" rows="10" class="w-full h-full min-h-62.5 resize-none bg-surface-container-lowest border border-surface-variant focus:border-primary rounded-lg p-4 pb-16 text-body-md text-on-surface outline-none transition-all shadow-sm" placeholder="Ketik atau paste catatan acak Anda di sini... (misal: bahas budget kurang 2jt, rudi janji revisi desain besok, event fix diundur minggu depan)"></textarea>
                    
                    <!-- Submit Button -->
                    <div class="absolute bottom-4 right-4">
                        <button onclick="generateMinutes(event)" id="generate-btn" class="bg-secondary text-on-secondary w-10 h-10 rounded-full flex items-center justify-center hover:bg-secondary-container transition-colors shadow-md group">
                            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column: Output -->
            <div class="p-6 md:p-8 md:col-span-5 flex flex-col h-full bg-surface-container-low">
                <div class="flex items-center justify-between mb-4">
                    <label class="text-lg font-bold text-on-surface">Hasil Notulen</label>
                    <button onclick="copyMinutes()" class="text-on-surface-variant hover:text-primary transition-colors" title="Salin Notulen">
                        <span class="material-symbols-outlined">content_copy</span>
                    </button>
                </div>
                
                <div class="relative grow rounded-lg border border-transparent">
                    <div id="loading-indicator" class="hidden absolute inset-0 flex-col items-center justify-center bg-surface-container-low/80 backdrop-blur-sm rounded-lg z-10">
                        <span class="material-symbols-outlined animate-spin text-primary text-4xl mb-2">progress_activity</span>
                        <p class="text-sm font-medium text-on-surface-variant">Menyusun notulen...</p>
                    </div>

                    <div id="minutes-result" class="w-full h-full min-h-87.5 p-4 text-on-surface whitespace-pre-wrap font-body-md overflow-y-auto">Notulen rapat yang rapi akan muncul di sini...</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Cara Kerja Section -->
<section class="py-20 px-4 md:px-10 max-w-7xl mx-auto" id="cara-kerja">
    <div class="text-center mb-16">
        <h2 class="text-3xl font-bold text-on-surface mb-4">Cara Kerja AI Meeting Minutes</h2>
        <p class="text-base text-on-surface-variant max-w-2xl mx-auto">Tiga langkah mengubah catatan berantakan menjadi dokumen rapi siap kirim.</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-surface-container hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-primary-fixed rounded-lg flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-primary" data-icon="event_note">event_note</span>
            </div>
            <h3 class="text-xl font-bold text-on-surface mb-3">1. Judul Rapat</h3>
            <p class="text-sm text-on-surface-variant">Masukkan topik atau agenda utama dari rapat yang baru saja Anda jalani.</p>
        </div>
        <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-surface-container hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-secondary-fixed rounded-lg flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-secondary" data-icon="edit">edit</span>
            </div>
            <h3 class="text-xl font-bold text-on-surface mb-3">2. Paste Catatan Acak</h3>
            <p class="text-sm text-on-surface-variant">Masukkan semua coretan, poin singkat, atau transkrip mentah yang Anda miliki.</p>
        </div>
        <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-surface-container hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-tertiary-fixed rounded-lg flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-tertiary" data-icon="task">task</span>
            </div>
            <h3 class="text-xl font-bold text-on-surface mb-3">3. Dapatkan Notulen</h3>
            <p class="text-sm text-on-surface-variant">AI akan merapikan catatan menjadi poin-poin diskusi, keputusan, dan tugas selanjutnya.</p>
        </div>
    </div>
</section>

<script>
    async function generateMinutes(event) {
        event.preventDefault();
        
        const agenda = document.getElementById('agenda').value;
        const rawNotes = document.getElementById('raw-notes').value;
        
        const resultArea = document.getElementById('minutes-result');
        const loading = document.getElementById('loading-indicator');
        const generateBtn = document.getElementById('generate-btn');

        if (!agenda.trim() || !rawNotes.trim()) {
            alert("Harap isi agenda rapat dan catatan mentah.");
            return;
        }

        loading.classList.remove('hidden');
        resultArea.classList.add('opacity-50');
        generateBtn.disabled = true;

        try {
            const response = await fetch("{{ route('ai-meeting-minutes.generate') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ agenda, raw_notes: rawNotes })
            });

            const data = await response.json();

            if (data.success) {
                resultArea.innerText = data.result;
            } else {
                resultArea.innerText = "Gagal membuat notulen. " + (data.message || "");
            }
        } catch (error) {
            resultArea.innerText = "Terjadi kesalahan koneksi ke server.";
        } finally {
            loading.classList.add('hidden');
            resultArea.classList.remove('opacity-50');
            generateBtn.disabled = false;
        }
    }

    function copyMinutes() {
        const textToCopy = document.getElementById('minutes-result').innerText;
        if(!textToCopy || textToCopy.includes("akan muncul di sini")) return;

        navigator.clipboard.writeText(textToCopy).then(() => {
            alert('Notulen berhasil disalin!');
        });
    }
</script>
@endsection