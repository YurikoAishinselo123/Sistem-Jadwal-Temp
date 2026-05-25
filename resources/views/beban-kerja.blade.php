<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beban Kerja - Scheduling System</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap');

        :root {
            --bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --glass-blur: blur(12px);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent: #6366f1;
            --accent-hover: #4f46e5;
            --danger: #ef4444;
            --success: #10b981;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: var(--bg-gradient);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            border-right: 1px solid var(--glass-border);
            padding: 2rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .sidebar h2 {
            margin-top: 0;
            margin-bottom: 2rem;
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--accent);
            text-align: center;
        }

        .nav-btn {
            background: transparent;
            border: none;
            color: var(--text-muted);
            padding: 1rem;
            text-align: left;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .nav-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-main);
        }

        .nav-btn.active {
            background: var(--accent);
            color: white;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        }

        /* Main Content */
        .main {
            flex: 1;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            gap: 2rem;
            overflow-y: auto;
        }

        .header h1 {
            margin: 0;
            font-weight: 300;
        }

        /* Filters Section */
        .filters {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 1.5rem;
            display: flex;
            gap: 1.5rem;
            align-items: flex-end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex: 1;
        }

        .form-group label {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .form-group select {
            padding: 0.8rem;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            color: white;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-group select:focus {
            border-color: var(--accent);
        }
        
        .form-group select option {
            background: #1e1b4b;
            color: white;
        }

        .btn {
            background: var(--accent);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
            height: 45px;
        }

        .btn:hover:not(:disabled) {
            background: var(--accent-hover);
            transform: translateY(-2px);
        }
        
        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            box-shadow: none;
        }

        /* Summary Section */
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            display: none; /* hidden until data loads */
        }
        
        .summary-cards.active {
            display: grid;
        }

        .card {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
        }

        .card h4 {
            margin: 0 0 0.5rem 0;
            color: var(--text-muted);
            font-weight: 400;
            font-size: 0.9rem;
        }

        .card .value {
            font-size: 2rem;
            font-weight: 600;
            color: var(--text-main);
        }
        
        .card.highlight .value {
            color: var(--accent);
        }

        /* Table Container */
        .table-container {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            overflow: hidden;
            flex: 1;
            display: none; /* hidden until data loads */
        }
        
        .table-container.active {
            display: block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th, td {
            padding: 1.2rem;
            border-bottom: 1px solid var(--glass-border);
        }

        th {
            background: rgba(0, 0, 0, 0.2);
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }

        /* Loading & Empty */
        .loading {
            padding: 3rem;
            text-align: center;
            color: var(--text-muted);
        }
        
        .empty-state {
            padding: 3rem;
            text-align: center;
            color: var(--text-muted);
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            display: none;
        }
        
        .empty-state.active {
            display: block;
        }

    </style>
</head>
<body>

    <aside class="sidebar">
        <h2>Beban Kerja</h2>
        <button class="nav-btn active" data-tab="dosen">Beban Dosen</button>
        <button class="nav-btn" data-tab="ruangan">Beban Ruangan</button>
    </aside>

    <main class="main">
        <div class="header">
            <h1 id="page-title">Beban Kerja Dosen</h1>
        </div>

        <!-- Filters -->
        <div class="filters" id="filter-section">
            <div class="form-group" id="filter-dosen-group">
                <label for="dosen-select">Pilih Dosen</label>
                <select id="dosen-select">
                    <option value="">Loading...</option>
                </select>
            </div>
            
            <div class="form-group" id="filter-ruangan-group" style="display: none;">
                <label for="ruangan-select">Pilih Ruangan</label>
                <select id="ruangan-select">
                    <option value="">Loading...</option>
                </select>
            </div>

            <div class="form-group">
                <label for="periode-select">Pilih Periode</label>
                <select id="periode-select">
                    <option value="">Loading...</option>
                </select>
            </div>

            <button class="btn" id="btn-fetch" disabled>Tampilkan Data</button>
        </div>
        
        <div id="loading-indicator" class="loading" style="display: none;">Memuat data...</div>
        
        <div id="empty-state" class="empty-state">
            Belum ada data jadwal untuk filter yang dipilih.
        </div>

        <!-- Summary Cards -->
        <div class="summary-cards" id="summary-section">
            <!-- Dynamic cards injected here -->
        </div>

        <!-- Table -->
        <div class="table-container" id="table-section">
            <table>
                <thead id="table-head">
                    <!-- Dynamic Headers -->
                </thead>
                <tbody id="table-body">
                    <!-- Dynamic Rows -->
                </tbody>
            </table>
        </div>
    </main>

    <script>
        // State
        let currentTab = 'dosen'; // 'dosen' or 'ruangan'
        let masterData = null;

        // DOM Elements
        const navBtns = document.querySelectorAll('.nav-btn');
        const pageTitle = document.getElementById('page-title');
        
        const dosenGroup = document.getElementById('filter-dosen-group');
        const ruanganGroup = document.getElementById('filter-ruangan-group');
        
        const selectDosen = document.getElementById('dosen-select');
        const selectRuangan = document.getElementById('ruangan-select');
        const selectPeriode = document.getElementById('periode-select');
        const btnFetch = document.getElementById('btn-fetch');
        
        const loadingIndicator = document.getElementById('loading-indicator');
        const emptyState = document.getElementById('empty-state');
        const summarySection = document.getElementById('summary-section');
        const tableSection = document.getElementById('table-section');
        
        const tableHead = document.getElementById('table-head');
        const tableBody = document.getElementById('table-body');

        // Initialization
        init();

        function init() {
            // Setup navigation
            navBtns.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    navBtns.forEach(b => b.classList.remove('active'));
                    e.target.classList.add('active');
                    currentTab = e.target.dataset.tab;
                    updateUIForTab();
                    resetView();
                });
            });

            btnFetch.addEventListener('click', fetchWorkload);

            // Fetch initial master data
            fetchMasterData();
        }

        function updateUIForTab() {
            if (currentTab === 'dosen') {
                pageTitle.textContent = 'Beban Kerja Dosen';
                dosenGroup.style.display = 'flex';
                ruanganGroup.style.display = 'none';
            } else {
                pageTitle.textContent = 'Beban Kerja Ruangan';
                dosenGroup.style.display = 'none';
                ruanganGroup.style.display = 'flex';
            }
        }
        
        function resetView() {
            summarySection.classList.remove('active');
            tableSection.classList.remove('active');
            emptyState.classList.remove('active');
            summarySection.innerHTML = '';
            tableBody.innerHTML = '';
        }

        async function fetchMasterData() {
            try {
                const res = await fetch('/api/v1/master-data');
                const json = await res.json();
                masterData = json;
                
                populateSelects();
                btnFetch.disabled = false;
            } catch (err) {
                console.error("Failed to load master data", err);
                selectDosen.innerHTML = '<option>Error loading</option>';
                selectRuangan.innerHTML = '<option>Error loading</option>';
                selectPeriode.innerHTML = '<option>Error loading</option>';
            }
        }

        function populateSelects() {
            // Dosen
            selectDosen.innerHTML = '<option value="">-- Pilih Dosen --</option>' + 
                masterData.dosens.map(d => `<option value="${d.id}">${d.nama_dosen} (${d.kode_dosen})</option>`).join('');
                
            // Ruangan
            selectRuangan.innerHTML = '<option value="">-- Pilih Ruangan --</option>' + 
                masterData.ruangans.map(r => `<option value="${r.id}">${r.nama_ruangan} (${r.kode_ruangan})</option>`).join('');
                
            // Periode
            selectPeriode.innerHTML = '<option value="">-- Pilih Periode --</option>' + 
                masterData.periodes.map(p => `<option value="${p.id}">${p.periode}</option>`).join('');
        }

        async function fetchWorkload() {
            const periodeId = selectPeriode.value;
            
            if (!periodeId) {
                alert("Silakan pilih periode terlebih dahulu!");
                return;
            }

            let endpoint = '';
            let payload = { periode_id: periodeId };

            if (currentTab === 'dosen') {
                if (!selectDosen.value) return alert("Pilih dosen!");
                endpoint = '/api/v1/beban-kerja/dosen';
                payload.dosen_id = selectDosen.value;
            } else {
                if (!selectRuangan.value) return alert("Pilih ruangan!");
                endpoint = '/api/v1/beban-kerja/ruangan';
                payload.ruangan_id = selectRuangan.value;
            }

            resetView();
            loadingIndicator.style.display = 'block';

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                
                if (!response.ok) {
                    throw new Error("Gagal mengambil data beban kerja.");
                }

                const json = await response.json();
                const data = json.data;
                
                loadingIndicator.style.display = 'none';

                if (data.schedules.length === 0) {
                    emptyState.classList.add('active');
                    return;
                }

                renderSummary(data.summary);
                renderTable(data.schedules);
                
                summarySection.classList.add('active');
                tableSection.classList.add('active');

            } catch (err) {
                loadingIndicator.style.display = 'none';
                console.error(err);
                alert('Terjadi kesalahan saat memuat data beban kerja.');
            }
        }

        function renderSummary(summary) {
            summarySection.innerHTML = '';
            
            if (currentTab === 'dosen') {
                summarySection.innerHTML = `
                    <div class="card">
                        <h4>Total Sesi Mengajar</h4>
                        <div class="value">${summary.total_mengajar_sesi}</div>
                    </div>
                    <div class="card">
                        <h4>Total SKS Teori</h4>
                        <div class="value">${summary.total_sks_teori}</div>
                    </div>
                    <div class="card">
                        <h4>Total SKS Praktik</h4>
                        <div class="value">${summary.total_sks_praktik}</div>
                    </div>
                    <div class="card highlight">
                        <h4>Total Keseluruhan Beban</h4>
                        <div class="value">${summary.total_keseluruhan_beban} SKS</div>
                    </div>
                `;
            } else {
                summarySection.innerHTML = `
                    <div class="card">
                        <h4>Total Penggunaan (Jadwal)</h4>
                        <div class="value">${summary.total_penggunaan_ruangan}</div>
                    </div>
                    <div class="card highlight">
                        <h4>Total Sesi Penggunaan</h4>
                        <div class="value">${summary.total_sesi_penggunaan_ruangan}</div>
                    </div>
                `;
            }
        }

        function renderTable(schedules) {
            if (currentTab === 'dosen') {
                tableHead.innerHTML = `<tr>
                    <th>Mata Kuliah</th>
                    <th>Kelas</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Ruangan</th>
                    <th>Jenis Ruangan</th>
                    <th>Total Sesi</th>
                    <th>Total SKS</th>
                </tr>`;
                
                tableBody.innerHTML = schedules.map(item => `
                    <tr>
                        <td>${item.mata_kuliah}</td>
                        <td>${item.kelas}</td>
                        <td>${item.hari}</td>
                        <td>${item.jam_mulai} - ${item.jam_selesai}</td>
                        <td>${item.ruangan || '-'}</td>
                        <td>${item.jenis_ruangan || '-'}</td>
                        <td>${item.total_sesi}</td>
                        <td>${item.total_sks}</td>
                    </tr>
                `).join('');
                
            } else {
                tableHead.innerHTML = `<tr>
                    <th>Mata Kuliah</th>
                    <th>Dosen</th>
                    <th>Kelas</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Jenis Ruangan</th>
                </tr>`;
                
                tableBody.innerHTML = schedules.map(item => `
                    <tr>
                        <td>${item.mata_kuliah}</td>
                        <td>${item.dosen}</td>
                        <td>${item.kelas}</td>
                        <td>${item.hari}</td>
                        <td>${item.jam_mulai} - ${item.jam_selesai}</td>
                        <td>${item.jenis_ruangan}</td>
                    </tr>
                `).join('');
            }
        }

    </script>
</body>
</html>
