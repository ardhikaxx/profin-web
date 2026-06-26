<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProduksiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal_produksi' => 'required|date',
            'produk_id'        => 'required|exists:produks,id',
            'jumlah_produksi'  => 'required|integer|min:1',
            'jumlah_gagal'     => 'nullable|integer|min:0|lte:jumlah_produksi',
            'keterangan'       => 'nullable|string',
            'karyawan_id'      => 'nullable|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_produksi.required' => 'Tanggal produksi wajib diisi.',
            'produk_id.required'        => 'Produk wajib dipilih.',
            'jumlah_produksi.required'  => 'Jumlah produksi wajib diisi.',
            'jumlah_produksi.min'       => 'Jumlah produksi minimal 1.',
            'jumlah_gagal.lte'          => 'Jumlah gagal tidak boleh melebihi jumlah produksi.',
        ];
    }
}
