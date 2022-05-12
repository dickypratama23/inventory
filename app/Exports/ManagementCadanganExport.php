<?php

namespace App\Exports;

use App\Cad;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ManagementCadanganExport implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize
{
    public function title(): string
    {
        return 'CADANGAN';
    }

    public function collection()
    {
        return Cad::all();
    }

    public function headings(): array
    {
        return [
            'KODE',
            'NAMA BARANG',
            'SERIAL NUMBER',
            'KATEGORI',
            'DEPT',
            'NAMA DEPT',
            'STATUS',
            'ALOKASI',
        ];
    }

    public function map($transaksi): array
    {
        return [
            $transaksi->kode,
            $transaksi->name,
            $transaksi->mac,
            $transaksi->kategori->name,
            $transaksi->recid == 1 ? $transaksi->department->kdtk : '',
            $transaksi->recid == 1 ? $transaksi->department->name : '',
            $transaksi->recid == 1 ? 'PINJAM' : '',
            $transaksi->recid == 3 ? 'ALOKASI' : '',
        ];
    }
}
