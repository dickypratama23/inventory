<?php

namespace App\Exports;

use App\Transaksi_detail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ServiceMasukDetailExport implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize
{
    public function title(): string
    {
        return 'DETAIL BARANG SERVICE MASUK';
    }

    public function collection()
    {
        $transaksi = Transaksi_detail::where('rtype', 's')->orderBy('created_at')->get();

        return $transaksi;
    }

    public function headings(): array
    {
        return [
            'INVOICE',
            'TOKO',
            'NAMA TOKO',
            'PIC',
            'BARANG',
            'S/N',
            'HO',
            'NOTE',
            'MASUK'
        ];
    }

    public function map($transaksi): array
    {   
        return [
            $transaksi->transaksi->invoice,
            $transaksi->transaksi->department->kdtk,
            $transaksi->transaksi->department->name,
            $transaksi->transaksi->pic,
            $transaksi->barang->name,
            $transaksi->barang->kode == 'KT-0062' ? 'S/N : ' . $transaksi->Serial_number : $transaksi->Serial_number,
            $transaksi->docno_ho,
            $transaksi->note,
            $transaksi->transaksi->created_at,
        ];
    }
}
