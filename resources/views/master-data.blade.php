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
                    { key: 'tanggal_selesai', label: 'Tgl Selesai' },
                    { key: 'is_locked', label: 'Locked' }
                ],
                fields: [
                    { name: 'periode', label: 'Periode', type: 'text', required: true },
                    { name: 'tanggal_mulai', label: 'Tanggal Mulai', type: 'date', required: true },
                    { name: 'tanggal_selesai', label: 'Tanggal Selesai', type: 'date', required: true },
                    { name: 'status', label: 'Status', type: 'select', options: ['aktif', 'nonaktif'], required: true }
                ]
            }
        };

        // State
        let currentModule = 'makuls';
        let currentData = [];
        let editId = null;

        // DOM Elements
        const navBtns = document.querySelectorAll('.nav-btn');
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

        // Initialization
        init();

        function init() {
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
                const response = await fetch(config.endpoint);
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
                    if (val === true) val = 'Yes';
                    if (val === false) val = 'No';
                    if (val === null || val === undefined) val = '-';
                    rowHtml += `<td>${val}</td>`;
                });

                // Actions
                rowHtml += `
                    <td>
                        <button class="btn btn-warning btn-edit" data-id="${item.id}">Edit</button>
                        <button class="btn btn-danger btn-delete" data-id="${item.id}">Delete</button>
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
        }

        function openModal(id = null) {
            editId = id;
            const config = MODULES[currentModule];
            modalTitle.textContent = editId ? `Edit ${config.title}` : `Add ${config.title}`;
            
            const item = editId ? currentData.find(d => d.id == editId) : null;

            // Generate form fields
            formFields.innerHTML = config.fields.map(field => {
                let inputHtml = '';
                const val = item ? (item[field.name] || '') : '';

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
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

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
                    headers: { 'Accept': 'application/json' }
                });

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

    </script>
</body>
</html>
