<?php

namespace App\Exports;

use App\Stock;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LPPStockExport implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize
{
    public function title(): string
    {
        return 'STOCK';
    }

    public function collection()
    {
        return Stock::all();
    }

    public function headings(): array
    {
        return [
            'KODE',
            'BARANG',
            'IN',
            'OUT',
            'BAP',
            'ADJ',
            'TOTAL',
            'TANGGAL UPDATE'
        ];
    }

    public function map($transaksi): array
    {
        return [
            $transaksi->barang->kode,
            $transaksi->barang->name,
            $transaksi->in == '0' ? '0' : $transaksi->in,
            $transaksi->out == '0' ? '0' : $transaksi->out,
            $transaksi->bap == '0' ? '0' : $transaksi->bap,
            $transaksi->adj == '0' ? '0' : $transaksi->adj,
            $transaksi->total == '0' ? '0' : $transaksi->total,
            $transaksi->updated_at,
        ];
    }
}
