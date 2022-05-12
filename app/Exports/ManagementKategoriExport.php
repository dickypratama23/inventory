<?php

namespace App\Exports;

use App\Kategori;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ManagementKategoriExport implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize
{
    public function title(): string
    {
        return 'KATEGORI';
    }

    public function collection()
    {
        return Kategori::all();
    }

    public function headings(): array
    {
        return [
            'KATEGORI',
            'DESKRIPSI',
        ];
    }

    public function map($transaksi): array
    {
        return [
            $transaksi->name,
            $transaksi->deskripsi,
        ];
    }
}
