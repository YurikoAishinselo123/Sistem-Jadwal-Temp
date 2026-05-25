# API Documentation

## Base URL

All API endpoints use this base prefix:

```text
/api/v1
```

Example:

```text
GET /api/v1/jadwal
```

## Authentication

This project uses Bearer Token authentication for protected endpoints.

Add this header for authenticated requests:

```http
Authorization: Bearer YOUR_ACCESS_TOKEN
Accept: application/json
```

## Access Rules

### Public Endpoints

These endpoints can be accessed without login:

- `GET /api/v1/jadwal`
- `GET /api/v1/jadwal/{schedule}`
- `GET /api/v1/beban-kerja/dosen`
- `GET /api/v1/beban-kerja/ruangan`
- `GET /api/v1/master-data`
- `GET /api/v1/makuls`
- `GET /api/v1/makuls/{makul}`
- `GET /api/v1/dosens`
- `GET /api/v1/dosens/{dosen}`
- `GET /api/v1/laborans`
- `GET /api/v1/laborans/{laboran}`
- `GET /api/v1/prodis`
- `GET /api/v1/prodis/{prodi}`
- `GET /api/v1/ruangans`
- `GET /api/v1/ruangans/{ruangan}`
- `GET /api/v1/periodes`
- `GET /api/v1/periodes/{periode}`

### Protected Endpoints

These endpoints require a valid Bearer token:

- All schedule create, update, and delete endpoints
- All master data create, update, and delete endpoints
- Auth session endpoints like `logout`, `me`, and resend verification

## Authentication Endpoints

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `POST` | `/api/v1/auth/register` | No | Register new user |
| `POST` | `/api/v1/auth/login` | No | Login and get access token |
| `POST` | `/api/v1/auth/refresh` | No | Refresh token flow placeholder |
| `POST` | `/api/v1/auth/forgot-password` | No | Send reset password link |
| `POST` | `/api/v1/auth/reset-password` | No | Reset password |
| `GET` | `/api/v1/auth/email/verify/{id}/{hash}` | No | Verify email |
| `POST` | `/api/v1/auth/email/resend` | Yes | Resend verification email |
| `POST` | `/api/v1/auth/logout` | Yes | Logout current user |
| `GET` | `/api/v1/auth/me` | Yes | Get current authenticated user |

### Login Request

```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

### Login Success Response

```json
{
  "message": "Login successful.",
  "data": {
    "user": {
      "id": 1,
      "name": "User Name",
      "email": "user@example.com"
    },
    "token": {
      "token_type": "Bearer",
      "access_token": "YOUR_ACCESS_TOKEN",
      "refresh_token": null,
      "expires_in": null
    }
  }
}
```

### Register Request

```json
{
  "name": "User Name",
  "email": "user@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

## Jadwal Endpoints

Primary public/protected schedule endpoints:

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/jadwal` | No | Get all schedules |
| `GET` | `/api/v1/jadwal/{schedule}` | No | Get schedule detail |
| `POST` | `/api/v1/jadwal` | Yes | Create schedule |
| `PUT` | `/api/v1/jadwal/{schedule}` | Yes | Update schedule |
| `DELETE` | `/api/v1/jadwal/{schedule}` | Yes | Delete schedule |

Compatibility aliases also exist:

- `GET /api/v1/schedules`
- `GET /api/v1/schedules/{schedule}`
- `POST /api/v1/schedules`
- `PUT /api/v1/schedules/{schedule}`
- `DELETE /api/v1/schedules/{schedule}`

## Beban Kerja Endpoints

These endpoints are public.

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/beban-kerja/dosen` | No | Get lecturer workload |
| `GET` | `/api/v1/beban-kerja/ruangan` | No | Get room workload |

### Query Parameters

#### Dosen Workload

```text
/api/v1/beban-kerja/dosen?dosen_id=1&periode_id=1
```

Required parameters:

- `dosen_id`
- `periode_id`

#### Ruangan Workload

```text
/api/v1/beban-kerja/ruangan?ruangan_id=1&periode_id=1
```

Required parameters:

- `ruangan_id`
- `periode_id`

## Master Data Aggregate Endpoint

This endpoint is useful for frontend dropdowns and filters.

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/master-data` | No | Get all master data in one response |

Returned sections:

- `makuls`
- `dosens`
- `laborans`
- `prodis`
- `ruangans`
- `periodes`

## Master Data CRUD Endpoints

### Mata Kuliah

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

### Program Studi

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

### Periode Tahun Ajaran

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/v1/periodes` | No | List periode |
| `GET` | `/api/v1/periodes/{periode}` | No | Detail periode |
| `POST` | `/api/v1/periodes` | Yes | Create periode |
| `PUT/PATCH` | `/api/v1/periodes/{periode}` | Yes | Update periode |
| `DELETE` | `/api/v1/periodes/{periode}` | Yes | Delete periode |
| `POST` | `/api/v1/periodes/{periode}/tutup` | Yes | Close periode |

## Frontend Usage Notes

- After successful login, frontend should redirect user to `/jadwal`
- If user opens protected page `/master-data` without a valid token, frontend should redirect to `/login`
- Public pages:
  - `/jadwal`
  - `/beban-kerja`
- Protected page:
  - `/master-data`

## Quick cURL Examples

### Public: Get All Jadwal

```bash
curl -X GET http://localhost:8000/api/v1/jadwal \
  -H "Accept: application/json"
```

### Public: Get Beban Kerja Dosen

```bash
curl -X GET "http://localhost:8000/api/v1/beban-kerja/dosen?dosen_id=1&periode_id=1" \
  -H "Accept: application/json"
```

### Protected: Create Jadwal

```bash
curl -X POST http://localhost:8000/api/v1/jadwal \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -d '{
    "periode_id": 1,
    "prodi_id": 1,
    "makul_id": 1,
    "dosens": [1, 2],
    "laborans": [1],
    "schedule_type": "semester",
    "status": "offline",
    "class": "TI-A",
    "day": "Senin",
    "start_time": "08:00",
    "end_time": "10:00",
    "theory_room_id": 1,
    "practice_room_id": null
  }'
```

### Protected: Get Current User

```bash
curl -X GET http://localhost:8000/api/v1/auth/me \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```
