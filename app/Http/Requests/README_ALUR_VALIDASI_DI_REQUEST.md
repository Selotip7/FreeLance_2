# Laravel Form Request Validation Flow

Dokumentasi lengkap tentang alur kerja `RegisterRequest` dan bagaimana Laravel menangani validasi serta response ketika validasi gagal.

---

## 📋 Table of Contents

- [Overview](#overview)
- [Alur Lengkap Validasi](#alur-lengkap-validasi)
- [Penjelasan Detail](#penjelasan-detail)
- [Perbedaan `messages()` vs `failedValidation()`](#perbedaan-messages-vs-failedvalidation)
- [Contoh Implementasi](#contoh-implementasi)
- [Kesimpulan](#kesimpulan)

---

## 🎯 Overview

Form Request di Laravel memungkinkan kita untuk:
- Memvalidasi input secara otomatis sebelum masuk ke controller
- Mengkustomisasi pesan error validasi
- Mengubah format response error (terutama untuk API)
- Melakukan otorisasi akses

---

## 🔄 Alur Lengkap Validasi

```
Frontend kirim POST /api/register
          │
          ▼
   Laravel Route → Controller Method (RegisterRequest)
          │
          ▼
   RegisterRequest Instantiation
          │
          ├─ authorize() → return true → lanjut
          │                 return false → 403 Forbidden
          ├─ rules() → ambil rules validasi
          │
          ├─ Validator Internal jalankan rules
          │       │
          │       ├─ ✅ Lolos → Controller dijalankan
          │       │
          │       └─ ❌ Gagal → failedValidation() dipanggil
          │                    │
          │                    └─ Gunakan messages() untuk teks error
          │                        │
          │                        └─ Return JSON Response 422
          ▼
Controller hanya dijalankan jika validasi sukses
```

---

## 📝 Penjelasan Detail

### 1️⃣ Frontend Mengirim Request

Frontend mengirim POST request ke `/api/register`:

```json
POST /api/register
Content-Type: application/json
Accept: application/json

{
  "name": "Akhyar",
  "email": "salah-format",
  "password": "123",
  "role": "superadmin"
}
```

**Penting:** Header `Accept: application/json` memberi tahu Laravel untuk mengembalikan response dalam format JSON.

---

### 2️⃣ Route Laravel

```php
Route::post('/api/register', [AuthController::class, 'register']);
```

Request diteruskan ke controller dengan parameter `RegisterRequest`.

---

### 3️⃣ Controller Method

```php
public function register(RegisterRequest $request)
{
    // Kode ini HANYA dijalankan jika validasi berhasil
    $data = $request->validated();
    
    // Proses registrasi...
}
```

**Catatan:** Controller method **tidak akan dieksekusi** jika validasi gagal.

---

### 4️⃣ RegisterRequest Dijalankan

Saat controller dipanggil, Laravel otomatis:

#### a) Memanggil `authorize()`

```php
public function authorize(): bool
{
    return true; // Izinkan semua request
}
```

- `return true` → Request diizinkan, lanjut ke validasi
- `return false` → Laravel return 403 Forbidden, proses berhenti

#### b) Mengambil Rules dari `rules()`

```php
public function rules(): array
{
    return [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required|string|min:6',
        'role' => 'required|in:user,admin'
    ];
}
```

#### c) Menjalankan Validator

Laravel membuat instance `Illuminate\Validation\Validator` dan membandingkan input dengan rules:

| Field | Input | Rule | Status |
|-------|-------|------|--------|
| name | "Akhyar" | required\|string\|max:255 | ✅ Pass |
| email | "salah-format" | required\|email | ❌ Fail |
| password | "123" | required\|string\|min:6 | ❌ Fail |
| role | "superadmin" | required\|in:user,admin | ❌ Fail |

---

### 5️⃣ Handling Validation Failure

Jika ada field yang gagal validasi, Laravel memanggil `failedValidation()`:

```php
protected function failedValidation(Validator $validator)
{
    throw new HttpResponseException(response()->json([
        'status' => false,
        'message' => 'Validasi gagal',
        'errors' => $validator->errors()
    ], 422));
}
```

**Response yang dikembalikan:**

```json
{
  "status": false,
  "message": "Validasi gagal",
  "errors": {
    "email": ["Format email tidak valid"],
    "password": ["Password minimal 6 karakter"],
    "role": ["Role harus user atau admin"]
  }
}
```

---

## 🔍 Perbedaan `messages()` vs `failedValidation()`

### `messages()` - Mengubah Isi Pesan Error

```php
public function messages(): array
{
    return [
        'name.required' => 'Nama wajib diisi!',
        'email.email' => 'Format email tidak valid',
        'password.min' => 'Password minimal :min karakter',
        'role.in' => 'Role harus user atau admin'
    ];
}
```

**Fungsi:** Menentukan **teks pesan error** untuk setiap rule.

---

### `failedValidation()` - Mengubah Format Response

```php
protected function failedValidation(Validator $validator)
{
    throw new HttpResponseException(response()->json([
        'status' => false,
        'message' => 'Validasi gagal',
        'errors' => $validator->errors()
    ], 422));
}
```

**Fungsi:** Menentukan **struktur dan format response** ketika validasi gagal.

---

### Ringkasan Perbedaan

| Aspect | `messages()` | `failedValidation()` |
|--------|--------------|---------------------|
| **Fungsi** | Mengubah teks error | Mengubah format response |
| **Output** | Array pesan | HTTP Response |
| **Kapan Dipakai** | Setiap kali ada error | Ketika validasi gagal |
| **Customisasi** | Pesan per rule | Struktur JSON, status code |

---

## 💡 Contoh Implementasi

### RegisterRequest Lengkap

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    /**
     * Tentukan apakah user boleh menggunakan request ini
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Definisi rules validasi
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:user,admin'
        ];
    }

    /**
     * Custom pesan error untuk setiap rule
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi',
            'name.string' => 'Nama harus berupa teks',
            'name.max' => 'Nama maksimal 255 karakter',
            
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            
            'role.required' => 'Role wajib dipilih',
            'role.in' => 'Role harus user atau admin'
        ];
    }

    /**
     * Custom response ketika validasi gagal
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ], 422));
    }
}
```

---

## ✅ Kesimpulan

### Poin Penting:

1. **`authorize()`** → Menentukan apakah user boleh menggunakan request
2. **`rules()`** → Mendefinisikan aturan validasi
3. **`messages()`** → Mengkustomisasi teks pesan error
4. **`failedValidation()`** → Mengkustomisasi format response error

### Alur Singkat:

```
Request → authorize() → rules() → Validator
                                      ├─ Pass → Controller
                                      └─ Fail → failedValidation()
                                                    └─ JSON Response 422
```

### Yang Perlu Diingat:

- ❌ Jika validasi gagal → **Controller TIDAK dijalankan**
- ✅ Frontend langsung menerima JSON error dengan status 422
- 📝 Pesan error diambil dari `messages()`
- 🎨 Format response ditentukan oleh `failedValidation()`

---

## 📚 Referensi

- [Laravel Form Request Validation](https://laravel.com/docs/validation#form-request-validation)
- [Customizing Error Messages](https://laravel.com/docs/validation#customizing-the-error-messages)
- [HTTP Response Status Codes](https://developer.mozilla.org/en-US/docs/Web/HTTP/Status)

---

**Dibuat dengan ❤️ untuk pembelajaran Laravel**