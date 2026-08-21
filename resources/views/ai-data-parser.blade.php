@extends('layouts.app')

@section('title', 'AI Data Parser - Rapihkan Data ke Tabel')

@section('content')
<section class="py-16 md:py-24 px-4 max-w-5xl mx-auto text-center flex flex-col items-center">
    <h1 class="text-3xl md:text-5xl font-bold text-on-surface mb-4">
        AI Data Parser
    </h1>
    <p class="text-base text-on-surface-variant max-w-2xl">
        Ubah teks berantakan, *chat* WhatsApp, atau catatan acak menjadi format tabel rapi yang siap di-*copy-paste* ke Excel atau Google Sheets.
    </p>
</section>

<section class="pb-20 mx-auto py-16 px-4 md:px-10 bg-surface-container-lowest"> 
    <div class="max-w-7xl w-full mx-auto bg-surface-container-lowest rounded-xl shadow-sm border border-surface-variant overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-12 divide-y md:divide-y-0 md:divide-x divide-surface-variant">
            
            <!-- Left Column: Inputs -->
            <div class="p-6 md:p-9 md:col-span-6 flex flex-col h-full gap-5">
                <div class="flex flex-col">
                    <label class="text-base font-bold text-on-surface mb-2">Target Kolom (Opsional / Arahan)</label>
                    <input type="text" id="kolom-target" class="w-full bg-surface-container-lowest border border-surface-variant focus:border-primary rounded-lg p-3 text-body-md text-on-surface outline-none transition-all shadow-sm" placeholder="Contoh: Nama, No HP, Alamat, Pesanan">
                </div>

                <div class="relative grow flex flex-col mt-2">
                    <label class="text-base font-bold text-on-surface mb-2">Data Mentah Berantakan</label>
                    <textarea id="data-mentah" rows="10" class="w-full h-full min-h-62.5 resize-none bg-surface-container-lowest border border-surface-variant focus:border-primary rounded-lg p-4 pb-16 text-body-md text-on-surface outline-none transition-all shadow-sm" placeholder="Paste chat WA di sini. Misal:&#10;1. rudi (081234) pesen nasi goreng ke jl mawar&#10;2. siska pesen mie goreng, hp 0856, almt jl melati..."></textarea>
                    
                    <div class="absolute bottom-4 right-4">
                        <button onclick="parseData(event)" id="generate-btn" class="bg-secondary text-on-secondary w-10 h-10 rounded-full flex items-center justify-center hover:bg-secondary-container transition-colors shadow-md group">
                            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column: Output -->
            <div class="p-6 md:p-8 md:col-span-6 flex flex-col h-full bg-surface-container-low">
                <div class="flex items-center justify-between mb-4">
                    <label class="text-lg font-bold text-on-surface">Hasil Ekstraksi (Tabel)</label>
                    <button onclick="copyTable()" class="text-on-surface-variant hover:text-primary transition-colors" title="Salin Tabel">
                        <span class="material-symbols-outlined">content_copy</span>
                    </button>
                </div>
                
                <div class="relative grow rounded-lg border border-surface-variant bg-surface-container-lowest overflow-hidden">
                    <div id="loading-indicator" class="hidden absolute inset-0 flex-col items-center justify-center bg-surface-container-low/80 backdrop-blur-sm z-10">
                        <span class="material-symbols-outlined animate-spin text-primary text-4xl mb-2">progress_activity</span>
                        <p class="text-sm font-medium text-on-surface-variant">Mengekstrak data...</p>
                    </div>

                    <!-- Gunakan tag <pre> agar format spasi tabel Markdown tidak hancur -->
                    <pre id="data-result" class="w-full h-full min-h-87.5 p-4 text-on-surface font-mono text-sm overflow-auto">Data berbentuk tabel akan muncul di sini...</pre>
                </div>
                <p class="text-xs text-on-surface-variant mt-3 text-center">💡 Tips: Salin hasil di atas dan langsung paste ke cell A1 di Excel / Spreadsheet.</p>
            </div>
        </div>
    </div>
</section>

<script>
    async function parseData(event) {
        event.preventDefault();
        
        const kolomTarget = document.getElementById('kolom-target').value || "Deteksi Otomatis";
        const dataMentah = document.getElementById('data-mentah').value;
        
        const resultArea = document.getElementById('data-result');
        const loading = document.getElementById('loading-indicator');
        const generateBtn = document.getElementById('generate-btn');

        if (!dataMentah.trim()) {
            alert("Harap masukkan data mentah yang ingin dirapikan.");
            return;
        }

        loading.classList.remove('hidden');
        resultArea.classList.add('opacity-50');
        generateBtn.disabled = true;

        try {
            const response = await fetch("{{ route('ai-data-parser.generate') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ kolom_target: kolomTarget, data_mentah: dataMentah })
            });

            const data = await response.json();

            if (data.success) {
                resultArea.innerText = data.result;
            } else {
                resultArea.innerText = "Gagal memproses data. " + (data.message || "");
            }
        } catch (error) {
            resultArea.innerText = "Terjadi kesalahan koneksi ke server.";
        } finally {
            loading.classList.add('hidden');
            resultArea.classList.remove('opacity-50');
            generateBtn.disabled = false;
        }
    }

    function copyTable() {
        const textToCopy = document.getElementById('data-result').innerText;
        if(!textToCopy || textToCopy.includes("akan muncul di sini")) return;

        navigator.clipboard.writeText(textToCopy).then(() => {
            alert('Tabel berhasil disalin! Silakan paste (Ctrl+V) langsung di Excel.');
        });
    }
</script>
@endsection