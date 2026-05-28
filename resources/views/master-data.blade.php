<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data Management</title>
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

        .btn:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
        }

        .btn-warning {
            background: transparent;
            border: 1px solid #eab308;
            color: #eab308;
            box-shadow: none;
            padding: 0.4rem 0.8rem;
        }
        .btn-warning:hover {
            background: #eab308;
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
            max-width: 500px;
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

        .form-group {
            margin-bottom: 1.5rem;
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

        .form-group input:focus, .form-group select:focus {
            border-color: var(--accent);
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

        /* Loading & Empty */
        .loading {
            padding: 3rem;
            text-align: center;
            color: var(--text-muted);
        }

    </style>
</head>
<body>

    <aside class="sidebar">
        <h2>Master Data</h2>
        <button class="nav-btn active" data-module="makuls">Mata Kuliah</button>
        <button class="nav-btn" data-module="dosens">Dosen</button>
        <button class="nav-btn" data-module="laborans">Laboran</button>
        <button class="nav-btn" data-module="prodis">Program Studi</button>
        <button class="nav-btn" data-module="ruangans">Ruangan</button>
        <button class="nav-btn" data-module="periodes">Periode</button>
        
        <hr style="border: 0; border-top: 1px solid var(--glass-border); margin: 1rem 0;">
        <a href="/jadwal" class="nav-btn" style="text-decoration: none; display: block;">📅 Jadwal</a>
        <a href="/beban-kerja" class="nav-btn" style="text-decoration: none; display: block;">📊 Beban Kerja</a>
        
        <button id="auth-action-btn" onclick="handleLogout()" class="nav-btn" style="margin-top: auto; color: var(--danger); font-weight: 600; display: flex; align-items: center; gap: 0.5rem; background: transparent; border: none; width: 100%; cursor: pointer;">
            🚪 Sign Out
        </button>
    </aside>

    <main class="main">
        <div class="header">
            <h1 id="page-title">Mata Kuliah</h1>
            <button class="btn" id="btn-add">+ Add New</button>
        </div>

        <div class="table-container">
            <table>
                <thead id="table-head">
                    <!-- Dynamic Headers -->
                </thead>
                <tbody id="table-body">
                    <!-- Dynamic Rows -->
                </tbody>
            </table>
            <div id="loading-indicator" class="loading" style="display: none;">Loading data...</div>
        </div>
    </main>

    <!-- Modal -->
    <div class="modal-overlay" id="modal-overlay">
        <div class="modal">
            <h3 id="modal-title">Add Data</h3>
            <form id="data-form">
                <div id="form-fields">
                    <!-- Dynamic form fields -->
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" id="btn-close-modal">Cancel</button>
                    <button type="submit" class="btn">Save Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const LOGIN_URL = '/login';

        // Module configurations
        const MODULES = {
            makuls: {
                title: 'Mata Kuliah',
                endpoint: '/api/v1/makuls',
                columns: [
                    { key: 'kode_makul', label: 'Kode' },
                    { key: 'nama_makul', label: 'Nama Makul' },
                    { key: 'jumlah_sesi_teori', label: 'Sesi Teori' },
                    { key: 'jumlah_sesi_praktek', label: 'Sesi Praktik' },
                    { key: 'jumlah_sks_teori', label: 'SKS Teori' },
                    { key: 'jumlah_sks_praktek', label: 'SKS Praktik' }
                ],
                fields: [
                    { name: 'kode_makul', label: 'Kode Makul', type: 'text', required: true },
                    { name: 'nama_makul', label: 'Nama Makul', type: 'text', required: true },
                    { name: 'jumlah_sesi_teori', label: 'Jumlah Sesi Teori', type: 'number', required: true },
                    { name: 'jumlah_sesi_praktek', label: 'Jumlah Sesi Praktik', type: 'number', required: true }
                ]
            },
            dosens: {
                title: 'Dosen',
                endpoint: '/api/v1/dosens',
                columns: [
                    { key: 'kode_dosen', label: 'Kode' },
                    { key: 'nama_dosen', label: 'Nama Dosen' }
                ],
                fields: [
                    { name: 'kode_dosen', label: 'Kode Dosen', type: 'text', required: true },
                    { name: 'nama_dosen', label: 'Nama Dosen', type: 'text', required: true }
                ]
            },
            laborans: {
                title: 'Laboran',
                endpoint: '/api/v1/laborans',
                columns: [
                    { key: 'kode_laboran', label: 'Kode' },
                    { key: 'nama_laboran', label: 'Nama Laboran' }
                ],
                fields: [
                    { name: 'kode_laboran', label: 'Kode Laboran', type: 'text', required: true },
                    { name: 'nama_laboran', label: 'Nama Laboran', type: 'text', required: true }
                ]
            },
            prodis: {
                title: 'Program Studi',
                endpoint: '/api/v1/prodis',
                columns: [
                    { key: 'kode_prodi', label: 'Kode' },
                    { key: 'nama_prodi', label: 'Nama Prodi' }
                ],
                fields: [
                    { name: 'kode_prodi', label: 'Kode Prodi', type: 'text', required: true },
                    { name: 'nama_prodi', label: 'Nama Prodi', type: 'text', required: true }
                ]
            },
            ruangans: {
                title: 'Ruangan',
                endpoint: '/api/v1/ruangans',
                columns: [
                    { key: 'kode_ruangan', label: 'Kode' },
                    { key: 'nama_ruangan', label: 'Nama Ruangan' },
                    { key: 'jenis_ruangan', label: 'Jenis Ruangan' }
                ],
                fields: [
                    { name: 'kode_ruangan', label: 'Kode Ruangan', type: 'text', required: true },
                    { name: 'nama_ruangan', label: 'Nama Ruangan', type: 'text', required: true },
                    { name: 'jenis_ruangan', label: 'Jenis Ruangan', type: 'select', options: ['teori', 'praktik'], required: true }
                ]
            },
            periodes: {
                title: 'Periode Tahun Ajaran',
                endpoint: '/api/v1/periodes',
                columns: [
                    { key: 'periode', label: 'Periode' },
                    { key: 'status', label: 'Status' },
                    { key: 'tanggal_mulai', label: 'Tgl Mulai' },
                    { key: 'tanggal_selesai', label: 'Tgl Selesai' }
                ],
                fields: [
                    { name: 'periode', label: 'Periode', type: 'text', required: true },
                    { name: 'tanggal_mulai', label: 'Tanggal Mulai', type: 'date', required: true },
                    { name: 'tanggal_selesai', label: 'Tanggal Selesai', type: 'date', required: false },
                    { name: 'status', label: 'Status', type: 'select', options: ['aktif', 'nonaktif'], required: true }
                ]
            }
        };

        // State
        let currentModule = 'makuls';
        let currentData = [];
        let editId = null;

        // DOM Elements
        const navBtns = document.querySelectorAll('[data-module]');
        const pageTitle = document.getElementById('page-title');
        const tableHead = document.getElementById('table-head');
        const tableBody = document.getElementById('table-body');
        const loadingIndicator = document.getElementById('loading-indicator');
        
        const modalOverlay = document.getElementById('modal-overlay');
        const modalTitle = document.getElementById('modal-title');
        const formFields = document.getElementById('form-fields');
        const dataForm = document.getElementById('data-form');
        const btnAdd = document.getElementById('btn-add');
        const btnCloseModal = document.getElementById('btn-close-modal');
        const authActionBtn = document.getElementById('auth-action-btn');

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

        async function requireAuth() {
            const token = getAccessToken();

            if (!token) {
                window.location.href = LOGIN_URL;
                return false;
            }

            try {
                const response = await fetch('/api/v1/auth/me', {
                    headers: authHeaders()
                });

                if (!response.ok) {
                    clearTokens();
                    window.location.href = LOGIN_URL;
                    return false;
                }

                storeTokens(token);
                return true;
            } catch (error) {
                clearTokens();
                window.location.href = LOGIN_URL;
                return false;
            }
        }

        init();

        async function init() {
            const authenticated = await requireAuth();
            if (!authenticated) {
                return;
            }

            // Setup navigation
            navBtns.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    navBtns.forEach(b => b.classList.remove('active'));
                    e.target.classList.add('active');
                    currentModule = e.target.dataset.module;
                    loadData();
                });
            });

            // Modal listeners
            btnAdd.addEventListener('click', () => openModal());
            btnCloseModal.addEventListener('click', closeModal);
            dataForm.addEventListener('submit', handleFormSubmit);

            // Load initial data
            loadData();
        }

        async function loadData() {
            const config = MODULES[currentModule];
            pageTitle.textContent = config.title;
            
            // Render Headers
            tableHead.innerHTML = `<tr>
                ${config.columns.map(col => `<th>${col.label}</th>`).join('')}
                <th>Actions</th>
            </tr>`;
            
            tableBody.innerHTML = '';
            loadingIndicator.style.display = 'block';

            try {
                const response = await fetch(config.endpoint, {
                    headers: authHeaders()
                });

                if (response.status === 401) {
                    clearTokens();
                    window.location.href = LOGIN_URL;
                    return;
                }

                const json = await response.json();
                currentData = json.data;
                renderTable();
            } catch (err) {
                console.error("Failed to fetch data:", err);
                tableBody.innerHTML = `<tr><td colspan="10" style="text-align:center; color: var(--danger)">Failed to load data.</td></tr>`;
            } finally {
                loadingIndicator.style.display = 'none';
            }
        }

        function renderTable() {
            const config = MODULES[currentModule];
            tableBody.innerHTML = '';

            if (currentData.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="10" style="text-align:center;">No data found.</td></tr>`;
                return;
            }

            currentData.forEach(item => {
                const tr = document.createElement('tr');
                
                let rowHtml = '';
                config.columns.forEach(col => {
                    let val = item[col.key];
                    if (col.key === 'status') {
                        if (val === 'aktif') val = 'Aktif';
                        else if (val === 'nonaktif') val = 'Tidak Aktif';
                    } else {
                        if (val === true) val = 'Yes';
                        if (val === false) val = 'No';
                    }
                    if (val === null || val === undefined) val = '-';
                    rowHtml += `<td>${val}</td>`;
                });

                // Actions
                let extraAction = '';
                if (currentModule === 'periodes') {
                    if (item.status === 'aktif') {
                        extraAction = `<button class="btn btn-warning btn-tutup" style="background:transparent; border:1px solid #f97316; color:#f97316" data-id="${item.id}">Tutup</button>`;
                    } else if (item.status === 'nonaktif') {
                        extraAction = `<button class="btn btn-warning btn-buka" style="background:transparent; border:1px solid #10b981; color:#10b981" data-id="${item.id}">Buka</button>`;
                    }
                }
                rowHtml += `
                    <td>
                        <button class="btn btn-warning btn-edit" data-id="${item.id}">Edit</button>
                        <button class="btn btn-danger btn-delete" data-id="${item.id}">Delete</button>
                        ${extraAction}
                    </td>
                `;
                
                tr.innerHTML = rowHtml;
                tableBody.appendChild(tr);
            });

            // Attach edit listeners
            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    openModal(e.target.dataset.id);
                });
            });

            // Attach delete listeners
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    const id = e.target.dataset.id;
                    if(confirm('Are you sure you want to delete this record?')) {
                        await deleteRecord(id);
                    }
                });
            });

            // Attach tutup listeners
            document.querySelectorAll('.btn-tutup').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    const id = e.target.dataset.id;
                    if(confirm('Are you sure you want to close this periode?')) {
                        await tutupPeriode(id);
                    }
                });
            });

            // Attach buka listeners
            document.querySelectorAll('.btn-buka').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    const id = e.target.dataset.id;
                    if(confirm('Are you sure you want to open this periode?')) {
                        await bukaPeriode(id);
                    }
                });
            });
        }

        function openModal(id = null) {
            editId = id;
            const config = MODULES[currentModule];
            modalTitle.textContent = editId ? `Edit ${config.title}` : `Add ${config.title}`;
            
            const item = editId ? currentData.find(d => d.id == editId) : null;

            // Generate form fields
            formFields.innerHTML = config.fields.map(field => {
                // Hide status and tanggal_selesai on Create
                if (!editId && (field.name === 'status' || field.name === 'tanggal_selesai')) {
                    return '';
                }

                let inputHtml = '';
                let val = item ? (item[field.name] || '') : '';
                
                // Format date for <input type="date"> (YYYY-MM-DD)
                if (field.type === 'date' && val) {
                    val = val.substring(0, 10);
                }

                if (field.type === 'select') {
                    const options = field.options.map(opt => `<option value="${opt}" ${val === opt ? 'selected' : ''}>${opt}</option>`).join('');
                    inputHtml = `<select name="${field.name}" id="${field.name}" ${field.required ? 'required' : ''}>${options}</select>`;
                } else {
                    inputHtml = `<input type="${field.type}" name="${field.name}" id="${field.name}" value="${val}" ${field.required ? 'required' : ''}>`;
                }
                return `
                    <div class="form-group">
                        <label for="${field.name}">${field.label}</label>
                        ${inputHtml}
                    </div>
                `;
            }).join('');

            // Special logic for Periodes: Reset tanggal_selesai when changing status from nonaktif to aktif
            if (currentModule === 'periodes' && item && item.status === 'nonaktif') {
                const statusSelect = document.getElementById('status');
                const tglSelesaiInput = document.getElementById('tanggal_selesai');
                if (statusSelect && tglSelesaiInput) {
                    statusSelect.addEventListener('change', (e) => {
                        if (e.target.value === 'aktif') {
                            tglSelesaiInput.value = '';
                        }
                    });
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
            const config = MODULES[currentModule];
            const formData = new FormData(dataForm);
            const data = Object.fromEntries(formData.entries());

            // Convert numbers
            config.fields.forEach(f => {
                if(f.type === 'number' && data[f.name]) {
                    data[f.name] = parseInt(data[f.name], 10);
                }
            });

            try {
                const url = editId ? `${config.endpoint}/${editId}` : config.endpoint;
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
                loadData();
            } catch (err) {
                console.error(err);
                alert('An error occurred while saving.');
            }
        }

        async function deleteRecord(id) {
            const config = MODULES[currentModule];
            try {
                const response = await fetch(`${config.endpoint}/${id}`, {
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
                    alert('Error: ' + (result.message || 'Failed to delete'));
                    return;
                }
                
                loadData();
            } catch (err) {
                console.error(err);
                alert('An error occurred while deleting.');
            }
        }

        async function tutupPeriode(id) {
            try {
                const response = await fetch(`/api/v1/periodes/${id}/tutup`, {
                    method: 'POST',
                    headers: authHeaders()
                });

                if (response.status === 401) {
                    clearTokens();
                    window.location.href = LOGIN_URL;
                    return;
                }

                if (!response.ok) {
                    const result = await response.json();
                    alert('Error: ' + (result.message || 'Failed to close periode'));
                    return;
                }
                
                loadData();
            } catch (err) {
                console.error(err);
                alert('An error occurred while closing the periode.');
            }
        }

        async function bukaPeriode(id) {
            try {
                const response = await fetch(`/api/v1/periodes/${id}/buka`, {
                    method: 'POST',
                    headers: authHeaders()
                });

                if (response.status === 401) {
                    clearTokens();
                    window.location.href = LOGIN_URL;
                    return;
                }

                if (!response.ok) {
                    const result = await response.json();
                    alert('Error: ' + (result.message || 'Failed to open periode'));
                    return;
                }
                
                loadData();
            } catch (err) {
                console.error(err);
                alert('An error occurred while opening the periode.');
            }
        }

        async function handleLogout() {
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
            window.location.href = LOGIN_URL;
        }

    </script>
</body>
</html>
