<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ManagementExport implements WithMultipleSheets
{
    public function sheets(): array
    {

      return [
        new ManagementKategoriExport(),
        new ManagementBarangExport(),
        new ManagementCadanganExport(),
      ];

    }
}