<?php

namespace App\Exports;

use App\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ServiceMasukExport implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize
{
    public function title(): string
    {
        return 'SERVICE MASUK';
    }

    public function collection()
    {
        $transaksi = Transaksi::where('invoice','like', '%SERV/I/%')->orderBy('created_at')->get();

        return $transaksi;
    }

    public function headings(): array
    {
        return [
            'INVOICE',
            'TOKO',
            'NAMA TOKO',
            'ITEM',
            'PIC',
            'NOTE',
            'MASUK'
        ];
    }

    public function map($transaksi): array
    {   
        return [
            $transaksi->invoice,
            $transaksi->department->kdtk,
            $transaksi->department->name,
            $transaksi->detail->count(),
            $transaksi->pic,
            $transaksi->note,
            $transaksi->created_at,
        ];
    }
}
