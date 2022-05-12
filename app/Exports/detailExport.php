<?php

namespace App\Exports;

use App\Transaksi_detail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class detailExport implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize
{
    public function title(): string
    {
        return 'BARANG MASUK';
    }

    public function collection()
    {
        return Transaksi_detail::where('rtype','I')->get();
    }

    public function map($transaksi) : array {
        return [
            $transaksi->transaksi->invoice,
            $transaksi->created_at,
            $transaksi->transaksi->department->kdtk,
            $transaksi->transaksi->pic,
            $transaksi->barang->name,
            $transaksi->qty,
            $transaksi->transaksi->note,
            $transaksi->Serial_number == '' ? '' : 'SN: ' . $transaksi->Serial_number,
        ] ;
    }

     public function headings() : array {
        return [
           'INVOICE',
           'TANGGAL',
           'DARI',
           'PIC',
           'BARANG',
           'QTY',
           'NOTE',
           'SN'
        ] ;
    }
}
