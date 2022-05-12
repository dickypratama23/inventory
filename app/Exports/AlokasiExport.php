<?php

namespace App\Exports;

use App\Transaksi_detail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AlokasiExport implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize
{
    public function title(): string
    {
        return 'ALOKASI';
    }

    public function collection()
    {
        return Transaksi_detail::where('rtype', 'A')->get();
    }

    public function headings(): array
    {
        return [
            'INVOICE',
            'TANGGAL',
            'KE',
            'PIC',
            'BARANG',
            'SN',
            'QTY',
            'NOTE',
            'NOTE2'
        ];
    }

    public function map($transaksi): array
    {
        return [
            $transaksi->transaksi->invoice,
            $transaksi->transaksi->created_at,
            $transaksi->transaksi->department->kdtk,
            $transaksi->transaksi->pic,
            $transaksi->barang->name,
            $transaksi->Serial_number,
            $transaksi->qty,
            $transaksi->transaksi->note,
            $transaksi->note,
        ];
    }
}
