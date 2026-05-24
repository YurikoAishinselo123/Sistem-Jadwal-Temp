<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Jadwal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #0f1117;
            --surface: #1a1d27;
            --surface-2: #222636;
            --border: #2d3148;
            --accent: #6366f1;
            --accent-hover: #4f46e5;
            --accent-light: rgba(99,102,241,0.15);
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
            --text: #e2e8f0;
            --text-muted: #64748b;
            --text-soft: #94a3b8;
            --radius: 12px;
            --radius-sm: 8px;
            --shadow: 0 4px 24px rgba(0,0,0,0.4);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── HEADER ── */
        .header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(12px);
        }
        .header-brand { display: flex; align-items: center; gap: 0.75rem; }
        .header-brand .logo {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--accent), #a855f7);
            border-radius: 10px;
            display: grid; place-items: center;
            font-size: 1.1rem;
        }
        .header-brand h1 { font-size: 1.1rem; font-weight: 600; }
        .header-brand span { color: var(--text-muted); font-size: 0.8rem; font-weight: 400; }
        .btn-add {
            background: linear-gradient(135deg, var(--accent), #a855f7);
            color: white;
            border: none;
            padding: 0.55rem 1.2rem;
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            display: flex; align-items: center; gap: 0.5rem;
            transition: opacity 0.2s, transform 0.2s;
            font-family: inherit;
        }
        .btn-add:hover { opacity: 0.9; transform: translateY(-1px); }

        /* ── MAIN ── */
        .main { padding: 2rem; max-width: 1400px; margin: 0 auto; }

        /* ── STATS ── */
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.2rem 1.5rem;
            display: flex; align-items: center; gap: 1rem;
        }
        .stat-icon {
            width: 42px; height: 42px;
            border-radius: 10px;
            display: grid; place-items: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        .stat-icon.indigo  { background: rgba(99,102,241,0.15); }
        .stat-icon.green   { background: rgba(34,197,94,0.15); }
        .stat-icon.amber   { background: rgba(245,158,11,0.15); }
        .stat-icon.purple  { background: rgba(168,85,247,0.15); }
        .stat-info h3 { font-size: 1.5rem; font-weight: 700; }
        .stat-info p { font-size: 0.75rem; color: var(--text-muted); margin-top: 2px; }

        /* ── TABLE SECTION ── */
        .section-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1rem;
        }
        .section-title { font-size: 1rem; font-weight: 600; }
        .search-input {
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--text);
            padding: 0.45rem 0.9rem;
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s;
            width: 220px;
        }
        .search-input:focus { border-color: var(--accent); }
        .search-input::placeholder { color: var(--text-muted); }

        .table-wrap {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            background: var(--surface-2);
            padding: 0.85rem 1rem;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            text-align: left;
            white-space: nowrap;
        }
        tbody tr {
            border-top: 1px solid var(--border);
            transition: background 0.15s;
        }
        tbody tr:hover { background: var(--surface-2); }
        tbody td { padding: 0.85rem 1rem; font-size: 0.85rem; vertical-align: middle; }
        .td-muted { color: var(--text-muted); }

        .badge {
            display: inline-flex; align-items: center; gap: 0.3rem;
            padding: 0.2rem 0.65rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 600;
        }
        .badge-offline { background: rgba(239,68,68,0.15); color: #f87171; }
        .badge-online  { background: rgba(34,197,94,0.15);  color: #4ade80; }
        .badge-semester { background: rgba(99,102,241,0.15); color: #818cf8; }
        .badge-pengganti { background: rgba(245,158,11,0.15); color: #fbbf24; }
        .badge-ujian { background: rgba(168,85,247,0.15); color: #c084fc; }

        .actions-cell { display: flex; gap: 0.5rem; }
        .btn-icon {
            width: 32px; height: 32px; border-radius: 6px;
            border: 1px solid var(--border);
            background: transparent;
            cursor: pointer;
            display: grid; place-items: center;
            font-size: 0.85rem;
            transition: all 0.15s;
            color: var(--text-soft);
        }
        .btn-icon:hover { background: var(--accent-light); border-color: var(--accent); color: var(--accent); }
        .btn-icon.danger:hover { background: rgba(239,68,68,0.12); border-color: var(--danger); color: var(--danger); }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-muted);
        }
        .empty-state .icon { font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; }
        .empty-state p { font-size: 0.9rem; }

        .loading-row td { text-align: center; padding: 3rem; color: var(--text-muted); font-size: 0.85rem; }

        /* ── MODAL ── */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.65);
            backdrop-filter: blur(4px);
            z-index: 200;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            animation: fadeIn 0.2s ease;
        }
        .modal-overlay.open { display: flex; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .modal {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            width: 100%;
            max-width: 780px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: var(--shadow);
            animation: slideUp 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        @keyframes slideUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        .modal-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.5rem 1.75rem;
            border-bottom: 1px solid var(--border);
            position: sticky; top: 0;
            background: var(--surface);
            z-index: 2;
        }
        .modal-header h2 { font-size: 1rem; font-weight: 600; }
        .modal-header p { font-size: 0.8rem; color: var(--text-muted); margin-top: 2px; }
        .btn-close {
            width: 32px; height: 32px; border-radius: 8px;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text-muted);
            cursor: pointer; font-size: 1.1rem;
            display: grid; place-items: center;
            transition: all 0.15s;
        }
        .btn-close:hover { background: var(--surface-2); color: var(--text); }

        .modal-body { padding: 1.75rem; }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.1rem; }
        .form-grid .span-2 { grid-column: span 2; }

        .form-group { display: flex; flex-direction: column; gap: 0.45rem; }
        .form-group label { font-size: 0.78rem; font-weight: 500; color: var(--text-soft); }
        .form-control {
            background: var(--surface-2);
            border: 1px solid var(--border);
            color: var(--text);
            padding: 0.6rem 0.85rem;
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
        }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(99,102,241,0.15); }
        .form-control.error { border-color: var(--danger); }
        select.form-control { cursor: pointer; }
        select.form-control option { background: var(--surface-2); }

        .multi-select-wrap {
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 0.5rem;
            max-height: 140px;
            overflow-y: auto;
            transition: border-color 0.2s;
        }
        .multi-select-wrap:focus-within { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(99,102,241,0.15); }
        .multi-select-wrap.error { border-color: var(--danger); }
        .checkbox-item {
            display: flex; align-items: center; gap: 0.6rem;
            padding: 0.35rem 0.5rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.83rem;
            transition: background 0.15s;
        }
        .checkbox-item:hover { background: var(--border); }
        .checkbox-item input[type="checkbox"] { accent-color: var(--accent); width: 14px; height: 14px; cursor: pointer; }

        .form-hint { font-size: 0.72rem; color: var(--text-muted); margin-top: 0.2rem; }
        .error-text { font-size: 0.72rem; color: var(--danger); margin-top: 0.2rem; display: none; }
        .error-text.show { display: block; }

        .divider { border: none; border-top: 1px solid var(--border); margin: 0.5rem 0 1.2rem; }
        .section-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        .modal-footer {
            display: flex; justify-content: flex-end; gap: 0.75rem;
            padding: 1.25rem 1.75rem;
            border-top: 1px solid var(--border);
        }
        .btn-cancel {
            background: transparent;
            color: var(--text-soft);
            border: 1px solid var(--border);
            padding: 0.6rem 1.2rem;
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            cursor: pointer;
            font-family: inherit;
            font-weight: 500;
            transition: all 0.15s;
        }
        .btn-cancel:hover { background: var(--surface-2); color: var(--text); }
        .btn-submit {
            background: linear-gradient(135deg, var(--accent), #a855f7);
            color: white;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: opacity 0.2s, transform 0.15s;
            display: flex; align-items: center; gap: 0.5rem;
        }
        .btn-submit:hover:not(:disabled) { opacity: 0.9; transform: translateY(-1px); }
        .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }

        /* ── TOAST ── */
        .toast-container { position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 999; display: flex; flex-direction: column; gap: 0.75rem; }
        .toast {
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 0.85rem 1.25rem;
            font-size: 0.85rem;
            display: flex; align-items: center; gap: 0.75rem;
            box-shadow: var(--shadow);
            animation: toastIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            min-width: 280px;
        }
        @keyframes toastIn { from { transform: translateX(30px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .toast.success .toast-icon { color: var(--success); }
        .toast.error .toast-icon   { color: var(--danger); }
        .toast-icon { font-size: 1.1rem; }

        /* ── CONFIRM DIALOG ── */
        .confirm-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.65);
            backdrop-filter: blur(4px);
            z-index: 300;
            display: none;
            align-items: center;
            justify-content: center;
        }
        .confirm-overlay.open { display: flex; }
        .confirm-box {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 2rem;
            max-width: 380px;
            width: 90%;
            text-align: center;
            box-shadow: var(--shadow);
            animation: slideUp 0.2s ease;
        }
        .confirm-icon { font-size: 2.5rem; margin-bottom: 1rem; }
        .confirm-box h3 { font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; }
        .confirm-box p { font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem; }
        .confirm-actions { display: flex; gap: 0.75rem; justify-content: center; }
        .btn-danger {
            background: var(--danger);
            color: white; border: none;
            padding: 0.6rem 1.4rem;
            border-radius: var(--radius-sm);
            font-size: 0.875rem; font-weight: 600;
            cursor: pointer; font-family: inherit;
            transition: opacity 0.2s;
        }
        .btn-danger:hover { opacity: 0.85; }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--surface); }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

        @media (max-width: 640px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-grid .span-2 { grid-column: span 1; }
            .main { padding: 1rem; }
            .header { padding: 1rem; }
        }
    </style>
