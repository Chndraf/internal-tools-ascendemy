// import './bootstrap';

// --- 1. Logika Switcher & Animasi Slide ---
window.switchTab = function(tab) {
    const slider = document.getElementById('form-slider');
    const tabBg = document.getElementById('tab-bg');
    const btnKeyword = document.getElementById('btn-keyword');
    const btnUrl = document.getElementById('btn-url');
    const resultsArea = document.getElementById('results-area');

    resultsArea.classList.add('hidden');

    if (tab === 'url') {
        slider.style.transform = 'translateX(-100%)';
        tabBg.style.transform = 'translateX(100%)';
        
        btnKeyword.classList.replace('text-on-primary', 'text-on-surface-variant');
        btnUrl.classList.replace('text-on-surface-variant', 'text-on-primary');
    } else {
        slider.style.transform = 'translateX(0)';
        tabBg.style.transform = 'translateX(0)';
        
        btnUrl.classList.replace('text-on-primary', 'text-on-surface-variant');
        btnKeyword.classList.replace('text-on-surface-variant', 'text-on-primary');
    }
};

// --- 2. Logika AJAX Extract Data ---
window.extractData = async function(event, type) {
    event.preventDefault(); 
    
    const loading = document.getElementById('loading-indicator');
    const resultsArea = document.getElementById('results-area');
    const tbody = document.getElementById('results-tbody');
    const title = document.getElementById('results-title');

    loading.classList.remove('hidden');
    resultsArea.classList.add('hidden');
    tbody.innerHTML = '';

    try {
        // Menggunakan path absolut '/' menggantikan blade route('home')
        let url = "/email-extractor?";
        let params = new URLSearchParams({ type: type });

        if (type === 'keyword') {
            params.append('country', document.getElementById('country-select').value);
            params.append('keyword', document.getElementById('keyword-select').value);
        } else if (type === 'url') {
            params.append('url_endpoint', document.getElementById('url-input').value);
        }

        const response = await fetch(url + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        const result = await response.json();
        
        let emails = result.data;
        let total = result.total;

        title.innerText = `Hasil Ekstraksi (${total} email ditemukan)`;

        if (emails && emails.length > 0) {
            emails.forEach(item => {
                let emailText = item.email ? item.email : item; 
                
                tbody.innerHTML += `
                    <tr class="border-b border-surface-variant hover:bg-surface-container-low transition-colors">
                        <td class="py-3 px-3">${emailText}</td>
                        <td class="py-3 px-3 text-center">
                            <button type="button" onclick="copyEmail('${emailText}')" class="text-on-surface-variant hover:text-primary transition-colors" title="Copy Email">
                                <span class="material-symbols-outlined text-[18px]">content_copy</span>
                            </button>
                        </td>
                    </tr>
                `;
            });
        } else {
            tbody.innerHTML = `<tr><td colspan="2" class="py-4 text-center text-on-surface-variant">Tidak ada email yang ditemukan.</td></tr>`;
        }

        loading.classList.add('hidden');
        resultsArea.classList.remove('hidden');

    } catch (error) {
        console.error("Error fetching data:", error);
        loading.classList.add('hidden');
        alert('Terjadi kesalahan saat mengambil data.');
    }
};

// --- 3. Logika Copy To Clipboard ---
window.copyEmail = function(email) {
    navigator.clipboard.writeText(email).then(() => {
        alert('Email berhasil disalin: ' + email);
    }).catch(err => {
        console.error('Gagal menyalin email', err);
    });
};

// --- 4. Logika Export to CSV ---
window.exportToCSV = function() {
    const rows = document.querySelectorAll('#results-tbody tr');
    let emails = [];
    
    rows.forEach(row => {
        const emailCell = row.querySelector('td');
        if (emailCell && emailCell.innerText.includes('@')) {
            emails.push(emailCell.innerText.trim());
        }
    });

    if (emails.length === 0) {
        alert('Tidak ada email valid yang bisa diexport.');
        return;
    }

    let csvContent = "Email\n" + emails.join("\n");
    
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    
    const link = document.createElement("a");
    link.setAttribute("href", url);
    link.setAttribute("download", "hasil_ekstraksi_email.csv");
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

// Logika Email Check Spam
async function checkSpam(event) {
        event.preventDefault();
        
        const loading = document.getElementById('loading-indicator');
        const resultsArea = document.getElementById('results-area');
        
        resultsArea.classList.add('hidden');
        loading.classList.remove('hidden');

        const subject = document.getElementById('email-subject').value;
        const body = document.getElementById('email-body').value;

        try {
            // Mengirim request ke backend Laravel
            const response = await fetch("{{ route('spam-checker.analyze') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Keamanan wajib Laravel
                },
                body: JSON.stringify({ subject: subject, body: body })
            });

            const result = await response.json();

            // Render Hasil ke Layar
            document.getElementById('spam-score').innerText = `${result.score}/100`;
            
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