@extends('layouts.app')

@section('title', 'AI Surat Resmi Generator - OmniTools')

@section('content')
<section class="py-16 md:py-24 px-4 max-w-5xl mx-auto text-center flex flex-col items-center">
    <h1 class="text-3xl md:text-5xl font-bold text-on-surface mb-4">
        AI Surat Resmi Generator
    </h1>
    <p class="text-base text-on-surface-variant max-w-2xl">
        Ketik inti pesan secara singkat, dan AI akan merangkainya menjadi draf surat resmi yang profesional, baku, dan siap di-*copy* ke Word.
    </p>
</section>

<section class="pb-20 mx-auto py-16 px-4 md:px-10 bg-surface-container-lowest"> 
    <div class="max-w-7xl w-full mx-auto bg-surface-container-lowest rounded-xl shadow-sm border border-surface-variant overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-12 divide-y md:divide-y-0 md:divide-x divide-surface-variant">
            
            <!-- Left Column: Inputs -->
            <div class="p-6 md:p-9 md:col-span-7 flex flex-col h-full gap-5">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="flex flex-col">
                        <label class="text-base font-bold text-on-surface mb-2">Jenis Surat</label>
                        <select id="jenis-surat" class="w-full bg-surface-container-lowest border border-surface-variant focus:border-primary rounded-lg p-3 text-body-md text-on-surface outline-none transition-all shadow-sm cursor-pointer">
                            <option value="Surat Permohonan">Surat Permohonan</option>
                            <option value="Surat Undangan">Surat Undangan Rapat/Acara</option>
                            <option value="Surat Tugas">Surat Tugas / Perintah</option>
                            <option value="Surat Pemberitahuan">Surat Pemberitahuan</option>
                            <option value="Surat Peringatan (SP)">Surat Peringatan (SP)</option>
                            <option value="Surat Keterangan">Surat Keterangan</option>
                        </select>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-base font-bold text-on-surface mb-2">Pihak Tujuan/Penerima</label>
                        <input type="text" id="penerima" class="w-full bg-surface-container-lowest border border-surface-variant focus:border-primary rounded-lg p-3 text-body-md text-on-surface outline-none transition-all shadow-sm" placeholder="Contoh: Kepala Dinas, Seluruh Karyawan, dll">
                    </div>
                </div>

                <div class="relative grow flex flex-col mt-2">
                    <label class="text-base font-bold text-on-surface mb-2">Konteks / Inti Pesan (Acak tidak apa-apa)</label>
                    <textarea id="konteks" rows="8" class="w-full h-full min-h-55 resize-none bg-surface-container-lowest border border-surface-variant focus:border-primary rounded-lg p-4 pb-16 text-body-md text-on-surface outline-none transition-all shadow-sm" placeholder="Contoh: Tolong buatkan undangan untuk meeting bahas progres project aplikasi kasir. Hari jumat besok jam 2 siang di ruang rapat utama. Wajib bawa laptop."></textarea>
                    
                    <div class="absolute bottom-4 right-4">
                        <button onclick="generateSurat(event)" id="generate-btn" class="bg-secondary text-on-secondary w-10 h-10 rounded-full flex items-center justify-center hover:bg-secondary-container transition-colors shadow-md group">
                            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column: Output -->
            <div class="p-6 md:p-8 md:col-span-5 flex flex-col h-full bg-surface-container-low">
                <div class="flex items-center justify-between mb-4">
                    <label class="text-lg font-bold text-on-surface">Draf Surat</label>
                    <button onclick="copySurat()" class="text-on-surface-variant hover:text-primary transition-colors" title="Salin Draf">
                        <span class="material-symbols-outlined">content_copy</span>
                    </button>
                </div>
                
                <div class="relative grow rounded-lg border border-transparent">
                    <div id="loading-indicator" class="hidden absolute inset-0 flex-col items-center justify-center bg-surface-container-low/80 backdrop-blur-sm rounded-lg z-10">
                        <span class="material-symbols-outlined animate-spin text-primary text-4xl mb-2">progress_activity</span>
                        <p class="text-sm font-medium text-on-surface-variant">Menyusun format surat...</p>
                    </div>

                    <div id="surat-result" class="w-full h-full min-h-87.5 p-4 text-on-surface whitespace-pre-wrap font-body-md overflow-y-auto">Draf surat resmi akan muncul di sini...</div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    async function generateSurat(event) {
        event.preventDefault();
        
        const jenisSurat = document.getElementById('jenis-surat').value;
        const penerima = document.getElementById('penerima').value;
        const konteks = document.getElementById('konteks').value;
        
        const resultArea = document.getElementById('surat-result');
        const loading = document.getElementById('loading-indicator');
        const generateBtn = document.getElementById('generate-btn');

        if (!penerima.trim() || !konteks.trim()) {
            alert("Harap isi penerima dan konteks isi surat.");
            return;
        }

        loading.classList.remove('hidden');
        resultArea.classList.add('opacity-50');
        generateBtn.disabled = true;

        try {
            const response = await fetch("{{ route('ai-surat-generator.generate') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ jenis_surat: jenisSurat, penerima, konteks })
            });

            const data = await response.json();

            if (data.success) {
                resultArea.innerText = data.result;
            } else {
                resultArea.innerText = "Gagal membuat surat. " + (data.message || "");
            }
        } catch (error) {
            resultArea.innerText = "Terjadi kesalahan koneksi ke server.";
        } finally {
            loading.classList.add('hidden');
            resultArea.classList.remove('opacity-50');
            generateBtn.disabled = false;
        }
    }

    function copySurat() {
        const textToCopy = document.getElementById('surat-result').innerText;
        if(!textToCopy || textToCopy.includes("akan muncul di sini")) return;

        navigator.clipboard.writeText(textToCopy).then(() => {
            alert('Draf surat berhasil disalin!');
        });
    }
</script>
@endsection