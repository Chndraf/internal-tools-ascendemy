@extends('layouts.app')

@section('title', 'AI PDF Summarizer - OmniTools')

@section('content')
<section class="py-16 md:py-24 px-4 max-w-5xl mx-auto text-center flex flex-col items-center">
    <h1 class="text-3xl md:text-5xl font-bold text-on-surface mb-4">
        AI PDF Summarizer
    </h1>
    <p class="text-base text-on-surface-variant max-w-2xl">
        Ekstrak poin-poin penting dari laporan, jurnal, atau dokumen panjang dalam hitungan detik. Cukup unggah file PDF Anda.
    </p>
</section>

<section class="pb-20 mx-auto py-16 px-4 md:px-10 bg-surface-container-lowest"> 
    <div class="max-w-7xl w-full mx-auto bg-surface-container-lowest rounded-xl shadow-sm border border-surface-variant overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-12 divide-y md:divide-y-0 md:divide-x divide-surface-variant">
            
            <!-- Left Column: PDF Input -->
            <div class="p-6 md:p-9 md:col-span-7 flex flex-col h-full gap-6">
                <label class="text-lg font-bold text-on-surface">Unggah Dokumen PDF</label>
                
                <div class="relative grow flex flex-col items-center justify-center border-2 border-dashed border-outline-variant rounded-xl bg-surface-container-lowest hover:bg-surface-container-low transition-colors p-8 text-center cursor-pointer" onclick="document.getElementById('pdf-file').click()">
                    <input type="file" id="pdf-file" class="hidden" accept="application/pdf" onchange="showFileName(event)">
                    
                    <span class="material-symbols-outlined text-5xl text-primary mb-3">upload_file</span>
                    <p class="text-on-surface font-bold text-lg mb-1">Klik untuk memilih file PDF</p>
                    <p class="text-sm text-on-surface-variant mb-4">Maksimal ukuran file: 5MB</p>
                    
                    <!-- Area nama file terpilih -->
                    <div id="file-info" class="hidden items-center gap-2 bg-primary-fixed text-on-primary-fixed px-4 py-2 rounded-lg mt-2">
                        <span class="material-symbols-outlined text-sm">check_circle</span>
                        <span id="file-name" class="font-bold text-sm truncate max-w-50"></span>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button onclick="generateSummary(event)" id="generate-btn" class="bg-primary text-on-primary font-bold py-4 px-6 rounded-xl shadow-md hover:bg-primary-container transition-colors w-full flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">auto_awesome</span> RANGKUM SEKARANG
                </button>
            </div>

            <!-- Right Column: Output -->
            <div class="p-6 md:p-8 md:col-span-5 flex flex-col h-full bg-surface-container-low">
                <div class="flex items-center justify-between mb-4">
                    <label class="text-lg font-bold text-on-surface">Hasil Ringkasan</label>
                    <button onclick="copySummary()" class="text-on-surface-variant hover:text-primary transition-colors" title="Salin Ringkasan">
                        <span class="material-symbols-outlined">content_copy</span>
                    </button>
                </div>
                
                <div class="relative grow rounded-lg border border-transparent">
                    <div id="loading-indicator" class="hidden absolute inset-0 flex-col items-center justify-center bg-surface-container-low/80 backdrop-blur-sm rounded-lg z-10">
                        <span class="material-symbols-outlined animate-spin text-primary text-4xl mb-2">progress_activity</span>
                        <p class="text-sm font-medium text-on-surface-variant">AI sedang membaca dokumen...</p>
                    </div>

                    <div id="summary-result" class="w-full h-full min-h-87.5 p-4 text-on-surface whitespace-pre-wrap font-body-md overflow-y-auto">Ringkasan dokumen Anda akan muncul di sini...</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Cara Kerja Section -->
<section class="py-20 px-4 md:px-10 max-w-7xl mx-auto" id="cara-kerja">
    <div class="text-center mb-16">
        <h2 class="text-3xl font-bold text-on-surface mb-4">Cara Kerja AI PDF Summarizer</h2>
        <p class="text-base text-on-surface-variant max-w-2xl mx-auto">Tiga langkah mudah untuk memahami inti dari dokumen panjang tanpa harus membaca semuanya.</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-surface-container hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-primary-fixed rounded-lg flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-primary" data-icon="upload_file">upload_file</span>
            </div>
            <h3 class="text-xl font-bold text-on-surface mb-3">1. Unggah PDF</h3>
            <p class="text-sm text-on-surface-variant">Pilih file PDF dari perangkat Anda (maks 5MB) berupa teks seperti laporan, artikel, atau proposal.</p>
        </div>
        
        <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-surface-container hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-secondary-fixed rounded-lg flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-secondary" data-icon="memory">memory</span>
            </div>
            <h3 class="text-xl font-bold text-on-surface mb-3">2. Ekstraksi AI</h3>
            <p class="text-sm text-on-surface-variant">Sistem akan mengekstrak teks dan AI akan menganalisis poin-poin paling krusial dari dokumen tersebut.</p>
        </div>
        
        <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-surface-container hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-tertiary-fixed rounded-lg flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-tertiary" data-icon="format_list_bulleted">format_list_bulleted</span>
            </div>
            <h3 class="text-xl font-bold text-on-surface mb-3">3. Baca Ringkasan</h3>
            <p class="text-sm text-on-surface-variant">Dapatkan ringkasan terstruktur yang rapi, siap untuk dibaca, disalin, atau dibagikan ke tim Anda.</p>
        </div>
    </div>
</section>

<script>
    function showFileName(event) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById('file-info').classList.remove('hidden');
            document.getElementById('file-name').innerText = file.name;
        }
    }

    async function generateSummary(event) {
        event.preventDefault();
        
        const fileInput = document.getElementById('pdf-file');
        const resultArea = document.getElementById('summary-result');
        const loading = document.getElementById('loading-indicator');
        const generateBtn = document.getElementById('generate-btn');

        if (!fileInput.files[0]) {
            alert("Harap unggah file PDF terlebih dahulu.");
            return;
        }

        // Gunakan FormData karena kita mengirimkan file, bukan teks JSON
        const formData = new FormData();
        formData.append('pdf_file', fileInput.files[0]);

        loading.classList.remove('hidden');
        resultArea.classList.add('opacity-50');
        generateBtn.disabled = true;

        try {
            const response = await fetch("{{ route('ai-pdf-summarizer.generate') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Tidak perlu Content-Type untuk FormData
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                resultArea.innerText = data.result;
            } else {
                resultArea.innerText = "Maaf, gagal merangkum dokumen. " + (data.message || "");
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

    function copySummary() {
        const textToCopy = document.getElementById('summary-result').innerText;
        if(!textToCopy || textToCopy.includes("akan muncul di sini")) return;

        navigator.clipboard.writeText(textToCopy).then(() => {
            alert('Ringkasan berhasil disalin!');
        });
    }
</script>
@endsection