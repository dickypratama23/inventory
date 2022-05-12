<?php

namespace App\Exports;

use App\Transaksi_detail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransOutExport implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize
{
    public function title(): string
    {
        return 'BARANG KELUAR';
    }

    public function collection()
    {
        return Transaksi_detail::whereIn('rtype',['O', 'AO'])->get();
    }

    public function map($transaksi) : array {
        return [
            $transaksi->transaksi->invoice,
            $transaksi->transaksi->created_at,
            $transaksi->transaksi->department->kdtk,
            $transaksi->transaksi->pic,
            $transaksi->barang->name,
            $transaksi->qty,
            $transaksi->transaksi->note,
            $transaksi->note,
        ] ;
    }

     public function headings() : array {
        return [
           'INVOICE',
           'TANGGAL',
           'KE',
           'PIC',
           'BARANG',
           'QTY',
           'NOTE',
           'NOTE2'
        ] ;
    }
}
