<?php

namespace App\Exports;

use App\Transaksi_detail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ServiceHOExport implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize
{
    public function title(): string
    {
        return 'SERVICE HO';
    }

    public function collection()
    {
        return Transaksi_detail::where('docno_ho', '!=', '')->get();
    }

    public function headings(): array
    {
        return [
            'BARANG',
            'S/N',
            'CAD',
            'DEPT',
            'NAMA',
            'MASALAH',
            'PIC',
            'KIRIM',
        ];
    }

    public function map($transaksi): array
    {
        return [
            $transaksi->barang->name,
            $transaksi->barang->kode == 'KT-0062' ? 'S/N: ' . $transaksi->Serial_number : $transaksi->Serial_number,
            $transaksi->cads == '1' ? 'CADANGAN EDP' : '',
            $transaksi->transaksi->department->kdtk,
            $transaksi->transaksi->department->name,
            $transaksi->note,
            $transaksi->transaksi->pic,
            $transaksi->updated_at,
        ];
    }
}
