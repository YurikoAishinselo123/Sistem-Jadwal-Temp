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

This project uses Bearer Token authentication via Laravel Sanctum for protected endpoints.

Add this header for authenticated requests:

```http
Authorization: Bearer YOUR_ACCESS_TOKEN
Accept: application/json
```

## Access Rules

### Public Endpoints
Most `index` and `show` endpoints are accessible without login:
- `GET /api/v1/jadwal`
- `GET /api/v1/jadwal/{jadwal}`
- `GET /api/v1/datainput`
- `POST /api/v1/datainputfilter`
- `GET /api/v1/filter`
- `GET /api/v1/jadwalpagi`
- `GET /api/v1/jadwalmalam`
- `GET /api/v1/makul` (and other master data `GET` routes)

### Protected Endpoints
These endpoints require a valid Bearer token:
- All create (`POST`), update (`PUT/PATCH`), and delete (`DELETE`) endpoints for schedule and master data.
- Auth session endpoints like `logout` and `user`.

---

## Authentication Endpoints

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `POST` | `/api/v1/register` | No | Register new user |
| `POST` | `/api/v1/login` | No | Login and get access token |
| `POST` | `/api/v1/logout` | Yes | Logout current user |
| `GET` | `/api/v1/user` | Yes | Get current authenticated user details |

### Login Request

```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

---

## Data Input & Filtering Endpoints

These endpoints are specifically tailored for populating frontend form dropdowns and filters.

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/datainput` | No | Get aggregate master data for initial form setup (periode, prodi, makul, dll) |
| `POST` | `/api/v1/datainputfilter` | No | Get filtered dropdown data (dosen, ruangan, laboran) based on selected time and date |
| `GET` | `/api/v1/filter` | No | Get available filter options |

---

## Jadwal Endpoints

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/jadwal` | No | Get all schedules |
| `GET` | `/api/v1/jadwal/{jadwal}` | No | Get schedule detail |
| `POST` | `/api/v1/jadwal` | Yes | Create schedule |
| `PUT/PATCH` | `/api/v1/jadwal/{jadwal}` | Yes | Update schedule |
| `DELETE` | `/api/v1/jadwal/{jadwal}` | Yes | Delete schedule |

---

## Waktu Perkuliahan Endpoints

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/waktu` | No | List waktu perkuliahan |
| `GET` | `/api/v1/waktu/{waktu}` | No | Detail waktu perkuliahan |
| `POST` | `/api/v1/waktu` | Yes | Create waktu perkuliahan |
| `PUT/PATCH` | `/api/v1/waktu/{waktu}` | Yes | Update waktu perkuliahan |
| `DELETE` | `/api/v1/waktu/{waktu}` | Yes | Delete waktu perkuliahan |
| `GET` | `/api/v1/jadwalpagi` | No | Get morning schedules / times |
| `GET` | `/api/v1/jadwalmalam` | No | Get night schedules / times |

---

## Master Data CRUD Endpoints

### Mata Kuliah
| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/makul` | No | List mata kuliah |
| `GET` | `/api/v1/makul/{makul}` | No | Detail mata kuliah |
| `POST` | `/api/v1/makul` | Yes | Create mata kuliah |
| `PUT/PATCH` | `/api/v1/makul/{makul}` | Yes | Update mata kuliah |
| `DELETE` | `/api/v1/makul/{makul}` | Yes | Delete mata kuliah |

### Dosen
| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/dosen` | No | List dosen |
| `GET` | `/api/v1/dosen/{dosen}` | No | Detail dosen |
| `POST` | `/api/v1/dosen` | Yes | Create dosen |
| `PUT/PATCH` | `/api/v1/dosen/{dosen}` | Yes | Update dosen |
| `DELETE` | `/api/v1/dosen/{dosen}` | Yes | Delete dosen |

### Laboran
| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/laboran` | No | List laboran |
| `GET` | `/api/v1/laboran/{laboran}` | No | Detail laboran |
| `POST` | `/api/v1/laboran` | Yes | Create laboran |
| `PUT/PATCH` | `/api/v1/laboran/{laboran}` | Yes | Update laboran |
| `DELETE` | `/api/v1/laboran/{laboran}` | Yes | Delete laboran |

### Program Studi
| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/prodi` | No | List program studi |
| `GET` | `/api/v1/prodi/{prodi}` | No | Detail program studi |
| `POST` | `/api/v1/prodi` | Yes | Create program studi |
| `PUT/PATCH` | `/api/v1/prodi/{prodi}` | Yes | Update program studi |
| `DELETE` | `/api/v1/prodi/{prodi}` | Yes | Delete program studi |

### Ruangan
| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/ruangan` | No | List ruangan |
| `GET` | `/api/v1/ruangan/{ruangan}` | No | Detail ruangan |
| `POST` | `/api/v1/ruangan` | Yes | Create ruangan |
| `PUT/PATCH` | `/api/v1/ruangan/{ruangan}` | Yes | Update ruangan |
| `DELETE` | `/api/v1/ruangan/{ruangan}` | Yes | Delete ruangan |

### Periode Tahun Ajaran
| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/periode` | No | List periode |
| `GET` | `/api/v1/periode/{periode}` | No | Detail periode |
| `POST` | `/api/v1/periode` | Yes | Create periode |
| `PUT/PATCH` | `/api/v1/periode/{periode}` | Yes | Update periode |
| `DELETE` | `/api/v1/periode/{periode}` | Yes | Delete periode |
