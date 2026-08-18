@extends('layouts.app')

@section('title', 'AI Caption Generator - OmniTools')

@section('content')
<section class="py-16 md:py-24 px-4 max-w-5xl mx-auto text-center flex flex-col items-center">
    <div class="bg-primary-fixed text-primary-container px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest mb-6 flex items-center gap-2 shadow-sm border border-primary-fixed-dim">
        <span class="material-symbols-outlined text-sm">image</span> Visual AI
    </div>
    <h1 class="text-3xl md:text-5xl font-bold text-on-surface mb-4">
        AI Caption Generator
    </h1>
    <p class="text-base text-on-surface-variant max-w-2xl">
        Unggah gambar produk atau kegiatanmu, tentukan gaya bahasanya, dan biarkan AI membuatkan caption menarik untuk setiap gambarnya.
    </p>
</section>

<section class="pb-20 mx-auto py-16 px-4 md:px-10 bg-surface-container-lowest"> 
    <div class="max-w-7xl w-full mx-auto bg-surface-container-lowest rounded-xl shadow-sm border border-surface-variant overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-12 divide-y md:divide-y-0 md:divide-x divide-surface-variant">
            
            <!-- Left Column: Inputs -->
            <div class="p-6 md:p-9 md:col-span-7 flex flex-col h-full gap-5">
                <!-- Image Upload -->
                <div class="flex flex-col">
                    <label class="text-base font-bold text-on-surface mb-2">Unggah Gambar (Maks 4)</label>
                    <div class="relative flex flex-col items-center justify-center border-2 border-dashed border-outline-variant rounded-xl bg-surface-container-lowest hover:bg-surface-container-low transition-colors p-6 text-center cursor-pointer" onclick="document.getElementById('image-upload').click()">
                        <input type="file" id="image-upload" class="hidden" accept="image/jpeg, image/png, image/webp" multiple onchange="previewImages(event)">
                        
                        <span class="material-symbols-outlined text-4xl text-primary mb-2">add_photo_alternate</span>
                        <p class="text-on-surface font-bold text-sm mb-1">Klik untuk memilih gambar</p>
                    </div>
                    
                    <!-- Area Preview Thumbnail -->
                    <div id="image-preview-container" class="flex flex-wrap gap-3 mt-4">
                        <!-- Thumbnail akan dimunculkan di sini via JS -->
                    </div>
                </div>

                <!-- Text Context -->
                <div class="relative grow flex flex-col mt-2">
                    <label class="text-base font-bold text-on-surface mb-2">Gaya & Konteks Caption</label>
                    <textarea id="caption-context" rows="6" class="w-full h-full min-h-37.5 resize-none bg-surface-container-lowest border border-surface-variant focus:border-primary rounded-lg p-4 pb-16 text-body-md text-on-surface outline-none transition-all shadow-sm" placeholder="Contoh: Buatkan caption promosi diskon 50% untuk produk baju ini. Gaya bahasanya santai, kekinian, dan ajak audiens untuk komen..."></textarea>
                    
                    <div class="absolute bottom-4 right-4">
                        <button onclick="generateCaption(event)" id="generate-btn" class="bg-secondary text-on-secondary w-10 h-10 rounded-full flex items-center justify-center hover:bg-secondary-container transition-colors shadow-md group">
                            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column: Output -->
            <div class="p-6 md:p-8 md:col-span-5 flex flex-col h-full bg-surface-container-low">
                <div class="flex items-center justify-between mb-4">
                    <label class="text-lg font-bold text-on-surface">Hasil Caption</label>
                    <button onclick="copyCaption()" class="text-on-surface-variant hover:text-primary transition-colors" title="Salin Caption">
                        <span class="material-symbols-outlined">content_copy</span>
                    </button>
                </div>
                
                <div class="relative grow rounded-lg border border-transparent">
                    <div id="loading-indicator" class="hidden absolute inset-0 flex-col items-center justify-center bg-surface-container-low/80 backdrop-blur-sm rounded-lg z-10">
                        <span class="material-symbols-outlined animate-spin text-primary text-4xl mb-2">progress_activity</span>
                        <p class="text-sm font-medium text-on-surface-variant mt-2 text-center">AI sedang menganalisis gambar<br>dan merangkai kata...</p>
                    </div>

                    <div id="caption-result" class="w-full h-full min-h-87.5 p-4 text-on-surface whitespace-pre-wrap font-body-md overflow-y-auto">Caption akan muncul di sini...</div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Menyimpan file gambar agar mudah diakses
    let selectedFiles = [];

    function previewImages(event) {
        const previewContainer = document.getElementById('image-preview-container');
        previewContainer.innerHTML = ''; // Kosongkan preview sebelumnya
        
        const files = Array.from(event.target.files);
        
        if (files.length > 4) {
            alert('Maksimal hanya boleh mengunggah 4 gambar.');
            event.target.value = ''; // Reset input
            selectedFiles = [];
            previewContainer.classList.add('hidden');
            return;
        }

        selectedFiles = files;

        if (files.length > 0) {
            previewContainer.classList.remove('hidden');
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-20 h-20 object-cover rounded-lg border border-surface-variant shadow-sm';
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        } else {
            previewContainer.classList.add('hidden');
        }
    }

    async function generateCaption(event) {
        event.preventDefault();
        
        const context = document.getElementById('caption-context').value;
        const resultArea = document.getElementById('caption-result');
        const loading = document.getElementById('loading-indicator');
        const generateBtn = document.getElementById('generate-btn');

        if (selectedFiles.length === 0 || !context.trim()) {
            alert("Harap unggah minimal 1 gambar dan isi instruksi konteksnya.");
            return;
        }

        loading.classList.remove('hidden');
        resultArea.classList.add('opacity-50');
        generateBtn.disabled = true;

        // Gunakan FormData karena mengirim File gambar utuh
        const formData = new FormData();
        formData.append('context', context);
        selectedFiles.forEach((file) => {
            formData.append('images[]', file); // Array input file
        });

        try {
            const response = await fetch("{{ route('ai-caption-generator.generate') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Fetch otomatis set Content-Type untuk FormData
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                resultArea.innerText = data.result;
            } else {
                resultArea.innerText = "Gagal membuat caption. " + (data.message || "");
            }
        } catch (error) {
            resultArea.innerText = "Terjadi kesalahan koneksi ke server.";
        } finally {
            loading.classList.add('hidden');
            resultArea.classList.remove('opacity-50');
            generateBtn.disabled = false;
        }
    }

    function copyCaption() {
        const textToCopy = document.getElementById('caption-result').innerText;
        if(!textToCopy || textToCopy.includes("akan muncul di sini")) return;

        navigator.clipboard.writeText(textToCopy).then(() => {
            alert('Caption berhasil disalin!');
        });
    }
</script>
@endsection