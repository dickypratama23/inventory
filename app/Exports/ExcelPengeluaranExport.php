<?php

namespace App\Exports;

use App\Transaksi;
use App\Transaksi_detail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExcelPengeluaranExport implements FromCollection, WithMapping, WithHeadings, WithTitle, ShouldAutoSize
{
    public function title(): string
    {
        return 'Pengeluaran';
    }

    public function collection()
    {
        $transaksi = Transaksi_detail::where('rtype', 'o')->orderBy('created_at')->get();

        return $transaksi;
    }

    public function headings(): array
    {
        return [
            'KODE',
            'BARANG',
            'QTY',
            'ASSEMBLY',
            'GL',
            'SERIAL NUMBER',
            'PIC',
            'KDTK',
            'TOKO',
            'NOTE',
            'NOTE2',
            'NOTE3',
            'TANGGAL',
            'DOCNO',
            'DOCNO2'
        ];
    }

    public function map($transaksi): array
    {   
        return [
            $transaksi->barang->kode,
            $transaksi->barang->name,
            $transaksi->qty,
            $transaksi->to_assembly,
            $transaksi->to_gl,
            $transaksi->Serial_number,
            $transaksi->transaksi->pic,
            $transaksi->transaksi->department->kdtk,
            $transaksi->transaksi->department->name,
            $transaksi->note,
            $transaksi->transaksi->note,
            $transaksi->transaksi->note2,
            $transaksi->created_at,
            $transaksi->transaksi->invoice,
            $transaksi->transaksi->inv_relation
        ];
    }
}
