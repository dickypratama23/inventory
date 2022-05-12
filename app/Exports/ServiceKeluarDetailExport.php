<?php

namespace App\Exports;

use App\Transaksi_detail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ServiceKeluarDetailExport implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize
{
    public function title(): string
    {
        return 'DETAIL PENGGANTIAN SERVICE';
    }

    public function collection()
    {
        $transaksi = Transaksi_detail::where('note', 'Ganti Spare Part')->orderBy('created_at')->get();

        return $transaksi;
    }

    public function headings(): array
    {
        return [
            'INVOICE',
            'INVOICE2',
            'TOKO',
            'NAMA TOKO',
            'PIC',
            'BARANG',
            'SPARE PART',
            'QTY',
            'NOTE',
            'SELESAI'
        ];
    }

    public function map($transaksi): array
    {   
        return [
            $transaksi->transaksi->invoice,
            $transaksi->transaksi->inv_relation,
            $transaksi->transaksi->department->kdtk,
            $transaksi->transaksi->department->name,
            $transaksi->transaksi->pic == 'NIK - NAMA PERSONIL TOKO' ? 'Belum Ambil' : $transaksi->transaksi->pic,
            $transaksi->transaksi->barang->name,
            $transaksi->barang->name,
            $transaksi->qty,
            $transaksi->note,
            $transaksi->transaksi->created_at,
        ];
    }
}
