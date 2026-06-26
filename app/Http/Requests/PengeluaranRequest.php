<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengeluaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal_pengeluaran'     => 'required|date',
            'kategori_pengeluaran_id' => 'required|exists:kategori_pengeluarans,id',
            'jumlah'                  => 'required|numeric|min:1',
            'keterangan'              => 'required|string',
            'bukti_foto'              => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_pengeluaran.required'     => 'Tanggal pengeluaran wajib diisi.',
            'kategori_pengeluaran_id.required' => 'Kategori pengeluaran wajib dipilih.',
            'jumlah.required'                  => 'Jumlah nominal pengeluaran wajib diisi.',
            'jumlah.min'                       => 'Jumlah nominal minimal Rp 1.',
            'keterangan.required'              => 'Keterangan pengeluaran wajib diisi.',
            'bukti_foto.image'                 => 'File bukti harus berupa gambar.',
            'bukti_foto.max'                   => 'Ukuran file bukti maksimal 2MB.',
        ];
    }
}
