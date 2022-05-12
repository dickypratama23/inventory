<?php

namespace App\Exports;

use App\Permintaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LPP_PPCabangExport implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize
{
    public function title(): string
    {
        return 'PERMINTAAN PEMBELIAN';
    }

    public function collection()
    {
        return Permintaan::all();
    }

    public function headings(): array
    {
        return [
            'JENIS',
            'NOMOR PP',
            'BARANG',
            'QTY',
            'GA',
            'REALISASI'
        ];
    }

    public function map($transaksi): array
    {
        return [
            $transaksi->jenis_pp,
            $transaksi->nomor_pp,
            $transaksi->barang->name,
            $transaksi->qty,
            $transaksi->serah2,
            $transaksi->realisasi,
        ];
    }
}
