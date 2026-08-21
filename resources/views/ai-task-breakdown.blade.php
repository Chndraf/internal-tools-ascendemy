@extends('layouts.app')

@section('title', 'AI Task Breakdown - Pemecah Proyek & Tugas')

@section('content')
<section class="py-16 md:py-24 px-4 max-w-5xl mx-auto text-center flex flex-col items-center">
    <h1 class="text-3xl md:text-5xl font-bold text-on-surface mb-4">
        AI Task Breakdown
    </h1>
    <p class="text-base text-on-surface-variant max-w-2xl">
        Ubah target proyek yang besar dan membingungkan menjadi daftar langkah kerja (*To-Do List*) yang detail, bertahap, dan dilengkapi estimasi waktu.
    </p>
</section>

<section class="pb-20 mx-auto py-16 px-4 md:px-10 bg-surface-container-lowest"> 
    <div class="max-w-7xl w-full mx-auto bg-surface-container-lowest rounded-xl shadow-sm border border-surface-variant overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-12 divide-y md:divide-y-0 md:divide-x divide-surface-variant">
            
            <!-- Left Column: Inputs -->
            <div class="p-6 md:p-9 md:col-span-7 flex flex-col h-full gap-5">
                <div class="flex flex-col">
                    <label class="text-base font-bold text-on-surface mb-2">Nama Proyek / Target Besar</label>
                    <input type="text" id="project-name" class="w-full bg-surface-container-lowest border border-surface-variant focus:border-primary rounded-lg p-3 text-body-md text-on-surface outline-none transition-all shadow-sm" placeholder="Contoh: Membuat Aplikasi Kasir Sederhana Berbasis Web">
                </div>

                <div class="relative grow flex flex-col mt-2">
                    <label class="text-base font-bold text-on-surface mb-2">Konteks / Detail Tambahan</label>
                    <textarea id="context" rows="8" class="w-full h-full min-h-50 resize-none bg-surface-container-lowest border border-surface-variant focus:border-primary rounded-lg p-4 pb-16 text-body-md text-on-surface outline-none transition-all shadow-sm" placeholder="Contoh: Aplikasi ini dibuat pakai Laravel. Harus ada fitur login admin, kasir, dan laporan penjualan bulanan. Waktu pengerjaan sekitar 2 minggu."></textarea>
                    
                    <div class="absolute bottom-4 right-4">
                        <button onclick="generateTasks(event)" id="generate-btn" class="bg-secondary text-on-secondary w-10 h-10 rounded-full flex items-center justify-center hover:bg-secondary-container transition-colors shadow-md group">
                            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column: Output -->
            <div class="p-6 md:p-8 md:col-span-5 flex flex-col h-full bg-surface-container-low">
                <div class="flex items-center justify-between mb-4">
                    <label class="text-lg font-bold text-on-surface">Rincian Tugas (To-Do List)</label>
                    <button onclick="copyTasks()" class="text-on-surface-variant hover:text-primary transition-colors" title="Salin Tugas">
                        <span class="material-symbols-outlined">content_copy</span>
                    </button>
                </div>
                
                <div class="relative grow rounded-lg border border-transparent">
                    <div id="loading-indicator" class="hidden absolute inset-0 flex-col items-center justify-center bg-surface-container-low/80 backdrop-blur-sm rounded-lg z-10">
                        <span class="material-symbols-outlined animate-spin text-primary text-4xl mb-2">progress_activity</span>
                        <p class="text-sm font-medium text-on-surface-variant">Memecah proyek...</p>
                    </div>

                    <div id="tasks-result" class="w-full h-full min-h-87.5 p-4 text-on-surface whitespace-pre-wrap font-body-md overflow-y-auto">Langkah-langkah kerja akan muncul di sini...</div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    async function generateTasks(event) {
        event.preventDefault();
        
        const projectName = document.getElementById('project-name').value;
        const context = document.getElementById('context').value;
        
        const resultArea = document.getElementById('tasks-result');
        const loading = document.getElementById('loading-indicator');
        const generateBtn = document.getElementById('generate-btn');

        if (!projectName.trim()) {
            alert("Harap isi nama proyek atau target besarnya.");
            return;
        }

        loading.classList.remove('hidden');
        resultArea.classList.add('opacity-50');
        generateBtn.disabled = true;

        try {
            const response = await fetch("{{ route('ai-task-breakdown.generate') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ project_name: projectName, context })
            });

            const data = await response.json();

            if (data.success) {
                resultArea.innerText = data.result;
            } else {
                resultArea.innerText = "Gagal memecah tugas. " + (data.message || "");
            }
        } catch (error) {
            resultArea.innerText = "Terjadi kesalahan koneksi ke server.";
        } finally {
            loading.classList.add('hidden');
            resultArea.classList.remove('opacity-50');
            generateBtn.disabled = false;
        }
    }

    function copyTasks() {
        const textToCopy = document.getElementById('tasks-result').innerText;
        if(!textToCopy || textToCopy.includes("akan muncul di sini")) return;

        navigator.clipboard.writeText(textToCopy).then(() => {
            alert('Daftar tugas berhasil disalin!');
        });
    }
</script>
@endsection