<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePengaduanRequest extends FormRequest
{
    /**
     * Tentukan apakah user boleh melakukan request ini.
     */
    public function authorize(): bool
    {
        return true; // <--- PENTING: Ubah false jadi true
    }

    /**
     * Aturan validasi input.
     */
    public function rules(): array
    {
        return [
            'isi_laporan' => 'required|string',
            'foto'        => 'nullable|image|max:2048|mimes:jpg,jpeg,png',
        ];
    }
    
    /**
     * Pesan error custom (Opsional).
     */
    public function messages(): array
    {
        return [
            'isi_laporan.required' => 'Isi laporan wajib diisi.',
            'foto.image'           => 'File harus berupa gambar.',
            'foto.max'             => 'Ukuran foto maksimal 2MB.',
        ];
    }
}