</head>
<body>

<!-- HEADER -->
<header class="header">
    <div class="header-brand">
        <div class="logo">📅</div>
        <div>
            <h1>Manajemen Jadwal</h1>
            <span>Sistem Informasi Akademik</span>
        </div>
    </div>
    <button class="btn-add" onclick="openCreateModal()">
        <span>＋</span> Tambah Jadwal
    </button>
</header>

<!-- MAIN -->
<main class="main">

    <!-- STATS -->
    <div class="stats" id="stats">
        <div class="stat-card">
            <div class="stat-icon indigo">📋</div>
            <div class="stat-info">
                <h3 id="stat-total">0</h3>
                <p>Total Jadwal</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">🌐</div>
            <div class="stat-info">
                <h3 id="stat-online">0</h3>
                <p>Online</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber">🏫</div>
            <div class="stat-info">
                <h3 id="stat-offline">0</h3>
                <p>Offline</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple">📚</div>
            <div class="stat-info">
                <h3 id="stat-courses">0</h3>
                <p>Mata Kuliah Aktif</p>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="section-header">
        <span class="section-title">Daftar Jadwal</span>
        <input type="text" class="search-input" placeholder="🔍 Cari jadwal..." id="searchInput" oninput="filterTable()">
    </div>
    <div class="table-wrap">
        <table id="scheduleTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Mata Kuliah</th>
                    <th>Kelas</th>
                    <th>Hari & Waktu</th>
                    <th>Jenis</th>
                    <th>Status</th>
                    <th>Ruangan</th>
                    <th>Dosen</th>
                    <th>Laboran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <tr class="loading-row"><td colspan="10">⏳ Memuat data jadwal...</td></tr>
            </tbody>
        </table>
    </div>
