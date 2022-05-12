<?php

namespace App\Exports;

use App\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ServiceKeluarExport implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize
{
    public function title(): string
    {
        return 'SERVICE SELESAI';
    }

    public function collection()
    {
        $transaksi = Transaksi::where('invoice','like', '%SERV/O/%')->whereNotIn('id', [3062, 3063, 3064])->orderBy('created_at')->get();

        return $transaksi;
    }

    public function headings(): array
    {
        return [
            'INVOICE',
            'INVOICE2',
            'BARANG',
            'TOKO',
            'NAMA TOKO',
            'ITEM PENGANTIAN',
            'PIC',
            'NOTE',
            'SELESAI'
        ];
    }

    public function map($transaksi): array
    {   
	
        return [
            $transaksi->invoice,
            $transaksi->inv_relation,
            $transaksi->barang->name,
            $transaksi->department->kdtk,
            $transaksi->department->name,
            $transaksi->detail->count(),
            $transaksi->pic == 'NIK - NAMA PERSONIL TOKO' ? 'Belum Ambil' : $transaksi->pic,
            $transaksi->note,
            $transaksi->created_at,
        ];
		

    }
}
