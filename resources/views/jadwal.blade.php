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

    </style>
</head>
<body>

    <aside class="sidebar">
        <h2>Dashboard</h2>
        <a href="/master-data" class="nav-btn">Master Data</a>
        <a href="/jadwal" class="nav-btn active">Jadwal</a>
        <a href="/beban-kerja" class="nav-btn">Beban Kerja</a>
    </aside>

    <main class="main">
        <div class="header">
            <h1>Manajemen Jadwal</h1>
            <button class="btn" id="btn-add" disabled>+ Tambah Jadwal</button>
        </div>

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
                        <label>Dosen</label>
                        <select name="dosens" id="f_dosens" required></select>
                    </div>

                    <div class="form-group">
                        <label>Laboran</label>
                        <select name="laborans" id="f_laborans" required></select>
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

    <script>
        // State
        let masterData = null;
        let schedules = [];
        let editId = null;

        // DOM Elements
        const tableBody = document.getElementById('table-body');
        const loadingIndicator = document.getElementById('loading-indicator');
        
        const modalOverlay = document.getElementById('modal-overlay');
        const modalTitle = document.getElementById('modal-title');
        const dataForm = document.getElementById('data-form');
        const btnAdd = document.getElementById('btn-add');
        const btnCloseModal = document.getElementById('btn-close-modal');

        // Form selects
        const f_periode = document.getElementById('f_periode_id');
        const f_prodi = document.getElementById('f_prodi_id');
        const f_makul = document.getElementById('f_makul_id');
        const f_dosens = document.getElementById('f_dosens');
        const f_laborans = document.getElementById('f_laborans');
        const f_theory = document.getElementById('f_theory_room_id');
        const f_practice = document.getElementById('f_practice_room_id');

        // Initialization
        init();

        async function init() {
            btnAdd.addEventListener('click', () => openModal());
            btnCloseModal.addEventListener('click', closeModal);
            dataForm.addEventListener('submit', handleFormSubmit);

            await fetchMasterData();
            await fetchSchedules();
        }

        async function fetchMasterData() {
            try {
                const res = await fetch('/api/v1/master-data');
                masterData = await res.json();
                
                // Populate Dropdowns
                f_periode.innerHTML = masterData.periodes.map(p => `<option value="${p.id}">${p.periode}</option>`).join('');
                f_prodi.innerHTML = masterData.prodis.map(p => `<option value="${p.id}">${p.nama_prodi}</option>`).join('');
                f_makul.innerHTML = masterData.makuls.map(m => `<option value="${m.id}">${m.nama_makul}</option>`).join('');
                
                f_dosens.innerHTML = masterData.dosens.map(d => `<option value="${d.id}">${d.nama_dosen}</option>`).join('');
                f_laborans.innerHTML = masterData.laborans.map(l => `<option value="${l.id}">${l.nama_laboran}</option>`).join('');
                
                const teoriRooms = masterData.ruangans.filter(r => r.jenis_ruangan === 'teori');
                const praktikRooms = masterData.ruangans.filter(r => r.jenis_ruangan === 'praktik');
                
                f_theory.innerHTML = '<option value="">-- Pilih Ruang Teori --</option>' + teoriRooms.map(r => `<option value="${r.id}">${r.nama_ruangan}</option>`).join('');
                f_practice.innerHTML = '<option value="">-- Pilih Ruang Praktik --</option>' + praktikRooms.map(r => `<option value="${r.id}">${r.nama_ruangan}</option>`).join('');

                btnAdd.disabled = false;
            } catch (err) {
                console.error("Failed to load master data", err);
                alert("Gagal memuat Master Data.");
            }
        }

        async function fetchSchedules() {
            loadingIndicator.style.display = 'block';
            tableBody.innerHTML = '';
            try {
                const res = await fetch('/api/v1/schedules');
                const json = await res.json();
                schedules = json.data;
                renderTable();
            } catch (err) {
                console.error(err);
                tableBody.innerHTML = `<tr><td colspan="8" style="text-align:center; color: var(--danger)">Gagal memuat jadwal.</td></tr>`;
            } finally {
                loadingIndicator.style.display = 'none';
            }
        }

        function renderTable() {
            if (schedules.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="8" style="text-align:center;">Tidak ada jadwal.</td></tr>`;
                return;
            }

            tableBody.innerHTML = schedules.map(item => {
                const dosenNames = item.dosens ? item.dosens.map(d => d.nama_dosen).join('<br>') : '-';
                
                let ruanganStr = '';
                if (item.status === 'online') ruanganStr = '<span style="color:var(--accent)">Online</span>';
                else {
                    if (item.theory_room) ruanganStr += item.theory_room.nama_ruangan + ' (T)<br>';
                    if (item.practice_room) ruanganStr += item.practice_room.nama_ruangan + ' (P)';
                }
                if(!ruanganStr) ruanganStr = '-';

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
                        <button class="btn btn-warning btn-edit" data-id="${item.id}">Edit</button>
                        <button class="btn btn-danger btn-delete" data-id="${item.id}">Delete</button>
                    </td>
                </tr>
                `;
            }).join('');

            // Attach listeners
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

        function openModal(id = null) {
            editId = id;
            modalTitle.textContent = editId ? `Edit Jadwal` : `Tambah Jadwal`;
            
            dataForm.reset();

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

                    // Select single dosen
                    if (item.dosens && item.dosens.length > 0) {
                        f_dosens.value = item.dosens[0].id;
                    }

                    // Select single laboran
                    if (item.laborans && item.laborans.length > 0) {
                        f_laborans.value = item.laborans[0].id;
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

            const formData = new FormData(dataForm);
            const data = Object.fromEntries(formData.entries());

            // Cast types and inject arrays for single dropdowns
            data.periode_id = parseInt(data.periode_id);
            data.prodi_id = parseInt(data.prodi_id);
            data.makul_id = parseInt(data.makul_id);
            data.theory_room_id = data.theory_room_id ? parseInt(data.theory_room_id) : null;
            data.practice_room_id = data.practice_room_id ? parseInt(data.practice_room_id) : null;
            
            // Backend requires dosens and laborans as arrays
            data.dosens = data.dosens ? [parseInt(data.dosens)] : [];
            data.laborans = data.laborans ? [parseInt(data.laborans)] : [];

            try {
                const url = editId ? `/api/v1/schedules/${editId}` : `/api/v1/schedules`;
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
                fetchSchedules();
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan saat menyimpan jadwal.');
            }
        }

        async function deleteRecord(id) {
            try {
                const response = await fetch(`/api/v1/schedules/${id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) {
                    const result = await response.json();
                    alert('Error: ' + (result.message || 'Gagal menghapus jadwal.'));
                    return;
                }
                
                fetchSchedules();
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan.');
            }
        }

    </script>
</body>
</html>