</main>

<!-- ========== FORM MODAL ========== -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal">
        <div class="modal-header">
            <div>
                <h2 id="modalTitle">Tambah Jadwal Baru</h2>
                <p id="modalSubtitle">Isi semua field yang diperlukan</p>
            </div>
            <button class="btn-close" onclick="closeModal()">✕</button>
        </div>

        <div class="modal-body">
            <form id="scheduleForm" novalidate>
                <div class="section-label">Informasi Umum</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Periode Tahun Ajaran <span style="color:var(--danger)">*</span></label>
                        <input type="text" class="form-control" id="f_academic_year" placeholder="Contoh: 2024/2025" required>
                        <span class="error-text" id="err_academic_year"></span>
                    </div>
                    <div class="form-group">
                        <label>Jenis Jadwal <span style="color:var(--danger)">*</span></label>
                        <select class="form-control" id="f_schedule_type" required>
                            <option value="">— Pilih Jenis —</option>
                            <option value="semester">Jadwal Semester</option>
                            <option value="pengganti">Jadwal Pengganti</option>
                            <option value="ujian">Jadwal Ujian</option>
                        </select>
                        <span class="error-text" id="err_schedule_type"></span>
                    </div>
                    <div class="form-group">
                        <label>Program Studi <span style="color:var(--danger)">*</span></label>
                        <select class="form-control" id="f_study_program_id" required>
                            <option value="">— Pilih Prodi —</option>
                        </select>
                        <span class="error-text" id="err_study_program_id"></span>
                    </div>
                    <div class="form-group">
                        <label>Mata Kuliah <span style="color:var(--danger)">*</span></label>
                        <select class="form-control" id="f_course_id" required>
                            <option value="">— Pilih Mata Kuliah —</option>
                        </select>
                        <span class="error-text" id="err_course_id"></span>
                    </div>
                </div>

                <hr class="divider">
                <div class="section-label">Waktu & Kelas</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Kelas <span style="color:var(--danger)">*</span></label>
                        <select class="form-control" id="f_class" required>
                            <option value="">— Pilih Kelas —</option>
                            @foreach(range('A', 'Z') as $kelas)
                                <option value="{{ $kelas }}">{{ $kelas }}</option>
                            @endforeach
                        </select>
                        <span class="error-text" id="err_class"></span>
                    </div>
                    <div class="form-group">
                        <label>Hari <span style="color:var(--danger)">*</span></label>
                        <select class="form-control" id="f_day" required>
                            <option value="">— Pilih Hari —</option>
                            <option>Senin</option><option>Selasa</option><option>Rabu</option>
                            <option>Kamis</option><option>Jumat</option><option>Sabtu</option>
                        </select>
                        <span class="error-text" id="err_day"></span>
                    </div>
                    <div class="form-group">
                        <label>Waktu Mulai <span style="color:var(--danger)">*</span></label>
                        <input type="time" class="form-control" id="f_start_time" required>
                        <span class="error-text" id="err_start_time"></span>
                    </div>
                    <div class="form-group">
                        <label>Waktu Selesai <span style="color:var(--danger)">*</span></label>
                        <input type="time" class="form-control" id="f_end_time" required>
                        <span class="error-text" id="err_end_time"></span>
                    </div>
                </div>

                <hr class="divider">
                <div class="section-label">Status & Ruangan</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Status <span style="color:var(--danger)">*</span></label>
                        <select class="form-control" id="f_status" required onchange="toggleRoomFields()">
                            <option value="">— Pilih Status —</option>
                            <option value="offline">Offline</option>
                            <option value="online">Online</option>
                        </select>
                        <span class="error-text" id="err_status"></span>
                    </div>
                    <div class="form-group" id="grp_theory_room">
                        <label>Ruang Teori <span style="color:var(--danger)" id="theory_star">*</span></label>
                        <select class="form-control" id="f_theory_room_id">
                            <option value="">— Tidak ada —</option>
                        </select>
                        <span class="error-text" id="err_theory_room_id"></span>
                    </div>
                    <div class="form-group" id="grp_practice_room">
                        <label>Ruang Praktik</label>
                        <select class="form-control" id="f_practice_room_id">
                            <option value="">— Tidak ada —</option>
                        </select>
                    </div>
                </div>

                <hr class="divider">
                <div class="section-label">Dosen (Pilih 2–3)</div>
                <div class="multi-select-wrap" id="wrap_lecturers">
                    <p style="color:var(--text-muted);font-size:0.82rem;padding:0.5rem">Memuat data dosen...</p>
                </div>
                <span class="error-text" id="err_lecturers"></span>
                <p class="form-hint">Minimal 2 dosen, maksimal 3 dosen.</p>

                <hr class="divider">
                <div class="section-label">Laboran (Pilih 1–2)</div>
                <div class="multi-select-wrap" id="wrap_assistants">
                    <p style="color:var(--text-muted);font-size:0.82rem;padding:0.5rem">Memuat data laboran...</p>
                </div>
                <span class="error-text" id="err_assistants"></span>
                <p class="form-hint">Minimal 1 laboran, maksimal 2 laboran.</p>
            </form>
        </div>

        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal()">Batal</button>
            <button class="btn-submit" id="btnSubmit" onclick="submitForm()">
                <span id="submitIcon">💾</span>
                <span id="submitLabel">Simpan Jadwal</span>
            </button>
        </div>
    </div>
