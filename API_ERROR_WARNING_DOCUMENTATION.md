# API Error & Warning Message Documentation

This document catalogs all possible error and warning messages returned by the backend API. All messages are localized in Indonesian (`id`). This documentation is structured to help frontend developers and AI coding assistants map HTTP status codes and response messages to proper UI feedback (e.g., toast notifications, inline form errors, or global error boundaries).

---

## 1. Global Exceptions & Errors

These messages are handled globally and can occur across **any** API endpoint depending on the context.

| HTTP Status | Context / Trigger | Response `message` | Notes for Frontend |
|---|---|---|---|
| **401 Unauthorized** | Missing, invalid, or expired Bearer token. | `"Tidak terautentikasi."` | Clear local storage/cookies and redirect the user to the `/login` screen. |
| **404 Not Found** | Querying a record ID that does not exist in the database, or accessing a non-existent route. | `"Data tidak ditemukan."` | Display a "Not Found" state or redirect to a 404 page. |
| **422 Unprocessable Entity** | Form validation failure (e.g., missing required fields, invalid format). | `"Kesalahan validasi."` | The response will include an `errors` object. Map these errors directly to the corresponding form inputs. Keys use the exact payload variable names (e.g., `kode_dosen`). |
| **409 Conflict** | **Duplicate Entry**: Attempting to create a record with a unique field that already exists (e.g., duplicate code). | `"Data sudah ada (duplikat)."` | Highlight the conflicting field. |
| **409 Conflict** | **Foreign Key Constraint**: Attempting to delete Master Data (e.g., Dosen, Ruangan) that is currently assigned to a Schedule (Jadwal). | `"Data tidak dapat dihapus karena masih digunakan oleh data lain."` | Warn the user that they must remove the dependencies first before deleting. |
| **500 Internal Server Error** | General database query failures (other than duplicate/FK constraints). | `"Terjadi kesalahan pada database."` | Show a generic "System Error" toast notification. |
| **500 Internal Server Error** | Uncaught exceptions or server crashes. | `"Terjadi kesalahan pada server."` | Show a generic "System Error" toast notification. |

---

## 2. Authentication Endpoints (`/api/v1/auth/*`)

Specific warnings and errors triggered during the authentication lifecycle.

### `POST /api/v1/auth/login`
| HTTP Status | Context / Trigger | Response `message` / `errors` |
|---|---|---|
| **422** | User provides an incorrect email or password. | `"Email atau kata sandi yang Anda masukkan salah."` (Returned inside the `errors.email` array) |

### `POST /api/v1/auth/refresh`
| HTTP Status | Context / Trigger | Response `message` / `errors` |
|---|---|---|
| **422** | Attempting to refresh a Personal Access Token (which is currently unsupported). | `"Pembaruan token tidak didukung. Silakan login kembali."` (Returned inside `errors.refresh_token` array) |

### `GET /api/v1/auth/email/verify/{id}/{hash}`
| HTTP Status | Context / Trigger | Response `message` |
|---|---|---|
| **403 Forbidden** | The verification link has an invalid hash or is malformed. | `"Tautan verifikasi tidak valid."` |
| **200 OK** (Warning) | User clicks the link but their email is already verified. | `"Email sudah diverifikasi."` |

### `POST /api/v1/auth/email/resend`
| HTTP Status | Context / Trigger | Response `message` |
|---|---|---|
| **200 OK** (Warning) | User requests a new link but is already verified. | `"Email sudah diverifikasi."` |

### Google OAuth Callback (Web/Frontend Redirect)
| Trigger | Context / Trigger | Redirect Query Parameter |
|---|---|---|
| **Exception** | Google Authentication fails or is cancelled. | Redirects to `/login?error=Autentikasi+Google+gagal:+[REASON]` |

---

## 3. Master Data Endpoints (`/api/v1/periodes`)

While most Master Data CRUD operations strictly rely on Global 422 Validations and 409 Constraints, the `Periode` endpoints contain custom business logic regarding the "Locking" (Tutup) mechanism.

### `PUT / PATCH /api/v1/periodes/{periode}`
| HTTP Status | Context / Trigger | Response `message` |
|---|---|---|
| **403 Forbidden** | Attempting to edit a Periode that has already been closed (`isLocked() === true`). | `"Periode ini sudah ditutup dan tidak dapat diubah."` |

### `DELETE /api/v1/periodes/{periode}`
| HTTP Status | Context / Trigger | Response `message` |
|---|---|---|
| **403 Forbidden** | Attempting to delete a Periode that is closed. | `"Periode ini sudah ditutup dan tidak dapat dihapus."` |

### `POST /api/v1/periodes/{periode}/tutup`
| HTTP Status | Context / Trigger | Response `message` |
|---|---|---|
| **422 Unprocessable** | Attempting to close a Periode that is already closed. | `"Periode ini sudah ditutup sebelumnya."` |

### `POST /api/v1/periodes/{periode}/buka`
| HTTP Status | Context / Trigger | Response `message` |
|---|---|---|
| **422 Unprocessable** | Attempting to open a Periode that is already active/open. | `"Periode ini sudah aktif."` |

---

## 4. Frontend Implementation Guidelines

When configuring your frontend HTTP Client (e.g., Axios or Fetch API):

1. **Global Interceptor**:
   Create a global response interceptor to automatically catch `401`, `500`, and `404` errors using the messages documented in **Section 1**.
2. **Form Validation (422)**:
   When catching a `422 Kesalahan validasi.`, parse the `errors` object and map the strings to the corresponding form inputs. The backend provides fully translated Indonesian validation strings (e.g. *"Kolom nama dosen wajib diisi."*).
3. **Conflict Handling (409)**:
   Catch `409` errors explicitly on forms. This is most important when trying to delete master data. Show a modal or an alert dialog using the `response.data.message` ("Data tidak dapat dihapus karena masih digunakan...").
