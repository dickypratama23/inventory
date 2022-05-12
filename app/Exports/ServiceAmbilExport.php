<?php

namespace App\Exports;

use App\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ServiceAmbilExport implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize
{
    public function title(): string
    {
        return 'BELUM AMBIL';
    }

    public function collection()
    {
        $service  = Transaksi::with(['department', 'detail'])
            ->where([
                ['invoice', 'like', 'SERV/O%'],
                ['status', '=', 2],
                ['pic', 'NIK - NAMA PERSONIL TOKO']
            ])
            ->orderBy('updated_at', 'DESC')->get();
        
            return $service;
    }

    public function headings(): array
    {
        return [
            'BARANG',
            'DEPT',
            'NAMA',
            'SELESAI',
        ];
    }

    public function map($transaksi): array
    {
        return [
            $transaksi->barang->name,
            $transaksi->department->kdtk,
            $transaksi->department->name,
            $transaksi->created_at
        ];
    }
}
