# API Documentation

## Base URL

All API endpoints use this base prefix (depending on your environment setup, this might be `/api` or `/api/v1`):

```text
/api/v1
```

Example:

```text
GET /api/v1/jadwal
```

## Authentication

This project uses Bearer Token authentication. Some endpoints use Sanctum (`auth:sanctum`), and others use the default `auth:api` guard.

Add this header for authenticated requests:

```http
Authorization: Bearer YOUR_ACCESS_TOKEN
Accept: application/json
```

## Access Rules

### Public Endpoints
Most `index` and `show` endpoints are accessible without login:
- `GET /api/v1/master-data`
- `GET /api/v1/makuls` (and other master data `GET` routes)
- `GET /api/v1/jadwal`
- `GET /api/v1/schedules`
- Beban Kerja endpoints

### Protected Endpoints
These endpoints require a valid Bearer token:
- All create (`POST`), update (`PUT/PATCH`), and delete (`DELETE`) endpoints for schedule and master data.
- Auth session endpoints like `logout` and `me`.
- The `/api/user` endpoint.

---

## Default User Endpoint

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/user` | Yes (Sanctum) | Get current authenticated user details |

---

## Test Endpoint

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/test` | No | Test API connection and status |

---

## Authentication Endpoints

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `POST` | `/api/v1/auth/register` | No | Register new user |
| `POST` | `/api/v1/auth/login` | No | Login and get access token |
| `POST` | `/api/v1/auth/refresh` | No | Refresh token |
| `POST` | `/api/v1/auth/forgot-password` | No | Request password reset link |
| `POST` | `/api/v1/auth/reset-password` | No | Reset password |
| `GET` | `/api/v1/auth/email/verify/{id}/{hash}` | No | Verify email |
| `POST` | `/api/v1/auth/email/resend` | Yes | Resend verification email |
| `POST` | `/api/v1/auth/logout` | Yes | Logout current user |
| `GET` | `/api/v1/auth/me` | Yes | Get current authenticated user details via API guard |

---

## General / Aggregate Endpoints

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/master-data` | No | Get aggregated master data |

---

## Beban Kerja Endpoints

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET/POST` | `/api/v1/beban-kerja/dosen` | No | Get/Process beban kerja dosen |
| `GET/POST` | `/api/v1/beban-kerja/ruangan` | No | Get/Process beban kerja ruangan |
| `GET/POST` | `/api/v1/beban-kerja/laboran` | No | Get/Process beban kerja laboran |

---

## Schedule / Jadwal Endpoints

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/jadwal` | No | List jadwal |
| `GET` | `/api/v1/jadwal/{schedule}` | No | Detail jadwal |
| `POST` | `/api/v1/jadwal` | Yes | Create jadwal |
| `PUT/PATCH` | `/api/v1/jadwal/{schedule}` | Yes | Update jadwal |
| `DELETE` | `/api/v1/jadwal/{schedule}` | Yes | Delete jadwal |
| `POST` | `/api/v1/jadwal/bulk-delete` | Yes | Bulk delete jadwal |

*Note: The `/api/v1/schedules` endpoints mirror the `/api/v1/jadwal` functionality exactly.*

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/schedules` | No | List schedules |
| `GET` | `/api/v1/schedules/{schedule}` | No | Detail schedule |
| `POST` | `/api/v1/schedules` | Yes | Create schedule |
| `PUT/PATCH` | `/api/v1/schedules/{schedule}` | Yes | Update schedule |
| `DELETE` | `/api/v1/schedules/{schedule}` | Yes | Delete schedule |

---

## Master Data Endpoints

### Mata Kuliah (Makuls)
| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/makuls` | No | List mata kuliah |
| `GET` | `/api/v1/makuls/{makul}` | No | Detail mata kuliah |
| `POST` | `/api/v1/makuls` | Yes | Create mata kuliah |
| `PUT/PATCH` | `/api/v1/makuls/{makul}` | Yes | Update mata kuliah |
| `DELETE` | `/api/v1/makuls/{makul}` | Yes | Delete mata kuliah |

### Dosen
| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/dosens` | No | List dosen |
| `GET` | `/api/v1/dosens/{dosen}` | No | Detail dosen |
| `POST` | `/api/v1/dosens` | Yes | Create dosen |
| `PUT/PATCH` | `/api/v1/dosens/{dosen}` | Yes | Update dosen |
| `DELETE` | `/api/v1/dosens/{dosen}` | Yes | Delete dosen |

### Laboran
| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/laborans` | No | List laboran |
| `GET` | `/api/v1/laborans/{laboran}` | No | Detail laboran |
| `POST` | `/api/v1/laborans` | Yes | Create laboran |
| `PUT/PATCH` | `/api/v1/laborans/{laboran}` | Yes | Update laboran |
| `DELETE` | `/api/v1/laborans/{laboran}` | Yes | Delete laboran |

### Program Studi (Prodis)
| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/prodis` | No | List program studi |
| `GET` | `/api/v1/prodis/{prodi}` | No | Detail program studi |
| `POST` | `/api/v1/prodis` | Yes | Create program studi |
| `PUT/PATCH` | `/api/v1/prodis/{prodi}` | Yes | Update program studi |
| `DELETE` | `/api/v1/prodis/{prodi}` | Yes | Delete program studi |

### Ruangan
| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/ruangans` | No | List ruangan |
| `GET` | `/api/v1/ruangans/{ruangan}` | No | Detail ruangan |
| `POST` | `/api/v1/ruangans` | Yes | Create ruangan |
| `PUT/PATCH` | `/api/v1/ruangans/{ruangan}` | Yes | Update ruangan |
| `DELETE` | `/api/v1/ruangans/{ruangan}` | Yes | Delete ruangan |

### Periode
| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/periodes` | No | List periode |
| `GET` | `/api/v1/periodes/{periode}` | No | Detail periode |
| `POST` | `/api/v1/periodes` | Yes | Create periode |
| `PUT/PATCH` | `/api/v1/periodes/{periode}` | Yes | Update periode |
| `DELETE` | `/api/v1/periodes/{periode}` | Yes | Delete periode |
| `POST` | `/api/v1/periodes/{periode}/tutup` | Yes | Tutup periode |
| `POST` | `/api/v1/periodes/{periode}/buka` | Yes | Buka periode |
