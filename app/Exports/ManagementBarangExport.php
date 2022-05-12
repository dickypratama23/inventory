<?php

namespace App\Exports;

use App\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ManagementBarangExport implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize
{
    public function title(): string
    {
        return 'BARANG';
    }

    public function collection()
    {
        return Barang::all();
    }

    public function headings(): array
    {
        return [
            'KODE',
            'NAMA BARANG',
            'SATUAN',
            'KATEGORI',
            'RECID',
			'MAIN',
        ];
    }

    public function map($transaksi): array
    {
        return [
            $transaksi->kode,
            $transaksi->name,
            $transaksi->satuan,
            $transaksi->kategori->name,
            $transaksi->mac,
			$transaksi->recid,
        ];
    }
}
