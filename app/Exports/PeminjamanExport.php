<?php

namespace App\Exports;

use App\Transaksi_detail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PeminjamanExport implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize
{
    public function title(): string
    {
        return 'PEMINJAMAN';
    }

    public function collection()
    {
        return Transaksi_detail::where('rtype','L')->get();
    }

    public function map($transaksi) : array {
        return [
            $transaksi->transaksi->invoice,
            $transaksi->transaksi->created_at,
            $transaksi->transaksi->department->kdtk,
            $transaksi->transaksi->pic,
            $transaksi->cad->name,
            $transaksi->cad->mac,
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
           'SN',
           'QTY',
           'NOTE',
           'NOTE2'
        ] ;
    }
}