</div>

<!-- CONFIRM DELETE DIALOG -->
<div class="confirm-overlay" id="confirmOverlay">
    <div class="confirm-box">
        <div class="confirm-icon">🗑️</div>
        <h3>Hapus Jadwal?</h3>
        <p>Tindakan ini tidak dapat dibatalkan. Jadwal akan dihapus secara permanen.</p>
        <div class="confirm-actions">
            <button class="btn-cancel" onclick="closeConfirm()">Batal</button>
            <button class="btn-danger" id="btnConfirmDelete" onclick="confirmDelete()">Ya, Hapus</button>
        </div>
    </div>
</div>

<!-- TOAST CONTAINER -->
<div class="toast-container" id="toastContainer"></div>

<script>
    // ── STATE ──
    let schedules  = [];
    let masterData = { study_programs: [], courses: [], rooms: [], lecturers: [], assistants: [] };
    let editingId  = null;
    let deleteId   = null;

    // ── BOOT ──
    async function boot() {
        await Promise.all([ loadMasterData(), loadSchedules() ]);
    }

    async function loadMasterData() {
        try {
            const res  = await fetch('/api/v1/master-data');
            masterData = await res.json();
            populateSelects();
            buildCheckboxLists();
        } catch (e) { showToast('Gagal memuat master data', 'error'); }
    }

    async function loadSchedules() {
        try {
            const res  = await fetch('/api/v1/schedules');
            const json = await res.json();
            schedules  = json.data;
            renderTable(schedules);
            updateStats(schedules);
        } catch (e) {
            document.getElementById('tableBody').innerHTML =
                `<tr class="loading-row"><td colspan="10">❌ Gagal memuat data.</td></tr>`;
        }
    }

    // ── POPULATE SELECTS ──
    function populateSelects() {
        populate('f_study_program_id', masterData.study_programs, '— Pilih Prodi —');
        populate('f_course_id',        masterData.courses,        '— Pilih Mata Kuliah —');
        populate('f_theory_room_id',   masterData.rooms,          '— Tidak ada —');
        populate('f_practice_room_id', masterData.rooms,          '— Tidak ada —');
    }

    function populate(id, items, placeholder) {
        const el = document.getElementById(id);
        el.innerHTML = `<option value="">${placeholder}</option>` +
            items.map(i => `<option value="${i.id}">${i.name}</option>`).join('');
    }

    function buildCheckboxLists() {
        buildCheckbox('wrap_lecturers',  masterData.lecturers,  'lec');
        buildCheckbox('wrap_assistants', masterData.assistants, 'ast');
    }

    function buildCheckbox(wrapperId, items, prefix) {
        const wrap = document.getElementById(wrapperId);
        if (!items.length) { wrap.innerHTML = '<p style="color:var(--text-muted);font-size:0.82rem;padding:0.5rem">Tidak ada data.</p>'; return; }
        wrap.innerHTML = items.map(i => `
            <label class="checkbox-item">
                <input type="checkbox" id="${prefix}_${i.id}" value="${i.id}">
                <span>${i.name}</span>
            </label>`).join('');
    }

    // ── RENDER TABLE ──
    function renderTable(data) {
        const tbody = document.getElementById('tableBody');
        if (!data.length) {
            tbody.innerHTML = `<tr><td colspan="10">
                <div class="empty-state">
                    <div class="icon">📭</div>
                    <p>Belum ada jadwal. Klik <strong>Tambah Jadwal</strong> untuk memulai.</p>
                </div></td></tr>`;
            return;
        }
        tbody.innerHTML = data.map((s, i) => `
            <tr>
                <td class="td-muted">${i + 1}</td>
                <td><strong>${s.course?.name ?? '—'}</strong><br>
                    <span class="td-muted" style="font-size:0.78rem">${s.study_program?.name ?? '—'}</span></td>
                <td><span class="badge badge-semester" style="background:rgba(99,102,241,.15);color:#818cf8">${s.class}</span></td>
                <td>${s.day}<br>
                    <span class="td-muted" style="font-size:0.78rem">${s.start_time?.slice(0,5)} – ${s.end_time?.slice(0,5)}</span></td>
                <td><span class="badge badge-${s.schedule_type}">${scheduleTypeLabel(s.schedule_type)}</span></td>
                <td><span class="badge badge-${s.status}">${s.status === 'online' ? '🌐 Online' : '🏫 Offline'}</span></td>
                <td class="td-muted" style="font-size:0.78rem">
                    ${s.theory_room?.name ? '📝 ' + s.theory_room.name : ''}
                    ${s.practice_room?.name ? '<br>🔬 ' + s.practice_room.name : ''}
                    ${!s.theory_room?.name && !s.practice_room?.name ? '—' : ''}
                </td>
                <td style="font-size:0.78rem">${(s.lecturers ?? []).map(l => `<div>${l.name}</div>`).join('') || '—'}</td>
                <td style="font-size:0.78rem">${(s.assistants ?? []).map(a => `<div>${a.name}</div>`).join('') || '—'}</td>
                <td>
                    <div class="actions-cell">
                        <button class="btn-icon" onclick="openEditModal(${s.id})" title="Edit">✏️</button>
                        <button class="btn-icon danger" onclick="openConfirm(${s.id})" title="Hapus">🗑️</button>
                    </div>
                </td>
            </tr>`).join('');
    }

    function scheduleTypeLabel(t) {
        return { semester: 'Semester', pengganti: 'Pengganti', ujian: 'Ujian' }[t] ?? t;
    }

    // ── STATS ──
    function updateStats(data) {
        document.getElementById('stat-total').textContent   = data.length;
        document.getElementById('stat-online').textContent  = data.filter(s => s.status === 'online').length;
        document.getElementById('stat-offline').textContent = data.filter(s => s.status === 'offline').length;
        const courses = new Set(data.map(s => s.course_id));
        document.getElementById('stat-courses').textContent = courses.size;
    }

    // ── SEARCH ──
    function filterTable() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        const filtered = schedules.filter(s =>
            (s.course?.name ?? '').toLowerCase().includes(q) ||
            (s.day ?? '').toLowerCase().includes(q) ||
            (s.class ?? '').toLowerCase().includes(q) ||
            (s.study_program?.name ?? '').toLowerCase().includes(q)
        );
        renderTable(filtered);
    }

    // ── MODAL ──
    function openCreateModal() {
        editingId = null;
        resetForm();
        document.getElementById('modalTitle').textContent    = 'Tambah Jadwal Baru';
        document.getElementById('modalSubtitle').textContent = 'Isi semua field yang diperlukan';
        document.getElementById('submitLabel').textContent   = 'Simpan Jadwal';
        document.getElementById('submitIcon').textContent    = '💾';
        document.getElementById('modalOverlay').classList.add('open');
    }

    async function openEditModal(id) {
        const schedule = schedules.find(s => s.id === id);
        if (!schedule) return;
        editingId = id;
        resetForm();

        document.getElementById('modalTitle').textContent    = 'Edit Jadwal';
        document.getElementById('modalSubtitle').textContent = `Mengubah data jadwal #${id}`;
        document.getElementById('submitLabel').textContent   = 'Perbarui Jadwal';
        document.getElementById('submitIcon').textContent    = '🔄';
        document.getElementById('modalOverlay').classList.add('open');

        // fill fields
        document.getElementById('f_academic_year').value   = schedule.academic_year ?? '';
        document.getElementById('f_schedule_type').value   = schedule.schedule_type ?? '';
        document.getElementById('f_study_program_id').value = schedule.study_program_id ?? '';
        document.getElementById('f_course_id').value       = schedule.course_id ?? '';
        document.getElementById('f_class').value           = schedule.class ?? '';
        document.getElementById('f_day').value             = schedule.day ?? '';
        document.getElementById('f_start_time').value      = schedule.start_time?.slice(0,5) ?? '';
        document.getElementById('f_end_time').value        = schedule.end_time?.slice(0,5) ?? '';
        document.getElementById('f_status').value          = schedule.status ?? '';
        document.getElementById('f_theory_room_id').value  = schedule.theory_room_id ?? '';
        document.getElementById('f_practice_room_id').value = schedule.practice_room_id ?? '';

        toggleRoomFields();

        // tick checkboxes
        const lecIds = (schedule.lecturers ?? []).map(l => l.id);
        const astIds = (schedule.assistants ?? []).map(a => a.id);
        lecIds.forEach(id => { const el = document.getElementById(`lec_${id}`); if(el) el.checked = true; });
        astIds.forEach(id => { const el = document.getElementById(`ast_${id}`); if(el) el.checked = true; });
    }

    function closeModal() {
        document.getElementById('modalOverlay').classList.remove('open');
        editingId = null;
    }

    function toggleRoomFields() {
        const status    = document.getElementById('f_status').value;
        const isOffline = status === 'offline';
        document.getElementById('theory_star').style.display = isOffline ? 'inline' : 'none';
    }

    function resetForm() {
        document.getElementById('scheduleForm').reset();
        clearErrors();
        toggleRoomFields();
        // uncheck all
        document.querySelectorAll('#wrap_lecturers input, #wrap_assistants input').forEach(c => c.checked = false);
    }

    function clearErrors() {
        document.querySelectorAll('.error-text').forEach(e => { e.textContent = ''; e.classList.remove('show'); });
        document.querySelectorAll('.form-control, .multi-select-wrap').forEach(e => e.classList.remove('error'));
    }

    function showFieldError(field, msg) {
        const errEl = document.getElementById(`err_${field}`);
        if (errEl) { errEl.textContent = msg; errEl.classList.add('show'); }
        const inputEl = document.getElementById(`f_${field}`);
        if (inputEl) inputEl.classList.add('error');
        const wrapEl = document.getElementById(`wrap_${field}`);
        if (wrapEl) wrapEl.classList.add('error');
    }

    // ── SUBMIT ──
    async function submitForm() {
        clearErrors();

        const lecturers  = [...document.querySelectorAll('#wrap_lecturers input:checked')].map(c => parseInt(c.value));
        const assistants = [...document.querySelectorAll('#wrap_assistants input:checked')].map(c => parseInt(c.value));

        const payload = {
            academic_year:    document.getElementById('f_academic_year').value.trim(),
            schedule_type:    document.getElementById('f_schedule_type').value,
            study_program_id: parseInt(document.getElementById('f_study_program_id').value) || null,
            course_id:        parseInt(document.getElementById('f_course_id').value) || null,
            class:            document.getElementById('f_class').value,
            day:              document.getElementById('f_day').value,
            start_time:       document.getElementById('f_start_time').value,
            end_time:         document.getElementById('f_end_time').value,
            status:           document.getElementById('f_status').value,
            theory_room_id:   parseInt(document.getElementById('f_theory_room_id').value) || null,
            practice_room_id: parseInt(document.getElementById('f_practice_room_id').value) || null,
            lecturers,
            assistants
        };

        const btn = document.getElementById('btnSubmit');
        btn.disabled = true;
        document.getElementById('submitLabel').textContent = 'Menyimpan...';

        const url    = editingId ? `/api/v1/schedules/${editingId}` : '/api/v1/schedules';
        const method = editingId ? 'PUT' : 'POST';

        try {
            const res  = await fetch(url, {
                method,
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload)
            });
            const json = await res.json();

            if (!res.ok) {
                // Show validation errors
                if (json.errors) {
                    Object.entries(json.errors).forEach(([field, msgs]) => showFieldError(field, msgs[0]));
                } else if (json.message) {
                    // Conflict error - find which field
                    const msg = json.message;
                    if (msg.includes('dosen') || msg.includes('lecturer')) showFieldError('lecturers', msg);
                    else if (msg.includes('laboran') || msg.includes('assistant')) showFieldError('assistants', msg);
                    else if (msg.includes('room') || msg.includes('ruang')) showFieldError('theory_room_id', msg);
                    else if (msg.includes('class') || msg.includes('kelas')) showFieldError('class', msg);
                    else showToast(msg, 'error');
                }
            } else {
                showToast(editingId ? 'Jadwal berhasil diperbarui! 🎉' : 'Jadwal berhasil ditambahkan! 🎉', 'success');
                closeModal();
                await loadSchedules();
            }
        } catch (e) {
            showToast('Terjadi kesalahan jaringan.', 'error');
        } finally {
            btn.disabled = false;
            document.getElementById('submitLabel').textContent = editingId ? 'Perbarui Jadwal' : 'Simpan Jadwal';
        }
    }

    // ── DELETE ──
    function openConfirm(id) {
        deleteId = id;
        document.getElementById('confirmOverlay').classList.add('open');
    }

    function closeConfirm() {
        deleteId = null;
        document.getElementById('confirmOverlay').classList.remove('open');
    }

    async function confirmDelete() {
        if (!deleteId) return;
        const btn = document.getElementById('btnConfirmDelete');
        btn.disabled = true;
        btn.textContent = 'Menghapus...';
        try {
            const res = await fetch(`/api/v1/schedules/${deleteId}`, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json' }
            });
            if (res.ok || res.status === 204) {
                showToast('Jadwal berhasil dihapus.', 'success');
                closeConfirm();
                await loadSchedules();
            } else {
                showToast('Gagal menghapus jadwal.', 'error');
            }
        } catch (e) {
            showToast('Terjadi kesalahan jaringan.', 'error');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Ya, Hapus';
        }
    }

    // ── TOAST ──
    function showToast(msg, type = 'success') {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `<span class="toast-icon">${type === 'success' ? '✅' : '❌'}</span><span>${msg}</span>`;
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }

    // Close modal on backdrop click
    document.getElementById('modalOverlay').addEventListener('click', e => {
        if (e.target === e.currentTarget) closeModal();
    });
    document.getElementById('confirmOverlay').addEventListener('click', e => {
        if (e.target === e.currentTarget) closeConfirm();
    });

    // Close on Escape
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') { closeModal(); closeConfirm(); }
    });

    boot();
</script>
</body>
</html>
