<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auth — Sistem Jadwal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0a0f1e;
            --surface: rgba(255,255,255,0.04);
            --surface-hover: rgba(255,255,255,0.07);
            --border: rgba(255,255,255,0.1);
            --border-focus: #6366f1;
            --text: #f0f4ff;
            --muted: #7c8db5;
            --accent: #6366f1;
            --accent-glow: rgba(99,102,241,0.35);
            --accent-hover: #4f46e5;
            --success: #10b981;
            --success-glow: rgba(16,185,129,0.2);
            --danger: #f43f5e;
            --danger-glow: rgba(244,63,94,0.2);
            --warn: #f59e0b;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: grid;
            place-items: center;
            overflow: hidden;
        }

        /* Animated Orbs Background */
        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
        }
        body::before {
            width: 500px; height: 500px;
            background: rgba(99,102,241,0.15);
            top: -150px; left: -100px;
            animation: float 8s ease-in-out infinite;
        }
        body::after {
            width: 400px; height: 400px;
            background: rgba(16,185,129,0.1);
            bottom: -100px; right: -80px;
            animation: float 10s ease-in-out infinite reverse;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-30px); }
        }

        .card {
            position: relative;
            width: 440px;
            padding: 2.5rem;
            background: rgba(10,15,40,0.85);
            backdrop-filter: blur(24px);
            border: 1px solid var(--border);
            border-radius: 20px;
            box-shadow: 0 30px 80px -20px rgba(0,0,0,0.7), 0 0 0 1px rgba(255,255,255,0.03) inset;
            animation: slideUp 0.5s cubic-bezier(0.16,1,0.3,1);
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Logo */
        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }
        .logo-icon {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 8px 20px var(--accent-glow);
        }
        .logo-text { font-size: 1.2rem; font-weight: 700; letter-spacing: -0.5px; }
        .logo-sub { font-size: 0.7rem; color: var(--muted); letter-spacing: 1px; text-transform: uppercase; }

        /* Tabs */
        .tabs {
            display: flex;
            gap: 0.25rem;
            background: rgba(0,0,0,0.3);
            border-radius: 10px;
            padding: 4px;
            margin-bottom: 2rem;
        }
        .tab {
            flex: 1;
            padding: 0.6rem;
            background: transparent;
            border: none;
            border-radius: 8px;
            color: var(--muted);
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .tab.active {
            background: var(--accent);
            color: white;
            box-shadow: 0 4px 15px var(--accent-glow);
        }

        /* Forms */
        .form-panel { display: none; }
        .form-panel.active { display: block; animation: fadein 0.3s ease; }
        @keyframes fadein { from { opacity: 0; transform: translateX(8px); } to { opacity: 1; transform: translateX(0); } }

        .panel-title { font-size: 1.4rem; font-weight: 700; margin-bottom: 0.35rem; }
        .panel-sub   { color: var(--muted); font-size: 0.875rem; margin-bottom: 1.75rem; }

        .form-group { margin-bottom: 1.2rem; }
        label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }
        input {
            width: 100%;
            padding: 0.8rem 1rem;
            background: rgba(0,0,0,0.25);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        input:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
        }
        input::placeholder { color: #3d4a6a; }

        .btn {
            width: 100%;
            padding: 0.875rem;
            background: var(--accent);
            color: white;
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 0.5rem;
            box-shadow: 0 8px 25px var(--accent-glow);
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        .btn:hover { background: var(--accent-hover); transform: translateY(-1px); box-shadow: 0 12px 30px var(--accent-glow); }
        .btn:active { transform: translateY(0); }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

        .btn-loading { opacity: 0.7; pointer-events: none; }
        .btn-loading::after {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            width: 18px; height: 18px;
            margin: -9px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Alert */
        .alert {
            padding: 0.85rem 1rem;
            border-radius: 10px;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
            display: none;
            line-height: 1.5;
        }
        .alert.show { display: flex; gap: 0.6rem; align-items: flex-start; }
        .alert-success { background: var(--success-glow); border: 1px solid rgba(16,185,129,0.3); color: #34d399; }
        .alert-error   { background: var(--danger-glow); border: 1px solid rgba(244,63,94,0.3); color: #fb7185; }

        /* Dashboard (post-login) */
        #dashboard {
            display: none;
            width: 440px;
            animation: slideUp 0.5s cubic-bezier(0.16,1,0.3,1);
        }
        #dashboard.active { display: block; }

        .dash-card {
            background: rgba(10,15,40,0.85);
            backdrop-filter: blur(24px);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 30px 80px -20px rgba(0,0,0,0.7);
        }
        .dash-avatar {
            width: 64px; height: 64px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
            box-shadow: 0 10px 25px var(--accent-glow);
        }
        .dash-name { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.25rem; }
        .dash-email { color: var(--muted); font-size: 0.9rem; margin-bottom: 0.5rem; }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.3rem 0.75rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 1.75rem;
        }
        .badge-success { background: var(--success-glow); color: #34d399; border: 1px solid rgba(16,185,129,0.3); }
        .badge-warn    { background: rgba(245,158,11,0.15); color: var(--warn); border: 1px solid rgba(245,158,11,0.3); }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 1.75rem;
        }
        .info-item {
            background: rgba(0,0,0,0.25);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.85rem 1rem;
        }
        .info-label { font-size: 0.7rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.3rem; }
        .info-value { font-size: 0.9rem; font-weight: 600; }

        .btn-danger-outline {
            width: 100%;
            padding: 0.875rem;
            background: transparent;
            color: var(--danger);
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            border: 1px solid rgba(244,63,94,0.4);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-danger-outline:hover { background: var(--danger-glow); border-color: var(--danger); }

        /* Divider & Google Button */
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.25rem 0;
            color: var(--muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--border);
        }
        .divider:not(:empty)::before { margin-right: .75em; }
        .divider:not(:empty)::after { margin-left: .75em; }

        .google-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            width: 100%;
            padding: 0.875rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }
        .google-btn:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }
        .google-btn svg {
            width: 18px;
            height: 18px;
        }
    </style>
</head>
<body>

    <!-- AUTH CARD -->
    <div class="card" id="auth-card">
        <div class="logo">
            <div class="logo-icon">📅</div>
            <div>
                <div class="logo-text">SistemJadwal</div>
                <div class="logo-sub">Academic Scheduling</div>
            </div>
        </div>

        <div class="tabs">
            <button class="tab active" id="tab-login"    onclick="switchTab('login')">Login</button>
            <button class="tab"        id="tab-register" onclick="switchTab('register')">Register</button>
        </div>

        <!-- LOGIN PANEL -->
        <div class="form-panel active" id="panel-login">
            <div class="panel-title">Welcome back 👋</div>
            <div class="panel-sub">Sign in to manage your schedule.</div>

            <div class="alert" id="login-alert"></div>

            <form id="login-form" onsubmit="handleLogin(event)">
                <div class="form-group">
                    <label for="login-email">Email</label>
                    <input type="email" id="login-email" placeholder="you@example.com" required>
                </div>
                <div class="form-group">
                    <label for="login-password">Password</label>
                    <input type="password" id="login-password" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn" id="login-btn">Sign In</button>

                <!--
                <div class="divider">or</div>

                <a href="/auth/google/redirect" class="google-btn">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.66l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Continue with Google
                </a>
                -->
            </form>
        </div>

        <!-- REGISTER PANEL -->
        <div class="form-panel" id="panel-register">
            <div class="panel-title">Create account ✨</div>
            <div class="panel-sub">Join and start managing schedules.</div>

            <div class="alert" id="register-alert"></div>

            <form id="register-form" onsubmit="handleRegister(event)">
                <div class="form-group">
                    <label for="reg-name">Full Name</label>
                    <input type="text" id="reg-name" placeholder="Ahmad Fauzi" required>
                </div>
                <div class="form-group">
                    <label for="reg-email">Email</label>
                    <input type="email" id="reg-email" placeholder="you@example.com" required>
                </div>
                <div class="form-group">
                    <label for="reg-password">Password</label>
                    <input type="password" id="reg-password" placeholder="Min. 8 characters" required minlength="8">
                </div>
                <div class="form-group">
                    <label for="reg-password-confirm">Confirm Password</label>
                    <input type="password" id="reg-password-confirm" placeholder="Repeat password" required>
                </div>
                <button type="submit" class="btn" id="register-btn">Create Account</button>

                <!--
                <div class="divider">or</div>

                <a href="/auth/google/redirect" class="google-btn">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.66l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Continue with Google
                </a>
                -->
            </form>
        </div>
    </div>

    <!-- DASHBOARD CARD (after login) -->
    <div id="dashboard">
        <div class="dash-card">
            <div class="dash-avatar" id="dash-avatar">?</div>
            <div class="dash-name" id="dash-name">—</div>
            <div class="dash-email" id="dash-email">—</div>
            <div class="badge" id="dash-verified-badge">—</div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">User ID</div>
                    <div class="info-value" id="dash-id">—</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Session</div>
                    <div class="info-value" id="dash-session">Active ✓</div>
                </div>
                <div class="info-item" style="grid-column: 1 / -1;">
                    <div class="info-label">Access Token (preview)</div>
                    <div class="info-value" id="dash-token" style="font-size:0.72rem; word-break:break-all; color: var(--muted);">—</div>
                </div>
            </div>

            <div class="alert" id="logout-alert"></div>

            <button class="btn-danger-outline" onclick="handleLogout()">Sign Out</button>
        </div>
    </div>

    <script>
        const API_BASE = '/api/v1/auth';
        const LOGIN_REDIRECT = '/jadwal';

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

        // ── Tab switching ────────────────────────────────────────────
        function switchTab(tab) {
            const panels = { login: 'panel-login', register: 'panel-register' };
            const tabs   = { login: 'tab-login',   register: 'tab-register' };
            Object.keys(panels).forEach(k => {
                document.getElementById(panels[k]).classList.toggle('active', k === tab);
                document.getElementById(tabs[k]).classList.toggle('active', k === tab);
            });
            clearAlerts();
        }

        // ── Alert helpers ────────────────────────────────────────────
        function showAlert(id, message, type = 'error') {
            const el = document.getElementById(id);
            el.className = `alert alert-${type} show`;
            el.innerHTML = `<span>${type === 'error' ? '⚠️' : '✅'}</span><span>${message}</span>`;
        }
        function clearAlerts() {
            ['login-alert', 'register-alert', 'logout-alert'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.className = 'alert';
            });
        }

        // ── Loading state ────────────────────────────────────────────
        function setLoading(btnId, loading) {
            const btn = document.getElementById(btnId);
            btn.classList.toggle('btn-loading', loading);
            btn.textContent = loading ? '' : btn.dataset.label;
        }
        document.addEventListener('DOMContentLoaded', async () => {
            document.getElementById('login-btn').dataset.label    = 'Sign In';
            document.getElementById('register-btn').dataset.label = 'Create Account';

            const params = new URLSearchParams(window.location.search);
            const tokenFromGoogle = params.get('token');
            const googleError = params.get('error');

            if (googleError) {
                showAlert('login-alert', googleError, 'error');
            }

            if (tokenFromGoogle) {
                storeTokens(tokenFromGoogle);
                window.history.replaceState({}, document.title, '/login');
                return await checkAutoLogin(tokenFromGoogle, true);
            }

            const storedToken = getAccessToken();
            if (storedToken) {
                await checkAutoLogin(storedToken, true);
            }
        });

        async function checkAutoLogin(token, redirectOnSuccess = false) {
            try {
                const res = await fetch(`/api/v1/auth/me`, {
                    method: 'GET',
                    headers: { 
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });
                if (res.ok) {
                    const json = await res.json();
                    storeTokens(token);
                    showDashboard(json.data, token);
                    if (redirectOnSuccess) {
                        window.location.href = LOGIN_REDIRECT;
                    }
                } else {
                    clearTokens();
                }
            } catch (err) {
                console.error('Error fetching user session:', err);
            }
        }

        // ── REGISTER ────────────────────────────────────────────────
        async function handleRegister(e) {
            e.preventDefault();
            clearAlerts();
            const name    = document.getElementById('reg-name').value.trim();
            const email   = document.getElementById('reg-email').value.trim();
            const pass    = document.getElementById('reg-password').value;
            const confirm = document.getElementById('reg-password-confirm').value;

            if (pass !== confirm) {
                return showAlert('register-alert', 'Passwords do not match.', 'error');
            }

            setLoading('register-btn', true);
            try {
                const res  = await fetch(`${API_BASE}/register`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ name, email, password: pass, password_confirmation: confirm }),
                });
                const json = await res.json();
                if (!res.ok) {
                    const errors = json.errors ? Object.values(json.errors).flat().join('<br>') : json.message;
                    return showAlert('register-alert', errors, 'error');
                }
                showAlert('register-alert', 'Account created! Please check your email to verify your account.', 'success');
                document.getElementById('register-form').reset();
            } catch (err) {
                showAlert('register-alert', 'Network error. Is the server running?', 'error');
            } finally {
                setLoading('register-btn', false);
            }
        }

        // ── LOGIN ───────────────────────────────────────────────────
        async function handleLogin(e) {
            e.preventDefault();
            clearAlerts();
            const email    = document.getElementById('login-email').value.trim();
            const password = document.getElementById('login-password').value;

            setLoading('login-btn', true);
            try {
                const res  = await fetch(`${API_BASE}/login`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ email, password }),
                });
                const json = await res.json();
                if (!res.ok) {
                    const errors = json.errors ? Object.values(json.errors).flat().join('<br>') : json.message;
                    return showAlert('login-alert', errors, 'error');
                }
                const { user, token } = json.data;
                storeTokens(token.access_token, token.refresh_token);
                showDashboard(user, token.access_token);
                window.location.href = LOGIN_REDIRECT;
            } catch (err) {
                showAlert('login-alert', 'Network error. Is the server running?', 'error');
            } finally {
                setLoading('login-btn', false);
            }
        }

        // ── LOGOUT ──────────────────────────────────────────────────
        async function handleLogout() {
            const token = getAccessToken();
            try {
                await fetch(`${API_BASE}/logout`, {
                    method: 'POST',
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' },
                });
            } catch (_) { /* ignore network errors on logout */ }
            clearTokens();
            hideDashboard();
        }

        // ── Dashboard ────────────────────────────────────────────────
        function showDashboard(user, token) {
            document.getElementById('auth-card').style.display = 'none';
            document.getElementById('dashboard').classList.add('active');

            // Avatar initials
            const initials = user.name.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase();
            document.getElementById('dash-avatar').textContent  = initials;
            document.getElementById('dash-name').textContent    = user.name;
            document.getElementById('dash-email').textContent   = user.email;
            document.getElementById('dash-id').textContent      = `#${user.id}`;
            document.getElementById('dash-token').textContent   = token.slice(0, 60) + '…';

            const badge = document.getElementById('dash-verified-badge');
            if (user.email_verified_at) {
                badge.className   = 'badge badge-success';
                badge.textContent = '✓ Email Verified';
            } else {
                badge.className   = 'badge badge-warn';
                badge.textContent = '⚠ Email Not Verified';
            }
        }

        function hideDashboard() {
            document.getElementById('dashboard').classList.remove('active');
            document.getElementById('auth-card').style.display = 'block';
            clearAlerts();
        }
    </script>
</body>
</html>
