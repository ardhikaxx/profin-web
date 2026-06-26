<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProdukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $produkId = $this->route('produk') ? $this->route('produk')->id : null;

        return [
            'kode_produk'    => ['required', 'string', 'max:20', Rule::unique('produks', 'kode_produk')->ignore($produkId)],
            'nama_produk'    => 'required|string|max:100',
            'satuan_id'      => 'required|exists:satuans,id',
            'harga_estimasi' => 'required|numeric|min:0',
            'stok_minimum'   => 'required|integer|min:0',
            'deskripsi'      => 'nullable|string',
            'is_active'      => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'kode_produk.required'    => 'Kode produk wajib diisi.',
            'kode_produk.unique'      => 'Kode produk sudah digunakan.',
            'nama_produk.required'    => 'Nama produk wajib diisi.',
            'satuan_id.required'      => 'Satuan produk wajib dipilih.',
            'harga_estimasi.required' => 'Harga estimasi wajib diisi.',
            'stok_minimum.required'   => 'Stok minimum wajib diisi.',
        ];
    }
}
