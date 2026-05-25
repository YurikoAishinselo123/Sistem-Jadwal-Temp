<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Management - Scheduling System</title>
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
            --warning: #eab308;
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
            text-decoration: none;
            display: block;
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

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            margin: 0;
            font-weight: 300;
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
        }

        .btn:hover:not(:disabled) {
            background: var(--accent-hover);
            transform: translateY(-2px);
        }

        .btn-warning {
            background: transparent;
            border: 1px solid var(--warning);
            color: var(--warning);
            box-shadow: none;
            padding: 0.4rem 0.8rem;
        }
        .btn-warning:hover {
            background: var(--warning);
            color: white;
        }

        .btn-danger {
            background: transparent;
            border: 1px solid var(--danger);
            color: var(--danger);
            box-shadow: none;
            padding: 0.4rem 0.8rem;
        }
        .btn-danger:hover {
            background: var(--danger);
            color: white;
        }

        .btn-info {
            background: transparent;
            border: 1px solid #0ea5e9;
            color: #0ea5e9;
            box-shadow: none;
            padding: 0.4rem 0.8rem;
        }
        .btn-info:hover {
            background: #0ea5e9;
            color: white;
        }

        /* Table Container */
        .table-container {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            overflow: hidden;
            flex: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th, td {
            padding: 1rem;
            border-bottom: 1px solid var(--glass-border);
            font-size: 0.9rem;
        }

        th {
            background: rgba(0, 0, 0, 0.2);
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            z-index: 100;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: all;
        }

        .modal {
            background: #1e1b4b;
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 2rem;
            width: 100%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            transform: translateY(20px);
            transition: transform 0.3s ease;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .modal-overlay.active .modal {
            transform: translateY(0);
        }

        .modal h3 {
            margin-top: 0;
            margin-bottom: 1.5rem;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group.full {
            grid-column: span 2;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 0.8rem;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            color: white;
            font-size: 1rem;
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.2s;
        }
        
        .form-group input, .form-group select {
            width: 100%;
            padding: 0.8rem;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            color: white;
            font-size: 1rem;
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-group input:focus, .form-group select:focus {
            border-color: var(--accent);
        }

        .form-group input:focus, .form-group select:focus {
            border-color: var(--accent);
        }
        
        .form-group select option {
            background: #1e1b4b;
            color: white;
            padding: 0.5rem;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-cancel {
            background: transparent;
            border: 1px solid var(--text-muted);
            color: var(--text-muted);
        }

        .btn-cancel:hover {
            background: rgba(255,255,255,0.1);
        }

        .loading {
            padding: 3rem;
            text-align: center;
            color: var(--text-muted);
        }
        
        .help-text {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }

        .filter-panel {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 1.5rem;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem 1.5rem;
            align-items: end;
        }

        .filter-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
            margin-top: 1rem;
        }

        .btn-secondary {
            background: transparent;
            border: 1px solid var(--glass-border);
            color: var(--text-main);
            box-shadow: none;
        }

        .btn-secondary:hover {
            background: rgba(255,255,255,0.08);
        }

        @media (max-width: 960px) {
            .filter-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 640px) {
            .filter-grid {
                grid-template-columns: 1fr;
            }

            .filter-actions {
                justify-content: stretch;
                flex-direction: column;
            }
        }

    </style>
</head>
<body>

    <aside class="sidebar">
        <h2>Dashboard</h2>
        <a href="/jadwal" class="nav-btn active">📅 Jadwal</a>
        <a href="/master-data" class="nav-btn">🗂️ Master Data</a>
        <a href="/beban-kerja" class="nav-btn">📊 Beban Kerja</a>
        
        <button id="auth-action-btn" onclick="handleAuthAction()" class="nav-btn" style="margin-top: auto; color: var(--danger); font-weight: 600; display: flex; align-items: center; gap: 0.5rem; background: transparent; border: none; width: 100%; cursor: pointer;">
            🔐 Sign In
        </button>
    </aside>

    <main class="main">
        <div class="header">
            <h1>Manajemen Jadwal</h1>
            <button class="btn" id="btn-add" disabled>+ Tambah Jadwal</button>
        </div>

        <section class="filter-panel">
            <div class="filter-grid">
                <div class="form-group">
                    <label for="filter-periode">Periode</label>
                    <select id="filter-periode">
                        <option value="">Semua Periode</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="filter-day">Hari</label>
                    <select id="filter-day">
                        <option value="">Semua Hari</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="filter-prodi">Program Studi</label>
                    <select id="filter-prodi">
                        <option value="">Semua Program Studi</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="filter-makul">Mata Kuliah</label>
                    <select id="filter-makul">
                        <option value="">Semua Mata Kuliah</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="filter-schedule-type">Tipe Jadwal</label>
                    <select id="filter-schedule-type">
                        <option value="">Semua Tipe</option>
                        <option value="semester">Semester</option>
                        <option value="pengganti">Pengganti</option>
                        <option value="ujian">Ujian</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="filter-laboran">Laboran</label>
                    <select id="filter-laboran">
                        <option value="">Semua Laboran</option>
                    </select>
                </div>
            </div>

            <div class="filter-actions">
                <button type="button" class="btn btn-secondary" id="btn-reset-filter">Reset</button>
                <button type="button" class="btn" id="btn-search-filter">Search</button>
            </div>
        </section>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Makul</th>
                        <th>Prodi</th>
                        <th>Dosen</th>
                        <th>Hari & Jam</th>
                        <th>Kelas</th>
                        <th>Tipe / Status</th>
                        <th>Ruangan</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <!-- Dynamic Rows -->
                </tbody>
            </table>
            <div id="loading-indicator" class="loading">Memuat data...</div>
        </div>
    </main>

    <!-- Modal -->
    <div class="modal-overlay" id="modal-overlay">
        <div class="modal">
            <h3 id="modal-title">Tambah Jadwal</h3>
            <form id="data-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Periode</label>
                        <select name="periode_id" id="f_periode_id" required></select>
                    </div>
                    
                    <div class="form-group">
                        <label>Program Studi</label>
                        <select name="prodi_id" id="f_prodi_id" required></select>
                    </div>
                    
                    <div class="form-group full">
                        <label>Mata Kuliah</label>
                        <select name="makul_id" id="f_makul_id" required></select>
                    </div>

                    <div class="form-group">
                        <label>Dosen 1</label>
                        <select name="dosens" id="f_dosens_1" required></select>
                    </div>

                    <div class="form-group">
                        <label>Dosen 2</label>
                        <select name="dosens" id="f_dosens_2" required></select>
                    </div>

                    <div class="form-group" id="dynamic-dosen-container">
                        <label style="display:flex; justify-content:space-between;">
                            <span>Dosen 3 (Opsional)</span>
                            <span style="color:var(--danger); cursor:pointer; font-size:0.8rem;" onclick="removeDynamicDosen()">Hapus</span>
                        </label>
                        <select name="dosens" id="f_dosens_3"></select>
                    </div>

                    <div class="form-group">
                        <label>Laboran 1</label>
                        <select name="laborans" id="f_laborans_1" required></select>
                    </div>

                    <div class="form-group" id="dynamic-laboran-container">
                        <label style="display:flex; justify-content:space-between;">
                            <span>Laboran 2 (Opsional)</span>
                            <span style="color:var(--danger); cursor:pointer; font-size:0.8rem;" onclick="removeDynamicLaboran()">Hapus</span>
                        </label>
                        <select name="laborans" id="f_laborans_2"></select>
                    </div>
                    
                    <div class="form-group full" id="btn-add-dynamic-container" style="display:none; text-align:right;">
                        <button type="button" class="btn btn-warning" style="font-size:0.8rem; padding: 0.4rem 0.8rem;" onclick="restoreDynamicFields()">+ Kembalikan Input (Dosen 3 / Laboran)</button>
                    </div>

                    <div class="form-group">
                        <label>Tipe Jadwal</label>
                        <select name="schedule_type" id="f_schedule_type" required>
                            <option value="semester">Semester</option>
                            <option value="pengganti">Pengganti</option>
                            <option value="ujian">Ujian</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="f_status" required>
                            <option value="offline">Offline</option>
                            <option value="online">Online</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Kelas</label>
                        <input type="text" name="class" id="f_class" required placeholder="Contoh: TI-A">
                    </div>

                    <div class="form-group">
                        <label>Hari</label>
                        <select name="day" id="f_day" required>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Jam Mulai</label>
                        <input type="time" name="start_time" id="f_start_time" required>
                    </div>

                    <div class="form-group">
                        <label>Jam Selesai</label>
                        <input type="time" name="end_time" id="f_end_time" required>
                    </div>

                    <div class="form-group">
                        <label>Ruang Teori (Opsional)</label>
                        <select name="theory_room_id" id="f_theory_room_id"></select>
                    </div>
                    
                    <div class="form-group">
                        <label>Ruang Praktik (Opsional)</label>
                        <select name="practice_room_id" id="f_practice_room_id"></select>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" id="btn-close-modal">Cancel</button>
                    <button type="submit" class="btn">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Detail -->
    <div class="modal-overlay" id="detail-modal-overlay">
        <div class="modal">
            <h3>Detail Jadwal</h3>
            <div id="detail-content" style="line-height: 1.6;">
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="document.getElementById('detail-modal-overlay').classList.remove('active')">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        const LOGIN_URL = '/login';

        // State
        let masterData = null;
        let schedules = [];
        let displayedSchedules = [];
        let editId = null;
        let isAuthenticated = false;

        // DOM Elements
        const tableBody = document.getElementById('table-body');
        const loadingIndicator = document.getElementById('loading-indicator');
        
        const modalOverlay = document.getElementById('modal-overlay');
        const modalTitle = document.getElementById('modal-title');
        const dataForm = document.getElementById('data-form');
        const btnAdd = document.getElementById('btn-add');
        const btnCloseModal = document.getElementById('btn-close-modal');
        const authActionBtn = document.getElementById('auth-action-btn');
        const btnSearchFilter = document.getElementById('btn-search-filter');
        const btnResetFilter = document.getElementById('btn-reset-filter');

        // Form selects
        const f_periode = document.getElementById('f_periode_id');
        const f_prodi = document.getElementById('f_prodi_id');
        const f_makul = document.getElementById('f_makul_id');
        const f_theory = document.getElementById('f_theory_room_id');
        const f_practice = document.getElementById('f_practice_room_id');
        const filterPeriode = document.getElementById('filter-periode');
        const filterDay = document.getElementById('filter-day');
        const filterProdi = document.getElementById('filter-prodi');
        const filterMakul = document.getElementById('filter-makul');
        const filterScheduleType = document.getElementById('filter-schedule-type');
        const filterLaboran = document.getElementById('filter-laboran');

        function getAccessToken() {
            return sessionStorage.getItem('access_token') || localStorage.getItem('access_token');
        }

        function storeTokens(accessToken, refreshToken = null) {
            sessionStorage.setItem('access_token', accessToken);
            if (refreshToken) {
                sessionStorage.setItem('refresh_token', refreshToken);
            }

            localStorage.removeItem('access_token');
            localStorage.removeItem('refresh_token');
        }

        function clearTokens() {
            sessionStorage.removeItem('access_token');
            sessionStorage.removeItem('refresh_token');
            localStorage.removeItem('access_token');
            localStorage.removeItem('refresh_token');
        }

        function authHeaders(extraHeaders = {}) {
            return {
                ...extraHeaders,
                'Authorization': `Bearer ${getAccessToken()}`,
                'Accept': 'application/json'
            };
        }

        async function bootstrapAuth() {
            const token = getAccessToken();

            if (!token) {
                updateAuthUI(false);
                return;
            }

            try {
                const response = await fetch('/api/v1/auth/me', {
                    headers: authHeaders()
                });

                if (!response.ok) {
                    clearTokens();
                    updateAuthUI(false);
                    return;
                }

                storeTokens(token);
                updateAuthUI(true);
            } catch (error) {
                clearTokens();
                updateAuthUI(false);
            }
        }

        function updateAuthUI(authenticated) {
            isAuthenticated = authenticated;
            btnAdd.disabled = !authenticated;
            btnAdd.textContent = authenticated ? '+ Tambah Jadwal' : 'Login untuk Mengelola Jadwal';
            authActionBtn.textContent = authenticated ? '🚪 Sign Out' : '🔐 Sign In';
            renderTable();
        }

        function requireAuthOrRedirect() {
            if (!isAuthenticated) {
                window.location.href = LOGIN_URL;
                return false;
            }

            return true;
        }

        init();

        function removeDynamicDosen() {
            document.getElementById('dynamic-dosen-container').style.display = 'none';
            document.getElementById('f_dosens_3').value = '';
            checkDynamicButtons();
        }
        function removeDynamicLaboran() {
            document.getElementById('dynamic-laboran-container').style.display = 'none';
            document.getElementById('f_laborans_2').value = '';
            checkDynamicButtons();
        }
        function restoreDynamicFields() {
            document.getElementById('dynamic-dosen-container').style.display = 'block';
            document.getElementById('dynamic-laboran-container').style.display = 'block';
            checkDynamicButtons();
        }
        function checkDynamicButtons() {
            const dContainer = document.getElementById('dynamic-dosen-container').style.display;
            const lContainer = document.getElementById('dynamic-laboran-container').style.display;
            if (dContainer === 'none' || lContainer === 'none') {
                document.getElementById('btn-add-dynamic-container').style.display = 'block';
            } else {
                document.getElementById('btn-add-dynamic-container').style.display = 'none';
            }
        }

        async function init() {
            await bootstrapAuth();
            btnAdd.addEventListener('click', () => openModal());
            btnCloseModal.addEventListener('click', closeModal);
            dataForm.addEventListener('submit', handleFormSubmit);
            btnSearchFilter.addEventListener('click', applyFilters);
            btnResetFilter.addEventListener('click', resetFilters);

            await fetchMasterData();
            await fetchSchedules();
        }

        async function fetchMasterData() {
            try {
                const res = await fetch('/api/v1/master-data');
                masterData = await res.json();
                
                // Populate Dropdowns
                f_periode.innerHTML = masterData.periodes
                    .filter(p => p.status === 'aktif')
                    .map(p => `<option value="${p.id}">${p.periode}</option>`)
                    .join('');
                
                f_prodi.innerHTML = masterData.prodis.map(p => `<option value="${p.id}">${p.nama_prodi}</option>`).join('');
                f_makul.innerHTML = masterData.makuls.map(m => `<option value="${m.id}">${m.nama_makul}</option>`).join('');

                filterPeriode.innerHTML = '<option value="">Semua Periode</option>' + masterData.periodes
                    .map(p => `<option value="${p.id}">${p.periode}</option>`)
                    .join('');
                filterProdi.innerHTML = '<option value="">Semua Program Studi</option>' + masterData.prodis
                    .map(p => `<option value="${p.id}">${p.nama_prodi}</option>`)
                    .join('');
                filterMakul.innerHTML = '<option value="">Semua Mata Kuliah</option>' + masterData.makuls
                    .map(m => `<option value="${m.id}">${m.nama_makul}</option>`)
                    .join('');
                
                const dosenOptions = '<option value="">-- Pilih Dosen --</option>' + masterData.dosens.map(d => `<option value="${d.id}">${d.nama_dosen}</option>`).join('');
                document.getElementById('f_dosens_1').innerHTML = dosenOptions;
                document.getElementById('f_dosens_2').innerHTML = dosenOptions;
                document.getElementById('f_dosens_3').innerHTML = dosenOptions;
                
                const laboranOptions = '<option value="">-- Pilih Laboran --</option>' + masterData.laborans.map(l => `<option value="${l.id}">${l.nama_laboran}</option>`).join('');
                document.getElementById('f_laborans_1').innerHTML = laboranOptions;
                document.getElementById('f_laborans_2').innerHTML = laboranOptions;
                filterLaboran.innerHTML = '<option value="">Semua Laboran</option>' + masterData.laborans
                    .map(l => `<option value="${l.id}">${l.nama_laboran}</option>`)
                    .join('');
                
                const teoriRooms = masterData.ruangans.filter(r => r.jenis_ruangan === 'teori');
                const praktikRooms = masterData.ruangans.filter(r => r.jenis_ruangan === 'praktik');
                
                f_theory.innerHTML = '<option value="">-- Pilih Ruang Teori --</option>' + teoriRooms.map(r => `<option value="${r.id}">${r.nama_ruangan}</option>`).join('');
                f_practice.innerHTML = '<option value="">-- Pilih Ruang Praktik --</option>' + praktikRooms.map(r => `<option value="${r.id}">${r.nama_ruangan}</option>`).join('');

                btnAdd.disabled = !isAuthenticated;
            } catch (err) {
                console.error("Failed to load master data", err);
                alert("Gagal memuat Master Data.");
            }
        }

        async function fetchSchedules() {
            loadingIndicator.style.display = 'block';
            tableBody.innerHTML = '';
            try {
                const res = await fetch('/api/v1/jadwal');
                const json = await res.json();
                schedules = json.data;
                displayedSchedules = [...schedules];
                renderTable();
            } catch (err) {
                console.error(err);
                tableBody.innerHTML = `<tr><td colspan="8" style="text-align:center; color: var(--danger)">Gagal memuat jadwal.</td></tr>`;
            } finally {
                loadingIndicator.style.display = 'none';
            }
        }

        function renderTable() {
            const rows = displayedSchedules;

            if (rows.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="8" style="text-align:center;">Tidak ada jadwal.</td></tr>`;
                return;
            }

            tableBody.innerHTML = rows.map(item => {
                const dosenNames = item.dosens ? item.dosens.map(d => d.nama_dosen).join('<br>') : '-';
                
                let ruanganStr = '';
                if (item.status === 'online') ruanganStr = '<span style="color:var(--accent)">Online</span>';
                else {
                    if (item.theory_room) ruanganStr += item.theory_room.nama_ruangan + ' (T)<br>';
                    if (item.practice_room) ruanganStr += item.practice_room.nama_ruangan + ' (P)';
                }
                if(!ruanganStr) ruanganStr = '-';

                const periode = masterData.periodes.find(p => p.id == item.periode_id);
                const isNonaktif = periode && periode.status === 'nonaktif';
                
                let actionsHtml = `<button class="btn btn-info btn-detail" data-id="${item.id}">Detail</button>`;
                if (isAuthenticated && !isNonaktif) {
                    actionsHtml += `
                        <button class="btn btn-warning btn-edit" data-id="${item.id}">Edit</button>
                        <button class="btn btn-danger btn-delete" data-id="${item.id}">Delete</button>
                    `;
                }

                return `
                <tr>
                    <td>${item.makul ? item.makul.nama_makul : '-'}</td>
                    <td>${item.prodi ? item.prodi.nama_prodi : '-'}</td>
                    <td>${dosenNames}</td>
                    <td>${item.day}, ${item.start_time.substr(0,5)} - ${item.end_time.substr(0,5)}</td>
                    <td>${item.class}</td>
                    <td>${item.schedule_type} / ${item.status}</td>
                    <td>${ruanganStr}</td>
                    <td>
                        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                            ${actionsHtml}
                        </div>
                    </td>
                </tr>
                `;
            }).join('');

            // Attach listeners
            document.querySelectorAll('.btn-detail').forEach(btn => {
                btn.addEventListener('click', (e) => openDetailModal(e.target.dataset.id));
            });
            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', (e) => openModal(e.target.dataset.id));
            });
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    if(confirm('Are you sure you want to delete this schedule?')) {
                        await deleteRecord(e.target.dataset.id);
                    }
                });
            });
        }

        function applyFilters() {
            const filters = {
                periodeId: filterPeriode.value,
                day: filterDay.value,
                prodiId: filterProdi.value,
                makulId: filterMakul.value,
                scheduleType: filterScheduleType.value,
                laboranId: filterLaboran.value,
            };

            displayedSchedules = schedules.filter(item => {
                const matchPeriode = !filters.periodeId || String(item.periode_id) === filters.periodeId;
                const matchDay = !filters.day || item.day === filters.day;
                const matchProdi = !filters.prodiId || String(item.prodi_id) === filters.prodiId;
                const matchMakul = !filters.makulId || String(item.makul_id) === filters.makulId;
                const matchScheduleType = !filters.scheduleType || item.schedule_type === filters.scheduleType;
                const matchLaboran = !filters.laboranId || (item.laborans || []).some(laboran => String(laboran.id) === filters.laboranId);

                return matchPeriode
                    && matchDay
                    && matchProdi
                    && matchMakul
                    && matchScheduleType
                    && matchLaboran;
            });

            renderTable();
        }

        function resetFilters() {
            filterPeriode.value = '';
            filterDay.value = '';
            filterProdi.value = '';
            filterMakul.value = '';
            filterScheduleType.value = '';
            filterLaboran.value = '';
            displayedSchedules = [...schedules];
            renderTable();
        }

        function openDetailModal(id) {
            const item = schedules.find(s => s.id == id);
            if (!item) return;

            const dosenNames = item.dosens && item.dosens.length > 0 ? item.dosens.map(d => d.nama_dosen).join(', ') : '-';
            const laboranNames = item.laborans && item.laborans.length > 0 ? item.laborans.map(l => l.nama_laboran).join(', ') : '-';
            
            let ruanganStr = '';
            if (item.status === 'online') ruanganStr = 'Online';
            else {
                if (item.theory_room) ruanganStr += item.theory_room.nama_ruangan + ' (Teori)<br>';
                if (item.practice_room) ruanganStr += item.practice_room.nama_ruangan + ' (Praktik)';
            }
            if (!ruanganStr) ruanganStr = '-';

            const content = `
                <table style="width:100%; border:none;">
                    <tr style="background:transparent;"><td style="width:30%; font-weight:600; padding:0.5rem 0; border:none;">Mata Kuliah</td><td style="border:none;">: ${item.makul ? item.makul.nama_makul : '-'}</td></tr>
                    <tr style="background:transparent;"><td style="font-weight:600; padding:0.5rem 0; border:none;">Program Studi</td><td style="border:none;">: ${item.prodi ? item.prodi.nama_prodi : '-'}</td></tr>
                    <tr style="background:transparent;"><td style="font-weight:600; padding:0.5rem 0; border:none;">Dosen</td><td style="border:none;">: ${dosenNames}</td></tr>
                    <tr style="background:transparent;"><td style="font-weight:600; padding:0.5rem 0; border:none;">Laboran</td><td style="border:none;">: ${laboranNames}</td></tr>
                    <tr style="background:transparent;"><td style="font-weight:600; padding:0.5rem 0; border:none;">Kelas</td><td style="border:none;">: ${item.class}</td></tr>
                    <tr style="background:transparent;"><td style="font-weight:600; padding:0.5rem 0; border:none;">Hari</td><td style="border:none;">: ${item.day}</td></tr>
                    <tr style="background:transparent;"><td style="font-weight:600; padding:0.5rem 0; border:none;">Waktu</td><td style="border:none;">: ${item.start_time.substr(0,5)} - ${item.end_time.substr(0,5)}</td></tr>
                    <tr style="background:transparent;"><td style="font-weight:600; padding:0.5rem 0; border:none;">Ruangan</td><td style="border:none;">: ${ruanganStr}</td></tr>
                    <tr style="background:transparent;"><td style="font-weight:600; padding:0.5rem 0; border:none;">Tipe / Status</td><td style="border:none;">: ${item.schedule_type} / ${item.status}</td></tr>
                </table>
            `;
            document.getElementById('detail-content').innerHTML = content;
            document.getElementById('detail-modal-overlay').classList.add('active');
        }

        function openModal(id = null) {
            if (!requireAuthOrRedirect()) {
                return;
            }

            editId = id;
            modalTitle.textContent = editId ? `Edit Jadwal` : `Tambah Jadwal`;
            
            dataForm.reset();
            restoreDynamicFields();

            if (editId) {
                const item = schedules.find(s => s.id == editId);
                if (item) {
                    document.getElementById('f_periode_id').value = item.periode_id;
                    document.getElementById('f_prodi_id').value = item.prodi_id;
                    document.getElementById('f_makul_id').value = item.makul_id;
                    document.getElementById('f_schedule_type').value = item.schedule_type;
                    document.getElementById('f_status').value = item.status;
                    document.getElementById('f_class').value = item.class;
                    document.getElementById('f_day').value = item.day;
                    document.getElementById('f_start_time').value = item.start_time.substr(0,5);
                    document.getElementById('f_end_time').value = item.end_time.substr(0,5);
                    document.getElementById('f_theory_room_id').value = item.theory_room_id || '';
                    document.getElementById('f_practice_room_id').value = item.practice_room_id || '';

                    if (item.dosens && item.dosens.length > 0) {
                        document.getElementById('f_dosens_1').value = item.dosens[0].id || '';
                    }
                    if (item.dosens && item.dosens.length > 1) {
                        document.getElementById('f_dosens_2').value = item.dosens[1].id || '';
                    }
                    if (item.dosens && item.dosens.length > 2) {
                        document.getElementById('f_dosens_3').value = item.dosens[2].id || '';
                    } else {
                        removeDynamicDosen();
                    }

                    if (item.laborans && item.laborans.length > 0) {
                        document.getElementById('f_laborans_1').value = item.laborans[0].id || '';
                    }
                    if (item.laborans && item.laborans.length > 1) {
                        document.getElementById('f_laborans_2').value = item.laborans[1].id || '';
                    } else {
                        removeDynamicLaboran();
                    }
                }
            }

            modalOverlay.classList.add('active');
        }

        function closeModal() {
            modalOverlay.classList.remove('active');
            dataForm.reset();
            editId = null;
        }

        async function handleFormSubmit(e) {
            e.preventDefault();

            if (!requireAuthOrRedirect()) {
                return;
            }

            const d1 = document.getElementById('f_dosens_1').value;
            const d2 = document.getElementById('f_dosens_2').value;
            const d3 = document.getElementById('f_dosens_3').value;
            const selectedDosens = [d1, d2, d3].filter(v => v !== '');
            if (new Set(selectedDosens).size !== selectedDosens.length) {
                alert('Dosen 1, 2, dan 3 tidak boleh orang yang sama.');
                return;
            }

            const l1 = document.getElementById('f_laborans_1').value;
            const l2 = document.getElementById('f_laborans_2').value;
            const selectedLaborans = [l1, l2].filter(v => v !== '');
            if (new Set(selectedLaborans).size !== selectedLaborans.length) {
                alert('Laboran 1 dan 2 tidak boleh orang yang sama.');
                return;
            }

            const formData = new FormData(dataForm);
            const data = Object.fromEntries(formData.entries());

            // Cast types and inject arrays for single dropdowns
            data.periode_id = parseInt(data.periode_id);
            data.prodi_id = parseInt(data.prodi_id);
            data.makul_id = parseInt(data.makul_id);
            data.theory_room_id = data.theory_room_id ? parseInt(data.theory_room_id) : null;
            data.practice_room_id = data.practice_room_id ? parseInt(data.practice_room_id) : null;
            
            const rawDosens = formData.getAll('dosens').filter(x => x).map(x => parseInt(x));
            data.dosens = [...new Set(rawDosens)]; // Remove duplicates
            
            const rawLaborans = formData.getAll('laborans').filter(x => x).map(x => parseInt(x));
            data.laborans = [...new Set(rawLaborans)]; // Remove duplicates

            try {
                const url = editId ? `/api/v1/jadwal/${editId}` : `/api/v1/jadwal`;
                const method = editId ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: authHeaders({
                        'Content-Type': 'application/json'
                    }),
                    body: JSON.stringify(data)
                });

                if (response.status === 401) {
                    clearTokens();
                    window.location.href = LOGIN_URL;
                    return;
                }

                const result = await response.json();

                if (!response.ok) {
                    alert('Error: ' + JSON.stringify(result.errors || result.message));
                    return;
                }

                closeModal();
                fetchSchedules();
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan saat menyimpan jadwal.');
            }
        }

        async function deleteRecord(id) {
            if (!requireAuthOrRedirect()) {
                return;
            }

            try {
                const response = await fetch(`/api/v1/jadwal/${id}`, {
                    method: 'DELETE',
                    headers: authHeaders()
                });

                if (response.status === 401) {
                    clearTokens();
                    window.location.href = LOGIN_URL;
                    return;
                }

                if (!response.ok) {
                    const result = await response.json();
                    alert('Error: ' + (result.message || 'Gagal menghapus jadwal.'));
                    return;
                }
                
                fetchSchedules();
            } catch (err) {
                console.error(err);
            }
        }

        async function handleAuthAction() {
            if (!isAuthenticated) {
                window.location.href = LOGIN_URL;
                return;
            }

            const token = getAccessToken();
            if (token) {
                try {
                    await fetch('/api/v1/auth/logout', {
                        method: 'POST',
                        headers: authHeaders(),
                    });
                } catch (_) {}
            }
            clearTokens();
            updateAuthUI(false);
            window.location.href = '/jadwal';
        }

    </script>
</body>
</html>
