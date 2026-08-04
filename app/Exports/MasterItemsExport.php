<?php

namespace App\Exports;

use App\Models\MasterItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MasterItemsExport implements FromCollection, WithHeadings, WithMapping
{
    private int $no = 0;

    // ambil semua master item + relasi kategori (many-to-many) untuk kolom "Nama kategori"
    public function collection()
    {
        return MasterItem::with('kategoris')->orderBy('id')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kategori',
            'Nama Item',
            'Nama Supplier',
            'Harga',
            'Laba',
            'Harga Jual',
        ];
    }

    public function map($item): array
    {
        $this->no++;
        $hargaJual = $item->harga_beli + ($item->harga_beli * $item->laba) / 100;
        $namaKategori = $item->kategoris->pluck('nama')->implode(', ');

        return [
            $this->no,
            $namaKategori,
            $item->nama,
            $item->supplier,
            $item->harga_beli,
            $item->laba,
            round($hargaJual),
        ];
    }
}
